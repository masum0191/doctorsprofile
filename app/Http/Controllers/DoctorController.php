<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Chamber;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Subscription;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Services\SSLCommerzService;
use App\Jobs\InitializeTenantData;
use App\Services\PricingService;


class DoctorController extends Controller
{
    public function create()
    {
        $packages = Package::where('status', 1)->get();
        return view('doctor.register', compact('packages'));
    }

    public function storeall1(Request $request)
    {
       // dd($request->all());
       // return "<h1>Doctor Registration</h1>";
        Log::info('========== DOCTOR REGISTRATION STARTED ==========');
        Log::info('Request data:', $request->except(['password', 'password_confirmation', 'card_number', 'cvv']));

      //  try {
            Log::info('Starting validation...');

            $validated = Validator::make($request->all(), [
                // Step 3
                'specialty' => ['required', 'string', 'max:100'],
                'country'   => ['required', 'string', 'max:100'],
                'reg_no'    => [
                    'nullable', 'string', 'max:50',
                    Rule::requiredIf(fn () => ($request->country === 'Bangladesh')),
                    Rule::unique('mysql.users', 'reg_no')
                ],

                // Step 4
                'name'          => ['required', 'string', 'max:255'],
                'qualification' => ['required', 'string', 'max:255'],

                // Step 5
                'email'     => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
                'phone'     => ['required', 'string', 'max:20'],
                'password'  => ['required', 'confirmed', 'min:8'],
                'photo'     => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],

                // Step 1
                'package_id'             => ['required', 'exists:packages,id'],
                'selected_billing_cycle' => ['required', Rule::in(['monthly', 'yearly', 'free'])],

                // Step 2
                'domain_type'         => ['required', Rule::in(['new', 'subdomain', 'existing'])],
                'subdomain_name'      => ['required_if:domain_type,subdomain', 'nullable', 'string', 'max:100'],
                'new_domain_name'     => ['required_if:domain_type,new', 'nullable', 'string', 'max:100'],
                'new_domain_extension'=> ['required_if:domain_type,new', 'nullable', 'string', 'max:10'],
                'existing_domain'     => ['required_if:domain_type,existing', 'nullable', 'string', 'max:255'],

                // Step 6
                'payment_method' => ['nullable', Rule::in(['credit_card', 'digital_wallet', 'bank_transfer'])],
                'terms'          => ['required', 'accepted'],

                // price fields
                'package_price'   => ['required', 'numeric', 'min:0'],
                'domain_price'    => ['required', 'numeric', 'min:0'],
                'discount_amount' => ['required', 'numeric', 'min:0'],
                'total_amount'    => ['required', 'numeric', 'min:0'],

                // optional
                'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'city'      => ['nullable', 'string', 'max:255'],

                'coupon_id' => ['nullable', 'exists:coupons,id'],
            ], [
                'reg_no.required_if' => 'BMDC registration number is required for doctors in Bangladesh',
                'photo.required' => 'Please upload your professional photo',
                'terms.accepted' => 'You must accept the terms and conditions',
                'subdomain_name.required_if' => 'Subdomain name is required',
                'new_domain_name.required_if' => 'Domain name is required',
                'existing_domain.required_if' => 'Existing domain is required',
            ])->validate();

            Log::info('Validation passed', ['email' => $validated['email']]);

            DB::beginTransaction();
            Log::info('Database transaction started');

            // 1) Photo upload
            $databasePath = '';
            if ($request->hasFile('photo')) {
                Log::info('Processing photo upload...');
                $image = $request->file('photo');
                $folder = 'uploads/doctors/profile_photos';
                $extension = $image->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;

                if (!file_exists(public_path($folder))) {
                    mkdir(public_path($folder), 0755, true);
                }

                $image->move(public_path($folder), $imageName);
                $databasePath = $folder . '/' . $imageName;
                Log::info('Photo uploaded', ['path' => $databasePath]);
            }

            // 2) Create central user
            Log::info('Creating central user...');
            $user = new User();

            $user->fill([
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'password'       => Hash::make($validated['password']),
                'role'           => 'tenant',
                'mobile'         => $validated['phone'],
                'qualification'  => $validated['qualification'],
                'reg_no'         => $validated['reg_no'] ?? null,
                'specialization' => $validated['specialty'],
                'country'        => $validated['country'],
                'latitude'       => $validated['latitude'] ?? null,
                'longitude'      => $validated['longitude'] ?? null,
                'city'           => $validated['city'] ?? null,
                'photo'          => $databasePath,
                'status'         => 1,
            ]);

            $user->save();

            // 3) Determine domain name
            $domainType = $validated['domain_type'];
            $baseDomain = config('app.base_domain', 'doctorsprofile.xyz');

            $domainName = '';
            if ($domainType === 'new') {
                $domainName = ($validated['new_domain_name'] ?? '') . ($validated['new_domain_extension'] ?? '');
            } elseif ($domainType === 'subdomain') {
                $domainName = ($validated['subdomain_name'] ?? '') . '.' . $baseDomain;
            } else { // existing
              //  return 1;
                $domainName = $this->sanitizeDomain($validated['existing_domain'] ?? '');
            }

            // ensure unique in central domains
            if (Domain::where('domain', $domainName)->exists()) {
                throw new \Exception('This domain is already taken. Please choose another.');
            }

            // 4) Package + billing
            $package = Package::findOrFail($validated['package_id']);
            $billingCycle = $validated['selected_billing_cycle'];

            $trialEndsAt = null;
            if ($billingCycle === 'free') {
                $billingCycle = 'monthly';
                $trialEndsAt = now()->addDays(14);
            }

            // 5) Create tenant
            $tenantId = (string) Str::uuid();

            $tenant = Tenant::create([
                'id' => $tenantId,
                'name' => $user->name . ' - ' . $package->name,
                'status' => 1,
                'package_id' => $package->id,
                'billing_cycle' => $billingCycle,
                'monthly_price' => $package->price_monthly,
                'yearly_price' => $package->price_yearly,
                'storage_gb' => $package->storage_gb,
                'trial_ends_at' => $trialEndsAt,
            ]);

            // update central user with tenant_id
            $user->tenant_id = $tenant->id;
            $user->save();

            // 6) Create domain in central
            $domainRow = $tenant->domains()->create([
                'domain'           => $domainName,
                'type'             => $domainType,
                'registration_fee' => $validated['domain_price'],
                'status'           => 1,
            ]);


            // 8) Coupon usage
            if (!empty($validated['coupon_id'])) {
                $coupon = Coupon::find($validated['coupon_id']);
                if ($coupon) {
                    $coupon->increment('used_count');

                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id'   => $user->id,
                        'tenant_id' => $tenant->id,
                        'amount'    => $validated['discount_amount'],
                    ]);
                }
            }

            // 9) Initialize tenancy and create tenant user + setting + chamber
            tenancy()->initialize($tenant);

            // IMPORTANT: This creates user in TENANT DB (not central)
            $tuser = \App\Models\User::create([
                'id'       => (string) Str::uuid(),
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $user->password,
                'mobile'   => $user->mobile,
                'photo'    => $databasePath,
                'role'     => 'admin',
                'status'   => 1,
                'latitude' => $user->latitude,
                'longitude'=> $user->longitude,
            ]);

            // Tenant settings
            Setting::create([
                'site_name'      => $user->name . ' Medical Practice',
                'site_email'     => $user->email,
                'site_phone'     => $user->mobile,
                'package_id'     => $package->id,
                'billing_cycle'  => $billingCycle,
                'specialization' => $user->specialization,
                'qualification'  => $user->qualification,
                'country'        => $user->country,
            ]);

            // 7) Create payment record in central
            $paymentData = [
                'tenant_id'       => $tenant->id,
                'user_id'         => $user->id,
                'package_id'      => $package->id,
                'amount'          => $validated['total_amount'],
                'package_amount'  => $validated['package_price'],
                'domain_amount'   => $validated['domain_price'],
                'discount_amount' => $validated['discount_amount'],
                'payment_method'  => $validated['payment_method'] ?? 'offline',
                'status'          => 'completed',
                'payment_date'    => now(),
                'billing_cycle'   => $billingCycle,
                'coupon_id'       => $validated['coupon_id'] ?? null,
            ];

            if ($validated['payment_method'] === 'credit_card' && $request->filled('card_number')) {
                $paymentData['card_last_four'] = substr(str_replace(' ', '', $request->card_number), -4);
                $paymentData['card_type'] = $this->detectCardType($request->card_number);
            }

            $payment = Payment::create($paymentData);
            // Tenant chamber (use tenant user id)
            Chamber::create([
                'doctor_id' => $tuser->id,
                'name'      => $user->name . ' Chamber',
                'address'   => '', // keep safe defaults unless you collect it
                'city'      => $user->city ?? '',
                'fees'     => '00.00',
                'type'   => 'General',
                'schedule'  => [],
                'is_active' => true,
            ]);

            tenancy()->end();

            // event
            event(new \App\Events\TenantDomainCreated($domainRow));
        /* =====================================================
           7️⃣ CREATE SUBSCRIPTION
        ====================================================== */

        $startsAt = now();

        $endsAt = $billingCycle === 'yearly'
            ? now()->addYear()
            : now()->addMonth();

        if ($validated['payment_option'] === 'offline') {
            $subscriptionStatus = 'pending';
        }
        elseif ($validated['total_amount'] == 0) {
            $subscriptionStatus = 'active';
        }
        else {
            $subscriptionStatus = 'pending';
        }

        Subscription::create([
            'doctor_id'=>$user->id,
            'tenant_id'=>$tenant->id,
            'package_id'=>$package->id,
            'billing_cycle'=>$billingCycle,
            'starts_at'=>$startsAt,
            'ends_at'=>$endsAt,
            'status'=>$subscriptionStatus,
        ]);
            DB::commit();

            Auth::login($user);

            return redirect()->route('doctor.dashboard')
                ->with('success', 'Registration successful! Your account has been created.');

        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     Log::error('VALIDATION FAILED', $e->errors());

        //     return back()
        //         ->withErrors($e->validator)
        //         ->withInput($request->except(['password', 'password_confirmation']));

        // } catch (\Exception $e) {
        //     Log::error('REGISTRATION FAILED: ' . $e->getMessage(), [
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //     ]);

        //     if (DB::transactionLevel() > 0) {
        //         DB::rollBack();
        //     }

        //     return back()
        //         ->withInput($request->except(['password', 'password_confirmation', 'card_number', 'cvv']))
        //         ->with('error', 'Registration failed: ' . $e->getMessage());
        // }
    }
public function storeall(Request $request)
{
   //dd($request->all());
    Log::info('========== DOCTOR REGISTRATION STARTED ==========');
    Log::info('Request data:', $request->except(['password', 'password_confirmation', 'card_number', 'cvv']));

  //  try {
        Log::info('Starting validation...');

        $validated = Validator::make($request->all(), [
            // Step 5 (Now Step 3 after removal)
            'name'=>['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
            'phone'     => ['required', 'string', 'max:20'],
            'password'  => ['required', 'confirmed', 'min:8'],
            'photo'     => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],

            // Step 1
            'package_id'             => ['required', 'exists:packages,id'],
            'selected_billing_cycle' => ['required', Rule::in(['monthly', 'yearly', 'free'])],

            // Step 2
            'domain_type'         => ['required', Rule::in(['new', 'subdomain', 'existing'])],
            'subdomain_name'      => ['required_if:domain_type,subdomain', 'nullable', 'string', 'max:100'],
            'new_domain_name'     => ['required_if:domain_type,new', 'nullable', 'string', 'max:100'],
            'new_domain_extension'=> ['required_if:domain_type,new', 'nullable', 'string', 'max:10'],
            'existing_domain'     => ['required_if:domain_type,existing', 'nullable', 'string', 'max:255'],

            // Step 6 (Now Step 4 after removal)
            'payment_method' => ['nullable', 'required_if:payment_option,online', Rule::in(['paypal', 'sslcommerz', 'credit_card', 'bank_transfer', 'offline'])],
            'payment_option' => ['required', Rule::in(['online', 'offline'])],
            'terms'          => ['required', 'accepted'],

            // Coupon
            'coupon_code' => ['nullable', 'string', 'max:50'],

            // price fields
            'package_price'   => ['required', 'numeric', 'min:0'],
            'domain_price'    => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'total_amount'    => ['required', 'numeric', 'min:0'],

            // optional
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'city'      => ['nullable', 'string', 'max:255'],

        ], [
            'photo.required' => 'Please upload your professional photo',
            'terms.accepted' => 'You must accept the terms and conditions',
            'subdomain_name.required_if' => 'Subdomain name is required',
            'new_domain_name.required_if' => 'Domain name is required',
            'existing_domain.required_if' => 'Existing domain is required',
        ])->validate();
//dd($validated);
        Log::info('Validation passed', ['email' => $validated['email']]);

        Log::info('Creating registration records');

        // 1) Photo upload
        $databasePath = '';
        if ($request->hasFile('photo')) {
            Log::info('Processing photo upload...');
            $image = $request->file('photo');
            $folder = 'uploads/doctors/profile_photos';
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '_' . uniqid() . '.' . $extension;

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image->move(public_path($folder), $imageName);
            $databasePath = $folder . '/' . $imageName;
            Log::info('Photo uploaded', ['path' => $databasePath]);
        }

        // 2) Validate coupon if provided
        $coupon = null;
        $couponDiscount = 0;
        if (!empty($validated['coupon_code'])) {
            Log::info('Validating coupon', ['code' => $validated['coupon_code']]);

            $coupon = Coupon::where('code', strtoupper(trim($validated['coupon_code'])))->first();

            if (!$coupon || !$coupon->isCurrentlyValid()) {

                throw new \Exception('Invalid or expired coupon code');
                // massive error

            }

            // Calculate coupon discount
            $subtotal = $validated['package_price'] + $validated['domain_price'];
            $couponDiscount = $coupon->calculateDiscount($subtotal);

            Log::info('Coupon discount calculated', [
                'coupon_id' => $coupon->id,
                'discount' => $couponDiscount
            ]);
        }

        // 3) Create central user (name from email or default)
        Log::info('Creating central user...');
        $user = new User();

        // Extract name from email if not provided
        $emailName = explode('@', $validated['email'])[0];
        $name = ucwords(str_replace('.', ' ', $emailName));

        $user->fill([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'role'           => 'tenant',
            'mobile'         => $validated['phone'],
            'qualification'  => 'Medical Professional', // Default
            'reg_no'         => null,
            'specialization' => 'General Practitioner', // Default
            'country'        => 'Bangladesh', // Default
            'latitude'       => $validated['latitude'] ?? null,
            'longitude'      => $validated['longitude'] ?? null,
            'city'           => $validated['city'] ?? null,
            'photo'          => $databasePath,
            'status'         => 0,
            'feature'        => 0,
        ]);

        $user->save();

        // 4) Determine domain name
        $domainType = $validated['domain_type'];
        $baseDomain = config('app.base_domain', 'doctorsprofile.xyz');

        $domainName = '';
        if ($domainType === 'new') {
            $domainName = $this->sanitizeDomain(($validated['new_domain_name'] ?? '') . ($validated['new_domain_extension'] ?? ''));
        } elseif ($domainType === 'subdomain') {
            $subdomain = strtolower(trim((string) ($validated['subdomain_name'] ?? '')));
            $subdomain = preg_replace('/[^a-z0-9-]/', '', $subdomain);
            $domainName = trim($subdomain, '-') . '.' . $baseDomain;
        } else { // existing
        // dd($validated['existing_domain']);
            $domainName = $this->sanitizeDomain($validated['existing_domain'] ?? '');
        }

        if (empty($domainName)) {
            throw new \Exception('Please provide a valid domain name.');
        }

        // ensure unique in central domains
        if (Domain::where('domain', $domainName)->exists()) {
            throw new \Exception('This domain is already taken. Please choose another.');
        }

        // 5) Package + billing
        $package = Package::findOrFail($validated['package_id']);
        $billingCycle = $validated['selected_billing_cycle'];

        if (in_array($domainType, ['new', 'existing'], true) && !$package->hasFeature('custom_domain')) {
            throw new \Exception('The selected package only supports platform subdomains. Please choose a package with custom domain support.');
        }

        $trialEndsAt = null;
        if ($billingCycle === 'free') {
            $billingCycle = 'monthly';
            $trialEndsAt = now()->addDays(14);
        }

        // 6) Create tenant
        $tenantId = (string) Str::uuid();

        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => $user->name . ' - ' . $package->name,
            'status' => $validated['payment_option'] === 'offline' ? 0 : 1,
            'package_id' => $package->id,
            'billing_cycle' => $billingCycle,
            'monthly_price' => $package->price_monthly,
            'yearly_price' => $package->price_yearly,
            'storage_gb' => $package->storage_gb,
            'trial_ends_at' => $trialEndsAt,
        ]);

        // update central user with tenant_id
        $user->tenant_id = $tenant->id;
        $user->save();

        // 7) Create domain in central
        $domainRow = $tenant->domains()->create([
            'domain'           => $domainName,
            'type'             => $domainType,
            'registration_fee' => $validated['domain_price'],
            'status'           => $validated['payment_option'] === 'offline' ? 0 : 1,
        ]);

        // 8) Initialize tenancy and create tenant user + setting + chamber
        tenancy()->initialize($tenant);

        // IMPORTANT: This creates user in TENANT DB (not central)
        $tuser = \App\Models\User::create([
            'id'       => (string) Str::uuid(),
            'name'     => $user->name,
            'email'    => $user->email,
            'password' => $user->password,
            'mobile'   => $user->mobile,
            'photo'    => $databasePath,
            'role'     => 'admin',
            'status'   => 0,
            'package'  =>$package->id,
            'latitude' => $user->latitude,
            'longitude'=> $user->longitude,

        ]);

        // Assign admin role using Spatie
     //   $tuser->assignRole('admin');

        // Tenant settings
        Setting::create([
            'site_name'      => $user->name . ' Medical Practice',
            'site_email'     => $user->email,
            'site_phone'     => $user->mobile,
            //'package_id'     => $package->id,
            'billing_cycle'  => $billingCycle,
            'specialization' => $user->specialization,
            'qualification'  => $user->qualification,
            'country'        => $user->country,
        ]);

        // Tenant chamber
        Chamber::create([
            'doctor_id' => $tuser->id,
            'name'      => $user->name . ' Chamber',
            'address'   => '',
            'city'      => $user->city ?? '',
            'fees'      => '00.00',
            'type'      => 'fixed',
            'schedule'  => [],
            'is_active' => true,
        ]);
    $payment = Payment::create([
    //'tenant_id' => $tenantId,
    'user_id' => $tuser->id,
    'package_id' => $package->id,
    'amount' => $billingCycle === 'monthly'
        ? $package->price_monthly
        : $package->price_yearly,
    'status' => 'pending',
    'billing_cycle' => $billingCycle,
]);

        tenancy()->end();
        InitializeTenantData::dispatch($tenant->id);
 // 12) Fire events
        event(new \App\Events\TenantDomainCreated($domainRow));
        // 9) Process payment based on method
        $paymentStatus = 'pending';
        if ($validated['payment_option'] === 'online') {
            $paymentStatus = 'pending';

            // Initialize payment based on method
            if ($validated['payment_method'] === 'paypal') {
                return $this->initiatePayPalPayment($tenant, $user, $package, $validated, $coupon);
            } elseif ($validated['payment_method'] === 'sslcommerz') {
                $paymentUrl = $this->initiateSSLCommerzPaymentApi(
                    $tenant,
                    $user,
                    $package,
                    $validated,
                    $coupon
                );

                return redirect()->away($paymentUrl);
            } elseif ($validated['payment_method'] === 'credit_card') {
                $paymentStatus = 'completed';
            } elseif ($validated['payment_method'] === 'bank_transfer') {
                $paymentStatus = 'pending';
            } else {
                throw new \Exception('Unsupported payment gateway selected.');
            }
        } else {
            // Offline payment
            $paymentStatus = 'pending_approval';
        }

        // 10) Create payment record
        $paymentData = [
            'tenant_id'       => $tenant->id,
            'user_id'         => $user->id,
            'package_id'      => $package->id,
            'amount'          => $validated['total_amount'],
            'package_amount'  => $validated['package_price'],
            'domain_amount'   => $validated['domain_price'],
            'discount_amount' => $couponDiscount,
            'payment_method'  => $validated['payment_method']   ?? 'offline',
            'payment_option'  => $validated['payment_option'],
            'status'          => $paymentStatus,
            //'payment_date'    => now(),
            'billing_cycle'   => $billingCycle,
            'coupon_id'       => $coupon->id ?? null,
        ];

        // if ($validated['payment_method'] === 'credit_card' && $request->filled('card_number')) {
        //     $paymentData['card_last_four'] = substr(str_replace(' ', '', $request->card_number), -4);
        //     $paymentData['card_type'] = $this->detectCardType($request->card_number);
        // }

        $payment = Payment::create($paymentData);

        // 11) Update coupon usage if applied
        if ($coupon) {
            $coupon->increment('used_count');

            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id'   => $user->id,
                'tenant_id' => $tenant->id,
                'amount'    => $couponDiscount,
            ]);
        }

                /* =====================================================
           7️⃣ CREATE SUBSCRIPTION
        ====================================================== */

        $startsAt = now();

        $endsAt = $billingCycle === 'yearly'
            ? now()->addYear()
            : now()->addMonth();

        if ($validated['payment_option'] === 'offline') {
            $subscriptionStatus = 'pending';
        }
        elseif ($validated['total_amount'] == 0) {
            $subscriptionStatus = 'active';
        }
        else {
            $subscriptionStatus = 'pending';
        }

        Subscription::create([
            'doctor_id'=>$user->id,
            'tenant_id'=>$tenant->id,
            'package_id'=>$package->id,
            'billing_cycle'=>$billingCycle,
            'starts_at'=>$startsAt,
            'ends_at'=>$endsAt,
            'status'=>$subscriptionStatus,
        ]);

        // 13) Handle redirection based on payment status
        if ($validated['payment_option'] === 'offline') {
            // superadmin/dashboard
            if(auth()->user()){
            return redirect('/superadmin/dashboard');

            }else{
            Auth::login($user);
            return redirect('/superadmin/dashboard');
            }


            // return redirect()->route('registration.pending');
                //->with('success', 'Registration submitted! Please wait for admin approval and payment verification.');
        } elseif ($paymentStatus === 'completed') {
            // Auth::login($user);
            return redirect()->route('admin.login');
                //->with('success', 'Registration successful! Your account has been created.');
        } else {
            return redirect()->route('registration.success');
              //  ->with('success', 'Registration submitted! Please complete your payment to activate your account.');
        }

    // } catch (\Illuminate\Validation\ValidationException $e) {
    //     Log::error('VALIDATION FAILED', $e->errors());

    //     return back()
    //         ->withErrors($e->validator)
    //         ->withInput($request->except(['password', 'password_confirmation']));

    // } catch (\Exception $e) {
    //     Log::error('REGISTRATION FAILED: ' . $e->getMessage(), [
    //         'file' => $e->getFile(),
    //         'line' => $e->getLine(),
    //     ]);

    //     if (DB::transactionLevel() > 0) {
    //         DB::rollBack();
    //     }

    //     return back()
    //         ->withInput($request->except(['password', 'password_confirmation', 'card_number', 'cvv']))
    //         ->with('error', 'Registration failed: ' . $e->getMessage());
    // }
}

private function initiatePayPalPayment($tenant, $user, $package, $data, $coupon = null)
{
    $provider = new \Srmklive\PayPal\Services\PayPal(config('paypal'));

    $provider->getAccessToken();

    $orderData = [
        "intent" => "CAPTURE",
        "purchase_units" => [
            [
                "reference_id" => $tenant->id,
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $data['total_amount']
                ],
                "description" => "Doctor Registration: " . $package->name
            ]
        ],
        "application_context" => [
            "cancel_url" => route('payment.cancel'),
            "return_url" => route('payment.success')
        ]
    ];

    $order = $provider->createOrder($orderData);

    // Store payment session
    session([
        'payment_session' => [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'order_id' => $order['id'],
            'amount' => $data['total_amount'],
            'coupon_id' => $coupon->id ?? null
        ]
    ]);

    // Redirect to PayPal
    foreach ($order['links'] as $link) {
        if ($link['rel'] === 'approve') {
            return redirect()->away($link['href']);
        }
    }

    throw new \Exception('PayPal order creation failed');
}

private function initiateSSLCommerzPayment($tenant, $user, $package, $data, $coupon = null)
{
    $post_data = [];
    $post_data['store_id'] = config('sslcommerz.store_id');
    $post_data['store_passwd'] = config('sslcommerz.store_password');
    $post_data['total_amount'] = $data['total_amount'];
    $post_data['currency'] = "USD";
    $post_data['tran_id'] = uniqid();
    $post_data['success_url'] = route('sslcommerz.success');
    $post_data['fail_url'] = route('sslcommerz.fail');
    $post_data['cancel_url'] = route('sslcommerz.cancel');
    $post_data['cus_name'] = $user->name;
    $post_data['cus_email'] = $user->email;
    $post_data['cus_phone'] = $user->mobile;

    // Store in session
    session([
        'sslcommerz_payment' => [
            'tran_id' => $post_data['tran_id'],
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'amount' => $data['total_amount'],
            'coupon_id' => $coupon->id ?? null
        ]
    ]);

    // Initiate payment
    $sslc = new \App\Services\SSLCommerzService();
    $payment_url = $sslc->initiatePayment($post_data);

    if ($payment_url) {
        return redirect()->away($payment_url);
    }

    throw new \Exception('SSLCommerz payment initiation failed');
}
    public function checkSubdomain(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subdomain' => [
                    'required', 'string', 'min:3', 'max:63',
                    'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/'
                ]
            ], [
                'subdomain.required' => 'Please enter a subdomain name',
                'subdomain.min' => 'Subdomain must be at least 3 characters',
                'subdomain.max' => 'Subdomain cannot exceed 63 characters',
                'subdomain.regex' => 'Subdomain can only contain letters, numbers, and hyphens',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'available' => false,
                    'message'   => $validator->errors()->first('subdomain'),
                    'errors'    => $validator->errors(),
                ], 422);
            }

            $sub = strtolower(trim($request->input('subdomain')));
            $base = config('app.base_domain', 'doctorsprofile.xyz');
            $full = $sub . '.' . $base;

            // Check if domain exists in database
            $exists = Domain::where('domain', $full)->exists();

            if (!$exists) {
                return response()->json([
                    'available' => true,
                    'subdomain' => $sub,
                    'fullDomain'=> $full,
                ]);
            }

            // Find alternative subdomain suggestions
            $suggestion = null;
            for ($i = 1; $i <= 30; $i++) {
                $alt = $sub . $i;
                $altFull = $alt . '.' . $base;
                if (!Domain::where('domain', $altFull)->exists()) {
                    $suggestion = $alt;
                    break;
                }
            }

            return response()->json([
                'available'  => false,
                'subdomain'  => $sub,
                'fullDomain' => $full,
                'suggestion' => $suggestion,
            ]);
        } catch (\Exception $e) {
            Log::error('checkSubdomain failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'subdomain' => $request->input('subdomain'),
            ]);

            return response()->json([
                'available' => false,
                'message'   => 'Unable to check subdomain at this time. Please try again later.',
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], 500);
        }
    }
 private function initiateSSLCommerzPaymentApi($tenant, $user, $package, $data, $coupon = null)
{
    try {
        $totalAmount = (float)$data['total_amount'];
        $sslc = new SSLCommerzService();
        $sslcommerzAmount = app(PricingService::class)->convertFromUsd($totalAmount, 'BDT');

        // Create payment data
        $postData = $sslc->createDoctorRegistrationData($user, $package, $sslcommerzAmount);
        $postData['currency'] = 'BDT';

        // Add API URLs for callbacks
        $postData['success_url'] = url('/sslcommerz/success');
        $postData['fail_url'] = url('/sslcommerz/fail');
        $postData['cancel_url'] = url('/sslcommerz/cancel');
        $postData['ipn_url'] = url('/sslcommerz/ipn');

        // Store payment session in database
        \App\Models\PaymentSession::create([
            'session_id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'order_id' => $postData['tran_id'],
            'amount' => $sslcommerzAmount,
            'coupon_id' => $coupon->id ?? null,
            'payment_gateway' => 'sslcommerz',
            'expires_at' => now()->addHours(24)
        ]);

        Log::info('Initiating SSLCommerz payment', [
            'tran_id' => $postData['tran_id'],
            'amount' => $postData['total_amount'],
            'user_email' => $user->email
        ]);

        // Initiate payment
        $paymentUrl = $sslc->initiatePayment($postData);

        if ($paymentUrl) {
            Log::info('SSLCommerz payment URL generated', [
                'tran_id' => $postData['tran_id'],
                'payment_url' => substr($paymentUrl, 0, 100) . '...'
            ]);

            return $paymentUrl;
        }

        throw new \Exception('SSLCommerz payment initiation failed - no payment URL returned');

    } catch (\Exception $e) {
        Log::error('SSLCommerz payment initiation failed', [
            'error' => $e->getMessage(),
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'trace' => $e->getTraceAsString()
        ]);
        throw new \Exception('SSLCommerz payment initiation failed: ' . $e->getMessage());
    }
}
    public function checkDomain(Request $request)
    {
        try {
            if (!$request->isMethod('post')) {
                return response()->json([
                    'available' => false,
                    'message' => 'Please send a POST request with domain data.',
                ], 405);
            }

            $validator = Validator::make($request->all(), [
                'domain' => ['required', 'string', 'max:255'],
            ], [
                'domain.required' => 'Please enter a domain name',
                'domain.max' => 'Domain name cannot exceed 255 characters',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'available' => false,
                    'message' => $validator->errors()->first('domain'),
                ], 422);
            }

            $domain = strtolower(trim($request->domain));
            $domain = $this->sanitizeDomain($domain);

            $pricingService = app(PricingService::class);
            $pricingContext = $pricingService->contextForRequest($request);

            // Check database first (faster)
            if (Domain::where('domain', $domain)->exists()) {
                $domainPriceUsd = $pricingService->domainPriceUsd('new', '.' . pathinfo($domain, PATHINFO_EXTENSION));
                return response()->json([
                    'available' => false,
                    'domain' => $domain,
                    'suggestions' => $this->generateDomainSuggestions($domain),
                    'domain_price_usd' => $domainPriceUsd,
                    'domain_price' => $pricingService->convertFromUsd($domainPriceUsd, $pricingContext['currency_code']),
                    'currency' => $pricingContext['currency_code'],
                    'currency_symbol' => $pricingContext['currency_symbol'],
                ]);
            }

            // Check WhoisFreaks API
            $apiKey = config('services.whoisfreaks.api_key');
            if (!$apiKey) {
                throw new \Exception('WhoisFreaks API key not configured.');
            }

            $response = Http::timeout(10)->get('https://api.whoisfreaks.com/v1.0/whois', [
                'apiKey' => $apiKey,
                'whois' => 'live',
                'domainName' => $domain,
            ]);

            $data = $response->json();
            $available = isset($data['status']) && $data['status'] === 'available';
            $domainPriceUsd = $pricingService->domainPriceUsd('new', '.' . pathinfo($domain, PATHINFO_EXTENSION));

            if ($available) {
                return response()->json([
                    'available' => true,
                    'domain' => $domain,
                    'domain_price_usd' => $domainPriceUsd,
                    'domain_price' => $pricingService->convertFromUsd($domainPriceUsd, $pricingContext['currency_code']),
                    'currency' => $pricingContext['currency_code'],
                    'currency_symbol' => $pricingContext['currency_symbol'],
                ]);
            }

            return response()->json([
                'available' => false,
                'domain' => $domain,
                'suggestions' => $this->generateDomainSuggestions($domain),
                'domain_price_usd' => $domainPriceUsd,
                'domain_price' => $pricingService->convertFromUsd($domainPriceUsd, $pricingContext['currency_code']),
                'currency' => $pricingContext['currency_code'],
                'currency_symbol' => $pricingContext['currency_symbol'],
            ]);

        } catch (\Exception $e) {
            Log::error('checkDomain failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'domain' => $request->input('domain'),
            ]);

            // Fallback: Check database uniqueness only
            try {
                $domain = strtolower(trim($request->domain));
                $domain = $this->sanitizeDomain($domain);
                $pricingService = app(PricingService::class);
                $pricingContext = $pricingService->contextForRequest($request);
                $exists = Domain::where('domain', $domain)->exists();
                $domainPriceUsd = $pricingService->domainPriceUsd('new', '.' . pathinfo($domain, PATHINFO_EXTENSION));

                return response()->json([
                    'available' => !$exists,
                    'domain' => $domain,
                    'demo_mode' => true,
                    'suggestions' => !$exists ? [] : $this->generateDomainSuggestions($domain),
                    'domain_price_usd' => $domainPriceUsd,
                    'domain_price' => $pricingService->convertFromUsd($domainPriceUsd, $pricingContext['currency_code']),
                    'currency' => $pricingContext['currency_code'],
                    'currency_symbol' => $pricingContext['currency_symbol'],
                ]);
            } catch (\Exception $fallbackError) {
                Log::error('checkDomain fallback failed', [
                    'message' => $fallbackError->getMessage(),
                ]);

                return response()->json([
                    'available' => false,
                    'message' => 'Unable to check domain availability at this time. Please try again later.',
                    'error' => app()->environment('local') ? $fallbackError->getMessage() : null,
                ], 500);
            }
        }
    }

    private function generateDomainSuggestions(string $domain): array
    {
        $parts = explode('.', $domain);
        $name = $parts[0] ?? $domain;
        $ext = isset($parts[1]) ? '.' . $parts[1] : '.com';

        $alternatives = [
            $name . 'online' . $ext,
            $name . 'health' . $ext,
            $name . 'care' . $ext,
            'dr' . $name . $ext,
            $name . 'md' . $ext,
        ];

        return array_slice($alternatives, 0, 3);
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

        if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) return 'visa';
        if (preg_match('/^5[1-5][0-9]{14}$/', $cardNumber)) return 'mastercard';
        if (preg_match('/^3[47][0-9]{13}$/', $cardNumber)) return 'amex';
        if (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $cardNumber)) return 'discover';

        return 'unknown';
    }
    // package
    public function package(Request $request)
    {
        $packages = Package::all();
        $pricingContext = app(PricingService::class)->contextForRequest($request);

        return view('package', compact('packages', 'pricingContext'));
    }

    // doctor.register
    public function register()
    {
        return view('doc-register');
    }

    // registrationPending
    public function registrationPending()
    {
        return view('registration-pending');
    }
    // registrationSuccess
    public function registrationSuccess()
    {
        return view('registration-success');
    }

}
