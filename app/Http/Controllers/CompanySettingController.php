<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CompanySettingController extends Controller
{
    public function edit()
    {
        // Always keep single row
        $setting = CompanySetting::first() ?? new CompanySetting();
        return view('settings.company', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'      => 'nullable|string|max:119',
            'email'             => 'nullable|email|max:119',
            'logo'              => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon'           => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
            'ogimage'           => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $setting = CompanySetting::first() ?? new CompanySetting();

        $data = $request->except(['logo', 'favicon', 'ogimage']);

        // Logo upload
         if ($request->hasFile('logo')) {
        if ($setting->logo && file_exists(public_path($setting->logo))) {
            unlink(public_path($setting->logo));
        }

        $logoName = 'logo_' . Str::random(10) . '.' .
                    $request->logo->getClientOriginalExtension();

        $request->logo->move(public_path('uploads/settings'), $logoName);

        $data['logo'] = 'uploads/settings/' . $logoName;
    }

    // FAVICON
    if ($request->hasFile('favicon')) {
        if ($setting->favicon && file_exists(public_path($setting->favicon))) {
            unlink(public_path($setting->favicon));
        }

        $faviconName = 'favicon_' . Str::random(10) . '.' .
                       $request->favicon->getClientOriginalExtension();

        $request->favicon->move(public_path('uploads/settings'), $faviconName);

        $data['favicon'] = 'uploads/settings/' . $faviconName;
    }

    // OG IMAGE
    if ($request->hasFile('ogimage')) {
        if ($setting->ogimage && file_exists(public_path($setting->ogimage))) {
            unlink(public_path($setting->ogimage));
        }

        $ogName = 'og_' . Str::random(10) . '.' .
                  $request->ogimage->getClientOriginalExtension();

        $request->ogimage->move(public_path('uploads/settings'), $ogName);

        $data['ogimage'] = 'uploads/settings/' . $ogName;
    }

        $setting->fill($data)->save();

        return back()->with('success', 'Company settings updated successfully');
    }
}
