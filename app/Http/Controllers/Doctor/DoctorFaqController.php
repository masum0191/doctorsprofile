<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorFaq;
use Illuminate\Http\Request;

class DoctorFaqController extends Controller
{
    public function index(Request $request)
    {
        $doctor = $request->user();

        $query = DoctorFaq::where('user_id', $doctor->id);

        // Optional search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('question', 'like', "%{$q}%")
                    ->orWhere('answer', 'like', "%{$q}%");
            });
        }

        $faqs = $query->orderBy('order_column')->paginate(20);

        return view('doctor.faqs.index', compact('faqs'));
    }

    public function destroy(Request $request, DoctorFaq $faq)
    {
        // Ensure FAQ belongs to logged-in doctor
        if ($faq->user_id !== $request->user()->id) {
            abort(403);
        }

        $faq->delete();

        return back()->with('ok', 'FAQ deleted successfully.');
    }
}
