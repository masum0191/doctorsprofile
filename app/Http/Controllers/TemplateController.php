<?php
namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Tenant;
use App\Models\Setting;
use Illuminate\Support\Facades\Tenancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    // READ - list all
    public function index()
    {
        $templates = Template::all();
        //dd($templates);
        return view('templates.index', compact('templates'));
    }

    // CREATE - show form
    public function create()
    {
        $tenantId=auth()->user()->tenant_id;
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return redirect()->back()->with('error', 'You have no account found.');
        }

        tenancy()->initialize($tenant);

        $settings = \App\Models\Setting::query()->first();
        $templateValue=$settings->template;
       // dd($templateValue);
        tenancy()->end();
        $templates=Template::paginate(15);
        return view('templates.create',compact('templateValue','templates'));
    }
// activateTemplate
public function activateTemplate(Request $request)
{
   // dd($request->template_value);

        $tenantId=auth()->user()->tenant_id;
        $tenant = Tenant::find($tenantId);
        tenancy()->initialize($tenant);

        $settings = \App\Models\Setting::query()->first();
        $settings->template=$request->template_value;
        $settings->save();
        tenancy()->end();
    return back()->with('success', 'Template activated successfully!');
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'value' => 'required|string|unique:templates,value',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
        'preview_url' => 'nullable|url|max:2048',
        'status' => 'boolean'
    ]);

    $data = $request->only(['title', 'value', 'status']);
    $data['url'] = $request->input('preview_url');

    // Handle image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $folder = 'uploads/templates';

        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($folder), $filename);

        $data['image'] = $folder . '/' . $filename;
    }

    // Use Eloquent instead of DB facade
    Template::create($data);

    return redirect()
        ->route('superadmin.templates.index')
        ->with('success', 'Template created successfully!');
}


    // EDIT - show edit form
    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }


public function update(Request $request, Template $template)
{
    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'value' => 'nullable|string|max:255|unique:templates,value,' . $template->id,
        'preview_url' => 'nullable|url|max:2048',
        'new_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
        'status' => 'nullable|boolean',
    ]);

    $data = [];

    if ($request->filled('title')) {
        $data['title'] = $validated['title'];
    }

    if ($request->filled('value')) {
        $data['value'] = $validated['value'];
    }

    if ($request->has('status')) {
        $data['status'] = (bool) $request->input('status');
    }

    if ($request->filled('preview_url')) {
        $data['url'] = $validated['preview_url'];
    }

    if ($request->hasFile('new_image')) {
        $file = $request->file('new_image');
        $folder = 'uploads/templates';

        if ($template->image) {
            $oldPath = public_path($template->image);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($folder), $filename);

        $data['image'] = $folder . '/' . $filename;
    }

    if (!empty($data)) {
        $template->update($data);
    }

    return back()->with('success', 'Template updated successfully!');
}


}
