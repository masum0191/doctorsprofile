<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use Illuminate\Http\Request;

class SegmentController extends Controller
{
    public function index() {
        $segments = Segment::orderByDesc('id')->paginate(20);
        return view('admin.marketing.segments.index', compact('segments'));
    }

    public function create() {
        return view('admin.marketing.segments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:120',
            'rules'=>'required|string', // JSON text
        ]);

        $rules = json_decode($data['rules'], true);
        if (!is_array($rules)) return back()->withErrors(['rules'=>'Invalid JSON rules'])->withInput();

        Segment::create([
            'name' => $data['name'],
            'rules' => $rules
        ]);

        return redirect()->route('superadmin.marketing.segments.index')->with('success','Segment created');
    }
    // update 
    public function edit($id) {
        $segment = Segment::findOrFail($id);
        return view('admin.marketing.segments.edit', compact('segment'));
    }
    public function update(Request $request, $id)
    {
        $segment = Segment::findOrFail($id);

        $data = $request->validate([
            'name'=>'required|string|max:120',
            'rules'=>'required|string', // JSON text
        ]);

        $rules = json_decode($data['rules'], true);
        if (!is_array($rules)) return back()->withErrors(['rules'=>'Invalid JSON rules'])->withInput();

        $segment->update([
            'name' => $data['name'],
            'rules' => $rules
        ]);

        return redirect()->route('superadmin.marketing.segments.index')->with('success','Segment updated');
    }
}
