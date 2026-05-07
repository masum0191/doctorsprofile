<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\PlanTemplate;
use Illuminate\Http\Request;

class PlanTemplateController extends Controller
{
    public function index()
    {
        $plans = PlanTemplate::latest()->get();

        return view('plan_templates.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_name' => 'required|string|max:255|unique:plan_templates,plan_name',
            'plan_details' => 'nullable|string',
        ]);

        PlanTemplate::create($request->only('plan_name', 'plan_details'));

        return back()->with('success', 'Plan template added successfully');
    }

    public function update(Request $request, PlanTemplate $planTemplate)
    {
        $request->validate([
            'plan_name' =>
                'required|string|max:255|unique:plan_templates,plan_name,' . $planTemplate->id,
            'plan_details' => 'nullable|string',
        ]);

        $planTemplate->update($request->only('plan_name', 'plan_details'));

        return back()->with('success', 'Plan template updated successfully');
    }

    public function destroy(PlanTemplate $planTemplate)
    {
        $planTemplate->delete();

        return back()->with('success', 'Plan template deleted successfully');
    }
}
