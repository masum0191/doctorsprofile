<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\Setting;
use App\Models\MedicineTemplate;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting_page()
{
    $setting = \App\Models\Setting::first();
    return view('setting.setting', compact('setting'));
}

public function setting_update(Request $request)
{
    $setting = \App\Models\Setting::firstOrCreate([]);

    // ================= FILE UPLOAD (OG IMAGE) =================
    if ($request->hasFile('ogimage')) {
        $file = $request->file('ogimage');
        $path = 'uploads/settings';
        $name = 'og_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $name);

        $setting->ogimage = $path . '/' . $name;
    }

    // ================= BASIC FIELDS =================
    $setting->meta_title       = $request->meta_title;
    $setting->keywords         = $request->keywords;
    $setting->meta_description = $request->meta_description;
    $setting->tagline          = $request->tagline ?? null;

    $setting->facebook_url  = $request->facebook_url;
    $setting->youtube_url   = $request->youtube_url;
    $setting->instagram_url = $request->instagram_url;
    $setting->linkedin_url  = $request->linkedin_url;

    // ================= EXTRA DATA (JSON) =================
    $setting->extra_data = [

        'email' => [
            'from'     => $request->mail_from,
            'sender'   => $request->mail_sender_name,
            'host'     => $request->smtp_host,
            'port'     => $request->smtp_port,
            'username' => $request->smtp_username,
            'password' => $request->smtp_password,
        ],

        'sms' => [
            'provider'  => $request->sms_provider,
            'api_key'   => $request->sms_api_key,
            'sender_id' => $request->sms_sender_id,
            'enabled'   => $request->boolean('sms_enabled'),
        ],

        'payment' => [

            'sslcommerz' => [
                'enabled'  => $request->boolean('sslcommerz_enabled'),
                'store_id' => $request->sslcommerz_store_id,
                'secret'   => $request->sslcommerz_secret,
            ],

            'stripe' => [
                'enabled' => $request->boolean('stripe_enabled'),
                'key'     => $request->stripe_key,
                'secret'  => $request->stripe_secret,
            ],

            'bkash' => [
                'enabled' => $request->boolean('bkash_enabled'),
                'app_key' => $request->bkash_app_key,
                'secret'  => $request->bkash_secret,
            ],

            'paypal' => [
                'enabled'   => $request->boolean('paypal_enabled'),
                'client_id'=> $request->paypal_client_id,
                'secret'   => $request->paypal_secret,
            ],
        ],
    ];

    $setting->save();

    return back()->with('success', 'Settings updated successfully');
}

    public function prescriptions_page(){
        $medicine_template= MedicineTemplate::latest()->get();

         return view('setting.prescription')->with('medicine_template',$medicine_template);
    }

    public function test_page(){
         return view('setting.test');
    }

public function onlineSchedulePage()
{
    $setting = Setting::first();

    $settings = $setting?->online_schedule ?? [];

    $settings = array_merge([
        'enabled' => false,
        'timezone' => 'Asia/Dhaka',
        'slot_duration' => 30,
        'buffer_minutes' => 0,
        'allow_same_day_booking' => false,
        'advance_booking_days' => 7,
        'meeting_provider' => 'zoom',
        'auto_generate_meeting' => false,
        'working_days' => [
            'monday'    => ['enabled'=>false,'slots'=>[]],
            'tuesday'   => ['enabled'=>false,'slots'=>[]],
            'wednesday' => ['enabled'=>false,'slots'=>[]],
            'thursday'  => ['enabled'=>false,'slots'=>[]],
            'friday'    => ['enabled'=>false,'slots'=>[]],
            'saturday'  => ['enabled'=>false,'slots'=>[]],
            'sunday'    => ['enabled'=>false,'slots'=>[]],
        ],
    ], is_array($settings) ? $settings : []);

    return view('settings.online-schedule', compact('settings'));
}


    public function updateOnlineSchedule(Request $request)
{
    $request->validate([
        'enabled' => 'required|boolean',
        'timezone' => 'required|string',
        'slot_duration' => 'required|integer|min:5|max:120',
        'buffer_minutes' => 'nullable|integer|min:0|max:60',
        'working_days' => 'required|array',
    ]);

    $setting = Setting::firstOrFail();

    $existing = $setting->online_schedule ?? [];

    // ✅ Merge safely
    $updatedSchedule = array_merge($existing, [
        'enabled' => $request->enabled,
        'timezone' => $request->timezone,
        'slot_duration' => $request->slot_duration,
        'buffer_minutes' => $request->buffer_minutes ?? 0,
        'allow_same_day_booking' => $request->boolean('allow_same_day_booking'),
        'advance_booking_days' => $request->advance_booking_days ?? 7,
        'meeting_provider' => $request->meeting_provider ?? 'zoom',
        'auto_generate_meeting' => $request->boolean('auto_generate_meeting'),
        'working_days' => $request->working_days,
    ]);

    $setting->update([
        'online_schedule' => $updatedSchedule
    ]);

    return back()->with('success', 'Online schedule updated successfully');
}
}
