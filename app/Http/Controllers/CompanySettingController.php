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

    public function paymentEdit()
    {
        $setting = CompanySetting::first() ?? new CompanySetting();
        $payment = $setting->payment_gateway ?? [];

        return view('settings.payment', compact('setting', 'payment'));
    }

    public function paymentUpdate(Request $request)
    {
        $validated = $request->validate([
            'sslcommerz_enabled' => ['nullable', 'boolean'],
            'sslcommerz_store_id' => ['nullable', 'string', 'max:255'],
            'sslcommerz_store_password' => ['nullable', 'string', 'max:255'],
            'sslcommerz_test_mode' => ['nullable', 'boolean'],
            'stripe_enabled' => ['nullable', 'boolean'],
            'stripe_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret' => ['nullable', 'string', 'max:255'],
            'country_gateways' => ['nullable', 'string'],
        ]);

        $countryGateways = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) ($validated['country_gateways'] ?? '')) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, '=')) {
                continue;
            }

            [$country, $gateway] = array_map('trim', explode('=', $line, 2));
            if ($country !== '' && $gateway !== '') {
                $countryGateways[$country] = $gateway;
            }
        }

        $setting = CompanySetting::first() ?? new CompanySetting();
        $setting->payment_gateway = [
            'sslcommerz' => [
                'enabled' => $request->boolean('sslcommerz_enabled'),
                'store_id' => $validated['sslcommerz_store_id'] ?? null,
                'store_password' => $validated['sslcommerz_store_password'] ?? null,
                'secret' => $validated['sslcommerz_store_password'] ?? null,
                'test_mode' => $request->boolean('sslcommerz_test_mode'),
            ],
            'stripe' => [
                'enabled' => $request->boolean('stripe_enabled'),
                'key' => $validated['stripe_key'] ?? null,
                'secret' => $validated['stripe_secret'] ?? null,
            ],
            'country_gateways' => $countryGateways,
        ];
        $setting->save();

        return back()->with('success', 'Payment gateway settings updated successfully');
    }
}
