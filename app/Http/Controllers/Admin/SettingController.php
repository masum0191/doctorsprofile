<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Fee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $fee = Fee::first();
        return view('admin.settings.index', compact('setting', 'fee'));
    }

    public function update(Request $request)
    {
        // --- Validate scalar fields (kept from your version; fixed tagline key) ---
        $request->validate([
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255', // <-- was `tageline`
            'email' => 'nullable|max:255',
            'site_name_en' => 'nullable|max:255',
            'site_logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'govt_logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'approving_officer_signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'secretary_officer_signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'chairman_signature' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'leader_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            'family' => 'nullable|string',
            'income' => 'nullable|string',
            'anual_income' => 'nullable|string',
            'bibidh' => 'nullable|string',
            'character_certificate' => 'nullable|string',
            'landless' => 'nullable|string',
            'disabled' => 'nullable|string',
            'no_objection' => 'nullable|string',
            'financial_insolvency' => 'nullable|string',
            'voter' => 'nullable|string',
            'voter_transfer' => 'nullable|string',
            'unemployment' => 'nullable|string',
            'temporary_residence' => 'nullable|string',
            'nationality' => 'nullable|string',
            'permanent_resident' => 'nullable|string',
            'orphan' => 'nullable|string',

            'leader_name' => 'nullable|string|max:255',
            'leader_title' => 'nullable|string|max:255',
            'leader_message' => 'nullable|string|max:1000',

            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:255',
            'robots' => 'nullable|string|max:255',
            'ogtitle' => 'nullable|string|max:255',
            'ogdescription' => 'nullable|string|max:500',
            'ogtype' => 'nullable|string|max:50',
            'ogurl' => 'nullable|max:2048',
            'ogimage' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'watermark' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

            'facebook_url' => 'nullable|max:2048',
            'youtube_url' => 'nullable|max:2048',
            'twitter_url' => 'nullable|max:2048',
            'instagram_url' => 'nullable|max:2048',
            'linkedin_url' => 'nullable|max:2048',
            'whatsapp_number' => 'nullable|max:255',
            'address' => 'nullable',
            'phone' => 'nullable',
            'footer_text' => 'nullable',
            'trade_license_fee' => 'nullable|numeric',
            'worktime' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'office_name' => 'nullable|string|max:255',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'post_code' => 'nullable|string|max:20',
            'helpline_number' => 'nullable|max:255',
            'support_email' => 'nullable|max:255',
            'general_email' => 'nullable|max:255',
            'fax_number' => 'nullable|string|max:20',
            'emergency_number' => 'nullable|max:255',

            'vgd' => 'nullable|string|max:255',
            'kabikha' => 'nullable|string|max:255',
            'employment' => 'nullable|string|max:255',
            'tr' => 'nullable|string|max:255',
            'elderly_allowance' => 'nullable|string|max:255',
            'widow_allowance' => 'nullable|string|max:255',
            'maternity_allowance' => 'nullable|string|max:255',

            'sach_fee' => 'nullable|numeric',
            'aikor' => 'nullable|numeric',
            'sindeboard_fee' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'ocation_trade_fee' => 'nullable|numeric',
            'correction_fee' => 'nullable|numeric',
            'other_fee' => 'nullable|numeric',
            'service_fee' => 'nullable|numeric',
            'live_fee' => 'nullable|numeric',

            'wards' => 'nullable|integer|max:65535',
            'population' => 'nullable|integer|max:4294967295',
            'villages' => 'nullable|integer|max:65535',
            'area' => 'nullable|string|max:255',
            'mouza' => 'nullable|string|max:255',
            'population_density' => 'nullable|string|max:255',
            'unemployment_rate' => 'nullable|string|max:255',
            'education_rate' => 'nullable|string|max:255',

            'store_id' => 'nullable|string|max:255',
            'store_pass' => 'nullable|string|max:255',

            // --- Repeatable groups (arrays) ---
            'institutions' => 'array',
            'institutions.name' => 'array',
            'institutions.address' => 'array',
            'institutions.website' => 'array',
            'institutions.phone' => 'array',

            'villages' => 'array',
            'villages.name' => 'array',
            'villages.area' => 'array',
            'villages.population' => 'array',
            'villages.education_rate' => 'array',

            'markets' => 'array',
            'markets.name' => 'array',
            'markets.type' => 'array', // সরকারি/বেসরকারি

            'orgs' => 'array',
            'orgs.name' => 'array',
            'orgs.type' => 'array',
            'orgs.service' => 'array',
            'orgs.address' => 'array',
            'orgs.website' => 'array',
            'orgs.phone' => 'array',

            'religious' => 'array',
            'religious.name' => 'array',
            'religious.type' => 'array',
            'religious.description' => 'array',

            'tourism' => 'array',
            'tourism.name' => 'array',
            'tourism.type' => 'array',
            'tourism.description' => 'array',
        ]);

        // Ensure one settings row
        /** @var \App\Models\Setting $setting */
        $setting = Setting::firstOrCreate([], []);
        $tenantId = tenant('id'); // Stancl helper in tenant context

        // --- File uploads (DRY) ---
        $baseFolder = public_path("uploads/tenants/{$tenantId}/settings");
        $this->ensureDir($baseFolder);

        $setting->site_logo = $this->storeUploadedFile($request, 'site_logo', $baseFolder, $setting->site_logo);
        $setting->govt_logo = $this->storeUploadedFile($request, 'govt_logo', $baseFolder, $setting->govt_logo);
        $setting->signature = $this->storeUploadedFile($request, 'signature', $baseFolder, $setting->signature);
        $setting->approving_officer_signature = $this->storeUploadedFile($request, 'approving_officer_signature', $baseFolder, $setting->approving_officer_signature);
        $setting->secretary_officer_signature = $this->storeUploadedFile($request, 'secretary_officer_signature', $baseFolder, $setting->secretary_officer_signature);
        $setting->chairman_signature = $this->storeUploadedFile($request, 'chairman_signature', $baseFolder, $setting->chairman_signature);
        $setting->leader_image = $this->storeUploadedFile($request, 'leader_image', $baseFolder, $setting->leader_image);
        $setting->ogimage = $this->storeUploadedFile($request, 'ogimage', $baseFolder, $setting->ogimage);
        $setting->watermark = $this->storeUploadedFile($request, 'watermark', $baseFolder, $setting->watermark);

        // --- Scalar assignments (kept) ---
        $setting->site_name = $request->site_name;
        $setting->tagline = $request->tagline;
        $setting->about = $request->about;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->footer_text = $request->footer_text;
        $setting->trade_license_fee = $request->trade_license_fee;
        $setting->family = $request->family;
        $setting->income = $request->income;
        $setting->anual_income = $request->anual_income;
        $setting->bibidh = $request->bibidh;
        $setting->character_certificate = $request->character_certificate;
        $setting->landless = $request->landless;
        $setting->disabled = $request->disabled;
        $setting->no_objection = $request->no_objection;
        $setting->financial_insolvency = $request->financial_insolvency;
        $setting->voter = $request->voter;
        $setting->voter_transfer = $request->voter_transfer;
        $setting->unemployment = $request->unemployment;
        $setting->temporary_residence = $request->temporary_residence;
        $setting->nationality = $request->nationality;
        $setting->permanent_resident = $request->permanent_resident;
        $setting->orphan = $request->orphan;
        $setting->site_name_en = $request->site_name_en;

        $setting->leader_name = $request->leader_name;
        $setting->leader_title = $request->leader_title;
        $setting->leader_message = $request->leader_message;

        $setting->meta_description = $request->meta_description;
        $setting->keywords = $request->keywords;
        $setting->robots = $request->robots;
        $setting->ogtitle = $request->ogtitle;
        $setting->ogdescription = $request->ogdescription;
        $setting->ogtype = $request->ogtype;
        $setting->ogurl = $request->ogurl;

        $setting->facebook_url = $request->facebook_url;
        $setting->youtube_url = $request->youtube_url;
        $setting->twitter_url = $request->twitter_url;
        $setting->instagram_url = $request->instagram_url;
        $setting->linkedin_url = $request->linkedin_url;
        $setting->whatsapp_number = $request->whatsapp_number;

        $setting->worktime = $request->worktime;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->office_name = $request->office_name;
        $setting->address_line1 = $request->address_line1;
        $setting->address_line2 = $request->address_line2;
        $setting->district = $request->district;
        $setting->post_code = $request->post_code;
        $setting->helpline_number = $request->helpline_number;
        $setting->support_email = $request->support_email;
        $setting->general_email = $request->general_email;
        $setting->fax_number = $request->fax_number;
        $setting->emergency_number = $request->emergency_number;

        $setting->vgd = $request->vgd;
        $setting->kabikha = $request->kabikha;
        $setting->employment = $request->employment;
        $setting->tr = $request->tr;
        $setting->elderly_allowance = $request->elderly_allowance;
        $setting->widow_allowance = $request->widow_allowance;
        $setting->maternity_allowance = $request->maternity_allowance;

        $setting->sach_fee = $request->sach_fee;
        $setting->aikor = $request->aikor;
        $setting->sindeboard_fee = $request->sindeboard_fee;
        $setting->vat = $request->vat;
        $setting->ocation_trade_fee = $request->ocation_trade_fee;
        $setting->correction_fee = $request->correction_fee;
        $setting->other_fee = $request->other_fee;
        $setting->service_fee = $request->service_fee;
        $setting->live_fee = $request->live_fee;

        $setting->wards = $request->wards;
        $setting->population = $request->population;
        $setting->villages = $request->villages;
        $setting->area = $request->area;
        $setting->mouza = $request->mouza;
        $setting->population_density = $request->population_density;
        $setting->unemployment_rate = $request->unemployment_rate;
        $setting->education_rate = $request->education_rate;

        $setting->store_id = $request->store_id;
        $setting->store_pass = $request->store_pass;

        // --- extra_data (repeatables) ---
        $pack = fn(?array $group, array $keys) => $this->packParallelArrays($group, $keys);

        $extra = $setting->extra_data ?? [];
        $readGroup = function (string $groupKey, array $keys, string $prefixIfFlat) use ($request) {
    $group = $request->input($groupKey);        // e.g. institutions => ['name'=>[], 'address'=>...]
    if (is_array($group)) {
        return $this->packParallelArrays($group, $keys);
    }
    // fallback to flat prefixed fields: e.g. edu_name[], edu_address[] ...
    return $this->combineRows($request, $prefixIfFlat, $keys);
};

// শিক্ষা প্রতিষ্ঠান
$educationInstitutions = $readGroup('institutions', ['name','address','website','phone'], 'edu_');

// গ্রাম
$villagesList = $readGroup('villages', ['name','area','population','education_rate'], 'village_');

// হাট বাজার
$markets = $readGroup('markets', ['name','type'], 'market_');

// অন্যান্য প্রতিষ্ঠান
$otherOrgs = $readGroup('orgs', ['name','type','service','address','website','phone'], 'org_');

// ধর্মীয় স্থান
$religiousPlaces = $readGroup('religious', ['name','type','description'], 'relig_');

// ঐতিহাসিক/পর্যটন স্থান
$heritageSites = $readGroup('tourism', ['name','type','description'], 'heritage_');

// Merge into extra_data JSON
$extra = is_array($setting->extra_data) ? $setting->extra_data : [];
$extra['education_institutions'] = $educationInstitutions;
$extra['villages']               = $villagesList;
$extra['markets']                = $markets;
$extra['other_organizations']    = $otherOrgs;
$extra['religious_places']       = $religiousPlaces;
$extra['heritage_sites']         = $heritageSites;

$setting->extra_data = $extra;

// Keep scalar `villages` column numeric (optional: derive from list)
if ($request->filled('villages') && is_numeric($request->villages)) {
    $setting->villages = (int) $request->villages;
} else {
    $setting->villages = count($villagesList);
}

        $setting->save();

        // --- Optional: Fees block (only if present & model exists) ---
        if ($request->filled('fee') && class_exists(\App\Models\Fee::class)) {
            $fee = \App\Models\Fee::find($request->fee);
            if ($fee) {
                $request->validate([
                    'vdf_fee' => 'nullable|numeric',
                    'service_charge' => 'nullable|numeric',
                    'detention_fee' => 'nullable|numeric',
                    'detention_service_charge' => 'nullable|numeric',
                    'citizen_fee' => 'nullable|numeric',
                    'citezent_service_charge' => 'nullable|numeric',
                    'road_permit_fee' => 'nullable|numeric',
                    'rode_permit_service_charge' => 'nullable|numeric',
                    'constartion_fee' => 'nullable|numeric',
                    'constartion_service_charge' => 'nullable|numeric',
                    'land_clearance_fee' => 'nullable|numeric',
                    'land_clearance_service_charge' => 'nullable|numeric',
                    'family_fee' => 'nullable|numeric',
                    'family_service_charge' => 'nullable|numeric',
                    'monthly_income_fee' => 'nullable|numeric',
                    'monthly_income_service_charge' => 'nullable|numeric',
                    'yearly_income_fee' => 'nullable|numeric',
                    'yearly_income_service_charge' => 'nullable|numeric',
                    'marrige_fee' => 'nullable|numeric',
                    'marrige_service_charge' => 'nullable|numeric',
                    'unmarrige_fee' => 'nullable|numeric',
                    'unmarrige_service_charge' => 'nullable|numeric',
                    'secound_fee' => 'nullable|numeric',
                    'secound_service_charge' => 'nullable|numeric',
                    'bibodo_fee' => 'nullable|numeric',
                    'bibodo_service_charge' => 'nullable|numeric',
                    'charecter_fee' => 'nullable|numeric',
                    'charecter_service_charge' => 'nullable|numeric',
                    'disability_fee' => 'nullable|numeric',
                    'disability_service_charge' => 'nullable|numeric',
                    'no_objection_fee' => 'nullable|numeric',
                    'no_objection_service_charge' => 'nullable|numeric',
                    'financial_insolvency_fee' => 'nullable|numeric',
                    'financial_insolvency_service_charge' => 'nullable|numeric',
                    'new_voter_fee' => 'nullable|numeric',
                    'new_voter_service_charge' => 'nullable|numeric',
                    'voter_transfer_fee' => 'nullable|numeric',
                    'voter_transfer_service_charge' => 'nullable|numeric',
                    'unemployment_fee' => 'nullable|numeric',
                    'unemployment_service_charge' => 'nullable|numeric',
                    'temporary_residence_fee' => 'nullable|numeric',
                    'temporary_residence_service_charge' => 'nullable|numeric',
                    'nationality_fee' => 'nullable|numeric',
                    'nationality_service_charge' => 'nullable|numeric',
                    'permanent_resident_fee' => 'nullable|numeric',
                    'permanent_resident_service_charge' => 'nullable|numeric',
                    'maternity_fee' => 'nullable|numeric',
                    'maternity_service_charge' => 'nullable|numeric',
                    'widow_allowance_fee' => 'nullable|numeric',
                    'widow_allowance_service_charge' => 'nullable|numeric',
                    'elderly_allowance_fee' => 'nullable|numeric',
                    'elderly_allowance_service_charge' => 'nullable|numeric',
                    'employment_poor_fee' => 'nullable|numeric',
                    'employment_poor_service_charge' => 'nullable|numeric',
                    'tr_fee' => 'nullable|numeric',
                    'tr_service_charge' => 'nullable|numeric',
                    'kaba_allowance_fee' => 'nullable|numeric',
                    'kaba_allowance_service_charge' => 'nullable|numeric',
                    'vgd_fee' => 'nullable|numeric',
                    'vgd_service_charge' => 'nullable|numeric',
                    'orphan_fee' => 'nullable|numeric',
                    'orphan_service_charge' => 'nullable|numeric',
                ]);

                // Bulk-assign matching attributes if they exist on the model
                $feeFillables = array_keys($request->only([
                    'vdf_fee',
                    'service_charge',
                    'detention_fee',
                    'detention_service_charge',
                    'citizen_fee',
                    'citezent_service_charge',
                    'road_permit_fee',
                    'rode_permit_service_charge',
                    'constartion_fee',
                    'constartion_service_charge',
                    'land_clearance_fee',
                    'land_clearance_service_charge',
                    'family_fee',
                    'family_service_charge',
                    'monthly_income_fee',
                    'monthly_income_service_charge',
                    'yearly_income_fee',
                    'yearly_income_service_charge',
                    'marrige_fee',
                    'marrige_service_charge',
                    'unmarrige_fee',
                    'unmarrige_service_charge',
                    'secound_fee',
                    'secound_service_charge',
                    'bibodo_fee',
                    'bibodo_service_charge',
                    'charecter_fee',
                    'charecter_service_charge',
                    'disability_fee',
                    'disability_service_charge',
                    'no_objection_fee',
                    'no_objection_service_charge',
                    'financial_insolvency_fee',
                    'financial_insolvency_service_charge',
                    'new_voter_fee',
                    'new_voter_service_charge',
                    'voter_transfer_fee',
                    'voter_transfer_service_charge',
                    'unemployment_fee',
                    'unemployment_service_charge',
                    'temporary_residence_fee',
                    'temporary_residence_service_charge',
                    'nationality_fee',
                    'nationality_service_charge',
                    'permanent_resident_fee',
                    'permanent_resident_service_charge',
                    'maternity_fee',
                    'maternity_service_charge',
                    'widow_allowance_fee',
                    'widow_allowance_service_charge',
                    'elderly_allowance_fee',
                    'elderly_allowance_service_charge',
                    'employment_poor_fee',
                    'employment_poor_service_charge',
                    'tr_fee',
                    'tr_service_charge',
                    'kaba_allowance_fee',
                    'kaba_allowance_service_charge',
                    'vgd_fee',
                    'vgd_service_charge',
                    'orphan_fee',
                    'orphan_service_charge'
                ]));
                foreach ($feeFillables as $key) {
                    $fee->{$key} = $request->input($key);
                }

                $fee->save();
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
    public function marketingEdit()
    {
        $setting = MarketingSetting::first() ?? new MarketingSetting([
            'sending_limits' => ['email_per_hour'=>50,'whatsapp_per_hour'=>30]
        ]);
        return view('admin.marketing.settings.edit', compact('setting'));
    }
    

    public function marketingUpdate(Request $request)
    {
        $data = $request->validate([
            'email_provider'=>'nullable|string|max:50',
            'email_from_name'=>'nullable|string|max:120',
            'email_from_address'=>'nullable|email|max:190',

            'smtp_host'=>'nullable|string|max:190',
            'smtp_port'=>'nullable|integer',
            'smtp_user'=>'nullable|string|max:190',
            'smtp_pass'=>'nullable|string|max:190',
            'smtp_encryption'=>'nullable|string|max:20',

            'whatsapp_provider'=>'nullable|string|max:50',
            'whatsapp_token'=>'nullable|string',
            'whatsapp_phone_number_id'=>'nullable|string|max:120',
            'whatsapp_business_account_id'=>'nullable|string|max:120',

            'email_per_hour'=>'nullable|integer|min:1|max:5000',
            'whatsapp_per_hour'=>'nullable|integer|min:1|max:5000',
        ]);

        $setting = MarketingSetting::first() ?? new MarketingSetting();
        $limits = [
            'email_per_hour' => (int)($data['email_per_hour'] ?? 50),
            'whatsapp_per_hour' => (int)($data['whatsapp_per_hour'] ?? 30),
        ];

        $setting->fill($data);
        $setting->sending_limits = $limits;
        $setting->save();

        return back()->with('success','Marketing settings updated');
    }
    /**
     * Convert parallel input arrays (e.g. institutions[name][]) to a row-wise array.
     * Drops rows where all fields are empty.
     */
    private function packParallelArrays(?array $group, array $keys): array
    {
        $group = $group ?? [];
        $len = 0;
        foreach ($keys as $k) {
            $len = max($len, (isset($group[$k]) && is_array($group[$k])) ? count($group[$k]) : 0);
        }
        $rows = [];
        for ($i = 0; $i < $len; $i++) {
            $row = [];
            foreach ($keys as $k) {
                $row[$k] = $group[$k][$i] ?? null;
            }
            // keep if any non-empty value present
            if (collect($row)->filter(fn($v) => filled($v))->isNotEmpty()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * DRY uploader into public/uploads/tenants/{tenant}/settings
     * Returns the relative path if uploaded, otherwise returns the previous value.
     */
    private function storeUploadedFile(Request $request, string $key, string $baseFolder, ?string $current = null): ?string
    {
        if (!$request->hasFile($key)) {
            return $current;
        }
        $file = $request->file($key);
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move($baseFolder, $filename);
        // return relative path for DB
        $relative = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $baseFolder . DIRECTORY_SEPARATOR . $filename);
        return str_replace('\\', '/', $relative);
    }

    private function ensureDir(string $path): void
    {
        if (!is_dir($path)) {
            @mkdir($path, 0755, true);
        }
    }
    // userSettings
    public function userSettings()
    {
        $user = auth()->user();
        // dd($user);
        return view('admin.settings.user', compact('user'));
    }
    public function updateUserSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Save the changes
        $user->save();

        return redirect()->back()->with('success', 'User settings updated successfully!');
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে।');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়।']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে।');
    }
}
