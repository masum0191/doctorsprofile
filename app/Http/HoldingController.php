<?php
 namespace App\Http\Controllers;

 use App\Http\Controllers\Controller;
 use App\Models\Holding;
 use Illuminate\Http\Request;

 class HoldingController extends Controller
 {
     public function create()
     {
         return view('holding-apply');
     }

     public function store(Request $request)
     {
       // dd($request->all());
         $data = $request->validate([
             'applicant_name' => 'required',
             'father_name' => 'nullable',
             'nid' => 'nullable',
             'mobile' => 'required',
             'present_address' => 'required',
             'permanent_address' => 'required',
             'ward_no' => 'required',
             'village' => 'required',
             'holding_type' => 'required',
             'house_description' => 'nullable',
             'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
         ]);

         if ($request->hasFile('document')) {
             $photo = $request->file('document');
             $photoName = time() . '_' . $photo->getClientOriginalName();
             $photo->move(public_path('uploads/holding/documents'), $photoName);
             $data['document'] = 'uploads/holding/documents/' . $photoName;


         }

         Holding::create($data);

         return back()->with('success', 'আপনার হোল্ডিং আবেদন সফলভাবে জমা হয়েছে।');
     }
 }
