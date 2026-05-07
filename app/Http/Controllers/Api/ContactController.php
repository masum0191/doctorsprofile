<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Medicine;
use App\Models\Test;
use App\Models\Prescription;
use App\Models\Template;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
class ContactController extends Controller
{
    public function storeMedicine(Request $request)
{
     $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;

    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }

    $tenant = \App\Models\Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    $template=Template::all();
    tenancy()->initialize($tenant);

    $validated = $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'dosage'      => ['nullable', 'string', 'max:100'],
        'frequency'   => ['nullable', 'string', 'max:100'],
        'duration'    => ['nullable', 'string', 'max:100'],
        'instruction' => ['nullable', 'string'],
        'type'        => ['nullable', 'string', 'max:50'],
    ]);

    $medicine = Medicine::create($validated);

    return response()->json([
        'message' => 'Medicine created successfully',
        'data' => $medicine
    ], 201);
    tenancy()->end();
}
public function storeTest(Request $request)
{
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);

    $validated = $request->validate([
        'test_name' => ['required', 'string', 'max:255'],
    ]);

    $test = Test::create($validated);

    return response()->json([
        'message' => 'Test created successfully',
        'data' => $test
    ], 201);
    tenancy()->end();
}

public function storePrescription(Request $request)
{
        $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $validated = $request->validate([
        'doctor_id'  => ['required','exists:users,id'],
        'patient_id' => ['required','exists:users,id'],
        'appointment_id' => ['nullable','exists:appointments,id'],

        'prescribed_date' => ['required','date'],
        'chief_complaint' => ['nullable','string'],
        'diagnosis'       => ['nullable','string'],
        'instructions'    => ['nullable','string'],
        'next_visit_date' => ['nullable','date'],
        'status'          => ['nullable','string'],

        // medicines array
        'medicines' => ['nullable','array'],
        'medicines.*.medicine_id' => ['required','exists:medicines,id'],
        'medicines.*.dosage'      => ['nullable','string'],
        'medicines.*.frequency'   => ['nullable','string'],
        'medicines.*.duration'    => ['nullable','string'],
        'medicines.*.instruction' => ['nullable','string'],

        // tests array
        'tests' => ['nullable','array'],
        'tests.*' => ['exists:tests,id'],
    ]);

    return DB::transaction(function () use ($validated) {

        $prescription = Prescription::create($validated);

        // Attach medicines
        if (!empty($validated['medicines'])) {
            foreach ($validated['medicines'] as $med) {
                $prescription->medicines()->attach($med['medicine_id'], [
                    'dosage'      => $med['dosage'] ?? null,
                    'frequency'   => $med['frequency'] ?? null,
                    'duration'    => $med['duration'] ?? null,
                    'instruction' => $med['instruction'] ?? null,
                ]);
            }
        }

        // Attach tests
        if (!empty($validated['tests'])) {
            $prescription->tests()->attach($validated['tests']);
        }

        return response()->json([
            'message' => 'Prescription created successfully',
            'data' => $prescription->load('medicines','tests')
        ], 201);
    });
    tenancy()->end();
}
public function socialMedia(Request $request){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $settings = \App\Models\Setting::select('facebook_url','twitter_url','instagram_url','linkedin_url','whatsapp_number','telegram_url','tiktok_url')->first();
    return response()->json([
        'status' => true,
        'data' => $settings
    ]);
    tenancy()->end();
}
public function socialMediaUpdate(Request $request){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $validated = $request->validate([
        'facebook_url'   => ['nullable','url'],
        'twitter_url'    => ['nullable','url'],
        'instagram_url'  => ['nullable','url'],
        'linkedin_url'   => ['nullable','url'],
        'whatsapp_number'=> ['nullable','string'],
        'telegram_url'   => ['nullable','url'],
        'tiktok_url'     => ['nullable','url'],
    ]);
    $settings = \App\Models\Setting::first();
    $settings->update($validated);
    return response()->json([
        'status' => true,
        'message' => 'Social media links updated successfully.',
        'data' => $settings
    ]);
    tenancy()->end();

}
public function seoSetting(Request $request){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $settings = \App\Models\Setting::select('meta_title','meta_description','keywords','robots','ogtitle','ogdescription','ogtype','ogurl','ogimage')->first();
    return response()->json([
        'status' => true,
        'data' => $settings
    ]);
    tenancy()->end();
    }


public function seoSettingUpdate(Request $request){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $validated = $request->validate([
        'meta_title'       => ['nullable','string','max:219'],
        'meta_description' => ['nullable','string','max:219'],
        'keywords'         => ['nullable','string','max:219'],
        'robots'           => ['nullable','string','max:119'],
        'ogtitle'          => ['nullable','string','max:119'],
        'ogdescription'    => ['nullable','string','max:219'],
        'ogtype'           => ['nullable','string','max:119'],
        'ogurl'            => ['nullable','string','max:119'],
        'ogimage'          => ['nullable','file','max:119'],
        'google_analytics_id' => ['nullable','string','max:119'],
        'facebook_pixel_id'   => ['nullable','string','max:119'],
        'tagline'            => ['nullable','string','max:255'],
    ]);
    $settings = \App\Models\Setting::first();

    if ($request->hasFile('ogimage')) {
        $image = $request->file('ogimage');
        $folder = 'uploads/seo_images';

        // Delete old image if exists
        if ($settings->ogimage) {
            $oldFilePath = public_path($settings->ogimage);
            if (file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        // Generate unique filename and move
        $extension = $image->getClientOriginalExtension();
        $imageName = time() . '_' . uniqid() . '.' . $extension;

        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $image->move(public_path($folder), $imageName);
        $databasePath = $folder . '/' . $imageName;

        $settings->update(['ogimage' => $databasePath]);
    }



    $settings->update($validated);
    return response()->json([
        'status' => true,
        'message' => 'SEO settings updated successfully.',
        'data' => $settings
    ]);
    tenancy()->end();
}

// email setting sms payment gateway setting functions
public function email_sms_payment_Settings(Request $request,$type){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $settings = \App\Models\Setting::first();

    $extraData = $settings->extra_data ?? [];

    if($type=='email'){
    $settings = $extraData['email']   ?? [];
    }elseif($type=='sms'){
    $settings = $extraData['sms']     ?? [];
    }elseif($type=='payment'){
    $settings = $extraData['payment'] ?? [];
    }
    return response()->json([
        'status' => true,
        'data' => $settings
    ]);
    tenancy()->end();
}
public function update_email_sms_payment_Settings(Request $request,$type){
    $authUser = request()->user(); // Sanctum-safe
    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $tenantId = $authUser->tenant_id ?? null;
    if (!$tenantId) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not resolved.',
        ], 422);
    }
    $tenant = \App\Models\Tenant::find($tenantId);
    if (!$tenant) {
        return response()->json([
            'success' => false,
            'message' => 'Your account is not found.',
        ], 404);
    }
    tenancy()->initialize($tenant);
    $settings = \App\Models\Setting::first();
    if ($type === 'email') {

    $request->merge([
    'extra_data' => [
        'email' => [
            'from'     => $request->mail_from,
            'sender'   => $request->mail_sender_name,
            'host'     => $request->smtp_host,
            'port'     => $request->smtp_port,
            'username' => $request->smtp_username,
            'password' => $request->smtp_password,
        ],
    ],
]);

$validated = $request->validate([
    'extra_data.email' => ['required', 'array'],
]);

$settings->update([
    'extra_data->email' => $validated['extra_data']['email'],
]);


} elseif ($type === 'sms') {

    $validated = $request->validate([
        'extra_data.sms' => ['required', 'array'],
    ]);

    $settings->update([
        'extra_data->sms' => $validated['extra_data']['sms'],
    ]);

} elseif ($type === 'payment') {

    $validated = $request->validate([
        'extra_data.payment' => ['required', 'array'],
    ]);

    $settings->update([
        'extra_data->payment' => $validated['extra_data']['payment'],
    ]);
}

    return response()->json([
        'status' => true,
        'message' => ucfirst($type).' settings updated successfully.',
        'data' => $settings
    ]);
    tenancy()->end();
}
}


