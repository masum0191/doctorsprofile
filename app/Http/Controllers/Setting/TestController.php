<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $query = Test::query();

        if ($request->filled('search')) {
            $query->where('test_name', 'like', '%'.$request->search.'%');
        }

        $tests = $query->orderBy('id','desc')->paginate(15);

        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        return view('tests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string|max:255|unique:tests,test_name',
        ]);

        Test::create($request->only('test_name'));

        return redirect()
            ->route('admin.tests.index')
            ->with('success','Test added successfully');
    }

    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'test_name' => 'required|string|max:255|unique:tests,test_name,'.$test->id,
        ]);

        $test->update($request->only('test_name'));

        return redirect()
            ->route('admin.tests.index')
            ->with('success','Test updated successfully');
    }

    public function destroy(Test $test)
    {
        $test->delete();

        return back()->with('success','Test deleted successfully');
    }
}
