<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorTestimonial;
use Illuminate\Http\Request;

class DoctorTestimonialController extends Controller
{
    public function index(Request $request)
    {
        $doctor = $request->user();

        $query = DoctorTestimonial::where('user_id', $doctor->id);

        // Optional filters
        if ($request->filled('q')) {
            $query->where('patient_name', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('verified') && $request->verified !== 'all') {
            $query->where('verified', $request->verified === '1');
        }

        $testimonials = $query->orderBy('order_column')->paginate(20);

        return view('doctor.testimonials.index', compact('testimonials'));
    }

    // delete testimonial
    public function destroy(DoctorTestimonial $testimonial, Request $request)
    {
        $doctor = $request->user();

        // Ensure the testimonial belongs to the logged-in doctor
        if ($testimonial->user_id !== $doctor->id) {
            abort(403);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimonial deleted successfully.');
    }
    // status update
    public function updateStatus(DoctorTestimonial $testimonial, $status, Request $request)
    {
        $doctor = $request->user();

        // Ensure the testimonial belongs to the logged-in doctor
        // if ($testimonial->user_id !== $doctor->id) {
        //     abort(403);
        // }

        // Update the verified status based on the provided status
        if ($status === 'verify') {
            $testimonial->verified = true;
        } elseif ($status === 'unverify') {
            $testimonial->verified = false;
        } else {
            abort(400, 'Invalid status provided.');
        }

        $testimonial->save();

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimonial status updated successfully.');
    }
}
