<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use App\Models\User;
use App\Models\Chamber;
use App\Models\Package;
use App\Models\Subscription;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    //

    public function index(Request $request)
    {


      //  $notices = \App\Models\Notice::orderBy('published_at', 'desc')->take(5)->get();
        $setting = Setting::first();

        $doctorRelations = [
            'profile',
            'educations',
            'experiences',
            'certifications',
            'affiliations',
            'specialties',
            'services',
            'galleries',
            'testimonials',
            'faqs',
            'telemedicinePlatforms'
        ];

        $doctor = User::with($doctorRelations)
            ->where('role', 'admin')
            ->first() ?: User::with($doctorRelations)->first();

        abort_if(! $doctor, 404);

        $chambers = Chamber::where('doctor_id', $doctor->id)->get();
        $package = $this->resolveCurrentPackage($doctor);
        $packageFeatures = $package ? $package->featureMap() : config('package_features.presets.free', []);

        return view('welcome', compact('setting','doctor','chambers', 'packageFeatures'));

    }

    private function resolveCurrentPackage(User $doctor): ?Package
    {
        $tenant = function_exists('tenant') ? tenant() : null;
        $tenantId = data_get($tenant, 'id') ?: (function_exists('tenant') ? tenant('id') : null);

        if ($tenantId && Schema::connection('mysql')->hasTable('subscriptions')) {
            $subscription = Subscription::on('mysql')
                ->where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('ends_at')->orWhere('ends_at', '>', now());
                })
                ->with('package')
                ->latest()
                ->first();

            if ($subscription?->package) {
                return $subscription->package;
            }
        }

        $packageId = data_get($tenant, 'package_id')
            ?: data_get($tenant, 'data.package_id')
            ?: $this->tenantDataValue($tenant, 'package_id')
            ?: data_get($doctor, 'package_id')
            ?: data_get($doctor, 'package');

        return $packageId ? Package::on('mysql')->find($packageId) : null;
    }

    private function tenantDataValue($tenant, string $key)
    {
        $data = data_get($tenant, 'data');

        if (is_string($data)) {
            $data = json_decode($data, true) ?: [];
        }

        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return method_exists($tenant, 'getInternal') ? $tenant->getInternal($key) : null;
    }
}
