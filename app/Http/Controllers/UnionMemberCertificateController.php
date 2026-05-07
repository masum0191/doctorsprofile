<?php

namespace App\Http\Controllers;

use App\Models\UnionMemberCertificate;
use App\Models\User;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UnionMemberCertificateController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Applicant Information
            'applicant_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'applicant_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'father_or_husband_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'nid_number' => 'required|string|max:20',
            'birth_registration_number' => 'nullable|string|max:20',
            'resident_type' => 'nullable|string',
            'mobile_number' => 'required|string|max:15',
            'holding_number' => 'nullable|string|max:50',
            'holding_info' => 'nullable|string',
            'holding_owner_name' => 'nullable|string|max:255',
            'relation_with_owner' => 'nullable|string',
            'owner_mobile_number' => 'nullable|string|max:15',
            'birth_date' => 'required|date',
            'religion' => 'required|string|in:islam,hinduism,christianity,buddhism,other',
            'email' => 'nullable|email',

            // Address Information
            'present_address' => 'required|string',
            'division' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'union_name' => 'required|string',
            'post_office' => 'nullable|string',
            'ward_number' => 'nullable|string',
            'village_or_area' => 'nullable|string',

            // Permanent Address
            'permanent_address' => 'nullable|string',
            'permanent_division' => 'nullable|string',
            'permanent_district' => 'nullable|string',
            'permanent_upazila' => 'nullable|string',
            'permanent_union' => 'nullable|string',
            'permanent_post_office' => 'nullable|string',
            'permanent_ward_number' => 'nullable|string',
            'permanent_village_or_area' => 'nullable|string',


            // Documents

            'nid_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nid_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'other_documents' => 'nullable|array',
            'other_documents.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
           // Handle file uploads
$filePaths = [];

if ($request->hasFile('applicant_photo')) {
    $filename = time().'_'.$request->file('applicant_photo')->getClientOriginalName();
    $request->file('applicant_photo')->move(public_path('member-certificate/photos'), $filename);
    $filePaths['applicant_photo'] = 'member-certificate/photos/'.$filename;
}

if ($request->hasFile('nid_front')) {
    $filename = time().'_'.$request->file('nid_front')->getClientOriginalName();
    $request->file('nid_front')->move(public_path('member-certificate/nid'), $filename);
    $filePaths['nid_front'] = 'member-certificate/nid/'.$filename;
}

if ($request->hasFile('nid_back')) {
    $filename = time().'_'.$request->file('nid_back')->getClientOriginalName();
    $request->file('nid_back')->move(public_path('member-certificate/nid'), $filename);
    $filePaths['nid_back'] = 'member-certificate/nid/'.$filename;
}

if ($request->hasFile('signature')) {
    $filename = time().'_'.$request->file('signature')->getClientOriginalName();
    $request->file('signature')->move(public_path('member-certificate/signatures'), $filename);
    $filePaths['signature'] = 'member-certificate/signatures/'.$filename;
}

// Handle other documents if present
$otherDocuments = [];
if ($request->hasFile('other_documents')) {
    foreach ($request->file('other_documents') as $file) {
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('member-certificate/other'), $filename);
        $otherDocuments[] = 'member-certificate/other/'.$filename;
    }
}

            $user = User::firstOrCreate([
                'nid' => $request->input('nid_number'),
            ], [
                'name' => $request->input('applicant_name'),
                'email' => $request->input('email', Str::random(10) . '@example.com'), // Default email if not provided
                'password' => bcrypt(Str::random(10)), // Default password if not provided
                'role' => 'user', // Default role
                'mobile' => $request->input('mobile_number', null), // Optional phone number
            ]

            );
    $fee=Fee::first();
    $fee_fee=$fee->marrige_fee;
    $charge=($fee->marrige_fee*$fee->marrige_service_charge)/100;

            $user_id= $user->id;
            // Create application
            $application = UnionMemberCertificate::create(array_merge(
                $validated,
                $filePaths,
                [
                    'application_number' => $request->input('application_number'),
                    'other_documents' => !empty($otherDocuments) ? $otherDocuments : null,
                    'user_id' => $user_id,
                    'fee' =>  $fee_fee,
                    'service_charge' => $charge,
                ]
            ));

            return redirect()->back()
                ->with('success', 'আপনার আবেদন সফলভাবে জমা হয়েছে। আবেদন নম্বর: ' . $application->application_number. ' ফি : ' . $fee_fee+$charge.' টাকা ');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'আবেদন জমা দেওয়ার সময় একটি ত্রুটি হয়েছে: ' . $e->getMessage())
                ->withInput();
        }
    }
}
