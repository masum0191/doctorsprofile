<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PackageUpgradeService;

class UpgradeController extends Controller
{
    public function index(PackageUpgradeService $upgrades)
    {
        $options = $upgrades->options((string) tenant('id'));
        $packages = $options['packages'];
        $subscription = $options['subscription'];
        $isActive = $options['is_active'];
        $currentPackageId = $options['current_package_id'];
        $hasUsedFree = $options['has_used_free'];

        return view('doctor.packages.index', compact(
            'packages',
            'subscription',
            'isActive',
            'currentPackageId',
            'hasUsedFree'
        ));
    }

    public function process(Request $request, PackageUpgradeService $upgrades)
    {
        $request->validate([
            'package_id' => 'required',
            'billing_cycle' => 'required|in:monthly,yearly',
            'payment_method' => 'nullable|in:stripe,sslcommerz,bank_transfer,offline',
        ]);

        $result = $upgrades->requestUpgrade(
            $request->user(),
            (string) tenant('id'),
            (int) $request->package_id,
            (string) $request->billing_cycle,
            $request->input('payment_method'),
            false
        );

        if (! empty($result['payment_url'])) {
            return redirect()->away($result['payment_url']);
        }

        return back()
            ->with('success', 'Package upgrade request submitted. New package features will apply after superadmin approval.');
    }
}
