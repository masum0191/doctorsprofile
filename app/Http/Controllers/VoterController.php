<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Voter;

class VoterController extends Controller
{
public function index()
    {
        $voters = Voter::all();
         return view('citizen-service/voter', compact('voters'));

    }
    
}
