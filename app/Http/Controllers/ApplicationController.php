<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\TradeLicense;
use App\Models\User;
use App\Models\Setting;
use App\Models\BusinessPricing;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationController extends Controller
{
    public function store(Request $request)
{
   // dd($request->all());
    $data = $request->validate([
        'image' => 'nullable|image|max:2048',
        'head_name' => 'required|string',
        'father_or_husband_name' => 'required|string',
        'mother_name' => 'required|string',
        'nid_number' => 'required|string',
        'ward_number' => 'nullable|integer|min:1|max:9',
        'birth_date' => 'required|date',
        'mobile_number' => 'required|string',
        'address' => 'required|string',
        'card_type' => 'required|string|in:general,senior,disabled,widow',
        'previous_card_number' => 'nullable|string',
        'conditions' => 'nullable|array|min:4',
    'doc_nid_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    'doc_recommendation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    'doc_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',
    'doc_disability_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    'doc_husband_death_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'applicant_number' => 'nullable|string',
    ]);
    //return 1;
       $user = User::firstOrCreate([
                'nid' => $request->input('nid_number'),
            ], [
                'name' => $request->input('head_name'),
               // 'email' => $request->input('email', Str::random(10) . '@example.com'), // Default email if not provided
              //  'password' => bcrypt(Str::random(10)), // Default password if not provided
                'role' => 'user', // Default role
                'mobile' => $request->input('mobile_number', null), // Optional phone number
            ]

            );

    $data['user_id'] = $user->id;
    // if ($request->hasFile('image')) {
    //     $data['image'] = $request->file('image')->store('uploads/images', 'public');
    // }
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $data['image'] = $imageName;
    }
    if ($request->hasFile('doc_nid_copy')) {
        $file = $request->file('doc_nid_copy');
        $fileName = time() . '_nid.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/documents'), $fileName);
        $data['doc_nid_copy'] = 'uploads/documents/' . $fileName;
    }

    if ($request->hasFile('doc_recommendation')) {
        $file = $request->file('doc_recommendation');
        $fileName = time() . '_recommendation.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/documents'), $fileName);
        $data['doc_recommendation'] = 'uploads/documents/' . $fileName;
    }
    if ($request->hasFile('doc_photo')) {
        $file = $request->file('doc_photo');
        $fileName = time() . '_photo.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/documents'), $fileName);
        $data['doc_photo'] = 'uploads/documents/' . $fileName;
    }
    if ($request->hasFile('doc_disability_certificate')) {
        $file = $request->file('doc_disability_certificate');
        $fileName = time() . '_disability.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/documents'), $fileName);
        $data['doc_disability_certificate'] = 'uploads/documents/' . $fileName;
    }
    if ($request->hasFile('doc_husband_death_certificate')) {
        $file = $request->file('doc_husband_death_certificate');
        $fileName = time() . '_death.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/documents'), $fileName);
        $data['doc_husband_death_certificate'] = 'uploads/documents/' . $fileName;
    }
    $fee = Fee::first();

    $data['fee']=$fee->vdf_fee;
    $data['service_charge']=($fee->vdf_fee*$fee->service_charge)/100;

    // Auto-generate applicant number
    $data['applicant_number'] = 'BGF-' . time();
    $save= Application::create($data);


    return redirect()->back()->with('success', 'আবেদন সফলভাবে জমা হয়েছে!'.' আবেদন নম্বর: ' . $save->applicant_number  .' আবেদনের ফি : ৳ ' . $data['fee']+$data['service_charge']);
}

public function dgfCard()
{
    return view('dgf-dwnload');
}

public function download(Request $request)
{
   // dd($request->all());
    $id = $request->input('applicant_number');
    // dd($id);
    // Validate the ID
    if (!$id) {
        return redirect()->back()->with('error', 'Invalid applicant ID.');
    }

    // Find the applicant by ID
    // dd($id);

    $applicant = Application::where('applicant_number', $id)->first();
    if (!$applicant) {
        // Handle case when applicant is not found
        return redirect()->back()->with('success', 'Applicant not found');
    }
if($applicant->status == 'pending'){
    return redirect()->back()->with('success', 'আবেদনটি এখনও অনুমোদিত হয়নি।');

}
if ($applicant->status == 'rejected') {
    return redirect()->back()->with('success', 'আবেদনটি বাতিল করা হয়েছে।');
}
if ($applicant->status == 'suspended') {
    return redirect()->back()->with('success', 'আবেদনটি স্থগিত করা হয়েছে।');
}




    // Decode conditions if saved as JSON
    if (is_string($applicant->conditions)) {
        $applicant->conditions = json_decode($applicant->conditions, true);
    }

    if ($applicant->status == 'approved') {
        return view('admin.applications.card', compact('applicant'));
    }

    // $pdf = Pdf::loadView('pdf.dgf_download', compact('applicant'))->setOptions([
    //     'defaultFont' => 'SolaimanLipi'
    // ]);
    // return $pdf->download('applicant-' . $applicant->applicant_number . '.pdf');

}
public function profile($id)
{
    // Find the applicant by id
    $applicant = Application::where('applicant_number', $id)->first();
    // dd($applicant);
    // Return the view with the applicant data
    return view('pdf.dgf_download', compact('applicant'));
}


// trade license
public function tradeLicense(Request $request)
{
    $validated = $request->validate([
        // Personal Info
        'name_bn' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'mother_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'nid' => 'required|string|max:20',
        'mobile' => 'required|string|max:11',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        // Fees (optional override)
        'sach_fee' => 'nullable|numeric',
        'aikor' => 'nullable|numeric',
        'sindeboard_fee' => 'nullable|numeric',
        'vat' => 'nullable|numeric',
        'ocation_trade_fee' => 'nullable|numeric',
        'correction_fee' => 'nullable|numeric',
        'other_fee' => 'nullable|numeric',
        'service_fee' => 'nullable|numeric',
        'live_fee' => 'nullable|numeric',
        'bill' => 'nullable|numeric',
        'fine' => 'nullable|numeric',

        // Business Info
        'business_name_bn' => 'required|string|max:255',
        'business_name_en' => 'required|string|max:255',
        'business_type' => 'required|string|max:50',
        'business_category' => 'required|string|max:50',
        'ward_no' => 'required|string|max:20',
        'business_start_date' => 'required|date',
        'annual_income' => 'required|numeric',

        // Address
        'address_para' => 'required|string|max:255',
        'address_road' => 'required|string|max:255',
        'address_ward_no' => 'required|string|max:20',
        'address_thana' => 'required|string|max:50',
        'address_district' => 'required|string|max:50',

        // Documents
        'recommendation_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'municipal_tax_receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'previous_license_copy' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $licenseNo = 'TL-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $data = $validated;
    $data['license_no'] = $licenseNo;
    // ৩০ জুন, ২০২৩
    $data['validity']='June '. date('Y')+1;
    // Handle file uploads
    $fileFields = ['photo', 'recommendation_letter', 'municipal_tax_receipt', 'previous_license_copy'];

foreach ($fileFields as $field) {
    if ($request->hasFile($field)) {
        $file = $request->file($field);

        // Define the folder inside public
        $folder = public_path('trade_license_docs');

        // Create folder if it doesn't exist
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        // Generate a unique filename
        $filename = time() . '_' . $file->getClientOriginalName();

        // Move file to public folder
        $file->move($folder, $filename);

        // Save relative path to DB
        $data[$field] = 'trade_license_docs/' . $filename;
    }
}


    // Create or get user
    $user = User::firstOrCreate(
        ['nid' => $request->input('nid')],
        [
            'name' => $request->input('name_en'),
            'mobile' => $request->input('mobile'),
            'role' => 'user',
        ]
    );

    // Get settings and pricing
    $setting = Setting::first();
    // dd($setting);
    $bill = BusinessPricing::where('business_category_id', $request->input('business_category'))
        ->where('business_type_id', $request->input('business_type'))->where('license_type','new')
        ->first();

    // Assign fees
    //$data['fee'] = $setting->trade_license_fee;
    $data['sach_fee'] = $setting->sach_fee;
    $data['aikor'] = $setting->aikor;
    $data['sindeboard_fee'] = $setting->sindeboard_fee;
    $data['ocation_trade_fee'] = $setting->ocation_trade_fee;
    $data['correction_fee'] = $setting->correction_fee;
    $data['other_fee'] = $setting->other_fee;
    $data['service_fee'] = $setting->service_fee;
    $data['live_fee'] = $setting->live_fee;
    $data['bill'] = $bill->price ?? 0;

    // Calculate VAT
    $totalBeforeVat = $data['bill'] + $data['sach_fee'] + $data['aikor'] + $data['sindeboard_fee'] +
                      $data['ocation_trade_fee'] + $data['correction_fee'] +
                      $data['other_fee'] + $data['service_fee'] + $data['live_fee'] +
                      $data['bill'];
    $data['vat'] = ($totalBeforeVat * $setting->vat) / 100;
    $data['fee'] =$totalBeforeVat + $data['vat'];

    $data['user_id'] = $user->id;
    //$data['bill'] = $totalBeforeVat + $data['vat'];


    // Create Trade License
    $tradeLicense = TradeLicense::create($data);


    return back()->with('success', 'ট্রেড লাইসেন্সের আবেদন সফলভাবে জমা হয়েছে। লাইসেন্স নং: ' . $tradeLicense->license_no.' লাইনেন্স ফি : ৳ ' . $totalBeforeVat+$data['vat']);

}


public function trade_no(Request $request)
{
    $request->validate([
        'applicant_number' => 'required|string'
    ]);

    $license = TradeLicense::where('license_no', $request->applicant_number)
        ->orWhere('nid', $request->applicant_number)
        ->orWhere('mobile', $request->applicant_number)
        ->first();

    if (!$license) {
        return back()->with('error', 'আবেদন পাওয়া যায়নি। অনুগ্রহ করে ট্র্যাকিং নম্বরটি যাচাই করুন।');
    }

    return view('trade.trade-license-update-checking', [
        'applicant' => $license,
        'progress' => $license->progress()->orderBy('created_at', 'desc')->get()
    ]);
}
// certificate
public function certificate(Request $request)
{
    $request->validate([
        'applicant_number' => 'required|string'
    ]);

    $tradeLicense = TradeLicense::where('license_no', $request->applicant_number)
        ->orWhere('nid', $request->applicant_number)
        ->orWhere('mobile', $request->applicant_number)
        ->first();
    $setting = Setting::first();

    if (!$tradeLicense) {
        return back()->with('error', 'আবেদন পাওয়া যায়নি। অনুগ্রহ করে ট্র্যাকিং নম্বরটি যাচাই করুন।')
                    ->withInput();
    }
//dd($tradeLicense);
    return view('admin.trade-licenses.certificate', [
        'tradeLicense' => $tradeLicense,
        'setting' => $setting,
    ]);
}

public function searchByTradeNo(Request $request)
{
    $request->validate([
        'applicant_number' => 'required|string'
    ]);

    $license = TradeLicense::where('license_no', $request->applicant_number)
        ->orWhere('nid', $request->applicant_number)
        ->orWhere('mobile', $request->applicant_number)
        ->first();

    if (!$license) {
        return back()->with('error', 'আবেদন পাওয়া যায়নি। অনুগ্রহ করে ট্র্যাকিং নম্বরটি যাচাই করুন।')
                    ->withInput();
    }

    return view('your-view-name', [
        'applicant' => $license,
        'progress' => $license->progress()->orderBy('created_at', 'asc')->get()
    ]);
}

public function downloadApplication(TradeLicense $tradeLicense)
{
    // Generate PDF and download
    // Implementation depends on your PDF generation package
}

public function downloadDocument(TradeLicense $tradeLicense, $type)
{
    $validTypes = ['nid_copy', 'recommendation_letter', 'municipal_tax_receipt', 'photo'];

    if (!in_array($type, $validTypes)) {
        abort(404,'Invalid document type');
    }

    if (empty($tradeLicense->$type)) {
        abort(404, 'Document not found');
    }

    $path = storage_path('app/public/' . $tradeLicense->$type);

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->download($path);
}

}
