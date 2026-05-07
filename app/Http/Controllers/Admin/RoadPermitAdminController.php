<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoadExcavationPermit;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class RoadPermitAdminController extends Controller
{
   public function index(Request $request)
{
    $query = RoadExcavationPermit::query();

    // Admin user
    if (auth()->user()->role === 'admin') {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nid', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('road_location', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
    } else {
        // Regular user can only see their own permits
        $query->where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nid', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('road_location', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
    }

    $permits = $query->latest()->paginate(10);

    return view('admin.road_permits.index', compact('permits'));
}


    public function show($id)
    {
        $permit = RoadExcavationPermit::findOrFail($id);
        return view('admin.road_permits.show', compact('permit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $permit = RoadExcavationPermit::findOrFail($id);
        $permit->status = $request->status;
        $permit->save();

        return redirect()->back()->with('success', 'স্ট্যাটাস সফলভাবে আপডেট হয়েছে।');
    }
    public function getcertificate($id)
    {
        $permit = RoadExcavationPermit::findOrFail($id);
        $setting = Setting::first();

        if (!$permit->where('status', 'approved')) {
            return redirect()->back()->with('success', 'এই আবেদনটি এখনো ভেরিফাই করা হয়নি।');
        }

        return view('admin.road_permits.certificate', compact('permit', 'setting'));
    }
}
