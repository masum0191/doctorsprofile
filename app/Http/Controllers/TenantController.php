<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Support\Facades\Cache;
// Validator
use Illuminate\Support\Facades\Validator;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\User;
use App\Models\Setting;
use App\Models\Domain;
use App\Models\Payment;
use Illuminate\Support\Facades\Tenancy;
use Illuminate\Support\Facades\Mail;
// auth
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\PricingService;





class TenantController extends Controller
{
    /**
     * Create a new tenant
     */
    /**
     * GET /api/entities
     * Query params:
     * - q=...                         (search by tenant name, domain)
     * - type=unions|pourasovas|city_corporations
     * - division=ID
     * - district=ID
     * - upazila=ID
     * - include=domains               (comma sep: e.g. include=domains)
     * - minimal=1                     (skip settings lookup for speed)
     * - page=1                        (pagination)
     * - per_page=12                   (default 12)
     */
    public function index(Request $request)
    {
        // ---- Params ----
        $q          = trim((string) $request->query('q', ''));
        $type       = $request->query('type');       // unions|pourasovas|city_corporations
        $divisionId = $request->query('division');
        $districtId = $request->query('district');
        $upazilaId  = $request->query('upazila');
        $status     = $request->query('status');     // may be 0/1
        $include    = collect(explode(',', (string) $request->query('include', '')))
            ->map('trim')->filter()->values();
        $perPage    = (int) max(1, min(100, $request->integer('per_page', 12)));
        $page       = (int) max(1, $request->integer('page', 1));

        // Eager load central relations only when asked
        $with = [];
        if ($include->contains('domains')) $with[] = 'domains';

        // ---- Base central filters (NOTE: q is NOT used here at all) ----
        $builder = Tenant::query()->with($with);

        if (!empty($type)) {
            // many rows still have values in data JSON; use JSON_EXTRACT to be safe
            $builder->where(function ($w) use ($type) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.type')) = ?", [$type]);
            });
        }
        if (!empty($divisionId)) {
            $builder->where(function ($w) use ($divisionId) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.division_id')) = ?", [$divisionId]);
            });
        }
        if (!empty($districtId)) {
            $builder->where(function ($w) use ($districtId) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.district_id')) = ?", [$districtId]);
            });
        }
        if (!empty($upazilaId)) {
            $builder->where(function ($w) use ($upazilaId) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.upazilla_id')) = ?", [$upazilaId]);
                hereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.upazilla_id')) = ?", [$upazilaId]);
            });
        }
        if ($request->has('status')) {
            $builder->where(function ($w) use ($status) {
                $w->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`data`, '$.status')) = ?", [$status]);
            });
        }

        $builder->latest('created_at');

        // ---- If no q: plain DB pagination ----
        // ---------- HYBRID SEARCH (when q is present) ----------
        if ($q !== '') {
            $qLower = mb_strtolower($q, 'UTF-8');

            // Get a candidate set (respect other filters already applied above).
            // Use a high cap or remove the cap if your dataset is small.
            $candidates = $builder->limit(10000)->get();

            $filteredTenants = $candidates->filter(function (\App\Models\Tenant $tenant) use ($qLower) {
                try {
                    $settings = \Cache::remember("tenant:{$tenant->id}:settings:first", 300, function () use ($tenant) {
                        tenancy()->initialize($tenant);
                        try {
                            return \App\Models\Setting::query()->first(); // 'tenant' connection
                        } finally {
                            tenancy()->end();
                        }
                    });

                    if (!$settings) {
                        return false;
                    }

                    // Safely read names (don’t assume 'extras' exists/is array)
                    $siteName    = (string) \Illuminate\Support\Arr::get($settings, 'site_name', '');
                    $siteNameBn  = (string) \Illuminate\Support\Arr::get($settings, 'site_name_en', '');

                    // Multibyte, case-insensitive contains
                    $haystack = mb_strtolower($siteName . ' ' . $siteNameBn, 'UTF-8');

                    // Use mb_stripos for robust matching of Bengali substrings (e.g., "বরিশা")
                    return mb_stripos($haystack, $qLower, 0, 'UTF-8') !== false;
                } catch (\Throwable $e) {
                    // Optional: \Log::warning("Search failed for tenant {$tenant->id}: ".$e->getMessage());
                    return false;
                }
            })->values();

            // Manual pagination for filtered results
            $total = $filteredTenants->count();
            $items = $filteredTenants->slice(($page - 1) * $perPage, $perPage)->values();

            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // ---------- NO q: use standard DB pagination ----------
            $paginator = $builder->paginate($perPage)->appends($request->query());
            $items = collect($paginator->items());
        }

        // ---- Build tiny maps for division/district/upazila names (page scope only) ----
        $divisionIds = $items->pluck('division_id')->filter()->unique();
        $districtIds = $items->pluck('district_id')->filter()->unique();
        $upazilaIds  = $items->pluck('upazilla_id')->filter()->unique();

        $divMap  = $divisionIds->isNotEmpty() ? Division::whereIn('id', $divisionIds)->pluck('name', 'id') : collect();
        $distMap = $districtIds->isNotEmpty() ? District::whereIn('id', $districtIds)->pluck('name', 'id') : collect();
        $upzMap  = $upazilaIds->isNotEmpty()  ? Upazila::whereIn('id', $upazilaIds)->pluck('name', 'id') : collect();

        // ---- Transform rows; optionally include settings/domains payloads ----
        $rows = $items->map(function (Tenant $t) use ($include, $divMap, $distMap, $upzMap) {
            $row = [
                'id'           => $t->id,
                'name'         => data_get($t->data, 'name'), // central JSON name (optional)
                'type'         => $t->type ?? data_get($t->data, 'type'),
                'entity_id'    => $t->entity_id ?? data_get($t->data, 'entity_id'),
                'division_id'  => $t->division_id ?? data_get($t->data, 'division_id'),
                'division'     => $divMap[$t->division_id]  ?? null,
                'district_id'  => $t->district_id ?? data_get($t->data, 'district_id'),
                'district'     => $distMap[$t->district_id] ?? null,
                'upazila_id'   => $t->upazilla_id ?? data_get($t->data, 'upazilla_id'),
                'upazila'      => $upzMap[$t->upazilla_id] ?? null,
                'created_at'   => optional($t->created_at)->toIso8601String(),
                'updated_at'   => optional($t->updated_at)->toIso8601String(),
                'status'       => $t->status ?? data_get($t->data, 'status'),
                'slug'         => $t->slug,
            ];

            if ($include->contains('domains')) {
                $row['domains'] = $t->relationLoaded('domains')
                    ? $t->domains->pluck('domain')->values()
                    : \App\Models\Domain::where('tenant_id', $t->id)->pluck('domain')->values();
            }

            if ($include->contains('settings')) {
                try {
                    $row['settings'] = Cache::remember("tenant:{$t->id}:settings:first", 300, function () use ($t) {
                        tenancy()->initialize($t);
                        try {
                            $s = \App\Models\Setting::query()->first();
                            return $s ? [
                                'site_name'     => $s->site_name     ?? null,
                                'site_name_en'  => $s->site_name_en  ?? null,
                                'tagline'       => $s->tagline       ?? null,
                                'logo'          => $s->site_logo          ?? null,
                                'wards'        => $s->wards        ?? null,
                                'population'          => $s->population          ?? null,
                                'villages'          => $s->villages          ?? null,
                                'area'          => $s->area          ?? null,

                            ] : null;
                        } finally {
                            tenancy()->end();
                        }
                    });
                } catch (\Throwable $e) {
                    $row['settings'] = ['_error' => $e->getMessage()];
                }
            }

            // Optional: prefer settings->site_name as display name if present
            if (empty($row['name']) && !empty($row['settings']['site_name'] ?? null)) {
                $row['name'] = $row['settings']['site_name'];
            }

            return $row;
        })->values();

        // ---- JSON ----
        return response()->json([
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ],
            'data' => $rows,
        ]);
    }

    public function userIndex()
    {
        $users = \App\Models\User::query()
            ->where('role', 'tenant')
            ->with('tenant')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (User $user) {
                $user->primary_domain = Domain::where('tenant_id', $user->tenant_id)
                    ->orderByDesc('is_primary')
                    ->orderBy('id')
                    ->first();

                $user->latest_payment = Payment::where('tenant_id', $user->tenant_id)
                    ->latest('id')
                    ->first();

                return $user;
            });

        return view('tenant.users', compact('users'));
    }

    public function userShow($id)
    {
        $user = User::where('role', 'tenant')
            ->with('tenant')
            ->findOrFail($id);

        $domains = Domain::where('tenant_id', $user->tenant_id)
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->get();

        $payments = Payment::with(['package', 'coupon'])
            ->where(function ($query) use ($user) {
                $query->where('tenant_id', $user->tenant_id)
                    ->orWhere('user_id', $user->id);
            })
            ->latest('id')
            ->get();

        $tenantSummary = null;
        if ($user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);

            if ($tenant) {
                try {
                    tenancy()->initialize($tenant);
                    $tenantSetting = \App\Models\Setting::query()->first();
                    $tenantAdmin = User::query()->where('role', 'admin')->first();

                    $tenantSummary = [
                        'site_name' => $tenantSetting->site_name ?? null,
                        'site_email' => $tenantSetting->site_email ?? null,
                        'site_phone' => $tenantSetting->site_phone ?? null,
                        'billing_cycle' => $tenantSetting->billing_cycle ?? null,
                        'tenant_admin_name' => $tenantAdmin->name ?? null,
                        'tenant_admin_email' => $tenantAdmin->email ?? null,
                    ];
                } catch (\Throwable $e) {
                    Log::warning('Failed to load tenant doctor summary', [
                        'tenant_id' => $tenant->id,
                        'message' => $e->getMessage(),
                    ]);
                } finally {
                    tenancy()->end();
                }
            }
        }

        return view('tenant.user-show', compact('user', 'domains', 'payments', 'tenantSummary'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::where('role', 'tenant')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'reg_no' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['0', '1'])],
            'feature' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'] ?? null,
            'qualification' => $validated['qualification'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'reg_no' => $validated['reg_no'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'status' => (int) $validated['status'],
            'feature' => (bool) ($validated['feature'] ?? false),
        ]);

        if ($user->tenant_id) {
            $tenant = Tenant::find($user->tenant_id);

            if ($tenant) {
                $tenant->status = (int) $validated['status'];
                $tenant->save();

                Domain::where('tenant_id', $tenant->id)
                    ->update(['status' => (int) $validated['status']]);

                try {
                    tenancy()->initialize($tenant);

                    $tenantAdmin = User::query()->where('role', 'admin')->first();
                    if ($tenantAdmin) {
                        $tenantAdmin->update([
                            'name' => $validated['name'],
                            'email' => $validated['email'],
                            'mobile' => $validated['mobile'] ?? null,
                            'qualification' => $validated['qualification'] ?? null,
                            'specialization' => $validated['specialization'] ?? null,
                            'reg_no' => $validated['reg_no'] ?? null,
                            'country' => $validated['country'] ?? null,
                            'city' => $validated['city'] ?? null,
                            'status' => (int) $validated['status'],
                        ]);
                    }

                    $tenantSetting = \App\Models\Setting::query()->first();
                    if ($tenantSetting) {
                        $tenantSetting->update([
                            'site_name' => $validated['name'] . ' Medical Practice',
                            'site_email' => $validated['email'],
                            'site_phone' => $validated['mobile'] ?? null,
                            'qualification' => $validated['qualification'] ?? null,
                            'specialization' => $validated['specialization'] ?? null,
                            'country' => $validated['country'] ?? null,
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to sync tenant doctor admin data', [
                        'tenant_id' => $tenant->id,
                        'message' => $e->getMessage(),
                    ]);
                } finally {
                    tenancy()->end();
                }
            }
        }

        return redirect()->route('user.show', $user->id)->with('success', 'Doctor details updated successfully.');
    }

    public function createView(Request $request)
    {
        $packages = \App\Models\Package::all();
        $pricingContext = app(PricingService::class)->contextForRequest($request);

        return view('tenants.create', compact('packages', 'pricingContext'));
    }
public function toggleFeature($id)
{
    $doctor = User::findOrFail($id);
    $doctor->feature = !$doctor->feature;
    $doctor->save();

    return back()->with('success', 'Doctor feature status updated');
}

//     public function store(Request $request)
// {
//     //dd($request->all());
//     $validated = validator(
// array_merge($request->all()),
//     [
//         'specialty'       => ['required', 'string', 'max:100'],
//         'qualification'   => ['required', 'string', 'max:255'],
//         'reg_no'          => ['nullable', 'string', 'max:50', Rule::unique('mysql.users', 'reg_no')],
//         'name'            => ['required', 'string', 'max:255'],
//         'email'           => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
//         'password'        => ['required', 'confirmed', 'min:8'],
//         'phone'           => ['required', 'string', 'max:20'],

//         // ✅ Geolocation fields
//         'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
//         'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
//         'city'            => ['nullable', 'string', 'max:255'],
//         //'photo'           =>['nullable']
//     ]
// )->validate();

// $user = new \App\Models\User();
// $databasePath='';
//  if ($request->hasFile('photo')) {
//             $image = $request->file('photo');
//             $folder = 'uploads/doctors/profile_photos';



//             // Generate unique filename and move
//             $extension = $image->getClientOriginalExtension();
//             $imageName = time() . '_' . uniqid() . '.' . $extension;

//             if (!file_exists(public_path($folder))) {
//                 mkdir(public_path($folder), 0755, true);
//             }

//             $image->move(public_path($folder), $imageName);
//             $databasePath = $folder . '/' . $imageName;

//             //$doctor->update(['photo' => $databasePath]);
//         }
// $user->fill([
//     'name'          => $validated['name'],
//     'email'         => $validated['email'],
//     'password'      => Hash::make($validated['password']),
//     'role'          => 'tenant',
//     'mobile'        => $validated['phone'],
//     'qualification' => $validated['qualification'],
//     'reg_no'        => $validated['reg_no'],
//     'specialization'=> $validated['specialty'],
//     'mobile'        => $validated['phone'],
//     'latitude'      => $validated['latitude'] ?? null,
//     'longitude'     => $validated['longitude'] ?? null,
//     'city'          => $validated['city'] ?? null,
//     'photo'         => $databasePath,
// ]);


// $user->save();
// $domainType = $request->input('domain_type');
//     $rawDomain = $request->input('new_domain');
//     $sanitizedDomain = $this->sanitizeDomain($rawDomain);

//     // Determine the final domain name based on input type
//     $finalDomain = '';
//     // Use a clean config variable for the base domain
//     $baseDomain = config('app.base_domain', '.doctorsprofile.xyz');


//     $finalDomain = ($domainType === 'subdomain')
//         ? $sanitizedDomain. $baseDomain
//         : $sanitizedDomain. $baseDomain;

//     // --- 2. Validation ---
//     $validated = Validator::make(
//         array_merge($request->all(), ['final_domain' => $finalDomain]),
//         [
//             // Domain & Package Fields from the modal
//             'domain_type'   => ['required', Rule::in(['domain', 'subdomain', 'existing'])],
//             'new_domain'    => ['required', 'string', 'max:255', 'regex:/^[\w\d.-]+$/'], // Raw input validation
//             'final_domain'  => [ // Constructed domain validation
//                 'required',
//                 'string',
//                 'max:255',
//                 'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,}$/',
//                 Rule::unique('domains', 'domain'),
//             ],
//             'package_id'    => ['required', 'exists:packages,id'],
//             'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
//             'final_total'   => ['required', 'numeric', 'min:0'],
//             'domain_price'  => ['required', 'numeric', 'min:0'],
//             'coupon'        => ['nullable', 'string', 'max:50'],

//             // Assuming required User data is already saved on the user model before this form is accessed.
//         ],
//         ['final_domain.regex' => 'Please enter a valid domain like example.com or sub.example.com (no http/https).']
//     )->validate();
//      $package = \App\Models\Package::findOrFail($validated['package_id']);

//         // Calculate actual prices based on billing cycle
//         $packagePrice = ($validated['billing_cycle'] === 'yearly')
//             ? $package->price_yearly
//             : $package->price_monthly;

// $user = auth()->user();
//     $tenant = Tenant::create([
//             'id' => Str::uuid(),
//             'name' => $user->name . ' - ' . $package->name . ' Service',
//             'status' => 0, // Set to inactive until payment is confirmed
//             'package_id' => $validated['package_id'],
//             'billing_cycle' => $validated['billing_cycle'],
//             'monthly_price' => $package->price_monthly,
//             'yearly_price' => $package->price_yearly,
//             'storage_gb' => $package->storage_gb,
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);




//         $user->update([
//             'tenant_id' => $tenant->id,
//         ]);

//         // C. Attach Domain to Tenant
//         $domainRow = $tenant->domains()->create([
//             'domain'            => $finalDomain,
//             'type'              => $validated['domain_type'],
//             'registration_fee'  => $validated['domain_price'],
//         ]);
//     // Optional direct call (observer will also run):
//      event(new \App\Events\TenantDomainCreated($domainRow));
//         // D. Initialize tenancy for the current process to access tenant storage/settings
//         tenancy()->initialize($tenant);
//         $tuser = User::create([
//             'id'            => Str::uuid(), // Ensure UUIDs are supported if using them
//             'name'          => $user->name . ' - ' . Str::title($validated['domain_type']) . ' Service',
//             'role'          => 'user', // Assuming a fixed type for doctors purchasing a service
//             'status'        => 0, // Inactive until payment confirmed
//             'password'    => $user->password,
//             'email'         => $user->email,
//             'mobile'        => $user->phone ?? '0000000000',

//             // Add any other necessary fields
//         ]);

//         // E. Tenant Setting table data seeding
//         $settings = new Setting(); // Use the Tenant Setting Model (needs correct Tenant scope)
//         $settings->fill([
//             'site_name' => $user->name . ' Profile',
//             'package_id' => $validated['package_id'],
//             'billing_cycle' => $validated['billing_cycle'],
//         ]);
//         $settings->save();

//         // F. Event Notification (e.g., for provisioning)
//        // event(new \App\Events\TenantDomainCreated($domainRow));

//         // G. End tenancy
//         tenancy()->end();

//         // send mail to user
//         Mail::send('emails.doctor_registration', [
//             'name'     => $user->name,
//             'email'    => $user->email,
//     'password' => $request->input('password'),
//     'subject'  => 'Doctor Registration Successful',
// ], function ($message) use ($user) {
//     $message->to($user->email)
//             ->subject('Doctor Registration Successful')
//             ->from('admin@doctorsprofile.xyz', 'Doctorsprofile Support Team');
// });

// return redirect()
//     ->route('admin.login')
//     ->with('success', 'Registration successful! Please login to continue.');
// }
//     private function sanitizeDomain(?string $value): ?string
//     {
//         if (!$value) return $value;

//         $v = strtolower(trim($value));
//         $v = preg_replace('#^(?:https?:\/\/)?(?:www\.)?#', '', $v);
//         $v = preg_split('/[\/?#]/', $v, 2)[0] ?? $v;   // correct delimiter
//         $v = preg_replace('/[^a-z0-9.-]/', '', $v);
//         $v = trim($v, '.-');

//         return $v;
//     }

public function store(Request $request)
{
    \Log::info('========== DOCTOR REGISTRATION STARTED ==========');
    \Log::info('Full request data:', $request->all()); // Log ALL data

    try {
        // Validation with proper field names
        \Log::info('Starting validation...');

        // Debug: Check what's actually in the request
        \Log::info('Checking specific fields:');
        \Log::info('selected_billing_cycle: ' . $request->get('selected_billing_cycle', 'NOT FOUND'));
        \Log::info('photo type: ' . gettype($request->file('photo')));

        $validated = Validator::make($request->all(), [
            // Step 1: Specialty & Location
            'specialty' => ['required', 'string', 'max:100'],
            'country'   => ['required', 'string', 'max:100'],
            'reg_no'    => ['nullable', 'string', 'max:50', Rule::requiredIf($request->country === 'Bangladesh'), Rule::unique('mysql.users', 'reg_no')],

            // Step 2: Identity & Credentials
            'name'          => ['required', 'string', 'max:255'],
            'qualification' => ['required', 'string', 'max:255'],

            // Step 3: Account Details
            'email'     => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
            'phone'     => ['required', 'string', 'max:20'],
            'password'  => ['required', 'confirmed', 'min:8'],
            'photo'     => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],

            // Step 4: Package Selection - FIXED: Check for actual field name
            'package_id'            => ['required', 'exists:packages,id'],
            'selected_billing_cycle'=> ['required', Rule::in(['monthly', 'yearly', 'free'])],

            // Step 5: Domain Selection - FIXED: Use correct field names
            'domain_type'       => ['required', Rule::in(['new', 'subdomain', 'existing'])],
            'subdomain_name'    => ['required_if:domain_type,subdomain', 'nullable', 'string', 'max:100'],
            'new_domain_name'   => ['required_if:domain_type,new', 'nullable', 'string', 'max:100'],
            'new_domain_extension' => ['required_if:domain_type,new', 'nullable', 'string', 'max:10'],
            'existing_domain'   => ['required_if:domain_type,existing', 'nullable', 'string', 'max:255'],

            // Step 6: Payment
            'payment_method' => ['required', Rule::in(['credit_card', 'digital_wallet', 'bank_transfer'])],
            'terms'          => ['required', 'accepted'],

            // Price fields
            'package_price'     => ['required', 'numeric', 'min:0'],
            'domain_price'      => ['required', 'numeric', 'min:0'],
            'discount_amount'   => ['required', 'numeric', 'min:0'],
            'total_amount'      => ['required', 'numeric', 'min:0'],

            // Optional fields
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'city'      => ['nullable', 'string', 'max:255'],

        ], [
            'reg_no.required_if' => 'BMDC registration number is required for doctors in Bangladesh',
            'photo.required' => 'Please upload your professional photo',
            'terms.accepted' => 'You must accept the terms and conditions',
            'subdomain_name.required_if' => 'Subdomain name is required',
            'new_domain_name.required_if' => 'Domain name is required',
            'existing_domain.required_if' => 'Existing domain is required',
        ])->validate();

        \Log::info('Validation passed', ['email' => $validated['email']]);
        \Log::info('Validated data:', $validated);

        // Start transaction
        DB::beginTransaction();
        \Log::info('Database transaction started');

        // ======================
        // 1. Handle Photo Upload
        // ======================
        $databasePath = '';
        if ($request->hasFile('photo')) {
            \Log::info('Processing photo upload...');
            $image = $request->file('photo');
            \Log::info('Photo file info:', [
                'original_name' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
                'size' => $image->getSize(),
            ]);

            $folder = 'uploads/doctors/profile_photos';
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '_' . uniqid() . '.' . $extension;

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image->move(public_path($folder), $imageName);
            $databasePath = $folder . '/' . $imageName;
            \Log::info('Photo uploaded successfully', ['path' => $databasePath]);
        } else {
            \Log::warning('No photo file found in request!');
        }

        // ======================
        // 2. Create User
        // ======================
        \Log::info('Creating user...');
        $user = new \App\Models\User();
        $userData = [
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'role'           => 'tenant',
            'mobile'         => $validated['phone'],
            'qualification'  => $validated['qualification'],
            'reg_no'         => $validated['reg_no'] ?? null,
            'specialization' => $validated['specialty'],
            'country'        => $validated['country'],
            'photo'          => $databasePath,
            'status'         => 1,
        ];

        \Log::info('User data to create:', $userData);

        $user->fill($userData);

        // Save user
        if ($user->save()) {
            \Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } else {
            \Log::error('Failed to save user');
            throw new \Exception('Failed to create user account');
        }

        // ======================
        // 3. Determine Domain
        // ======================
        \Log::info('Determining domain...');
        $domainName = '';
        $domainType = $validated['domain_type'];
        $baseDomain = config('app.base_domain', 'doctorsprofile.xyz');

        switch ($domainType) {
            case 'new':
                $domainName = ($validated['new_domain_name'] ?? '') . ($validated['new_domain_extension'] ?? '');
                break;
            case 'subdomain':
                $domainName = ($validated['subdomain_name'] ?? '') . '.' . $baseDomain;
                break;
            case 'existing':
                $domainName = $this->sanitizeDomain($validated['existing_domain'] ?? '');
                break;
        }

        \Log::info('Domain determined', ['domain' => $domainName, 'type' => $domainType]);

        // ======================
        // 4. Get Package Details
        // ======================
        \Log::info('Getting package details...');
        $package = \App\Models\Package::findOrFail($validated['package_id']);
        $billingCycle = $validated['selected_billing_cycle'];

        // If free trial, set billing to monthly after trial
        $trialEndsAt = null;
        if ($billingCycle === 'free') {
            $billingCycle = 'monthly';
            $trialEndsAt = now()->addDays(14);
            \Log::info('Free trial activated', ['ends_at' => $trialEndsAt]);
        }

        // ======================
        // 5. Create Tenant
        // ======================
        \Log::info('Creating tenant...');
        $tenantId = Str::uuid();
        $tenantData = [
            'id' => $tenantId,
            'name' => $user->name . ' - ' . $package->name,
            'status' => 1,
            'package_id' => $package->id,
            'billing_cycle' => $billingCycle,
            'monthly_price' => $package->price_monthly,
            'yearly_price' => $package->price_yearly,
            'storage_gb' => $package->storage_gb,
            'trial_ends_at' => $trialEndsAt,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        \Log::info('Tenant data:', $tenantData);

        $tenant = Tenant::create($tenantData);

        if ($tenant) {
            \Log::info('Tenant created successfully', ['tenant_id' => $tenant->id]);
        } else {
            throw new \Exception('Failed to create tenant');
        }

        // Update user with tenant_id
        $user->tenant_id = $tenant->id;
        $user->save();
        \Log::info('User updated with tenant_id', ['tenant_id' => $tenant->id]);

        // ======================
        // 6. Create Domain
        // ======================
        \Log::info('Creating domain...');
        $domainRow = $tenant->domains()->create([
            'domain'            => $domainName,
            'type'              => $domainType,
            'registration_fee'  => $validated['domain_price'],
            'status'            => 1,
        ]);

        \Log::info('Domain created', ['domain_id' => $domainRow->id]);

        // ======================
        // 7. Initialize Tenancy & Create Tenant User
        // ======================
        \Log::info('Initializing tenancy...');
        tenancy()->initialize($tenant);

        $tenantUserData = [
            'id'       => Str::uuid(),
            'name'     => $user->name,
            'role'     => 'admin',
            'status'   => 1,
            'password' => $user->password,
            'email'    => $user->email,
            'mobile'   => $user->mobile,
            'photo'    => $databasePath,
        ];

        \Log::info('Creating tenant user...', $tenantUserData);
        $tuser = User::create($tenantUserData);

        if ($tuser) {
            \Log::info('Tenant user created', ['tenant_user_id' => $tuser->id]);
        }

        // ======================
        // 8. Create Tenant Settings
        // ======================
        \Log::info('Creating tenant settings...');
        $settings = new Setting();
        $settingsData = [
            'site_name'     => $user->name . ' Medical Practice',
            'site_email'    => $user->email,
            'site_phone'    => $user->mobile,
            'package_id'    => $package->id,
            'billing_cycle' => $billingCycle,
            'specialization'=> $user->specialization,
            'qualification' => $user->qualification,
            'country'       => $user->country,
        ];

        $settings->fill($settingsData);
        $settings->save();
        \Log::info('Tenant settings created');

        // ======================
        // 9. Create Payment Record
        // ======================
        \Log::info('Creating payment record...');
        $paymentData = [
            'tenant_id'      => $tenant->id,
            'user_id'        => $user->id,
            'package_id'     => $package->id,
            'amount'         => $validated['total_amount'],
            'package_amount' => $validated['package_price'],
            'domain_amount'  => $validated['domain_price'],
            'discount_amount'=> $validated['discount_amount'],
            'payment_method' => $validated['payment_method'],
            'status'         => 'completed',
            'payment_date'   => now(),
            'billing_cycle'  => $billingCycle,
        ];

        $payment = new \App\Models\Payment();
        $payment->fill($paymentData);
        $payment->save();
        \Log::info('Payment record created', ['payment_id' => $payment->id]);

        // ======================
        // 10. Trigger domain creation event
        // ======================
        \Log::info('Triggering TenantDomainCreated event...');
        event(new \App\Events\TenantDomainCreated($domainRow));

        // ======================
        // 11. End Tenancy
        // ======================
        tenancy()->end();
        \Log::info('Tenancy ended');

        // ======================
        // 12. Commit transaction
        // ======================
        DB::commit();
        \Log::info('Database transaction committed successfully');

        // ======================
        // 13. Login User
        // ======================
        \Log::info('Logging in user...');
        Auth::login($user);

        // ======================
        // 14. Return success response
        // ======================
        \Log::info('========== REGISTRATION COMPLETED SUCCESSFULLY ==========');

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Registration successful! Your account has been created.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('========== VALIDATION FAILED ==========');
        \Log::error('Validation errors:', $e->errors());
        \Log::error('Request data:', $request->all());

        return back()
            ->withErrors($e->validator)
            ->withInput($request->except(['password', 'password_confirmation']));

    } catch (\Exception $e) {
        \Log::error('========== REGISTRATION FAILED ==========');
        \Log::error('Error message: ' . $e->getMessage());
        \Log::error('Error trace: ' . $e->getTraceAsString());
        \Log::error('Error file: ' . $e->getFile() . ':' . $e->getLine());

        if (isset($user)) {
            \Log::error('User object exists but not saved');
        }

        if (DB::transactionLevel() > 0) {
            DB::rollBack();
            \Log::info('Database transaction rolled back');
        }

        return back()
            ->withInput($request->except(['password', 'password_confirmation', 'card_number', 'cvv']))
            ->with('error', 'Registration failed: ' . $e->getMessage());
    }
}

private function sanitizeDomain(?string $value): string
{
    if (!$value) return '';

    $v = strtolower(trim($value));
    $v = preg_replace('#^(?:https?:\/\/)?(?:www\.)?#', '', $v);
    $v = preg_split('/[\/?#]/', $v, 2)[0] ?? $v;
    $v = preg_replace('/[^a-z0-9.-]/', '', $v);
    $v = trim($v, '.-');

    return $v;
}

private function detectCardType($cardNumber): string
{
    $cardNumber = str_replace(' ', '', $cardNumber);

    if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) {
        return 'visa';
    } elseif (preg_match('/^5[1-5][0-9]{14}$/', $cardNumber)) {
        return 'mastercard';
    } elseif (preg_match('/^3[47][0-9]{13}$/', $cardNumber)) {
        return 'amex';
    } elseif (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $cardNumber)) {
        return 'discover';
    }

    return 'unknown';
}
    public function destroy($id)
    {
        $user = User::where('role', 'tenant')->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }

        $tenantId = $user->tenant_id;

        DB::transaction(function () use ($user, $tenantId) {
            if ($tenantId) {
                Domain::where('tenant_id', $tenantId)->delete();

                $tenant = Tenant::find($tenantId);
                if ($tenant) {
                    $tenant->delete();
                }
            }

            $user->delete();
        });

        return redirect()->back()->with('success', 'Doctor deleted successfully.');
    }
    public function allTanant()
    {
        return \App\Models\Tenant::all();
    }
// toggleUserStatus

public function toggleUserStatus($id, $status)
{
    // Superadmin selected user
    $adminUser = User::find($id);

    if (!$adminUser) {
        return back()->with('error', 'User not found.');
    }

    // Update admin user status
    $adminUser->status = $status;
    $adminUser->save();

    // Tenant Admin User (email receiver)
    $tenantUser = User::where('tenant_id', $adminUser->tenant_id)
        ->where('role', 'tenant')
        ->first();
    

    if (!$tenantUser) {
        return back()->with('error', 'Tenant admin user not found.');
    }

    // Tenant model
    $tenant = Tenant::find($adminUser->tenant_id);

    if (!$tenant || $tenant->domains->isEmpty()) {
        return back()->with('error', 'Tenant or domain not found.');
    }

    // Update tenant status
    $tenant->status = $status;
    $tenant->save();
    tenancy()->initialize($tenant);
    $tenantUserData = User::where('email', $tenantUser->email)->where('role', 'admin')->first();
    $tenantUserData->status = $status;
    $tenantUserData->save();
    tenancy()->end();

    $email = $tenantUser->email;

    $statusText = $status == 1 ? 'Active' : 'Suspended';

    $subject = $status == 1
        ? 'Your DoctorsProfile Account Has Been Activated'
        : 'Your DoctorsProfile Account Has Been Suspended';

    try {
        Mail::send('emails.tenant_status_changed', [
            'email'       => $email,
            'status'      => $statusText,
            'subject'     => $subject,
            'domain'      => $tenant->domains->first()->domain,
            'site_url'    => 'https://' . $tenant->domains->first()->domain,
            'adminlogin'  => 'https://' . $tenant->domains->first()->domain . '/login',
        ], function ($message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject)
                ->from('admin@doctorsprofile.xyz', 'DoctorsProfile Support Team');
        });
    } catch (\Exception $e) {
        \Log::error('Mail failed: ' . $e->getMessage());
        return back()->with('error', 'Status updated but email failed.');
    }

    return back()->with('success', 'Doctor status updated and email sent successfully.');
}


// setTemplate
public function setTemplate($value)
{
//dd($value);
$tenatId=Auth::user()->tenant_id;
//dd($tenatId);
$tenant = Tenant::find($tenatId);

tenancy()->initialize($tenant);

$settings = \App\Models\Setting::query()->first();
$settings->template=$value;
$settings->save();
tenancy()->end();
return redirect()->back()->with('success', 'Template updated successfully.');



}



}
