<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\FollowUpTemplate;
use Illuminate\Http\Request;

class FollowUpTemplateController extends Controller
{
    public function index()
    {
        $templates = FollowUpTemplate::latest()->get();
        return view('follow_up_templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:follow_up_templates,name',
            'details' => 'nullable|string',
        ]);

        FollowUpTemplate::create($request->only('name','details'));

        return back()->with('success', 'Follow-up template added successfully');
    }

    public function update(Request $request, FollowUpTemplate $followUpTemplate)
    {
        $request->validate([
            'name' =>
                'required|string|max:255|unique:follow_up_templates,name,' . $followUpTemplate->id,
            'details' => 'nullable|string',
        ]);

        $followUpTemplate->update($request->only('name','details'));

        return back()->with('success', 'Follow-up template updated successfully');
    }

    public function destroy(FollowUpTemplate $followUpTemplate)
    {
        $followUpTemplate->delete();

        return back()->with('success', 'Follow-up template deleted successfully');
    }
}
