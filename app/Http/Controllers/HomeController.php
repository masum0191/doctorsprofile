<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use App\Models\User;
use App\Models\Chamber;
use App\Models\Package;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(Request $request)
    {


      //  $notices = \App\Models\Notice::orderBy('published_at', 'desc')->take(5)->get();
        $setting = Setting::first();

          $doctor = User::with([
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
        ])->first();
       // dd($doctor);
        $chambers=Chamber::where('doctor_id',$doctor->id)->get();
        $tenant = function_exists('tenant') ? tenant() : null;
        $packageId = data_get($tenant, 'package_id') ?: data_get($tenant, 'data.package_id');
        $package = $packageId ? Package::on('mysql')->find($packageId) : null;
        $packageFeatures = $package ? $package->featureMap() : config('package_features.presets.free', []);

        return view('welcome', compact('setting','doctor','chambers', 'packageFeatures'));

    }
}

