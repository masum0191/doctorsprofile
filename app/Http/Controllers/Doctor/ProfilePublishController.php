<?php

namespace App\Http\Controllers\Doctor;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfilePublishController extends Controller
{
    public function edit(Request $request)
    {

        $doctor = $request->user()->load([
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
            'telemedicinePlatforms'
        ]);

        $settings = DB::table('settings')->first();

        return view('profile.doctorProfile', compact('doctor', 'settings'));
    }

    public function update(Request $request, $id)
    {
     //  dd($request->all());
        $doctor = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $doctor->id,
            'password'  => 'nullable|min:8|confirmed',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request, $doctor) {

            /* ================= USER ================= */
            $doctor->update([
                'name'       => $request->name,
                'email'      => $request->email,
                'mobile'     => $request->phone,
                'country'    => $request->country,
                'city'       => $request->city,
                'qualification' => $request->qualification,
                'reg_no'     => $request->reg_no,
                'latitude'   => $request->latitude,
                'longitude'  => $request->longitude,
                'address'    => $request->address,
                'is_available_today'     => $request->boolean('is_available_today'),
                'accepts_virtual_visits' => $request->boolean('accepts_virtual_visits'),
                'accepts_insurance'      => $request->boolean('accepts_insurance'),
                'rating'     => $request->rating,
                //             $table->json('specialization')->nullable();
                'specialization' => $request->medical_specialties,
            ]);
            $password = $doctor->password;
            if ($request->filled('password')) {
                $doctor->update(['password' => bcrypt($request->password)]);
                $password = bcrypt($request->password);
            }

            /* ================= PROFILE PHOTO ================= */
            $this->handleProfilePhotoUpload($request, $doctor);

            /* ================= PROFILE ================= */
            $profile = $doctor->profile()->updateOrCreate(
                ['user_id' => $doctor->id],
                [
                    'headline'          => $request->headline,
                    'subheadline'       => $request->subheadline,
                    'tagline'           => $request->tagline,
                    'about_short'       => $request->about_short,
                    'about_long'        => $request->about_long,
                    'years_experience'  => (int) ($request->years_experience ?? 0),
                    'patients_count'    => (int) ($request->patients_count ?? 0),
                    'satisfaction_rate' => (int) ($request->satisfaction_rate ?? 0),
                    'sections'          => $request->sections ?? [],
                    'meta'              => $request->meta ?? [],
                ]
            );

            /* ================= HERO IMAGE ================= */
            if ($profile) {
                $this->handleHeroImageUpload($request, $profile);
            }
           // dd($doctor->photo);
            // mother user update
// mother user update
$payload = [
    'email' => $request->email,
    'name'  => $request->name,
    'phone' => $request->phone,
    'address' => $request->address,
    'latitude' => $request->latitude,
    'longitude' => $request->longitude,
    'specialty' => $request->specialty,
    'photo' => $doctor->photo ? url($doctor->photo) : null, // Use existing photo URL
    'medical_specialties' => $request->medical_specialties,
    'is_available_today' => $request->boolean('is_available_today'),
    'accepts_virtual_visits' => $request->boolean('accepts_virtual_visits'),
    'accepts_insurance' => $request->boolean('accepts_insurance'),
];

if ($request->filled('password')) {
    $payload['password'] = $request->password;
    $payload['password_confirmation'] = $request->password;
}

/* ================= FILE HANDLING ================= */
$tempFilePath = null;
$originalFilename = null;

// // Check if a new photo is being uploaded
// if ($request->hasFile('photo')) {
//     try {
//         // Get file information
//         $photo = $request->file('photo');
//         $originalFilename = $photo->getClientOriginalName();
//         $mimeType = $photo->getMimeType();
//         $fileSize = $photo->getSize();

//         Log::info('File upload attempt:', [
//             'original_name' => $originalFilename,
//             'mime_type' => $mimeType,
//             'size' => $fileSize,
//             'extension' => $photo->getClientOriginalExtension()
//         ]);

//         // Create temp directory in storage
//         $tempDir = storage_path('app/temp_uploads');
//         if (!file_exists($tempDir)) {
//             mkdir($tempDir, 0755, true);
//         }

//         // Generate unique filename
//         $tempFileName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
//         $tempFilePath = $tempDir . '/' . $tempFileName;

//         // Save the file
//         $photo->move($tempDir, $tempFileName);

//         // Verify file was saved
//         if (!file_exists($tempFilePath)) {
//             Log::error('File not found after upload: ' . $tempFilePath);
//             throw new \Exception('Failed to save uploaded file');
//         }

//         Log::info('File saved successfully:', [
//             'path' => $tempFilePath,
//             'actual_size' => filesize($tempFilePath),
//             'is_readable' => is_readable($tempFilePath)
//         ]);

//     } catch (\Exception $e) {
//         Log::error('File upload error: ' . $e->getMessage());
//         // Continue without file upload if it fails
//         $tempFilePath = null;
//     }
// } else {
//     // If no new file is uploaded, include the existing photo URL
//     $payload['photo'] = $doctor->photo ? asset($doctor->photo) : null;
// }

/* ================= API CALL ================= */
$http = Http::withHeaders([
    'X-API-TOKEN' => config('services.static_api.token'),
    'Accept' => 'application/json',
]);

try {
    if ($tempFilePath && file_exists($tempFilePath)) {
        // Create multipart data for file upload
        $multipartData = [];

        // Add all form fields except 'photo' (since we're uploading file)
        foreach ($payload as $key => $value) {
            // Skip 'photo' field when uploading file
            if ($key !== 'photo') {
                $multipartData[] = [
                    'name' => $key,
                    'contents' => (string) $value
                ];
            }
        }

        // Add the file
        $multipartData[] = [
            'name' => 'photo',
            'contents' => fopen($tempFilePath, 'r'),
            'filename' => $originalFilename ?? 'profile_photo.jpg'
        ];

        Log::info('Sending multipart request with file');

        $response = $http->asMultipart()->post(
            'https://www.doctorsprofile.xyz/api/doctor/profile/update',
            $multipartData
        );

    } else {
        // No file upload, send regular form data
        Log::info('Sending request without file upload');

        $response = $http->asForm()->post(
            'https://www.doctorsprofile.xyz/api/doctor/profile/update',
            $payload
        );
    }

    /* ================= CLEANUP ================= */
    if ($tempFilePath && file_exists($tempFilePath)) {
        try {
            // Delete temp file
            unlink($tempFilePath);
            Log::info('Temp file cleaned up: ' . $tempFilePath);

            // Clean empty directory
            $tempDir = dirname($tempFilePath);
            if (is_dir($tempDir) && count(scandir($tempDir)) == 2) {
                rmdir($tempDir);
            }
        } catch (\Exception $cleanupError) {
            Log::warning('Cleanup error: ' . $cleanupError->getMessage());
        }
    }

    /* ================= RESPONSE HANDLING ================= */
    if ($response->successful()) {
        Log::info('API call successful', ['response' => $response->json()]);
    } else {
        Log::error('API Error Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers()
        ]);
    }

} catch (\Exception $e) {
    Log::error('API Call error: ' . $e->getMessage());
}

        /* ================= CLEANUP ================= */


        /* ================= RESPONSE HANDLING ================= */
    //     if ($response && $response->successful()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profile updated successfully',
    //             'data' => $response->json()
    //         ]);
    //     } elseif ($response) {
    //         Log::error('API Error Response:', [
    //             'status' => $response->status(),
    //             'body' => $response->body(),
    //             'headers' => $response->headers()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'API request failed',
    //             'error' => $response->body(),
    //             'status' => $response->status()
    //         ], $response->status());
    //     } else {
    //         throw new \Exception('No response received from API');
    //     }

    // } catch (\Exception $e) {
    //     // Cleanup on error
    //     if ($tempFilePath && file_exists($tempFilePath)) {
    //         @unlink($tempFilePath);
    //     }

    //     Log::error('API Call error: ' . $e->getMessage());
    //     Log::error('Trace: ' . $e->getTraceAsString());

    //     // return response()->json([
    //     //     'success' => false,
    //     //     'message' => 'API call failed',
    //     //     'error' => $e->getMessage()
    //     // ], 500);
    // }
            /* ================= SECTIONS ================= */
            $this->syncEducations($doctor, $request->educations ?? []);
            $this->syncExperiences($doctor, $request->experiences ?? []);
            $this->syncCertifications($doctor, $request->certifications ?? []);
            $this->syncAffiliations($doctor, $request->affiliations ?? []);
            $this->syncSpecialties($doctor, $request->specialties ?? []);
            $this->syncServices($doctor, $request->services ?? []);
            $this->syncFaqs($doctor, $request->faqs ?? []);
            $this->syncTelemedicinePlatforms($doctor, $request->telemedicine_platforms ?? []);

            /* ================= TESTIMONIALS ================= */
            if ($request->has('testimonials')) {
                $this->syncTestimonials($doctor, $request->testimonials);
            }

            /* ================= GALLERY ================= */
            if ($request->has('gallery')) {
                $this->syncGallery($doctor, $request->gallery);
            }
        });

        return redirect()->back()->with('success', 'Doctor profile updated successfully.');
    }

    // syncEducations

    /* ================= EDUCATIONS ================= */
private function syncEducations($doctor, $educations)
{
    $doctor->educations()->delete();

    foreach ($educations as $item) {
        if (!empty($item['degree'])) {
            $doctor->educations()->create($item);
        }
    }
}

/* ================= EXPERIENCES ================= */
private function syncExperiences($doctor, $experiences)
{
    $doctor->experiences()->delete();

    foreach ($experiences as $item) {
        if (!empty($item['title'])) {
            $doctor->experiences()->create($item);
        }
    }
}

/* ================= CERTIFICATIONS ================= */
private function syncCertifications($doctor, $certifications)
{
    $doctor->certifications()->delete();

    foreach ($certifications as $item) {
        if (!empty($item['title'])) {
            $doctor->certifications()->create($item);
        }
    }
}

/* ================= AFFILIATIONS ================= */
private function syncAffiliations($doctor, $affiliations)
{
    $doctor->affiliations()->delete();

    foreach ($affiliations as $item) {
        if (!empty($item['name'])) {
            $doctor->affiliations()->create($item);
        }
    }
}

/* ================= SPECIALTIES ================= */
private function syncSpecialties($doctor, $specialties)
{
    $doctor->specialties()->delete();

    foreach ($specialties as $item) {
        if (!empty($item['name'])) {
            $doctor->specialties()->create($item);
        }
    }
}

/* ================= SERVICES ================= */
private function syncServices($doctor, $services)
{
    $doctor->services()->delete();

    foreach ($services as $item) {
        if (!empty($item['title'])) {

            // Convert textarea features into array
            if (isset($item['features']) && is_string($item['features'])) {
                $item['features'] = array_filter(
                    array_map('trim', explode("\n", $item['features']))
                );
            }

            $doctor->services()->create($item);
        }
    }
}

/* ================= FAQ ================= */
private function syncFaqs($doctor, $faqs)
{
    $doctor->faqs()->delete();

    foreach ($faqs as $item) {
        if (!empty($item['question'])) {
            $doctor->faqs()->create($item);
        }
    }
}

/* ================= TELEMEDICINE ================= */
private function syncTelemedicinePlatforms($doctor, $platforms)
{
    $doctor->telemedicinePlatforms()->delete();

    foreach ($platforms as $item) {
        if (!empty($item['name'])) {
            $item['active'] = isset($item['active']) ? 1 : 0;
            $doctor->telemedicinePlatforms()->create($item);
        }
    }
}

    /* ======================================================
       FILE UPLOAD HELPERS
    ====================================================== */

    private function handleProfilePhotoUpload($request, $doctor)
    {
        if (!$request->hasFile('photo')) return;

        $folder = 'uploads/doctors/profile';
        $this->ensureFolder($folder);

        if ($doctor->photo) {
            $this->deleteOldFile($doctor->photo);
        }

        $file = $request->file('photo');
        $name = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $name);

        $doctor->update(['photo' => $folder . '/' . $name]);
    }

    private function handleHeroImageUpload($request, $profile)
    {
        if (!$request->hasFile('hero_image')) return;

        $folder = 'uploads/doctors/hero';
        $this->ensureFolder($folder);

        if ($profile->hero_image) {
            $this->deleteOldFile($profile->hero_image);
        }

        $file = $request->file('hero_image');
        $name = uniqid('hero_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $name);

        $profile->update(['hero_image' => $folder . '/' . $name]);
    }

    /* ======================================================
       TESTIMONIALS
    ====================================================== */

    private function syncTestimonials($doctor, $testimonials)
{
    if (empty($testimonials)) return;

    $existing = $doctor->testimonials()->get()->keyBy('id');
    $usedIds = [];

    foreach ($testimonials as $index => $item) {

        if (empty($item['patient_name'])) continue;

        $file = request()->file("testimonials.$index.photo");
        $photoPath = null;

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $photoPath = $this->handleTestimonialPhotoUpload(
                ['photo' => $file],
                $index
            );
        }

        if (!$photoPath && !empty($item['existing_photo'])) {
            $photoPath = $item['existing_photo'];
        }

        $model = $doctor->testimonials()->updateOrCreate(
            ['id' => $item['id'] ?? null],
            [
                'patient_name'    => $item['patient_name'] ?? 'Anonymous',
                'message' => $item['content'],
                'rating'  => $item['rating'] ?? 5,
                'photo'   => $photoPath,
                'content' => $item['content'] ?? '',
                'verified'=> isset($item['verified']) ? 1 : 0,
            ]
        );

        $usedIds[] = $model->id;

        if (!empty($item['id']) && isset($existing[$item['id']])) {
            if ($existing[$item['id']]->photo !== $photoPath && $photoPath) {
                $this->deleteOldFile($existing[$item['id']]->photo);
            }
        }
    }

    foreach ($existing as $old) {
        if (!in_array($old->id, $usedIds)) {
            $this->deleteOldFile($old->photo);
            $old->delete();
        }
    }
}


    /* ======================================================
       GALLERY
    ====================================================== */

    private function syncGallery($doctor, $gallery)
{
    if (empty($gallery)) return;

    $existing = $doctor->galleries()->get()->keyBy('id');
    $usedIds = [];

    foreach ($gallery as $index => $item) {

        if (empty($item['title'])) continue;

        // ✅ Correct file pickup
        $file = request()->file("gallery.$index.image_url");
        $imagePath = null;

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $this->handleGalleryImageUpload(['image' => $file], $index);
        }

        // fallback to old image
        if (!$imagePath && !empty($item['existing_image'])) {
            $imagePath = $item['existing_image'];
        }

        // ❌ NEVER SAVE NULL IMAGE
        if (!$imagePath) continue;

        $model = $doctor->galleries()->updateOrCreate(
            ['id' => $item['id'] ?? null],
            [
                'title'        => $item['title'],
                'category'     => $item['category'] ?? 'facility',
                'caption'      => $item['caption'] ?? null,
                'order_column' => $item['order_column'] ?? $index,
                'image_url'    => $imagePath,
            ]
        );

        $usedIds[] = $model->id;

        // delete replaced image
        if (!empty($item['id']) && isset($existing[$item['id']])) {
            if ($existing[$item['id']]->image_url !== $imagePath) {
                $this->deleteOldFile($existing[$item['id']]->image_url);
            }
        }
    }

    // delete removed items
    foreach ($existing as $old) {
        if (!in_array($old->id, $usedIds)) {
            $this->deleteOldFile($old->image_url);
            $old->delete();
        }
    }
}

/**
 * Handle Gallery Image Upload
 */
private function handleGalleryImageUpload(array $item, int $index)
{
    if (
        empty($item['image']) ||
        !$item['image'] instanceof \Illuminate\Http\UploadedFile
    ) {
        return null;
    }

    $folder = 'uploads/doctors/gallery';

    if (!file_exists(public_path($folder))) {
        mkdir(public_path($folder), 0755, true);
    }

    $file = $item['image'];

    $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $file->move(public_path($folder), $fileName);

    return $folder . '/' . $fileName;
}


    /* ======================================================
       COMMON HELPERS
    ====================================================== */
/**
 * Handle Testimonial Photo Upload
 */
private function handleTestimonialPhotoUpload(array $data, int $index)
{
    if (
        empty($data['photo']) ||
        !$data['photo'] instanceof \Illuminate\Http\UploadedFile
    ) {
        return null;
    }

    $file = $data['photo'];
    $folder = 'uploads/doctors/testimonials';

    if (!file_exists(public_path($folder))) {
        mkdir(public_path($folder), 0755, true);
    }

    $filename = 'testimonial_' . time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $file->move(public_path($folder), $filename);

    return $folder . '/' . $filename;
}

    private function storeFile($file, $folder, $prefix)
    {
        $this->ensureFolder($folder);
        $name = uniqid($prefix) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $name);
        return $folder . '/' . $name;
    }

    private function ensureFolder($folder)
    {
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }
    }

    private function deleteOldFile($path)
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
