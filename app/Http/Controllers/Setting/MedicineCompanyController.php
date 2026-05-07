<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\MedicineCompany;
use Illuminate\Http\Request;

class MedicineCompanyController extends Controller
{
        public function index(Request $request)
        {
            $query = MedicineCompany::query();

            if ($request->filled('search')) {
                $query->where('company_name', 'like', '%' . $request->search . '%');
            }

            $companies = $query->latest()->paginate(15)->withQueryString();

            return view('medicine_companies.index', compact('companies'));
        }

        public function create(){
            return view('medicine_companies.create');
        }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255|unique:medicine_companies,company_name',
        ]);

        MedicineCompany::create($request->only('company_name'));

        return back()->with('success', 'Medicine company added successfully');
    }
    public function edit(MedicineCompany $medicineCompany)
    {
        return view('medicine_companies.edit', compact('medicineCompany'));
    }

    public function update(Request $request, MedicineCompany $medicineCompany)
    {
        $request->validate([
            'company_name' => 'required|string|max:255|unique:medicine_companies,company_name,' . $medicineCompany->id,
        ]);

        $medicineCompany->update($request->only('company_name'));

        return back()->with('success', 'Medicine company updated successfully');
    }

    public function destroy(MedicineCompany $medicineCompany)
    {
        $medicineCompany->delete();

        return back()->with('success', 'Medicine company deleted successfully');
    }
}
