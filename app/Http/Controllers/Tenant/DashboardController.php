<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'upcoming' => 0,
            'pending' => 0,
            'patients' => 0,
            'revenue' => 0,
            'appointments' => collect(),
            'recentPatients' => collect(),
            'doctor' => null,
            'chambers' => collect(),
        ]);
    }
}
