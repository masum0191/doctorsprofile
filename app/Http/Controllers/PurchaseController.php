<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain; // Assuming central Domain model
use App\Models\Package; // Assuming you have a Package model
use App\Models\Setting; // Assuming you have a Setting model
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class PurchaseController extends Controller
{



// public function store(Request $request)
// {
//     $domainType = $request->input('domain_type');
//     $rawDomain = $request->input('new_domain');
//     $sanitizedDomain = $this->sanitizeDomain($rawDomain);

//     // Determine Final Domain Name based on type
//     if ($domainType === 'subdomain') {
//         $finalDomain = $sanitizedDomain ;
//     } elseif ($domainType === 'tree') {
//         $treeSubdomain = $this->sanitizeDomain($request->input('tree_subdomain', $sanitizedDomain));
//         $selectedTreeDomain = $request->input('tree_domain', 'gov.bd');
//         $finalDomain = $treeSubdomain . '.' . $selectedTreeDomain;
//     } else {
//         $finalDomain = $sanitizedDomain;
//     }

//     // Validate the request
//     $validated = validator(
//         array_merge($request->all(), ['final_domain' => $finalDomain]),
//         [
//             'domain_type' => ['required', Rule::in(['domain', 'subdomain', 'tree'])],
//             'new_domain' => [
//                 'required',
//                 'string',
//                 'max:255',
//                 function ($attribute, $value, $fail) use ($domainType, $sanitizedDomain) {
//                     if ($domainType === 'subdomain') {
//                         if (!preg_match('/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/', $sanitizedDomain)) {
//                             $fail('The subdomain format is invalid. Use only letters, numbers, and hyphens.');
//                         }
//                     } elseif ($domainType === 'tree') {
//                         if (!preg_match('/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/', $sanitizedDomain)) {
//                             $fail('The tree subdomain format is invalid. Use only letters, numbers, and hyphens.');
//                         }
//                     } else {
//                         if (!preg_match('/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?(\.[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?)+$/', $sanitizedDomain)) {
//                             $fail('The domain format is invalid.');
//                         }
//                     }
//                 },
//             ],
//             'package_id' => ['required', 'exists:packages,id'],
//             'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
//             'final_total' => ['required', 'numeric', 'min:0'],
//             'domain_price' => ['required', 'numeric', 'min:0'],
//             'final_domain' => [
//                 'required',
//                 'string',
//                 'max:255',
//                 Rule::unique('domains', 'domain'),
//             ],
//         ],
//         [
//             'final_domain.unique' => 'This domain is already taken. Please choose a different one.',
//             'new_domain.regex' => 'Please enter a valid domain format.',
//         ]
//     )->validate();

//     // Add tree domain specific validation
//     if ($domainType === 'tree') {
//         $treeValidated = validator($request->all(), [
//             'tree_subdomain' => ['required', 'string', 'max:63', 'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/'],
//             'tree_domain' => ['required', Rule::in(['gov.bd', 'org.bd', 'edu.bd'])],
//         ])->validate();
//     }

//     $user = auth()->user();

//     try {
//         // Get package details
//         $package = \App\Models\Package::findOrFail($validated['package_id']);

//         // Calculate actual prices based on billing cycle
//         $packagePrice = ($validated['billing_cycle'] === 'yearly')
//             ? $package->price_yearly
//             : $package->price_monthly;

//         // Create a new tenant
//         $tenant = Tenant::create([
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

//         // Prepare domain data
//         $domainData = [
//             'domain' => $finalDomain,
//             'type' => $validated['domain_type'],
//             'registration_fee' => $validated['domain_price'],
//             'is_primary' => true,
//             'status' => 'pending',
//             'created_at' => now(),
//             'updated_at' => now(),
//         ];

//         // Add tree domain specific data
//         if ($validated['domain_type'] === 'tree') {
//             $domainData['tree_subdomain'] = $this->sanitizeDomain($request->input('tree_subdomain'));
//             $domainData['tree_domain'] = $request->input('tree_domain');
//         }

//         // Optional direct call (observer will also run):
//        event(new \App\Events\TenantDomainCreated($domainRow));

//         // Attach domain to tenant
//         $domainRow = $tenant->domains()->create($domainData);

//         // Update user with tenant_id
//         $user->update([
//             'tenant_id' => $tenant->id,
//         ]);

//         // Initialize tenancy
//         tenancy()->initialize($tenant);

//         // Create initial settings for tenant
//         $settings = new \App\Models\Setting();
//         $settings->fill([
//             'site_name' => $user->name . ' - ' . $package->name . ' Service',
//             'site_description' => $package->description,
//         ]);
//         $settings->save();

//         // Create admin user for tenant
//         $tenantAdmin = new \App\Models\User();
//         $tenantAdmin->fill([
//             'name' => $user->name,
//             'email' => $user->email,
//             'password' => bcrypt(Str::random(16)), // Random password, user will reset
//             'role' => 'admin',
//             'mobile' => $user->phone ?? '0000000000',
//         ]);
//         $tenantAdmin->save();

//         // End tenancy
//         tenancy()->end();

//         // Create initial invoice
//         $this->createInitialInvoice($tenant, $packagePrice, $validated['domain_price']);

//         // Handle payment logic
//         $this->processPayment($tenant, $validated['final_total']);

//         return redirect()
//             ->back()
//             ->with('success', 'Service registered successfully! Please complete the payment to activate your service.');

//     } catch (\Exception $e) {
//         \Log::error('Service registration failed: ' . $e->getMessage());
//         \Log::error('Error trace: ' . $e->getTraceAsString());

//         return redirect()
//             ->back()
//             ->with('error', 'Failed to create service: ' . $e->getMessage())
//             ->withInput();
//     }
// }

// private function sanitizeDomain(?string $value): ?string
// {
//     if (!$value) return $value;

//     $v = strtolower(trim($value));
//     $v = preg_replace('#^(?:https?://)?(?:www\.)?#', '', $v);
//     $v = preg_split('/[\/?#]/', $v, 2)[0] ?? $v;
//     $v = preg_replace('/[^a-z0-9.-]/', '', $v);
//     $v = trim($v, '.-');

//     return $v;
// }

// private function createInitialInvoice($tenant, $packagePrice, $domainPrice)
// {
//     try {
//         $totalAmount = $packagePrice + $domainPrice;

//         $invoice = $tenant->invoices()->create([
//             'id' => Str::uuid(),
//             'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::random(6),
//             'amount' => $totalAmount,
//             'package_price' => $packagePrice,
//             'domain_price' => $domainPrice,
//             'status' => 'pending',
//             'due_date' => now()->addDays(7),
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);

//         \Log::info("Invoice created for tenant {$tenant->id}: {$invoice->invoice_number}");

//     } catch (\Exception $e) {
//         \Log::error('Invoice creation failed: ' . $e->getMessage());
//     }
// }

// private function processPayment($tenant, $amount)
// {
//     try {
//         // TODO: Implement your payment gateway integration
//         // This could be Stripe, PayPal, SSLCommerz, etc.

//         // For SSLCommerz integration example:
//         /*
//         $sslcommerz = new SslCommerz();
//         $paymentData = [
//             'total_amount' => $amount,
//             'currency' => "BDT",
//             'tran_id' => $tenant->id . '-' . time(),
//             'success_url' => route('payment.success'),
//             'fail_url' => route('payment.fail'),
//             'cancel_url' => route('payment.cancel'),
//             'cus_name' => $tenant->name,
//             'cus_email' => auth()->user()->email,
//         ];

//         $payment = $sslcommerz->initiatePayment($paymentData);
//         return redirect()->to($payment['GatewayPageURL']);
//         */

//         // For now, just log the payment attempt
//         \Log::info("Payment required for tenant {$tenant->id}: $" . $amount);

//     } catch (\Exception $e) {
//         \Log::error('Payment processing failed: ' . $e->getMessage());
//         throw $e;
//     }
// }
public function store(Request $request)
{
    // --- 1. Domain Cleaning and Construction ---
    // Note: domain input is 'new_domain' from the form, but we'll use 'domain' for validation context
    $domainType = $request->input('domain_type');
    $rawDomain = $request->input('new_domain');
    $sanitizedDomain = $this->sanitizeDomain($rawDomain);

    // Determine the final domain name based on input type
    $finalDomain = '';
    // Use a clean config variable for the base domain
    $baseDomain = config('app.base_domain', '.doctorsprofile.xyz');


    $finalDomain = ($domainType === 'subdomain')
        ? $sanitizedDomain. $baseDomain
        : $sanitizedDomain. $baseDomain;

    // --- 2. Validation ---
    $validated = Validator::make(
        array_merge($request->all(), ['final_domain' => $finalDomain]),
        [
            // Domain & Package Fields from the modal
            'domain_type'   => ['required', Rule::in(['domain', 'subdomain', 'existing'])],
            'new_domain'    => ['required', 'string', 'max:255', 'regex:/^[\w\d.-]+$/'], // Raw input validation
            'final_domain'  => [ // Constructed domain validation
                'required',
                'string',
                'max:255',
                'regex:/^(?!-)(?:[a-z0-9-]{1,63}\.)+[a-z]{2,}$/',
                Rule::unique('domains', 'domain'),
            ],
            'package_id'    => ['required', 'exists:packages,id'],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'final_total'   => ['required', 'numeric', 'min:0'],
            'domain_price'  => ['required', 'numeric', 'min:0'],
            'coupon'        => ['nullable', 'string', 'max:50'],

            // Assuming required User data is already saved on the user model before this form is accessed.
        ],
        ['final_domain.regex' => 'Please enter a valid domain like example.com or sub.example.com (no http/https).']
    )->validate();
     $package = \App\Models\Package::findOrFail($validated['package_id']);

        // Calculate actual prices based on billing cycle
        $packagePrice = ($validated['billing_cycle'] === 'yearly')
            ? $package->price_yearly
            : $package->price_monthly;

$user = auth()->user();
    $tenant = Tenant::create([
            'id' => Str::uuid(),
            'name' => $user->name . ' - ' . $package->name . ' Service',
            'status' => 0, // Set to inactive until payment is confirmed
            'package_id' => $validated['package_id'],
            'billing_cycle' => $validated['billing_cycle'],
            'monthly_price' => $package->price_monthly,
            'yearly_price' => $package->price_yearly,
            'storage_gb' => $package->storage_gb,
            'created_at' => now(),
            'updated_at' => now(),
        ]);




        $user->update([
            'tenant_id' => $tenant->id,
        ]);

        // C. Attach Domain to Tenant
        $domainRow = $tenant->domains()->create([
            'domain'            => $finalDomain,
            'type'              => $validated['domain_type'],
            'registration_fee'  => $validated['domain_price'],
        ]);
    // Optional direct call (observer will also run):
     event(new \App\Events\TenantDomainCreated($domainRow));
        // D. Initialize tenancy for the current process to access tenant storage/settings
        tenancy()->initialize($tenant);
        $tuser = User::create([
            'id'            => Str::uuid(), // Ensure UUIDs are supported if using them
            'name'          => $user->name . ' - ' . Str::title($validated['domain_type']) . ' Service',
            'role'          => 'user', // Assuming a fixed type for doctors purchasing a service
            'status'        => 0, // Inactive until payment confirmed
            'password'    => $user->password,
            'email'         => $user->email,
            'mobile'        => $user->phone ?? '0000000000',

            // Add any other necessary fields
        ]);

        // E. Tenant Setting table data seeding
        $settings = new Setting(); // Use the Tenant Setting Model (needs correct Tenant scope)
        $settings->fill([
            'site_name' => $user->name . ' Profile',
            'package_id' => $validated['package_id'],
            'billing_cycle' => $validated['billing_cycle'],
        ]);
        $settings->save();

        // F. Event Notification (e.g., for provisioning)
       // event(new \App\Events\TenantDomainCreated($domainRow));

        // G. End tenancy
        tenancy()->end();

        // NOTE: Payment processing/redirection would typically happen here.
        // Since we are skipping the payment gateway, the tenant remains status=0.
    // });

    // --- 4. Redirect ---
    return back()->with('success', 'Service registration initiated! Please check your dashboard for payment instructions.');
}

// NOTE: The sanitizeDomain function should be defined within the class or a trait.
private function sanitizeDomain(?string $value): ?string
{
    if (!$value) return $value;

    $v = strtolower(trim($value));
    $v = preg_replace('#^(?:https?:\/\/)?(?:www\.)?#', '', $v);
    $v = preg_split('/[\/?#]/', $v, 2)[0] ?? $v;
    $v = preg_replace('/[^a-z0-9.-]/', '', $v);
    $v = trim($v, '.-');

    return $v;
}
}
