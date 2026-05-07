<?php
namespace App\Http\Controllers;



use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminController extends Controller
{
public function dashboard()
{
    //return 1;
    $doctor = auth()->user(); // or however you get the doctor
    $centralUser = \App\Models\User::on('mysql')
        ->where('email',$doctor->email)
        ->first();

    if (!$centralUser) return null;


        $subscription = \App\Models\Subscription::where('tenant_id', tenant('id'))
    ->where('status','active')
    ->with('package')
    ->first();
       // dd($subscription);
    $chambers = Chamber::where('doctor_id', $doctor->id)->get();

    $appointments = Appointment::where('doctor_id', $doctor->id)
        ->orderByDesc('appointment_date')
        ->orderByDesc('appointment_time')
        ->limit(20)
        ->get();

    // Example recent patients
    $recentPatients = User::whereHas('patientAppointments', function ($q) use ($doctor) {
        //  $q->where('doctor_id','=!' ,$doctor->id);

        })
        ->with(['patientAppointments' => function ($q) use ($doctor) {
            $q->where('doctor_id', '=!',$doctor->id)
              ->orderByDesc('appointment_date')
              ->orderByDesc('appointment_time');
        }])
        ->limit(5)
        ->get();

    // stats
    $today = now();
    $upcoming = $appointments->where('appointment_date', '>=', $today->toDateString())->count();
    $pending  = $appointments->where('status', 'pending')->count();
    $patients = $recentPatients->count();
    $revenue  = $appointments->where('status', 'completed')->sum('amount');

    // calendar cells are whatever you already built
    $calendar = collect([]); // replace with your real calendar logic

    return view('dashboard', compact(
        'doctor',
        'chambers',
        'appointments',
        'recentPatients',
        'today',
        'calendar',
        'upcoming',
        'pending',
        'patients',
        'revenue',
        'subscription'
    ));
}

}
