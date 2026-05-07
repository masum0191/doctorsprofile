<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;


use App\Models\Comorbidity;
use Illuminate\Http\Request;

class ComorbidityController extends Controller
{
    public function index()
    {
        $comorbidities = Comorbidity::latest()->get();
        return view('comorbidities.index', compact('comorbidities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comorbidity_name' => 'required|string|max:255|unique:comorbidities,comorbidity_name',
        ]);

        Comorbidity::create($request->only('comorbidity_name'));

        return back()->with('success', 'Comorbidity added successfully');
    }

    public function update(Request $request, Comorbidity $comorbidity)
    {
        $request->validate([
            'comorbidity_name' =>
                'required|string|max:255|unique:comorbidities,comorbidity_name,' . $comorbidity->id,
        ]);

        $comorbidity->update($request->only('comorbidity_name'));

        return back()->with('success', 'Comorbidity updated successfully');
    }

    public function destroy(Comorbidity $comorbidity)
    {
        $comorbidity->delete();

        return back()->with('success', 'Comorbidity deleted successfully');
    }
}
