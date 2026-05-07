<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use App\Models\User;
use App\Models\Chamber;

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
        return view('welcome', compact('setting','doctor','chambers'));

    }
}


