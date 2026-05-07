<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $q = Contact::query()->where('status','active');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $q->where(function($qq) use ($s){
                $qq->where('name','like',"%{$s}%")
                   ->orWhere('email','like',"%{$s}%")
                   ->orWhere('phone','like',"%{$s}%")
                   ->orWhere('whatsapp','like',"%{$s}%")
                   ->orWhere('bmdc_no','like',"%{$s}%");
            });
        }

        if ($request->filled('city')) $q->where('city',$request->city);
        if ($request->filled('specialty')) $q->where('specialty',$request->specialty);

        if ($request->filled('channel')) {
            if ($request->channel === 'email') $q->where('opt_in_email',1);
            if ($request->channel === 'whatsapp') $q->where('opt_in_whatsapp',1);
        }

        if ($request->boolean('exclude_dnc', true)) {
            $q->where('do_not_contact',0);
        }

        $contacts = $q->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.marketing.contacts.index1', compact('contacts'));
    }

    public function create() {
        return view('admin.marketing.contacts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:150',
            'email'=>'nullable|email|max:190',
            'phone'=>'nullable|string|max:50',
            'whatsapp'=>'nullable|string|max:50',
            'bmdc_no'=>'nullable|string|max:50',
            'city'=>'nullable|string|max:120',
            'area'=>'nullable|string|max:120',
            'address'=>'nullable|string|max:255',
            'specialty'=>'nullable|string|max:120',
            'opt_in_email'=>'nullable|boolean',
            'opt_in_whatsapp'=>'nullable|boolean',
            'source'=>'nullable|string|max:80',
            'notes'=>'nullable|string',
        ]);

        $data['opt_in_email'] = (bool)($request->opt_in_email);
        $data['opt_in_whatsapp'] = (bool)($request->opt_in_whatsapp);

        Contact::create($data);

        return redirect()->route('superadmin.marketing.contacts.index')->with('success','Contact added');
    }

    public function show(Contact $contact) {
        return view('admin.marketing.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->update(['status'=>'inactive']);
        return back()->with('success','Contact removed');
    }
}
