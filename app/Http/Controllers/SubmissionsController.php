<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Setting;

class SubmissionsController extends Controller
{
public function store(Request $request)
{
   
    $validated = $request->validate([
        'type' => 'required|in:application,opinion,complaint',
        'subject' => 'required|string|max:255',
        'location' => $request->type !== 'opinion' ? 'required|string|max:255' : 'nullable',
        'message' => 'required|string',
        'file' => $request->type === 'application' ? 'required|file|mimes:jpeg,png,pdf,mp4,mov|max:2048' : 'nullable',
        'name' => 'required|string|max:255',
        'nid' => $request->type !== 'opinion' ? 'required|string|max:255' : 'nullable',
        'phone' => 'required|string|max:15',
        'address' => $request->type !== 'opinion' ? 'required|string|max:255' : 'nullable',
        'witnesses' => $request->type === 'complaint' ? 'required|string' : 'nullable',
        'proposed_mediators' => $request->type === 'complaint' ? 'required|string' : 'nullable',
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('public/uploads');
    }

    $submission = Submission::create([
        'type' => $validated['type'],
        'subject' => $validated['subject'],
        'location' => $validated['location'] ?? null,
        'message' => $validated['message'],
        'file_path' => $filePath,
        'name' => $validated['name'],
        'nid' => $validated['nid'] ?? null,
        'phone' => $validated['phone'],
        'address' => $validated['address'] ?? null,
        'witnesses' => $validated['witnesses'] ?? null,
        'proposed_mediators' => $validated['proposed_mediators'] ?? null,
    ]);
    if( $validated['type']=='complaint'||$validated['type']=='application'){

        return view('contact.complain_details')->with('submission',$submission);


    }else{
    return redirect()->back()->with('success', 'Your submission has been received!');
    }
    }

   

public function details($id){
    $submission = Submission::where('id',$id)->first();
    if($submission){
    return view('contact.complain_details')->with('submission',$submission);
    }else{
        return redirect()->back()->with('success', 'Your data not found !');
    }
    
}
}


