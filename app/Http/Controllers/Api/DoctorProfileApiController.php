<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Template;
use App\Models\DoctorAffiliation;
use App\Models\DoctorCertification;
use App\Models\DoctorEducation;
use App\Models\DoctorExperience;
use App\Models\DoctorFaq;
use App\Models\DoctorGallery;
use App\Models\DoctorService;
use App\Models\DoctorSpecialty;
use App\Models\DoctorTestimonial;
use App\Models\DoctorTelemedicinePlatform;
use App\Models\DoctorProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DoctorProfileApiController extends Controller
{
   public function get_profile_single_data1($type)
{
//return response()->json($type, 200);
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
            'message' => 'Tenant not found.',
        ], 404);
    }
    $template=Template::all();
    tenancy()->initialize($tenant);

    try {
        if($type === 'profile_photo'){
            $doctor = \App\Models\User::where('role','admin')->select('photo')->first();
        }
        elseif($type === 'specialty'){
            $doctor = \App\Models\User::where('role','admin')->select('specialization')->first();
        }

    // country field
        elseif($type === 'country'){
            $doctor = \App\Models\User::where('role','admin')->select('country')->first();
        }
        elseif($type === 'licence'){
            $doctor = \App\Models\User::where('role','admin')->select('licence')->first();
        }
        elseif($type === 'profile_info'){
            $doctor = \App\Models\User::where('role','admin')->select('id','name','email','mobile','city','state','present_address','permanent_address','zip_code')->first();
        }
        elseif($type === 'qualification'){
            $doctor = \App\Models\User::where('role','admin')->select('qualification','reg_no')->first();
        }
        // avilability
        elseif($type === 'availability'){
            $doctor = \App\Models\User::where('role','admin')->select('is_available_today','accepts_virtual_visits','accepts_insurance')->first();
        }
        elseif($type === 'location'){
            $doctor = \App\Models\User::where('role','admin')->select('latitude','longitude')->first();
        }
        elseif($type === 'educations'){
            $doctor = \App\Models\User::where('role','admin')->with('educations')->first();
        }
        elseif($type === 'experiences'){
            $doctor = \App\Models\User::where('role','admin')->with('experiences')->first();
        }
        elseif($type === 'certifications'){
            $doctor = \App\Models\User::where('role','admin')->with('certifications')->first();
        }
        elseif($type === 'affiliations'){
            $doctor = \App\Models\User::where('role','admin')->with('affiliations')->first();
        }
        elseif($type === 'specialties'){
            $doctor = \App\Models\User::where('role','admin')->with('specialties')->first();
        }
        elseif($type === 'services'){
            $doctor = \App\Models\User::where('role','admin')->with('services')->first();
        }
        elseif($type === 'testimonials'){
            $doctor = \App\Models\User::where('role','admin')->with('testimonials')->first();
        }
        elseif($type === 'faqs'){
            $doctor = \App\Models\User::where('role','admin')->with('faqs')->first();
        }
        elseif($type === 'telemedicine_platforms'){
            $doctor = \App\Models\User::where('role','admin')->with('telemedicinePlatforms')->first();
        }
        elseif($type === 'gallery'){
            $doctor = \App\Models\User::where('role','admin')->with('galleries')->first();
        }
        // website content
        elseif($type === 'website_content'){
            $doctor = \App\Models\User::where('role','admin')->with('profile')->first();
        }
        // theme data
        elseif($type === 'theme_data'){
            $doctor['all_template'] = $template;
            $doctor['active_template'] = \App\Models\Setting::select('template')->first();

        }
        else {
            $doctor = \App\Models\User::where('role','admin')->select($type)->first();
        }
       // $doctor = \App\Models\User::first();

        return response()->json([
            'success' => true,
            'data' => $doctor,
        ]);
    } finally {
        tenancy()->end();
    }
}


public function get_profile_single_data($type)
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
            'message' => 'Tenant not found.',
        ], 404);
    }
    $template=Template::all();
    tenancy()->initialize($tenant);

    try {

        // ===============================
        // BASIC USER FIELD DATA
        // ===============================

        if ($type === 'profile_photo') {
            $doctor = \App\Models\User::where('role', 'admin')->select('photo')->first();
        }

        elseif ($type === 'specialty') {
            $doctor = \App\Models\User::where('role', 'admin')->select('specialization')->first();
        }

        elseif ($type === 'country') {
            $doctor = \App\Models\User::where('role', 'admin')->select('country')->first();
        }

        elseif ($type === 'licence') {
            $doctor = \App\Models\User::where('role', 'admin')->first();
        }

        elseif ($type === 'profile_info') {
            $doctor = \App\Models\User::where('role', 'admin')
                ->select(
                    'id',
                    'name',
                    'email',
                    'mobile',
                    'city',
                    'state',
                    'present_address',
                    'permanent_address',
                    'zip_code'
                )
                ->first();
        }

        elseif ($type === 'qualification') {
            $doctor = \App\Models\User::where('role', 'admin')
                ->select('qualification', 'reg_no')
                ->first();
        }

        elseif ($type === 'availability') {
            $doctor = \App\Models\User::where('role', 'admin')
                ->select(
                    'is_available_today',
                    'accepts_virtual_visits',
                    'accepts_insurance'
                )
                ->first();
        }

        elseif ($type === 'location') {
            $doctor = \App\Models\User::where('role', 'admin')
                ->select('latitude', 'longitude')
                ->first();
        }

        // ===============================
        // JOIN TABLE DATA (ONLY)
        // ===============================

        elseif ($type === 'educations') {
            $doctor = \App\Models\DoctorEducation::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'experiences') {
            $doctor = \App\Models\DoctorExperience::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'certifications') {
            $doctor = \App\Models\DoctorCertification::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'affiliations') {
            $doctor = \App\Models\DoctorAffiliation::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'specialties') {
            $doctor = \App\Models\DoctorSpecialty::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'services') {
            $doctor = \App\Models\DoctorService::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'testimonials') {
            $doctor = \App\Models\DoctorTestimonial::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'faqs') {
            $doctor = \App\Models\DoctorFaq::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'telemedicine_platforms') {
            $doctor = \App\Models\DoctorTelemedicinePlatform::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        elseif ($type === 'gallery') {
            $doctor = \App\Models\DoctorGallery::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->get();
        }

        // ===============================
        // WEBSITE CONTENT
        // ===============================

        elseif ($type === 'website_content') {
            $doctor = \App\Models\DoctorProfile::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })->first();
        }

        // ===============================
        // THEME DATA
        // ===============================

        elseif ($type === 'theme_data') {
            $doctor = [
                'all_template' => $template,
                'active_template' => \App\Models\Setting::select('template')->first(),
            ];
        }

        // ===============================
        // FALLBACK (SINGLE COLUMN)
        // ===============================

        else {
            $doctor = \App\Models\User::where('role', 'admin')
                ->select($type)
                ->first();
        }

        return response()->json([
            'success' => true,
            'data' => $doctor,
        ]);

    } finally {
        tenancy()->end();
    }
}
public function update_profile_single_data(Request $request, $type)
{
    $authUser = request()->user(); // Sanctum-safe

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    // Update user's photo, latitude, longitude in central table
    $user = User::where('id', $authUser->id)->first();

    if ($request->hasFile('photo')) {
        $image = $request->file('photo');
        $folder = 'uploads/doctors/profile_photos';
        $extension = $image->getClientOriginalExtension();
        $imageName = time() . '_' . uniqid() . '.' . $extension;

        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $image->move(public_path($folder), $imageName);
        $databasePath = $folder . '/' . $imageName;
        $user->photo = $databasePath;
    }

    $user->latitude = $request->latitude ?? $user->latitude;
    $user->longitude = $request->longitude ?? $user->longitude;
    $user->save();

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
            'message' => 'Tenant not found.',
        ], 404);
    }

    tenancy()->initialize($tenant);

    try {
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
                'message' => 'Tenant not found.',
            ], 404);
        }

        //$template = Template::all();
        tenancy()->initialize($tenant);

        try {
            // Get admin user for tenant operations
            $adminUser = \App\Models\User::where('role', 'admin')->first();

            if (!$adminUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin user not found.',
                ], 404);
            }

            // ===============================
            // BASIC USER FIELD DATA
            // ===============================
            if ($type === 'profile_photo') {
               // return response()->json($request->hasFile('photo'), 200);
                $doctor = \App\Models\User::where('email', $authUser->email)
                //->select('photo')
                ->first();
                $databasePath = $doctor->photo;
                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $folder = 'uploads/doctors/profile_photos';
                    $extension = $image->getClientOriginalExtension();
                    $imageName = time() . '_' . uniqid() . '.' . $extension;

                    if (!file_exists(public_path($folder))) {
                        mkdir(public_path($folder), 0755, true);
                    }

                    $image->move(public_path($folder), $imageName);
                    $databasePath = $folder . '/' . $imageName;
                }
                $doctor->photo = $databasePath;
                $doctor->save();
            }

            elseif ($type === 'specialty') {
                $doctor = \App\Models\User::where('role', 'admin')->first();
                //->select('specialization')
               
$doctor->specialization = is_array($request->specialization)
    ? $request->specialization
    : [$request->specialization];                $doctor->save();
            }

            elseif ($type === 'country') {
                $doctor = \App\Models\User::where('role', 'admin')
                //->select('country')
                ->first();
                $doctor->country = $request->country;
                $doctor->save();
            }

            elseif ($type === 'licence') {
               // return response()->json($request->licence, 200);
                $doctor = \App\Models\User::where('role', 'admin')->first();
                //return response()->json($doctor, 200);
                $doctor->licence = $request->licence;
                 $doctor->save();
            }

            elseif ($type === 'profile_info') {
                $doctor = \App\Models\User::where('role', 'admin')
                    // ->select(
                    //     'id',
                    //     'name',
                    //     'email',
                    //     'mobile',
                    //     'city',
                    //     'state',
                    //     'present_address',
                    //     'permanent_address',
                    //     'zip_code'
                    // )
                    ->first();
                $doctor->name = $request->name;
                $doctor->email = $request->email;
                $doctor->mobile = $request->mobile;
                $doctor->city = $request->city;
                $doctor->state = $request->state;
                $doctor->present_address = $request->present_address;
                $doctor->permanent_address = $request->permanent_address;
                $doctor->zip_code = $request->zip_code;
                $doctor->save();
            }

            elseif ($type === 'qualification') {
                $doctor = \App\Models\User::where('role', 'admin')
                   // ->select('qualification', 'reg_no')
                    ->first();
                $doctor->qualification = $request->qualification;
                $doctor->reg_no = $request->reg_no;
                $doctor->save();
            }

            elseif ($type === 'availability') {
                $doctor = \App\Models\User::where('role', 'admin')
                    // ->select(
                    //     'is_available_today',
                    //     'accepts_virtual_visits',
                    //     'accepts_insurance'
                    // )
                    ->first();
                $doctor->is_available_today = $request->is_available_today;
                $doctor->accepts_virtual_visits = $request->accepts_virtual_visits;
                $doctor->accepts_insurance = $request->accepts_insurance;
                $doctor->save();
            }

            elseif ($type === 'location') {
                $doctor = \App\Models\User::where('role', 'admin')
                    //->select('latitude', 'longitude')
                    ->first();
                $doctor->latitude = $request->latitude;
                $doctor->longitude = $request->longitude;
                $doctor->save();
            }

            // ===============================
            // SINGLE JOIN TABLE DATA
            // ===============================

            elseif ($type === 'educations') {
                $doctor = new DoctorEducation();
                $doctor->user_id = $adminUser->id;
                $doctor->degree = $request->degree;
                $doctor->institution = $request->institution;
                $doctor->start_year = $request->start_year;
                $doctor->end_year = $request->end_year;
                $doctor->city = $request->city;
                $doctor->country = $request->country;
                $doctor->description = $request->description;
                $doctor->save();
            }

            elseif ($type === 'experiences') {
                $doctor = new DoctorExperience();
                $doctor->user_id = $adminUser->id;
                $doctor->title = $request->title;
                $doctor->start_year = $request->start_year;
                $doctor->end_year = $request->end_year;
                $doctor->description = $request->description;
                $doctor->organization = $request->organization;
                $doctor->save();
            }

            elseif ($type === 'certifications') {
                $doctor = new DoctorCertification();
                $doctor->user_id = $adminUser->id;
                $doctor->title = $request->title;
                $doctor->organization = $request->organization;
                $doctor->year = $request->year;
                $doctor->description = $request->description;
                $doctor->save();
            }

            elseif ($type === 'affiliations') {
                $doctor = new DoctorAffiliation();
                $doctor->user_id = $adminUser->id;
                $doctor->type = $request->type;
                $doctor->name = $request->name;
                $doctor->position = $request->position;
                $doctor->description = $request->description;
                $doctor->save();
            }

            elseif ($type === 'specialties') {
                $doctor = new DoctorSpecialty();
                $doctor->user_id = $adminUser->id;
                $doctor->name = $request->name;
                $doctor->description = $request->description;
                $doctor->patients_treated = $request->patients_treated;
                $doctor->save();
            }

            elseif ($type === 'services') {
                $doctor = new DoctorService();
                $doctor->user_id = $adminUser->id;
                $doctor->title = $request->title;
                $doctor->description = $request->description;
                $doctor->features = $request->features;
                $doctor->icon = $request->icon;
                $doctor->badge = $request->badge;
                $doctor->save();
            }

            elseif ($type === 'testimonials') {
                $doctor = new DoctorTestimonial();
                $doctor->user_id = $adminUser->id;
                $doctor->patient_name = $request->patient_name;
                $doctor->photo = $request->photo;
                $doctor->since = $request->since;
                $doctor->rating = $request->rating;
                $doctor->verified = $request->verified;
                $doctor->content = $request->content;
                $doctor->save();
            }

            elseif ($type === 'faqs') {
                $doctor = new DoctorFaq();
                $doctor->user_id = $adminUser->id;
                $doctor->question = $request->question;
                $doctor->answer = $request->answer;
                $doctor->save();
            }

            elseif ($type === 'telemedicine_platforms') {
                $doctor = new DoctorTelemedicinePlatform();
                $doctor->user_id = $adminUser->id;
                $doctor->name = $request->name;
                $doctor->icon = $request->icon;

                $doctor->save();
            }

            elseif ($type === 'gallery') {
                $doctor = new DoctorGallery();
                $doctor->user_id = $adminUser->id;
                $doctor->title = $request->title;
                $doctor->category = $request->category;
                $doctor->image_url = $request->image_url;
                $doctor->caption = $request->caption;
                $doctor->save();
            }

            // ===============================
            // BULK JOIN TABLE DATA (NEW FEATURE)
            // ===============================

            elseif ($type === 'bulk_educations') {
                $educations = $request->educations; // Expecting array of educations
                $savedEducations = [];

                foreach ($educations as $edu) {
                    $doctor = new DoctorEducation();
                    $doctor->user_id = $adminUser->id;
                    $doctor->degree = $edu['degree'] ?? null;
                    $doctor->institution = $edu['institution'] ?? null;
                    $doctor->start_year = $edu['start_year'] ?? null;
                    $doctor->end_year = $edu['end_year'] ?? null;
                    $doctor->city = $edu['city'] ?? null;
                    $doctor->country = $edu['country'] ?? null;
                    $doctor->description = $edu['description'] ?? null;
                    $doctor->save();
                    $savedEducations[] = $doctor;
                }
                $doctor = $savedEducations;
            }

            elseif ($type === 'bulk_experiences') {
                $experiences = $request->experiences; // Expecting array of experiences
                $savedExperiences = [];

                foreach ($experiences as $exp) {
                    $doctor = new DoctorExperience();
                    $doctor->user_id = $adminUser->id;
                    $doctor->title = $exp['title'] ?? null;
                    $doctor->start_year = $exp['start_year'] ?? null;
                    $doctor->end_year = $exp['end_year'] ?? null;
                    $doctor->description = $exp['description'] ?? null;
                    $doctor->organization = $exp['organization'] ?? null;
                    $doctor->save();
                    $savedExperiences[] = $doctor;
                }
                $doctor = $savedExperiences;
            }

            elseif ($type === 'bulk_certifications') {
                $certifications = $request->certifications; // Expecting array of certifications
                $savedCertifications = [];

                foreach ($certifications as $cert) {
                    $doctor = new DoctorCertification();
                    $doctor->user_id = $adminUser->id;
                    $doctor->title = $cert['title'] ?? null;
                    $doctor->organization = $cert['organization'] ?? null;
                    $doctor->year = $cert['year'] ?? null;
                    $doctor->description = $cert['description'] ?? null;
                    $doctor->save();
                    $savedCertifications[] = $doctor;
                }
                $doctor = $savedCertifications;
            }

            elseif ($type === 'bulk_affiliations') {
                $affiliations = $request->affiliations; // Expecting array of affiliations
                $savedAffiliations = [];

                foreach ($affiliations as $aff) {
                    $doctor = new DoctorAffiliation();
                    $doctor->user_id = $adminUser->id;
                    $doctor->type = $aff['type'] ?? null;
                    $doctor->name = $aff['name'] ?? null;
                    $doctor->position = $aff['position'] ?? null;
                    $doctor->description = $aff['description'] ?? null;
                    $doctor->save();
                    $savedAffiliations[] = $doctor;
                }
                $doctor = $savedAffiliations;
            }

            elseif ($type === 'bulk_specialties') {
                $specialties = $request->specialties; // Expecting array of specialties
                $savedSpecialties = [];

                foreach ($specialties as $spec) {
                    $doctor = new DoctorSpecialty();
                    $doctor->user_id = $adminUser->id;
                    $doctor->name = $spec['name'] ?? null;
                    $doctor->description = $spec['description'] ?? null;
                    $doctor->patients_treated = $spec['patients_treated'] ?? null;
                    $doctor->save();
                    $savedSpecialties[] = $doctor;
                }
                $doctor = $savedSpecialties;
            }

            elseif ($type === 'bulk_services') {
                $services = $request->services; // Expecting array of services
                $savedServices = [];

                foreach ($services as $serv) {
                    $doctor = new DoctorService();
                    $doctor->user_id = $adminUser->id;
                    $doctor->title = $serv['title'] ?? null;
                    $doctor->description = $serv['description'] ?? null;
                    $doctor->features = $serv['features'] ?? null;
                    $doctor->icon = $serv['icon'] ?? null;
                    $doctor->badge = $serv['badge'] ?? null;
                    $doctor->save();
                    $savedServices[] = $doctor;
                }
                $doctor = $savedServices;
            }

            elseif ($type === 'bulk_testimonials') {
                $testimonials = $request->testimonials; // Expecting array of testimonials
                $savedTestimonials = [];

                foreach ($testimonials as $test) {
                    $doctor = new DoctorTestimonial();
                    $doctor->user_id = $adminUser->id;
                    $doctor->patient_name = $test['patient_name'] ?? null;
                    $doctor->photo = $test['photo'] ?? null;
                    $doctor->since = $test['since'] ?? null;
                    $doctor->rating = $test['rating'] ?? null;
                    $doctor->verified = $test['verified'] ?? null;
                    $doctor->content = $test['content'] ?? null;
                    $doctor->save();
                    $savedTestimonials[] = $doctor;
                }
                $doctor = $savedTestimonials;
            }

            elseif ($type === 'bulk_faqs') {
                $faqs = $request->faqs; // Expecting array of FAQs
                $savedFaqs = [];

                foreach ($faqs as $faq) {
                    $doctor = new DoctorFaq();
                    $doctor->user_id = $adminUser->id;
                    $doctor->question = $faq['question'] ?? null;
                    $doctor->answer = $faq['answer'] ?? null;
                    $doctor->save();
                    $savedFaqs[] = $doctor;
                }
                $doctor = $savedFaqs;
            }

            elseif ($type === 'bulk_telemedicine_platforms') {
                $platforms = $request->platforms; // Expecting array of platforms
                $savedPlatforms = [];

                foreach ($platforms as $plat) {
                    $doctor = new DoctorTelemedicinePlatform();
                    $doctor->user_id = $adminUser->id;
                    $doctor->name = $plat['name'] ?? null;
                    $doctor->icon = $plat['icon'] ?? null;
                    $doctor->save();
                    $savedPlatforms[] = $doctor;
                }
                $doctor = $savedPlatforms;
            }

            elseif ($type === 'bulk_gallery') {
                $galleryItems = $request->gallery_items; // Expecting array of gallery items
                $savedGalleryItems = [];

                foreach ($galleryItems as $item) {
                    $doctor = new DoctorGallery();
                    $doctor->user_id = $adminUser->id;
                    $doctor->title = $item['title'] ?? null;
                    $doctor->category = $item['category'] ?? null;
                    $doctor->image_url = $item['image_url'] ?? null;
                    $doctor->caption = $item['caption'] ?? null;
                    $doctor->save();
                    $savedGalleryItems[] = $doctor;
                }
                $doctor = $savedGalleryItems;
            }

            // ===============================
            // WEBSITE CONTENT (UPDATE OR CREATE)
            // ===============================

            elseif ($type === 'website_content') {
               // return response()->json($request->all(), 200);
                $userID = User::where('email', $authUser->email)->first();
                // Check if profile exists
                $doctor = DoctorProfile::firstOrCreate(['user_id' => $userID->id]);

                // if ($existingProfile) {
                //     $doctor = $existingProfile;
                // } else {
                //     $doctor = new DoctorProfile();
                //     $doctor->user_id = $userID->id;
                // }

                $doctor->headline = $request->headline ?? $doctor->headline;
                $doctor->subheadline = $request->subheadline ?? $doctor->subheadline;
                $doctor->tagline = $request->tagline ?? $doctor->tagline;
                $doctor->about_short = $request->about_short ?? $doctor->about_short;
                $doctor->about_long = $request->about_long ?? $doctor->about_long;
                $doctor->years_experience = $request->years_experience ?? 0;
                $doctor->patients_count = $request->patients_count ?? 0;
                $doctor->satisfaction_rate = $request->satisfaction_rate ?? 0;

                if ($request->hasFile('hero_image')) {
                    $image = $request->file('hero_image');
                    $folder = 'uploads/doctors/hero_images';

                    // Delete old image if exists
                    if ($doctor->hero_image) {
                        $oldFilePath = public_path($doctor->hero_image);
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
                    $doctor->hero_image = $databasePath;
                }

                $doctor->save();
            }

            // ===============================
            // THEME DATA
            // ===============================

            elseif ($type === 'theme_data') {
                $doctor = \App\Models\Setting::first();
                if (!$doctor) {
                    $doctor = new \App\Models\Setting();
                }
                $doctor->template = $request->template;
                $doctor->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully.',
                'data' => $doctor,
            ]);

        } finally {
            tenancy()->end();
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], 500);
    }
}


    public function update(Request $request, $id)
    {
        // 1) Auth user
        $authUser = Auth::user();

        // 2) Resolve tenant id (choose ONE method)
        // Method A: tenant_id is stored on auth user (common)
        $tenantId = $authUser->tenant_id ?? null;

        // Method B (optional): tenant id comes from request header
        // $tenantId = $request->header('X-Tenant-ID');

        if (!$tenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not resolved.',
            ], 422);
        }

        // 3) Find tenant and initialize tenancy
        // If you use stancl/tenancy:
        $tenant = \App\Models\Tenant::find($tenantId); // or tenancy()->find($tenantId)
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        tenancy()->initialize($tenant);

        // From here, ALL queries run in TENANT DB (if tenancy is configured properly)

        // 4) Validate (adjust rules to your schema)
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone'         => ['required', 'string', 'max:30'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'reg_no'        => ['nullable', 'string', 'max:50', Rule::unique('users', 'reg_no')->ignore($id)],
            'city'          => ['nullable', 'string', 'max:100'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],

            'is_available_today'     => ['nullable', 'boolean'],
            'accepts_virtual_visits' => ['nullable', 'boolean'],
            'accepts_insurance'      => ['nullable', 'boolean'],
            'rating'                 => ['nullable', 'numeric', 'min:0', 'max:5'],

            // Profile fields
            'headline'          => ['nullable', 'string', 'max:255'],
            'subheadline'       => ['nullable', 'string', 'max:255'],
            'tagline'           => ['nullable', 'string', 'max:255'],
            'about_short'       => ['nullable', 'string'],
            'about_long'        => ['nullable', 'string'],
            'years_experience'  => ['nullable', 'integer', 'min:0', 'max:80'],
            'patients_count'    => ['nullable', 'integer', 'min:0'],
            'satisfaction_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sections'          => ['nullable', 'array'],
            'meta'              => ['nullable', 'array'],

            // Arrays
            'educations'              => ['nullable', 'array'],
            'experiences'             => ['nullable', 'array'],
            'certifications'          => ['nullable', 'array'],
            'affiliations'            => ['nullable', 'array'],
            'specialties'             => ['nullable', 'array'],
            'services'                => ['nullable', 'array'],
            'testimonials'            => ['nullable', 'array'],
            'faqs'                    => ['nullable', 'array'],
            'telemedicine_platforms'  => ['nullable', 'array'],
            'gallery'                 => ['nullable', 'array'],

            // Files (if applicable)
            'profile_photo' => ['nullable', 'image', 'max:4096'],
            'hero_image'    => ['nullable', 'image', 'max:6144'],
        ]);

        // 5) Authorization (important)
        // If only tenant admins can update any doctor:
        // abort_unless($authUser->can('doctor.update'), 403);
        //
        // Or if doctor can only update self:
        // abort_unless((int)$authUser->id === (int)$id || $authUser->is_admin, 403);

        try {
            $doctor = User::findOrFail($id);

            DB::transaction(function () use ($request, $doctor) {

                $doctor->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'qualification' => $request->qualification,
                    'reg_no' => $request->reg_no,
                    'city' => $request->city,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,

                    // API boolean handling (checkbox-style not guaranteed)
                    'is_available_today' => (bool) $request->input('is_available_today', false),
                    'accepts_virtual_visits' => (bool) $request->input('accepts_virtual_visits', false),
                    'accepts_insurance' => (bool) $request->input('accepts_insurance', false),

                    'rating' => $request->rating,
                ]);

                // If you have these methods already:
                $this->handleProfilePhotoUpload($request, $doctor);

                $profile = $doctor->profile()->updateOrCreate(
                    ['user_id' => $doctor->id],
                    [
                        'headline' => $request->headline,
                        'subheadline' => $request->subheadline,
                        'tagline' => $request->tagline,
                        'about_short' => $request->about_short,
                        'about_long' => $request->about_long,
                        'years_experience' => $request->years_experience,
                        'patients_count' => $request->patients_count,
                        'satisfaction_rate' => $request->satisfaction_rate,
                        'sections' => $request->sections,
                        'meta' => $request->meta,
                    ]
                );

                $this->handleHeroImageUpload($request, $profile);

                // Sync sections (your existing methods)
                $this->syncEducations($doctor, $request->educations ?? []);
                $this->syncExperiences($doctor, $request->experiences ?? []);
                $this->syncCertifications($doctor, $request->certifications ?? []);
                $this->syncAffiliations($doctor, $request->affiliations ?? []);
                $this->syncSpecialties($doctor, $request->specialties ?? []);
                $this->syncServices($doctor, $request->services ?? []);
                $this->syncTestimonials($doctor, $request->testimonials ?? []);
                $this->syncFaqs($doctor, $request->faqs ?? []);
                $this->syncTelemedicinePlatforms($doctor, $request->telemedicine_platforms ?? []);
                $this->syncGallery($doctor, $request->gallery ?? []);
            });

            // Optional: re-fetch to return updated state
            $doctor->load('profile');

            return response()->json([
                'success' => true,
                'message' => 'Doctor profile updated successfully.',
                'data' => $doctor,
            ], 200);

        } finally {
            // 6) Always end tenancy context for API request safety
            tenancy()->end();
        }
    }


     /**
     * Handle Profile Photo Upload
     */
    private function handleProfilePhotoUpload($request, $doctor)
    {
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $folder = 'uploads/doctors/profile_photos';

            // Delete old image if exists
            if ($doctor->photo) {
                $oldFilePath = public_path($doctor->photo);
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

            $doctor->update(['photo' => $databasePath]);
        }
    }

    /**
     * Handle Hero Image Upload
     */
    private function handleHeroImageUpload($request, $profile)
    {
        if ($request->hasFile('hero_image') && $profile) {
            $image = $request->file('hero_image');
            $folder = 'uploads/doctors/hero_images';

            // Delete old image if exists
            if ($profile->hero_image) {
                $oldFilePath = public_path($profile->hero_image);
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

            $profile->update(['hero_image' => $databasePath]);
        }
    }

    /**
     * Handle Testimonial Photo Upload
     */
    private function handleTestimonialPhotoUpload($testimonialData, $index)
    {
        if (isset($testimonialData['photo']) && $testimonialData['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $image = $testimonialData['photo'];
            $folder = 'uploads/doctors/testimonial_photos';

            // Generate unique filename and move
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '_' . $index . '_' . uniqid() . '.' . $extension;

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image->move(public_path($folder), $imageName);
            return $folder . '/' . $imageName;
        }

        return null;
    }

    /**
     * Handle Gallery Image Upload
     */
    private function handleGalleryImageUpload($galleryData, $index)
    {
        if (isset($galleryData['image_url']) && $galleryData['image_url'] instanceof \Illuminate\Http\UploadedFile) {
            $image = $galleryData['image_url'];
            $folder = 'uploads/doctors/gallery';

            // Generate unique filename and move
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '_' . $index . '_' . uniqid() . '.' . $extension;

            if (!file_exists(public_path($folder))) {
                mkdir(public_path($folder), 0755, true);
            }

            $image->move(public_path($folder), $imageName);
            return $folder . '/' . $imageName;
        }

        return null;
    }

    /**
     * Delete old file if exists
     */
    private function deleteOldFile($filePath)
    {
        if ($filePath && file_exists(public_path($filePath))) {
            @unlink(public_path($filePath));
        }
    }

    // Sync methods with file upload handling
    private function syncEducations($doctor, $educations) {
        $doctor->educations()->delete();
        foreach ($educations as $education) {
            if (!empty($education['degree'])) {
                $doctor->educations()->create($education);
            }
        }
    }

    private function syncExperiences($doctor, $experiences) {
        $doctor->experiences()->delete();
        foreach ($experiences as $experience) {
            if (!empty($experience['title'])) {
                $doctor->experiences()->create($experience);
            }
        }
    }

    private function syncCertifications($doctor, $certifications) {
        $doctor->certifications()->delete();
        foreach ($certifications as $certification) {
            if (!empty($certification['title'])) {
                $doctor->certifications()->create($certification);
            }
        }
    }

    private function syncAffiliations($doctor, $affiliations) {
        $doctor->affiliations()->delete();
        foreach ($affiliations as $affiliation) {
            if (!empty($affiliation['name'])) {
                $doctor->affiliations()->create($affiliation);
            }
        }
    }

    private function syncSpecialties($doctor, $specialties) {
        $doctor->specialties()->delete();
        foreach ($specialties as $specialty) {
            if (!empty($specialty['name'])) {
                $doctor->specialties()->create($specialty);
            }
        }
    }

    private function syncServices($doctor, $services) {
        $doctor->services()->delete();
        foreach ($services as $service) {
            if (!empty($service['title'])) {
                // Convert features from textarea to array
                if (isset($service['features']) && is_string($service['features'])) {
                    $service['features'] = array_filter(
                        array_map('trim', explode("\n", $service['features']))
                    );
                }
                $doctor->services()->create($service);
            }
        }
    }

    private function syncTestimonials($doctor, $testimonials) {
        // Store existing testimonials to delete old photos later
        $existingTestimonials = $doctor->testimonials()->get();

        $doctor->testimonials()->delete();

        foreach ($testimonials as $index => $testimonial) {
            if (!empty($testimonial['patient_name'])) {
                // Handle photo upload
                $photoPath = $this->handleTestimonialPhotoUpload($testimonial, $index);

                // Delete old photo if new one is uploaded
                if ($photoPath && isset($existingTestimonials[$index])) {
                    $this->deleteOldFile($existingTestimonials[$index]->photo);
                }

                $testimonialData = [
                    'patient_name' => $testimonial['patient_name'],
                    'since' => $testimonial['since'] ?? null,
                    'rating' => $testimonial['rating'] ?? 5,
                    'verified' => $testimonial['verified'] ? 1 : 0,
                    'content' => $testimonial['content'],
                    'order_column' => $testimonial['order_column'] ?? 0,
                ];

                if ($photoPath) {
                    $testimonialData['photo'] = $photoPath;
                } elseif (isset($testimonial['existing_photo'])) {
                    $testimonialData['photo'] = $testimonial['existing_photo'];
                }

                $doctor->testimonials()->create($testimonialData);
            }
        }

        // Delete all old testimonial photos
        foreach ($existingTestimonials as $oldTestimonial) {
            $this->deleteOldFile($oldTestimonial->photo);
        }
    }

    private function syncFaqs($doctor, $faqs) {
        $doctor->faqs()->delete();
        foreach ($faqs as $faq) {
            if (!empty($faq['question'])) {
                $doctor->faqs()->create($faq);
            }
        }
    }

    // private function syncTelemedicinePlatforms($doctor, $platforms) {
    //     $doctor->telemedicinePlatforms()->delete();
    //     foreach ($platforms as $platform) {
    //         if (!empty($platform['name'])) {
    //             $doctor->telemedicinePlatforms()->create($platform);
    //         }
    //     }
    // }
    private function syncTelemedicinePlatforms($doctor, $platforms)
{
    $doctor->telemedicinePlatforms()->delete();

    foreach ($platforms as $platform) {

        if (!empty($platform['name'])) {

            // Convert checkbox 'on' into 1/0
            $platform['active'] = isset($platform['active']) ? 1 : 0;

            $doctor->telemedicinePlatforms()->create($platform);
        }
    }
}


    private function syncGallery($doctor, $gallery) {
        // Store existing gallery items to delete old images later
        $existingGallery = $doctor->galleries()->get();

        $doctor->galleries()->delete();

        foreach ($gallery as $index => $item) {
            if (!empty($item['title'])) {
                // Handle image upload
                $imagePath = $this->handleGalleryImageUpload($item, $index);

                // Delete old image if new one is uploaded
                if ($imagePath && isset($existingGallery[$index])) {
                    $this->deleteOldFile($existingGallery[$index]->image_url);
                }

                $galleryData = [
                    'title' => $item['title'],
                    'category' => $item['category'] ?? 'facility',
                    'caption' => $item['caption'] ?? null,
                    'order_column' => $item['order_column'] ?? 0,
                ];

                if ($imagePath) {
                    $galleryData['image_url'] = $imagePath;
                } elseif (isset($item['existing_image'])) {
                    $galleryData['image_url'] = $item['existing_image'];
                }

                $doctor->galleries()->create($galleryData);
            }
        }

        // Delete all old gallery images
        foreach ($existingGallery as $oldGallery) {
            $this->deleteOldFile($oldGallery->image_url);
        }
    }
}
