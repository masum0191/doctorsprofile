<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Chamber;
use App\Models\Appointment;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\CompanyIncome;
use App\Models\Package;
use App\Models\Subscription;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\SSLCommerzService;
use App\Models\Domain;


class BrowseController extends Controller
{

    public function showDoctor(User $doctor, ?string $slug = null)
    {
        abort_unless($doctor->role === 'tenant' && (bool) $doctor->status, 404);

        $expectedSlug = Str::slug($doctor->name);
        if ($slug !== null && $slug !== $expectedSlug) {
            return redirect()->route('doc-details', [
                'doctor' => $doctor->id,
                'slug' => $expectedSlug,
            ]);
        }

        $specializations = json_decode($doctor->specialization, true);
        if (!is_array($specializations)) {
            $specializations = array_filter(array_map('trim', explode(',', (string) $doctor->specialization)));
        }

        $tenantDoctor = null;
        $chambers = collect();
        $packageFeatures = config('package_features.presets.free', []);

        if ($doctor->tenant_id) {
            $tenant = Tenant::find($doctor->tenant_id);

            if ($tenant) {
                $package = $this->resolveCurrentPackage($tenant, $doctor);
                $packageFeatures = $package ? $package->featureMap() : $packageFeatures;

                tenancy()->initialize($tenant);

                try {
                    $tenantDoctor = User::with([
                        'profile',
                        'educations',
                        'experiences',
                        'certifications',
                        'affiliations',
                        'specialties',
                        'services',
                        'galleries',
                        'testimonials',
                        'faqs',
                        'telemedicinePlatforms',
                    ])
                        ->where(function ($query) use ($doctor) {
                            $query->where('role', 'admin');

                            if ($doctor->email) {
                                $query->orWhere('email', $doctor->email);
                            }
                        })
                        ->first();

                    if ($tenantDoctor) {
                        $chambers = Chamber::where('doctor_id', $tenantDoctor->id)
                            ->where('is_active', true)
                            ->orderBy('name')
                            ->get();

                        if (blank($specializations)) {
                            $tenantSpecializations = $tenantDoctor->specialties->pluck('name')->filter()->values()->all();
                            $specializations = $tenantSpecializations ?: $specializations;
                        }
                    }
                } finally {
                    tenancy()->end();
                }
            }
        }

        $doctor->setAttribute('specialization_list', array_values($specializations ?: ['General Medicine']));
        $doctor->setAttribute('detail_domain', Domain::where('tenant_id', $doctor->tenant_id)->first());

        return view('doctor-details', compact('doctor', 'tenantDoctor', 'chambers', 'packageFeatures'));
    }


    /** AJAX: returns HTML of doctor cards filtered by geo or city */
  public function nearby(Request $r)
{
    $radius    = (int) $r->integer('radius', 25);
    $lat       = $r->float('lat');
    $lng       = $r->float('lng');
    $city      = trim((string) $r->get('city', ''));
    $searchTerm = trim((string) $r->get('search', ''));
    $specialty  = trim((string) $r->get('specialty', ''));

    // --- NEW: Get new badge filter parameters ---
    $topRated = $r->boolean('top_rated');
    $availableToday = $r->boolean('available_today');
    $virtualVisits = $r->boolean('virtual_visits');
    $acceptsInsurance = $r->boolean('accepts_insurance');
    // --- END NEW ---

    // Start by querying ONLY doctors
    $query = \App\Models\User::where('role', 'tenant');

    // Apply search term filter
    if ($searchTerm !== '') {
        $query->where(function($q) use ($searchTerm) {
            $q->where(DB::raw("CONCAT(name)"), 'LIKE', "%{$searchTerm}%")
             ->orWhere('specialization', 'LIKE', "%{$searchTerm}%")
              ->orWhere('name', 'LIKE', "%{$searchTerm}%");
        });
    }

    // Apply specialty filter
    if ($specialty !== '') {
        $query->where('specialization', $specialty);
    }

    // --- NEW: Apply badge filter queries ---
    // Make sure you add these columns to your 'users' table migration!

    if ($availableToday) {
        // Assumes a boolean 'is_available_today' column
        $query->where('is_available_today', true);
    }
    if ($virtualVisits) {
        // Assumes a boolean 'accepts_virtual_visits' column
        $query->where('accepts_virtual_visits', true);
    }
    if ($acceptsInsurance) {
        // Assumes a boolean 'accepts_insurance' column
        $query->where('accepts_insurance', true);
    }
    // --- END NEW ---


    // Apply location filters and ordering
    if ($lat !== null && $lng !== null) {
        $query = $query->whereNotNull('latitude')->whereNotNull('longitude')
                    ->nearby($lat, $lng, $radius); // This adds orderBy('distance_km')

        if ($topRated) {
            // Assumes a 'rating' column
            $query->orderBy('rating', 'desc'); // Add rating as a secondary sort
        }

    } elseif ($city !== '') {
        $query = $query->where('city','like',"%{$city}%");
        if ($topRated) {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy('name');
        }

    } else {
        // No location
        if ($topRated) {
            $query->orderBy('rating', 'desc');
        } elseif ($searchTerm === '' && $specialty === '') {
            $query->latest();
        } else {
            // If searching by text/specialty but no location, sort by name
            $query->orderBy('name');
        }
    }

    $doctors = $query->where('status',1)->take(24)->get();
// dd($doctors);
    // Return a rendered partial for the grid
    return response()->json([
        'html' => view('_partials.grid', compact('doctors'))->render(),
        'count'=> $doctors->count()
    ]);
}



    public function bySpecialty($id)
{
    $specialty = Specialty::findOrFail($id);

    $doctors = User::where('role', 'tenant')
        ->where('status', 1)
        ->whereJsonContains('specialization', $specialty->name)
        ->get();

    return view('by-specialty', compact('specialty', 'doctors'));
}

public function featuredDoctors()
{
    $doctors = User::where('role', 'tenant')
        ->where('status', 1)
        ->where('feature', 1)
        ->latest()
        ->paginate(16);

    return view('featured-doctors', compact('doctors'));
}


// Get available slots for a chamber on specific date
    /* ============================================================
     | OFFLINE SLOT API
     ============================================================ */
//     public function getAvailableSlots(User $doctor, $chamber, $date)
//     {

//         $tenant = Tenant::findOrFail($doctor->tenant_id);
//         tenancy()->initialize($tenant);

//         try {
//             $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();

//             if ($dateObj->isPast() && !$dateObj->isToday()) {
//                 return response()->json(['slots' => []]);
//             }

//             $slots = Chamber::where('id',$chamber)->first()->getAvailableSlots($date);
//              //
//            // $chamber->getAvailableSlots($date);
//             $chamber = Chamber::findOrFail($chamber);
//             $booked = Appointment::
//                where('chamber_id', $chamber->id)
//                ->whereDate('appointment_date', $date)
//                ->whereIn('status', ['pending','confirmed'])
//                 ->pluck('appointment_time')
//                 ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
//                 ->toArray();

//            // $filtered = array_filter($slots, fn ($s) => !in_array($s['start'], $booked));
//            $filtered = array_filter($slots, function ($s) use ($booked) {
//     return !in_array(Carbon::parse($s['start'])->format('H:i'), $booked);
// });

// //return response()->json($booked);
//             return response()->json([
//                 'slots' => array_values($filtered),
//                 'fees' => $chamber->fees
//             ]);
//         } finally {
//             tenancy()->end();
//         }
//     }
public function getAvailableSlots(User $doctor, $chamberId, $date)
{
    $tenant = Tenant::findOrFail($doctor->tenant_id);
    tenancy()->initialize($tenant);

    try {

        $chamber = Chamber::findOrFail($chamberId);

        // 🔥 IMPORTANT: Use chamber doctor_id directly
        $tenantDoctorId = $chamber->doctor_id;

        $slots = $chamber->getAvailableSlots($date);

        $bookedTimes = Appointment::where('doctor_id', $tenantDoctorId)
            ->where('chamber_id', $chamberId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending','confirmed'])
            ->pluck('appointment_time')
            ->toArray();

        $bookedMap = array_flip($bookedTimes);

        $filteredSlots = array_filter($slots, function ($slot) use ($bookedMap) {
            return !isset($bookedMap[$slot['start']]);
        });

        return response()->json([
            'slots' => array_values($filteredSlots),
            'fees'  => $chamber->fees
        ]);

    } finally {
        tenancy()->end();
    }
}






    /* ============================================================
     | ONLINE SLOT API
     ============================================================ */
    public function getOnlineSlots(User $doctor, $date)
    {
        $tenant = Tenant::findOrFail($doctor->tenant_id);
        tenancy()->initialize($tenant);

        try {
            $dateObj = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
            $settings = Setting::first()?->online_schedule ?? [];

            if (!data_get($settings, 'enabled')) {
                return response()->json(['slots' => []]);
            }

            $day = strtolower($dateObj->format('l'));
            $config = data_get($settings, "working_days.$day");

            if (!$config || !data_get($config, 'enabled')) {
                return response()->json(['slots' => []]);
            }

            $duration = data_get($settings, 'slot_duration', 30);
            $buffer   = data_get($settings, 'buffer_minutes', 0);

            $booked = Appointment::where('doctor_id', $doctor->id)
                ->where('consultation_type', 'online')
                ->whereDate('appointment_date', $date)
                ->whereIn('status', ['pending','confirmed'])
                ->pluck('appointment_time')
                ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
                ->toArray();

            $slots = [];

            foreach (data_get($config, 'slots', []) as $range) {
                $start = Carbon::parse($date.' '.$range['from']);
                $end   = Carbon::parse($date.' '.$range['to']);

                while ($start->copy()->addMinutes($duration) <= $end) {
                    $time = $start->format('H:i');

                    $slots[] = [
                        'start' => $time,
                        'available' => !in_array($time, $booked) &&
                                       !($dateObj->isToday() && $start->isPast())
                    ];

                    $start->addMinutes($duration + $buffer);
                }
            }

            return response()->json(['slots' => $slots]);
        } finally {
            tenancy()->end();
        }
    }

    /* ============================================================
     | CHAMBER LIST
     ============================================================ */
    public function chamberList(User $doctor)
    {
        $tenant = Tenant::findOrFail($doctor->tenant_id);
        tenancy()->initialize($tenant);

        try {
            return response()->json(
                Chamber::where('is_active', true)->get(['id','name','fees'])
            );
        } finally {
            tenancy()->end();
        }
    }

    public function paymentMethods(User $doctor)
    {
        abort_unless($doctor->role === 'tenant' && (bool) $doctor->status, 404);

        $methods = [
            [
                'value' => 'cod',
                'label' => 'Cash on Visit',
                'description' => 'Pay at chamber',
                'type' => 'offline',
            ],
        ];

        if (!$doctor->tenant_id) {
            return response()->json([
                'methods' => $methods,
                'default' => $methods[0]['value'],
            ]);
        }

        $tenant = Tenant::findOrFail($doctor->tenant_id);
        tenancy()->initialize($tenant);

        try {
            $setting = Setting::query()->first();
            $methods = $this->getAvailableAppointmentPaymentMethods($setting);

            return response()->json([
                'methods' => array_values($methods),
                'default' => $methods[0]['value'] ?? 'cod',
            ]);
        } finally {
            tenancy()->end();
        }
    }

    /* ============================================================
     | APPOINTMENT STORE (ONLINE + OFFLINE)
     ============================================================ */
   public function store(Request $request)
{
    \Log::info('Appointment booking request started', [
        'doctor_id' => $request->doctor_id,
        'consultation_type' => $request->consultation_type,
        'ip' => $request->ip()
    ]);

   // try {
        // Step 1: Find doctor in landlord database
        $doctor = User::on('mysql')->find($request->doctor_id);

        if (!$doctor) {
            \Log::error('Doctor not found in landlord database', ['doctor_id' => $request->doctor_id]);
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found. Please try again.',
                'errors' => ['doctor_id' => ['The selected doctor is not available.']]
            ], 404);
        }

        \Log::info('Doctor found in landlord database', [
            'doctor_id' => $doctor->id,
            'email' => $doctor->email,
            'tenant_id' => $doctor->tenant_id
        ]);

        // Step 2: Find tenant
        $tenant = Tenant::find($doctor->tenant_id);
        if (!$tenant) {
            \Log::error('Tenant not found for doctor', [
                'doctor_id' => $doctor->id,
                'tenant_id' => $doctor->tenant_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Clinic/Organization not found.'
            ], 404);
        }

        \Log::info('Tenant found, initializing tenancy', ['tenant_id' => $tenant->id]);

        $package = $this->resolveCurrentPackage($tenant, $doctor);
        if ($package && !$package->hasFeature('appointment_booking')) {
            return response()->json([
                'success' => false,
                'message' => 'Online appointment booking is not available on this doctor package.',
            ], 403);
        }

        // Step 3: Initialize tenancy
        tenancy()->initialize($tenant);

        // Step 4: Find doctor in tenant database (by email since ID might differ)
        $tenantDoctor = User::where('email', $doctor->email)->first();
        if (!$tenantDoctor) {
            \Log::error('Doctor not found in tenant database', [
                'landlord_doctor_id' => $doctor->id,
                'email' => $doctor->email,
                'tenant_id' => $tenant->id
            ]);
            tenancy()->end();
            return response()->json([
                'success' => false,
                'message' => 'Doctor profile not found in clinic database.'
            ], 404);
        }

        \Log::info('Doctor found in tenant database', [
            'tenant_doctor_id' => $tenantDoctor->id,
            'name' => $tenantDoctor->name
        ]);

        // Step 5: Validate request data
        $validated = $request->validate([
            'consultation_type' => 'required|in:online,offline',
            'chamber_id' => 'required_if:consultation_type,offline|nullable|exists:chambers,id',
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    // Check if date is not too far in future (optional)
                    $maxDate = now()->addMonths(3)->format('Y-m-d');
                    if (strtotime($value) > strtotime($maxDate)) {
                        $fail('Appointment date cannot be more than 3 months in advance.');
                    }
                }
            ],
            'appointment_time' => 'required',
            'patient_first_name' => 'required|string|max:100',
            'patient_last_name' => 'required|string|max:100',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_symptoms' => 'nullable|string',
            'terms_agreed' => 'required|accepted',
            'payment_method' => 'nullable|in:ssl_commerce,cod',
            'total_amount' => 'nullable|numeric|min:0'
        ]);

        \Log::info('Validation passed', [
            'consultation_type' => $validated['consultation_type'],
            'appointment_date' => $validated['appointment_date']
        ]);

        // Step 6: Check slot availability
        if (!$this->isSlotAvailable($validated, $tenantDoctor->id)) {
            \Log::warning('Slot already booked', [
                'doctor_id' => $tenantDoctor->id,
                'date' => $validated['appointment_date'],
                'time' => $validated['appointment_time'],
                'consultation_type' => $validated['consultation_type']
            ]);

            tenancy()->end();
            return response()->json([
                'success' => false,
                'message' => 'This time slot is already booked. Please choose another time.',
                'slot_available' => false
            ], 422);
        }

        \Log::info('Slot is available');

        // Step 7: Find or create patient
        $patient = User::firstOrCreate(
            ['email' => $validated['patient_email']],
            [
                'name' => trim($validated['patient_first_name'] . ' ' . $validated['patient_last_name']),
                'mobile' => $validated['patient_phone'],
                'password' => bcrypt(Str::random(12)),
                'role' => 'patient',
                'status' => 1,
            ]
        );

        \Log::info('Patient processed', [
            'patient_id' => $patient->id,
            'email' => $patient->email,
            'was_new' => $patient->wasRecentlyCreated
        ]);

        // Step 8: Calculate amount
        $amount = 0;
        if ($validated['consultation_type'] === 'offline' && isset($validated['chamber_id'])) {
            $chamber = Chamber::find($validated['chamber_id']);
            $amount = $chamber ? floatval($chamber->fees) : 0;

            \Log::info('Chamber fees calculated', [
                'chamber_id' => $validated['chamber_id'],
                'fees' => $amount,
                'chamber_name' => $chamber ? $chamber->name : 'N/A'
            ]);
        }

        $setting = Setting::query()->first();
        $availablePaymentMethods = $this->getAvailableAppointmentPaymentMethods($setting);

        // Step 9: Determine payment method
        $paymentMethod = $validated['payment_method'] ?? ($availablePaymentMethods[0]['value'] ?? 'cod');
        if ($amount <= 0) {
            $paymentMethod = 'free'; // Free appointment
        } elseif (!collect($availablePaymentMethods)->pluck('value')->contains($paymentMethod)) {
            tenancy()->end();

            return response()->json([
                'success' => false,
                'message' => 'The selected payment gateway is not active for this doctor.',
                'available_methods' => array_values($availablePaymentMethods),
            ], 422);
        }

        \Log::info('Payment method determined', [
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'original_payment_method' => $validated['payment_method'] ?? 'not_provided'
        ]);

        // Step 10: Create appointment
        $appointmentData = [
            'doctor_id' => $tenantDoctor->id,
            'patient_id' => $patient->id,
            'consultation_type' => $validated['consultation_type'],
            'chamber_id' => $validated['chamber_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'patient_symptoms' => $validated['patient_symptoms'] ?? null,
            'patient_first_name' => $validated['patient_first_name'],
            'patient_last_name' => $validated['patient_last_name'],
            'patient_email' => $validated['patient_email'],
            'patient_phone' => $validated['patient_phone'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $paymentMethod,
            'amount' => $amount,
            'currency' => 'BDT',
            'source' => 'online_booking',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notes' => 'Created via online booking form'
        ];

        $appointment = Appointment::create($appointmentData);

        \Log::info('Appointment created successfully', [
            'appointment_id' => $appointment->id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'reference_no' => $appointment->id
        ]);

        // Step 11: Handle payment based on method
        if ($paymentMethod === 'ssl_commerce' && $amount > 0) {
            \Log::info('Initiating SSL Commerce payment', [
                'appointment_id' => $appointment->id,
                'amount' => $amount
            ]);

            $sslResponse = $this->initiateSSLCommerce($appointment);

            \Log::info('SSL Commerce response prepared');
            tenancy()->end();

            return $sslResponse;
        } else {
            // For COD, free, or zero-amount appointments
            $successMessage = 'Appointment booked successfully!';

            if ($paymentMethod === 'cod') {
                $appointment->update(['notes' => 'Cash on Delivery - payment to be collected during visit']);
                $successMessage .= ' Please pay at the clinic during your visit.';
            }
             else {
                $appointment->update(['payment_status' => 'pending']);
            }

            // Send confirmation notification (you can implement this later)
            // $this->sendConfirmationNotification($appointment);

            \Log::info('Appointment completed without online payment', [
                'appointment_id' => $appointment->id,
                'payment_method' => $paymentMethod,
                'amount' => $amount
            ]);

            tenancy()->end();
           $commissionRate = 0;

if ($tenant->package_id == 1) {
    $commissionRate = 25;
} elseif ($tenant->package_id == 2) {
    $commissionRate = 20;
} else {
    $commissionRate = 15;
}

$commission = ($amount * $commissionRate) / 100;
$companyProfit = $commission;
$doctorProfit  = $amount - $commission;

           if ($amount > 0) {

    CompanyIncome::on('mysql')->create([
        'doctor_id'      => $doctor->id,
        'appointment_id' => $appointment->id ?? null,
        'reference_no'   => 'APT' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
        'type'           => 'appointment',

        'patient_name'   => trim($validated['patient_first_name'] . ' ' . $validated['patient_last_name']),
        'email'          => $validated['patient_email'],
        'phone'          => $validated['patient_phone'],

        'amount'         => $amount,           // Full amount
        'currency'       => 'BDT',
        'source'         => 'online_booking',

        'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'paid',

        // If you add these columns in DB
        'company_profit' => $companyProfit,
        'doctor_profit'  => $doctorProfit,
        'commission_rate'=>$commissionRate,
    ]);
}



            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'appointment_id' => $appointment->id,
                'appointment_reference' => 'APT' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                'confirmation_url' => route('appointment.confirmation', ['appointment' => $appointment->id, 'tenant' => $tenant->id]),
                'requires_payment' => false,
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'patient_name' => trim($validated['patient_first_name'] . ' ' . $validated['patient_last_name']),
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time']
            ]);
        }

    // } catch (\Illuminate\Validation\ValidationException $e) {
    //     \Log::error('Validation failed in appointment booking', [
    //         'errors' => $e->errors(),
    //         'request_data' => $request->except(['patient_symptoms']) // Exclude sensitive data
    //     ]);

    //     if (isset($tenant)) {
    //         tenancy()->end();
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Please check the form for errors.',
    //         'errors' => $e->errors()
    //     ], 422);

    // } catch (\Exception $e) {
    //     \Log::error('Appointment booking error', [
    //         'message' => $e->getMessage(),
    //         'file' => $e->getFile(),
    //         'line' => $e->getLine(),
    //         'trace' => $e->getTraceAsString()
    //     ]);

    //     if (isset($tenant)) {
    //         tenancy()->end();
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'An unexpected error occurred. Please try again or contact support.',
    //         'error' => config('app.debug') ? $e->getMessage() : 'Server error'
    //     ], 500);
    // }
}


/**
 * Initiate SSL eCommerce payment
 */
/**
 * Initiate SSL eCommerce payment using SSLCommerzService
 */
/**
 * Initiate SSL eCommerce payment using SSLCommerzService
 */
private function initiateSSLCommerce(Appointment $appointment)
{
    try {
        \Log::info('Starting SSL payment initiation', [
            'appointment_id' => $appointment->id,
            'amount' => $appointment->amount,
            'patient_email' => $appointment->patient_email
        ]);

        // Initialize SSLCommerzService
        $sslService = new \App\Services\SSLCommerzService();

        // Prepare transaction ID
        $tranId = 'APPT_' . $appointment->id . '_' . time() . '_' . rand(1000, 9999);

        // Prepare customer data
        $customerName = trim($appointment->patient_first_name . ' ' . $appointment->patient_last_name);
        $customerPhone = $appointment->patient_phone;

        // Remove any non-numeric characters from phone except +
        $customerPhone = preg_replace('/[^0-9+]/', '', $customerPhone);

        // If phone doesn't start with +, assume it's a local number
        if (!str_starts_with($customerPhone, '+')) {
            // Add Bangladesh country code if missing
            if (!str_starts_with($customerPhone, '88')) {
                $customerPhone = '88' . ltrim($customerPhone, '0');
            }
            $customerPhone = '+' . $customerPhone;
        }

        // Prepare payment data
        $postData = [
            'total_amount' => number_format($appointment->amount, 2, '.', ''),
            'tran_id' => $tranId,
            'cus_name' => $customerName,
            'cus_email' => $appointment->patient_email,
            'cus_phone' => $customerPhone,
            'cus_add1' => 'Not Provided',
            'cus_add2' => '',
            'cus_city' => 'Not Provided',
            'cus_state' => '',
            'cus_postcode' => '',
            'cus_country' => 'Bangladesh',
            'cus_fax' => '',
            'shipping_method' => 'NO',
            'ship_name' => $customerName,
            'ship_add1' => 'Not Provided',
            'ship_add2' => '',
            'ship_city' => 'Not Provided',
            'ship_state' => '',
            'ship_postcode' => '',
            'ship_country' => 'Bangladesh',
            'product_name' => 'Medical Appointment',
            'product_category' => 'Healthcare Services',
            'product_profile' => 'non-physical-goods',
            'hours_till_departure' => '',
            'flight_type' => '',
            'hotel_name' => '',
            'length_of_stay' => '',
            'check_in_time' => '',
            'hotel_city' => '',
            'product_type' => '',
            'topup_number' => '',
            'country_topup' => '',
            'cart' => json_encode([
                [
                    'product' => 'Doctor Appointment',
                    'amount' => number_format($appointment->amount, 2, '.', '')
                ]
            ]),
            'product_amount' => number_format($appointment->amount, 2, '.', ''),
            'vat' => '0',
            'discount_amount' => '0',
            'convenience_fee' => '0',
            'value_a' => $appointment->id, // Appointment ID
            'value_b' => $appointment->doctor_id, // Doctor ID
            'value_c' => $appointment->consultation_type, // Consultation type
            'value_d' => 'TENANT_' . (tenant('id') ?? 'default') // Tenant ID
        ];

        // Add URLs - use absolute URLs
        $baseUrl = config('app.url');
        $postData['success_url'] = $baseUrl . '/payment/ssl/success';
        $postData['fail_url'] = $baseUrl . '/payment/ssl/fail';
        $postData['cancel_url'] = $baseUrl . '/payment/ssl/cancel';
        $postData['ipn_url'] = $baseUrl . '/payment/ssl/ipn';

        \Log::info('SSL Payment data prepared', [
            'tran_id' => $tranId,
            'amount' => $postData['total_amount'],
            'customer' => $customerName,
            'success_url' => $postData['success_url']
        ]);

        // Initiate payment
        $gatewayUrl = $sslService->initiatePayment($postData);

        if ($gatewayUrl) {
            // Update appointment with transaction ID
            $appointment->update([
                'transaction_id' => $tranId,
                'payment_gateway' => 'ssl_commerce',
                'payment_status' => 'pending',
                'payment_initiated_at' => now(),
                // 'payment_session' => json_encode([
                //     'tran_id' => $tranId,
                //     'gateway_url' => $gatewayUrl,
                //     'initiated_at' => now()->toDateTimeString()
                // ]),
                'notes' => 'SSL Commerce payment initiated. Awaiting payment confirmation.'
            ]);

            \Log::info('SSL Payment initiated successfully', [
                'appointment_id' => $appointment->id,
                'tran_id' => $tranId,
                'gateway_url' => $gatewayUrl,
                'redirecting' => true
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $gatewayUrl,
                'message' => 'Redirecting to secure payment gateway...',
                'tran_id' => $tranId,
                'appointment_id' => $appointment->id
            ]);
        } else {
            \Log::error('SSL Payment initiation returned no gateway URL');
            throw new \Exception('Payment gateway did not return a valid URL.');
        }

    } catch (\Exception $e) {
        \Log::error('SSL Payment initiation failed', [
            'appointment_id' => $appointment->id,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        // Fallback to COD
        $appointment->update([
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'payment_gateway' => 'cod',
            'notes' => 'SSL Commerce failed: ' . substr($e->getMessage(), 0, 200) . ' - Switched to COD'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully! Please pay at the clinic during your visit.',
            'requires_payment' => false,
            'appointment_id' => $appointment->id,
            'confirmation_url' => route('appointment.confirmation', ['appointment' => $appointment->id, 'tenant' => tenant('id')]),
            'fallback_reason' => 'SSL Payment failed: ' . $e->getMessage()
        ]);
    }
}

private function getAvailableAppointmentPaymentMethods(?Setting $setting): array
{
    $methods = [];

    $payment = data_get($setting?->extra_data, 'payment', []);
    $sslEnabled = (bool) data_get($payment, 'sslcommerz.enabled', false)
        && filled(data_get($payment, 'sslcommerz.store_id'))
        && filled(data_get($payment, 'sslcommerz.secret'));

    if ($sslEnabled) {
        $methods[] = [
            'value' => 'ssl_commerce',
            'label' => 'SSL Commerz',
            'description' => 'Card/Bank/Mobile',
            'type' => 'online',
        ];
    }

    $methods[] = [
        'value' => 'cod',
        'label' => 'Cash on Visit',
        'description' => 'Pay at chamber',
        'type' => 'offline',
    ];

    return $methods;
}

/**
 * Check if time slot is available
 */
private function isSlotAvailable($data, $doctorId)
{
    return !Appointment::where('doctor_id', $doctorId)
        ->where('appointment_date', $data['appointment_date'])
        ->where('appointment_time', $data['appointment_time'])
        ->where('consultation_type', $data['consultation_type'])
        ->when($data['consultation_type'] === 'offline', function ($query) use ($data) {
            return $query->where('chamber_id', $data['chamber_id']);
        })
        ->whereIn('status', ['pending', 'confirmed', 'completed'])
        ->exists();
}

private function resolveCurrentPackage(Tenant $tenant, ?User $doctor = null): ?Package
{
    $subscription = Subscription::on('mysql')
        ->where('tenant_id', $tenant->id)
        ->where('status', 'active')
        ->where(function ($query) {
            $query->whereNull('ends_at')->orWhere('ends_at', '>', now());
        })
        ->with('package')
        ->latest()
        ->first();

    if ($subscription?->package) {
        return $subscription->package;
    }

    $packageId = data_get($tenant, 'package_id')
        ?: data_get($tenant, 'data.package_id')
        ?: data_get($doctor, 'package_id')
        ?: data_get($doctor, 'package');

    return $packageId ? Package::on('mysql')->find($packageId) : null;
}

    /* ============================================================
     | CONFIRMATION
     ============================================================ */
    public function confirmation(Request $request, $appointment)
    {
        $tenantId = $request->query('tenant');
        $appointmentModel = null;

        $tenantIds = collect();
        if ($tenantId) {
            $tenantIds->push($tenantId);
        }

        $tenantIds = $tenantIds
            ->merge(Tenant::query()->pluck('id'))
            ->unique()
            ->values();

        foreach ($tenantIds as $candidateTenantId) {
            $tenant = Tenant::find($candidateTenantId);
            if (!$tenant) {
                continue;
            }

            tenancy()->initialize($tenant);

            try {
                $appointmentModel = Appointment::with(['doctor', 'patient', 'chamber'])->find($appointment);

                if ($appointmentModel) {
                    $appointment = $appointmentModel;
                    return view('appointments.confirmation', compact('appointment'));
                }
            } finally {
                tenancy()->end();
            }
        }

        abort(404);
    }
}
