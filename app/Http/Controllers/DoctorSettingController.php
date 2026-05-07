<?php
namespace  App\Http\Controllers;
use Illuminate\Http\Request;
class DoctorSettingController extends Controller
{
    public function categories()
    {
        return view('doctor.setting.categories');
    }
}
