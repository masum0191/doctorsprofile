<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentSession;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Subscription;
// SSLCommerzService
use App\Services\SSLCommerzService;
use App\Jobs\InitializeTenantData;
use App\Services\PricingService;

class DoctorRegistrationController extends Controller
{
    /**
     * Register a new doctor via API
     *
     * @OA\Post(
     *     path="/api/v1/doctor/register",
     *     summary="Register a new doctor",
     *     description="Creates a new doctor account with tenant, domain, and payment",
     *     tags={"Doctor Registration"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "phone", "password", "package_id", "selected_billing_cycle", "domain_type", "payment_method", "payment_option", "terms"},
     *             @OA\Property(property="email", type="string", format="email", example="doctor@example.com"),
     *             @OA\Property(property="phone", type="string", example="+8801712345678"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(property="photo", type="string", format="binary", description="Profile photo file"),
     *             @OA\Property(property="name", type="string", example="Dr. John Doe"),
     *             @OA\Property(property="qualification", type="string", example="MBBS, MD"),
     *             @OA\Property(property="specialty", type="string", example="Cardiology"),
     *             @OA\Property(property="country", type="string", example="Bangladesh"),
     *             @OA\Property(property="reg_no", type="string", example="BMDC-12345"),
     *             @OA\Property(property="latitude", type="number", format="float", example=23.8103),
     *             @OA\Property(property="longitude", type="number", format="float", example=90.4125),
     *             @OA\Property(property="city", type="string", example="Dhaka"),
     *             @OA\Property(property="package_id", type="integer", example=1),
     *             @OA\Property(property="selected_billing_cycle", type="string", enum={"monthly", "yearly", "free"}, example="monthly"),
     *             @OA\Property(property="domain_type", type="string", enum={"new", "subdomain", "existing"}, example="subdomain"),
     *             @OA\Property(property="subdomain_name", type="string", example="drjohn"),
     *             @OA\Property(property="new_domain_name", type="string", example="drjohndoe"),
     *             @OA\Property(property="new_domain_extension", type="string", example=".com"),
     *             @OA\Property(property="existing_domain", type="string", example="drjohndoe.com"),
     *             @OA\Property(property="payment_method", type="string", enum={"paypal", "sslcommerz", "bank_transfer", "credit_card"}, example="paypal"),
     *             @OA\Property(property="payment_option", type="string", enum={"online", "offline"}, example="online"),
     *             @OA\Property(property="coupon_code", type="string", example="WELCOME20"),
     *             @OA\Property(property="terms", type="boolean", example=true),
     *             @OA\Property(property="package_price", type="number", format="float", example=29.99),
     *             @OA\Property(property="domain_price", type="number", format="float", example=0.00),
     *             @OA\Property(property="discount_amount", type="number", format="float", example=0.00),
     *             @OA\Property(property="total_amount", type="number", format="float", example=29.99),
     *             @OA\Property(property="card_number", type="string", example="4111111111111111"),
     *             @OA\Property(property="expiry_date", type="string", example="12/25"),
     *             @OA\Property(property="cvv", type="string", example="123"),
     *             @OA\Property(property="card_holder", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Registration successful!"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="tenant_id", type="string")
     *                 ),
     *                 @OA\Property(property="tenant", type="object",
     *                     @OA\Property(property="id", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="status", type="integer")
     *                 ),
     *                 @OA\Property(property="domain", type="object",
     *                     @OA\Property(property="domain", type="string"),
     *                     @OA\Property(property="type", type="string")
     *                 ),
     *                 @OA\Property(property="payment", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="amount", type="number"),
     *                     @OA\Property(property="status", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Registration failed"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function createPatient(Request $request)
{
    //return response()->json($request->all(), 200);
    Log::info('========== PATIENT CREATE API ==========');
        $user = $request->user();

        if (!$user) return response()->json(['message'=>'Unauthenticated'],401);

        $tenant = \App\Models\Tenant::find($user->tenant_id);
        if (!$tenant) return response()->json(['message'=>'Tenant not found'],404);

        tenancy()->initialize($tenant);
       //return response()->json($tenant, 200);
    $validated = Validator::make($request->all(), [

        'name'    => ['required', 'string', 'max:255'],
        'age'     => ['nullable', 'string'],
        'mobile'  => ['required', 'string', 'max:20'],
        'gender'  => ['required', 'in:male,female,other'],
        'address' => ['required', 'string'],

        'email' => ['nullable', 'email', 'max:255'],

        'vitality' => ['nullable', 'string'],

        // JSON arrays
        'emergency_contact' => ['nullable', 'array'],
        'emergency_contact.name'         => ['required_with:emergency_contact', 'string'],
        'emergency_contact.relationship' => ['required_with:emergency_contact', 'string'],
        'emergency_contact.contact'      => ['required_with:emergency_contact', 'string'],

        'basic_details' => ['nullable', 'array'],
        'basic_details.blood_group' => ['nullable', 'string'],
        'basic_details.height'      => ['nullable', 'numeric'],
        'basic_details.weight'      => ['nullable', 'numeric'],

        'medical_history' => ['nullable', 'string'],

    ])->validate();



// return response()->json($data, 200, $headers);
    $patient = User::create([
        'name'    => $validated['name'],
        'age'     => $validated['age'],
        'mobile'  => $validated['mobile'],
        'gender'  => $validated['gender'],
        'address' => $validated['address'],
        'email'   => $validated['email'] ?? null,

        'vitality'          => $validated['vitality'] ?? null,
        'emergency_contact' => $validated['emergency_contact'] ?? null,
        'basic_details'     => $validated['basic_details'] ?? null,
        'medical_history'   => $validated['medical_history'] ?? null,

        'role' => 'patient',
    ]);

    return response()->json([
        'message' => 'Patient created successfully',
        'patient' => $patient
    ], 201);
    tenancy()->end();
}
    public function register(Request $request)
    {
        Log::info('========== API DOCTOR REGISTRATION STARTED ==========');
        Log::info('API Request data:', $request->except(['password', 'password_confirmation', 'card_number', 'cvv']));

      //  try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email'     => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
                'phone'     => ['required', 'string', 'max:20'],
                'password'  => ['required', 'confirmed', 'min:8'],
                //'photo'     => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],

                // Optional personal info
                'name'          => ['nullable', 'string', 'max:255'],
                'qualification' => ['nullable', 'string', 'max:255'],
                'specialty'     => ['nullable', 'string', 'max:100'],
                'country'       => ['nullable', 'string', 'max:100'],
                'reg_no'        => ['nullable', 'string', 'max:50'],

                // Package selection
                'package_id'             => ['required', 'exists:packages,id'],
                'selected_billing_cycle' => ['required', Rule::in(['monthly', 'yearly', 'free'])],

                // Domain selection
                'domain_type'         => ['required', Rule::in(['new', 'subdomain', 'existing'])],
                'subdomain_name'      => ['required_if:domain_type,subdomain', 'nullable', 'string', 'max:100'],
                'new_domain_name'     => ['required_if:domain_type,new', 'nullable', 'string', 'max:100'],
                'new_domain_extension'=> ['required_if:domain_type,new', 'nullable', 'string', 'max:10'],
                'existing_domain'     => ['required_if:domain_type,existing', 'nullable', 'string', 'max:255'],

                // Payment
                'payment_method' => ['required', Rule::in(['paypal', 'sslcommerz', 'bank_transfer', 'credit_card'])],
                'payment_option' => ['required', Rule::in(['online', 'offline'])],
                'terms'          => ['required', 'accepted'],

                // Coupon
                'coupon_code' => ['nullable', 'string', 'max:50'],

                // Price fields
                'package_price'   => ['required', 'numeric', 'min:0'],
                'domain_price'    => ['required', 'numeric', 'min:0'],
                'discount_amount' => ['required', 'numeric', 'min:0'],
                'total_amount'    => ['required', 'numeric', 'min:0'],

                // Optional location
                'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'city'      => ['nullable', 'string', 'max:255'],

                // Credit card details (if applicable)
                'card_number' => ['nullable', 'string'],
                'expiry_date' => ['nullable', 'string'],
                'cvv'         => ['nullable', 'string'],
                'card_holder' => ['nullable', 'string'],
            ], [
               // 'photo.required' => 'Please upload your professional photo',
                'terms.accepted' => 'You must accept the terms and conditions',
                'subdomain_name.required_if' => 'Subdomain name is required',
                'new_domain_name.required_if' => 'Domain name is required',
                'existing_domain.required_if' => 'Existing domain is required',
            ]);

            if ($validator->fails()) {
                Log::error('API Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            Log::info('API Validation passed', ['email' => $validated['email']]);

            $isZeroAmount = (float) $validated['total_amount'] <= 0;
            $isInstantActivation = $isZeroAmount ||
                ($validated['payment_option'] === 'online' && $validated['payment_method'] === 'credit_card');
            $initialStatus = $isInstantActivation ? 1 : 0;

          //  DB::beginTransaction();
            Log::info('API Database transaction started');

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
            $couponDiscount = $validated['discount_amount'];
            if (!empty($validated['coupon_code'])) {
                Log::info('Validating coupon', ['code' => $validated['coupon_code']]);

                $coupon = Coupon::where('code', $validated['coupon_code'])->first();

                if (!$coupon || !$coupon->isCurrentlyValid()) {
                    throw new \Exception('Invalid or expired coupon code');
                }

                // Recalculate discount to ensure validity
                $subtotal = $validated['package_price'] + $validated['domain_price'];
                $calculatedDiscount = $coupon->calculateDiscount($subtotal);

                if ($calculatedDiscount != $couponDiscount) {
                    throw new \Exception('Coupon discount calculation mismatch');
                }

                Log::info('Coupon validated', [
                    'coupon_id' => $coupon->id,
                    'discount' => $couponDiscount
                ]);
            }

            // 3) Create central user
            Log::info('Creating central user...');
            $user = new User();

            // Generate name from email if not provided
            $name = $validated['name'] ?? ucwords(str_replace(['.', '_'], ' ', explode('@', $validated['email'])[0]));

            $user->fill([
                'name'           => $name,
                'email'          => $validated['email'],
                'password'       => Hash::make($validated['password']),
                'role'           => 'tenant',
                'mobile'         => $validated['phone'],
                'qualification'  => $validated['qualification'] ?? 'Medical Professional',
                'reg_no'         => $validated['reg_no'] ?? null,
                'specialization' => $validated['specialty'] ?? 'General Practitioner',
                'country'        => $validated['country'] ?? 'Unknown',
                'latitude'       => $validated['latitude'] ?? null,
                'longitude'      => $validated['longitude'] ?? null,
                'city'           => $validated['city'] ?? null,
                //'photo'          => $databasePath,
                'status'         => $initialStatus,
            ]);

            $user->save();

            // 4) Determine domain name
            $domainType = $validated['domain_type'];
            $baseDomain = config('app.base_domain', 'doctorsprofile.xyz');

            $domainName = '';
            if ($domainType === 'new') {
                $domainName = ($validated['new_domain_name'] ?? '') . ($validated['new_domain_extension'] ?? '');
            } elseif ($domainType === 'subdomain') {
                $domainName = ($validated['subdomain_name'] ?? '') . '.' . $baseDomain;
            } else { // existing
                $domainName = $this->sanitizeDomain($validated['existing_domain'] ?? '');
            }

            // Ensure domain is unique
            if (Domain::where('domain', $domainName)->exists()) {
                throw new \Exception('This domain is already taken. Please choose another.');
            }

            // 5) Package + billing
            $package = Package::findOrFail($validated['package_id']);
            $billingCycle = $validated['selected_billing_cycle'];

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
                'status' => $initialStatus,
                'package_id' => $package->id,
                'billing_cycle' => $billingCycle,
                'monthly_price' => $package->price_monthly,
                'yearly_price' => $package->price_yearly,
                'storage_gb' => $package->storage_gb,
                'trial_ends_at' => $trialEndsAt,
            ]);

            // Update central user with tenant_id
            $user->tenant_id = $tenant->id;
            $user->save();

            // 7) Create domain in central
            $domainRow = $tenant->domains()->create([
                'domain'           => $domainName,
                'type'             => $domainType,
                'registration_fee' => $validated['domain_price'],
                'status'           => $initialStatus,
            ]);

            // 8) Initialize tenancy and create tenant user + setting + chamber
            tenancy()->initialize($tenant);

            // Create user in tenant DB
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
            \App\Models\Setting::create([
                'site_name'      => $user->name . ' Medical Practice',
                'site_email'     => $user->email,
                'site_phone'     => $user->mobile,
                'package_id'     => $package->id,
                'billing_cycle'  => $billingCycle,
                'specialization' => $user->specialization,
                'qualification'  => $user->qualification,
                'country'        => $user->country,
                'template'      => 'one',
            ]);

            // Tenant chamber
            \App\Models\Chamber::create([
                'doctor_id' => $tuser->id,
                'name'      => $user->name . ' Chamber',
                'address'   => '',
                'city'      => $user->city ?? '',
                'fees'      => '00.00',
                'type'      => 'fixed',
                'schedule'  => [],
                'is_active' => true,
                'country'   => $user->country ?? '',
                'latitude'  => $user->latitude,
                'longitude' => $user->longitude,

            ]);

            tenancy()->end();
            InitializeTenantData::dispatch($tenant->id);

            // 9) Process payment
            $paymentStatus = 'pending';
            if ($isZeroAmount) {
                $paymentStatus = 'completed';
            } elseif ($validated['payment_option'] === 'online') {
                $paymentStatus = 'pending';

                // For API, we return payment URL if needed
                if ($validated['payment_method'] === 'paypal') {
                    $paymentUrl = $this->initiatePayPalPaymentApi($tenant, $user, $package, $validated, $coupon);
                } elseif ($validated['payment_method'] === 'sslcommerz') {
                    $paymentUrl = $this->initiateSSLCommerzPaymentApi($tenant, $user, $package,  $validated, $coupon);
                } elseif ($validated['payment_method'] === 'credit_card') {
                    $paymentStatus = 'completed';
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
                'payment_method'  => $validated['payment_method'],
                'payment_option'  => $validated['payment_option'],
                'status'          => $paymentStatus,
               // 'payment_date'    => now(),
                'billing_cycle'   => $billingCycle,
                'coupon_id'       => $coupon->id ?? null,
            ];

            if ($validated['payment_method'] === 'credit_card' && !empty($validated['card_number'])) {
                $paymentData['card_last_four'] = substr(str_replace(' ', '', $validated['card_number']), -4);
                $paymentData['card_type'] = $this->detectCardType($validated['card_number']);
            }

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

        $subscriptionStatus = $paymentStatus === 'completed' ? 'active' : 'pending';

        Subscription::create([
            'doctor_id'=>$user->id,
            'tenant_id'=>$tenant->id,
            'package_id'=>$package->id,
            'billing_cycle'=>$billingCycle,
            'starts_at'=>$startsAt,
            'ends_at'=>$endsAt,
            'status'=>$subscriptionStatus,
        ]);


            // 12) Fire events
            event(new \App\Events\TenantDomainCreated($domainRow));

            Log::info('API Registration completed successfully', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'domain' => $domainName
            ]);

            // 13) Prepare response
            $responseData = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->mobile,
                    'tenant_id' => $user->tenant_id,
                    'photo_url' => $user->photo ? url($user->photo) : null,
                ],
                'tenant' => [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'status' => $tenant->status,
                    'package_id' => $tenant->package_id,
                    'billing_cycle' => $tenant->billing_cycle,
                ],
                'domain' => [
                    'domain' => $domainRow->domain,
                    'type' => $domainRow->type,
                    'status' => $domainRow->status,
                ],
                'payment' => [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'payment_method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                ],
                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price_monthly' => $package->price_monthly,
                    'price_yearly' => $package->price_yearly,
                ]
            ];

            // Add payment URL if online payment initiated
            if (isset($paymentUrl) && $validated['payment_option'] === 'online') {
                $responseData['payment_url'] = $paymentUrl;
                $responseData['requires_payment'] = true;
            } else {
                $responseData['requires_payment'] = false;
            }

            // Generate API token only after completed payment
            if ($paymentStatus === 'completed') {
                $token = $user->createToken('doctor-api-token')->plainTextToken;
                $responseData['access_token'] = $token;
                $responseData['token_type'] = 'Bearer';
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'data' => $responseData

            ], 201);

        // } catch (\Exception $e) {
        //     Log::error('API REGISTRATION FAILED: ' . $e->getMessage(), [
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //         'trace' => $e->getTraceAsString()
        //     ]);

        //     if (DB::transactionLevel() > 0) {
        //         DB::rollBack();
        //     }

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Registration failed: ' . $e->getMessage(),
        //         'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        //     ], 500);
        // }
    }

    /**
     * Get available packages
     *
     * @OA\Get(
     *     path="/api/v1/packages",
     *     summary="Get available packages",
     *     tags={"Doctor Registration"},
     *     @OA\Response(
     *         response=200,
     *         description="Packages retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="price_monthly", type="number"),
     *                     @OA\Property(property="price_yearly", type="number"),
     *                     @OA\Property(property="storage_gb", type="integer"),
     *                     @OA\Property(property="features", type="array", @OA\Items(type="string"))
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getPackages()
    {
        try {
            $pricingService = app(PricingService::class);
            $pricingContext = $pricingService->contextForRequest(request());
            $packages = Package::all();

            $formattedPackages = $packages->map(function ($package) use ($pricingService, $pricingContext) {
                $pricePayload = $pricingService->packagePayload($package, $pricingContext['currency_code']);

                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'base_currency' => 'USD',
                    'display_currency' => $pricingContext['currency_code'],
                    'display_currency_symbol' => $pricingContext['currency_symbol'],
                    'exchange_rate' => $pricingContext['exchange_rate'],
                    'price_monthly_usd' => $pricePayload['price_monthly_usd'],
                    'price_yearly_usd' => $pricePayload['price_yearly_usd'],
                    'price_monthly' => $pricePayload['price_monthly'],
                    'price_yearly' => $pricePayload['price_yearly'],
                    'storage_gb' => $package->storage_gb,
                    'features' => is_array($package->features) ? $package->features : [],
                    'is_popular' => $package->is_popular ?? false
                ];
            });

            return response()->json([
                'success' => true,
                'pricing' => $pricingContext,
                'data' => $formattedPackages
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch packages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch packages'
            ], 500);
        }
    }

    /**
     * Check domain/subdomain availability
     *
     * @OA\Post(
     *     path="/api/v1/check-domain",
     *     summary="Check domain availability",
     *     tags={"Doctor Registration"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "domain"},
     *             @OA\Property(property="type", type="string", enum={"subdomain", "new", "existing"}, example="subdomain"),
     *             @OA\Property(property="domain", type="string", example="drjohn"),
     *             @OA\Property(property="extension", type="string", example=".com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Domain availability checked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="available", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="full_domain", type="string"),
     *             @OA\Property(property="domain_price", type="number", format="float", example=14.99)
     *         )
     *     )
     * )
     */
    public function checkDomain(Request $request)
    {
        $pricingService = app(PricingService::class);
        $pricingContext = $pricingService->contextForRequest($request);

        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['subdomain', 'new', 'existing'])],
            'domain' => ['required', 'string'],
            'extension' => ['nullable', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $type = $request->type;
            $domain = trim($request->domain);
            $baseDomain = config('app.base_domain', 'doctorsprofile.xyz');
            $extension = $request->extension ?? '.com';

            $fullDomain = '';
            if ($type === 'subdomain') {
                $fullDomain = $domain . '.' . $baseDomain;
            } elseif ($type === 'new') {
                $fullDomain = $domain . $extension;
            } else {
                $fullDomain = $this->sanitizeDomain($domain);
            }

            $domainPriceUsd = $pricingService->domainPriceUsd($type, $extension);
            $domainPrice = $pricingService->convertFromUsd($domainPriceUsd, $pricingContext['currency_code']);

            // Check if domain exists
            $exists = Domain::where('domain', $fullDomain)->exists();

            // For external domains, you might want to check DNS records
            if ($type === 'new' || $type === 'existing') {
                // Add external domain checking logic here
                // For example, using gethostbyname or DNS check
                $ip = gethostbyname($fullDomain);
                $isRegistered = ($ip !== $fullDomain); // If IP differs from domain, it's registered

                if ($type === 'new' && $isRegistered) {
                    return response()->json([
                        'success' => true,
                        'available' => false,
                        'message' => 'Domain is already registered',
                        'full_domain' => $fullDomain,
                        'domain_price' => round($domainPrice, 2),
                        'domain_price_usd' => round($domainPriceUsd, 2),
                        'currency' => $pricingContext['currency_code'],
                        'currency_symbol' => $pricingContext['currency_symbol'],
                        'domain' => [
                            'type' => $type,
                            'name' => $domain,
                            'extension' => $type === 'new' ? $extension : null,
                            'full_domain' => $fullDomain,
                            'price' => round($domainPrice, 2),
                            'price_usd' => round($domainPriceUsd, 2),
                        ],
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'available' => !$exists,
                'message' => $exists ? 'Domain is already taken' : 'Domain is available',
                'full_domain' => $fullDomain,
                'domain_price' => $domainPrice,
                'domain_price_usd' => $domainPriceUsd,
                'currency' => $pricingContext['currency_code'],
                'currency_symbol' => $pricingContext['currency_symbol'],
                'domain' => [
                    'type' => $type,
                    'name' => $domain,
                    'extension' => $type === 'new' ? $extension : null,
                    'full_domain' => $fullDomain,
                    'price' => $domainPrice,
                    'price_usd' => $domainPriceUsd,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Domain check failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Domain check failed'
            ], 500);
        }
    }

    /**
     * Validate coupon code
     *
     * @OA\Post(
     *     path="/api/v1/validate-coupon",
     *     summary="Validate coupon code",
     *     tags={"Doctor Registration"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code", "amount"},
     *             @OA\Property(property="code", type="string", example="WELCOME20"),
     *             @OA\Property(property="amount", type="number", example=100.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coupon validated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="valid", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="discount_amount", type="number"),
     *             @OA\Property(property="coupon", type="object")
     *         )
     *     )
     * )
     */
    public function validateCouponApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::where('code', $request->code)->first();

            if (!$coupon) {
                return response()->json([
                    'success' => true,
                    'valid' => false,
                    'message' => 'Invalid coupon code'
                ]);
            }

            if (!$coupon->isCurrentlyValid()) {
                return response()->json([
                    'success' => true,
                    'valid' => false,
                    'message' => 'Coupon is no longer valid'
                ]);
            }

            if ($coupon->min_amount > $request->amount) {
                return response()->json([
                    'success' => true,
                    'valid' => false,
                    'message' => 'Minimum purchase amount not met'
                ]);
            }

            // Calculate discount
            $discount = $coupon->calculateDiscount($request->amount);

            return response()->json([
                'success' => true,
                'valid' => true,
                'message' => 'Coupon applied successfully!',
                'discount_amount' => $discount,
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => (float) $coupon->value,
                    'min_amount' => (float) $coupon->min_amount,
                    'max_discount' => (float) $coupon->max_discount,
                    'description' => $coupon->description
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Coupon validation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Coupon validation failed'
            ], 500);
        }
    }

    /**
     * Get registration summary/calculation
     *
     * @OA\Post(
     *     path="/api/v1/calculate-registration",
     *     summary="Calculate registration costs",
     *     tags={"Doctor Registration"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"package_id", "billing_cycle", "domain_type"},
     *             @OA\Property(property="package_id", type="integer", example=1),
     *             @OA\Property(property="billing_cycle", type="string", enum={"monthly", "yearly", "free"}, example="monthly"),
     *             @OA\Property(property="domain_type", type="string", enum={"new", "subdomain", "existing"}, example="subdomain"),
     *             @OA\Property(property="coupon_code", type="string", example="WELCOME20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calculation successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="package", type="object"),
     *             @OA\Property(property="domain", type="object"),
     *             @OA\Property(property="coupon", type="object"),
     *             @OA\Property(property="summary", type="object",
     *                 @OA\Property(property="package_price", type="number"),
     *                 @OA\Property(property="domain_price", type="number"),
     *                 @OA\Property(property="discount_amount", type="number"),
     *                 @OA\Property(property="total_amount", type="number")
     *             )
     *         )
     *     )
     * )
     */
    public function calculateRegistration(Request $request)
    {
        $pricingService = app(PricingService::class);
        $pricingContext = $pricingService->contextForRequest($request);

        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly', 'free'])],
            'domain_type' => ['required', Rule::in(['new', 'subdomain', 'existing'])],
            'coupon_code' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $package = Package::findOrFail($request->package_id);

            // Calculate package price
            $packagePrice = 0;
            if ($request->billing_cycle === 'yearly') {
                $packagePrice = (float) $package->price_yearly;
            } elseif ($request->billing_cycle === 'monthly') {
                $packagePrice = (float) $package->price_monthly;
            }
            // Free trial has 0 price

            // Calculate domain price
            $domainPrice = 0;
            if ($request->domain_type === 'new') {
                $domainPrice = $pricingService->domainPriceUsd('new', $request->domain_extension ?? '.com');
            }

            $subtotal = $packagePrice + $domainPrice;

            // Calculate coupon discount
            $coupon = null;
            $discountAmount = 0;
            if (!empty($request->coupon_code)) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isCurrentlyValid() && $coupon->min_amount <= $subtotal) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                }
            }

            $totalAmount = max(0, $subtotal - $discountAmount);

            return response()->json([
                'success' => true,
                'pricing' => $pricingContext,
                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price_monthly' => (float) $package->price_monthly,
                    'price_yearly' => (float) $package->price_yearly,
                    'selected_price' => $packagePrice,
                    'price_monthly_display' => $pricingService->convertFromUsd((float) $package->price_monthly, $pricingContext['currency_code']),
                    'price_yearly_display' => $pricingService->convertFromUsd((float) $package->price_yearly, $pricingContext['currency_code']),
                    'selected_price_display' => $pricingService->convertFromUsd($packagePrice, $pricingContext['currency_code'])
                ],
                'domain' => [
                    'type' => $request->domain_type,
                    'price' => $domainPrice,
                    'price_display' => $pricingService->convertFromUsd($domainPrice, $pricingContext['currency_code'])
                ],
                'coupon' => $coupon ? [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_amount' => $discountAmount
                ] : null,
                'summary' => [
                    'package_price' => $packagePrice,
                    'domain_price' => $domainPrice,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount
                ],
                'display_summary' => [
                    'currency' => $pricingContext['currency_code'],
                    'currency_symbol' => $pricingContext['currency_symbol'],
                    'package_price' => $pricingService->convertFromUsd($packagePrice, $pricingContext['currency_code']),
                    'domain_price' => $pricingService->convertFromUsd($domainPrice, $pricingContext['currency_code']),
                    'subtotal' => $pricingService->convertFromUsd($subtotal, $pricingContext['currency_code']),
                    'discount_amount' => $pricingService->convertFromUsd($discountAmount, $pricingContext['currency_code']),
                    'total_amount' => $pricingService->convertFromUsd($totalAmount, $pricingContext['currency_code'])
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Registration calculation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Calculation failed'
            ], 500);
        }
    }

    // Helper methods for payment initiation
    private function initiatePayPalPaymentApi($tenant, $user, $package, $data, $coupon = null)
    {
        try {
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
                    "cancel_url" => url('/api/v1/payment/cancel'),
                    "return_url" => url('/api/v1/payment/success')
                ]
            ];

            $order = $provider->createOrder($orderData);

            // Store payment session in database for API
            \App\Models\PaymentSession::create([
                'session_id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'order_id' => $order['id'],
                'amount' => $data['total_amount'],
                'coupon_id' => $coupon->id ?? null,
                'expires_at' => now()->addHours(24)
            ]);

            // Find approval link
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return $link['href'];
                }
            }

            throw new \Exception('PayPal order creation failed');

        } catch (\Exception $e) {
            Log::error('PayPal payment initiation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function initiateSSLCommerzPaymentApi($tenant, $user, $package, $data, $coupon = null)
{
    try {
        $totalAmount = (float)$data['total_amount'];
        $sslc = new SSLCommerzService();

        // Create payment data
        $postData = $sslc->createDoctorRegistrationData($user, $package, $totalAmount);

        // Add API URLs for callbacks
        $postData['success_url'] = url('/api/v1/sslcommerz/success');
        $postData['fail_url'] = url('/api/v1/sslcommerz/fail');
        $postData['cancel_url'] = url('/api/v1/sslcommerz/cancel');
        $postData['ipn_url'] = url('/api/v1/sslcommerz/ipn');

        // Store payment session in database
        \App\Models\PaymentSession::create([
            'session_id' => Str::uuid(),
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'order_id' => $postData['tran_id'],
            'amount' => $data['total_amount'],
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

    private function sanitizeDomain($domain)
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        $domain = preg_replace('/^www\./', '', $domain);
        $domain = rtrim($domain, '/');
        return $domain;
    }

    private function detectCardType($cardNumber)
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
    /**
 * PayPal payment success callback
 */
public function paymentSuccessCallback(Request $request)
{
    try {
        $token = $request->input('token');
        $session = PaymentSession::where('order_id', $token)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment session'
            ], 400);
        }

        if ($session->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment session expired'
            ], 400);
        }

        // Verify payment with PayPal
        $provider = new \Srmklive\PayPal\Services\PayPal(config('paypal'));
        $provider->getAccessToken();
        $order = $provider->capturePaymentOrder($token);

        if ($order['status'] === 'COMPLETED') {
            DB::beginTransaction();

            // Update payment status
            $payment = Payment::where('tenant_id', $session->tenant_id)
                ->where('status', 'pending')
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $order['id'],
                    'payment_date' => now()
                ]);

                // Activate tenant and user
                $tenant = Tenant::find($session->tenant_id);
                if ($tenant) {
                    $tenant->update(['status' => 1]);
                    $tenant->domains()->update(['status' => 1]);
                }

                $user = User::find($session->user_id);
                if ($user) {
                    $user->update(['status' => 1]);
                }

                // Update session
                $session->update(['status' => 'completed']);
            }

            DB::commit();

            // Generate token for immediate login
            $user = User::find($session->user_id);
            $token = $user->createToken('doctor-api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment not completed'
        ], 400);

    } catch (\Exception $e) {
        Log::error('Payment success callback failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Payment processing failed'
        ], 500);
    }
}

/**
 * Check registration status
 *
 * @OA\Get(
 *     path="/api/v1/registration/status/{order_id}",
 *     summary="Check registration status",
 *     tags={"Doctor Registration"},
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     )
 * )
 */
public function checkStatus($orderId)
{
    try {
        $session = PaymentSession::where('order_id', $orderId)->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $payment = Payment::where('tenant_id', $session->tenant_id)->first();
        $tenant = Tenant::find($session->tenant_id);
        $user = User::find($session->user_id);

        return response()->json([
            'success' => true,
            'status' => $payment->status ?? 'unknown',
            'data' => [
                'payment_status' => $payment->status ?? null,
                'tenant_status' => $tenant->status ?? null,
                'user_status' => $user->status ?? null,
                'session_status' => $session->status,
                'expired' => $session->isExpired()
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Status check failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Status check failed'
        ], 500);
    }
}

/**
 * PayPal payment success callback
 */


/**
 * Check registration status
 *
 * @OA\Get(
 *     path="/api/v1/registration/status/{order_id}",
 *     summary="Check registration status",
 *     tags={"Doctor Registration"},
 *     @OA\Parameter(
 *         name="order_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status retrieved",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     )
 * )
 */
/**
 * SSLCommerz IPN (Instant Payment Notification)
 */
public function sslcommerzIpn(Request $request)
{
    try {
        Log::info('SSLCommerz IPN Received', $request->all());

        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');
        $amount = $request->input('amount');
        $cardType = $request->input('card_type');

        // Validate IPN data
        $sslc = new SSLCommerzService();
        $validation = $sslc->validatePayment($valId);

        if ($validation['valid']) {
            $session = PaymentSession::where('order_id', $tranId)->first();

            if ($session) {
                DB::beginTransaction();

                // Update payment status
                $payment = Payment::where('tenant_id', $session->tenant_id)
                    ->where('status', 'pending')
                    ->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'completed',
                        'transaction_id' => $tranId,
                        'payment_date' => now(),
                        'card_type' => $cardType
                    ]);

                    // Activate tenant and user
                    $tenant = Tenant::find($session->tenant_id);
                    if ($tenant) {
                        $tenant->update(['status' => 1]);
                        $tenant->domains()->update(['status' => 1]);

                        Log::info('Tenant activated via IPN', [
                            'tenant_id' => $tenant->id,
                            'tran_id' => $tranId
                        ]);
                    }

                    $user = User::find($session->user_id);
                    if ($user) {
                        $user->update(['status' => 1]);
                    }

                    // Update session
                    $session->update(['status' => 'completed']);

                    DB::commit();

                    Log::info('IPN processed successfully', ['tran_id' => $tranId]);
                    return response()->json(['status' => 'SUCCESS']);
                }
            }
        }

        Log::warning('IPN validation failed', ['tran_id' => $tranId]);
        return response()->json(['status' => 'FAILED'], 400);

    } catch (\Exception $e) {
        Log::error('SSLCommerz IPN processing failed', [
            'error' => $e->getMessage(),
            'data' => $request->all()
        ]);
        return response()->json(['status' => 'FAILED'], 500);
    }
}

/**
 * SSLCommerz success callback (user returns from payment)
 */
public function sslcommerzSuccess(Request $request)
{
    try {
        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');

        Log::info('SSLCommerz Success Callback', [
            'tran_id' => $tranId,
            'val_id' => $valId,
            'all_params' => $request->all()
        ]);

        $session = PaymentSession::where('order_id', $tranId)->first();

        if (!$session) {
            Log::warning('Payment session not found', ['tran_id' => $tranId]);
            return response()->json([
                'success' => false,
                'message' => 'Payment session not found'
            ], 400);
        }

        // Validate payment
        $sslc = new SSLCommerzService();
        $validation = $sslc->validatePayment($valId);

        if (!$validation['valid']) {
            Log::warning('Payment validation failed', [
                'tran_id' => $tranId,
                'val_id' => $valId,
                'validation' => $validation
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Payment validation failed'
            ], 400);
        }

        DB::beginTransaction();

        // Update payment status
        $payment = Payment::where('tenant_id', $session->tenant_id)
            ->where('status', 'pending')
            ->first();

        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'transaction_id' => $tranId,
                'payment_date' => now(),
                'card_type' => $validation['data']['card_type'] ?? null,
                'card_no' => $validation['data']['card_no'] ?? null
            ]);

            // Activate tenant and user
            $tenant = Tenant::find($session->tenant_id);
            if ($tenant) {
                $tenant->update(['status' => 1]);
                $tenant->domains()->update(['status' => 1]);

                Log::info('Tenant activated', [
                    'tenant_id' => $tenant->id,
                    'domain' => $tenant->domains()->first()->domain ?? 'N/A'
                ]);
            }

            $user = User::find($session->user_id);
            if ($user) {
                $user->update(['status' => 1]);
            }

            // Update session
            $session->update(['status' => 'completed']);

            DB::commit();

            // Generate token for immediate login
            $token = $user->createToken('doctor-api-token')->plainTextToken;

            Log::info('Payment completed successfully', [
                'user_id' => $user->id,
                'tran_id' => $tranId,
                'amount' => $payment->amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your account is now active.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'tenant_id' => $user->tenant_id
                    ],
                    'tenant' => [
                        'id' => $tenant->id,
                        'name' => $tenant->name,
                        'status' => $tenant->status
                    ],
                    'payment' => [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'transaction_id' => $payment->transaction_id
                    ]
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        } else {
            DB::rollBack();
            Log::error('Payment record not found', ['tran_id' => $tranId]);
            return response()->json([
                'success' => false,
                'message' => 'Payment record not found'
            ], 400);
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('SSLCommerz success callback failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Payment processing failed: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * SSLCommerz fail callback
 */
public function sslcommerzFail(Request $request)
{
    $tranId = $request->input('tran_id');
    $error = $request->input('error');

    Log::warning('SSLCommerz payment failed', [
        'tran_id' => $tranId,
        'error' => $error,
        'all_params' => $request->all()
    ]);

    // Update payment session status
    $session = PaymentSession::where('order_id', $tranId)->first();
    if ($session) {
        $session->update(['status' => 'failed']);
    }

    return response()->json([
        'success' => false,
        'message' => 'Payment failed: ' . ($error ?? 'Unknown error')
    ], 400);
}

/**
 * SSLCommerz cancel callback
 */
public function sslcommerzCancel(Request $request)
{
    $tranId = $request->input('tran_id');

    Log::info('SSLCommerz payment cancelled', [
        'tran_id' => $tranId,
        'all_params' => $request->all()
    ]);

    // Update payment session status
    $session = PaymentSession::where('order_id', $tranId)->first();
    if ($session) {
        $session->update(['status' => 'cancelled']);
    }

    return response()->json([
        'success' => false,
        'message' => 'Payment cancelled by user.'
    ], 400);
}
}
