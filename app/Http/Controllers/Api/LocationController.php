<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CityCorporation;
use App\Models\District;
use App\Models\Division;
use App\Models\Pourosova;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Get all divisions
     */
    public function divisions()
    {
        return response()->json(Division::all());
    }

    /**
     * Get districts by division ID
     */
    public function districts($division_id)
    {
        return response()->json(District::where('division_id', $division_id)->get());
    }

    /**
     * Get upazilas by district ID
     * Note: Your table might use 'zilla_id'. Adjust if necessary.
     */
    public function upazilas($district_id)
    {
        return response()->json(Upazila::where('district_id', $district_id)->get());
    }
    
    /**
     * Get unions by upazila ID
     */
    public function unions($upazila_id)
    {
        return response()->json(Union::where('upazilla_id', $upazila_id)->get());
    }

    /**
     * Get pourasovas by district ID
     */
    public function pourasovas($district_id)
    {
        return response()->json(Pourosova::where('district_id', $district_id)->get());
    }

    /**
     * Get city corporations by district ID
     * Note: Linking by district is more direct based on our schema.
     */
    public function cityCorporations($district_id)
    {
        return response()->json(CityCorporation::where('district_id', $district_id)->get());
    }
}