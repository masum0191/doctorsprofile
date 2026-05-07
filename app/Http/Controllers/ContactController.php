<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
        ]);

        return back()->with('success', 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে!');
    }

    public function index()
{
    $contacts = Contact::latest()->paginate(10); // সর্বশেষ বার্তাগুলো আগে
    return view('admin.contacts.index', compact('contacts'));
}
public function destroy($id)
{
    $contact = Contact::findOrFail($id);
    $contact->delete();

    return back()->with('success', 'বার্তাটি সফলভাবে মুছে ফেলা হয়েছে!');
}

}
