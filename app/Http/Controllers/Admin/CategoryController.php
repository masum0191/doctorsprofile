<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('parent');

        // 🔍 Filter by parent
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // 🔍 Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->orderBy('id','desc')->paginate(15);
        $parents = Category::whereNull('parent_id')->get();

        return view('admin.categories.index', compact('categories','parents'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'

        ]);

        Category::create($request->only('name','parent_id'));

        return redirect()->route('categories.index')
            ->with('success','Category created successfully');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id','!=',$category->id)
            ->get();

        return view('admin.categories.edit', compact('category','parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update($request->only('name','parent_id'));

        return redirect()->route('categories.index')
            ->with('success','Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success','Category deleted successfully');
    }
}
