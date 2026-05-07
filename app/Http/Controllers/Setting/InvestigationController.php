<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Investigation;
use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index(Request $request)
    {
        $query = Investigation::query();

        if ($request->filled('search')) {
            $query->where('investigation_name', 'like', '%'.$request->search.'%');
        }

        $investigations = $query->orderBy('id','desc')->paginate(15);

        return view('investigations.index', compact('investigations'));
    }

    public function create()
    {
        return view('investigations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'investigation_name' => 'required|string|max:255'
        ]);

        Investigation::create($request->only('investigation_name'));

        return redirect()
            ->route('admin.investigations.index')
            ->with('success','Investigation added successfully');
    }

    public function edit(Investigation $investigation)
    {
        return view('investigations.edit', compact('investigation'));
    }

    public function update(Request $request, Investigation $investigation)
    {
        $request->validate([
            'investigation_name' => 'required|string|max:255'
        ]);

        $investigation->update($request->only('investigation_name'));

        return redirect()
            ->route('admin.investigations.index')
            ->with('success','Investigation updated successfully');
    }

    public function destroy(Investigation $investigation)
    {
        $investigation->delete();
        return back()->with('success','Investigation deleted successfully');
    }
}
