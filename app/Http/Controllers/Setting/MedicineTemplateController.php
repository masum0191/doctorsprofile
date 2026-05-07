<?php
namespace App\Http\Controllers\Setting;
use App\Http\Controllers\Controller;

use App\Models\MedicineTemplate;
use App\Models\MedicineCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

// validate
use Illuminate\Support\Facades\Validator;


class MedicineTemplateController extends Controller
{
     public function index()
    {
        $medcinCompany=MedicineCompany::latest()->get();
        $templates = MedicineTemplate::latest()->paginate(21);
        return view('medicine_templates.index', compact('templates','medcinCompany'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'medicine_type' => 'required|string|max:100',
            'medicine_dose' => 'required|string|max:100',
            'medicine_day' => 'required|string|max:100',
            'medicine_mg' => 'nullable|string|max:100',
            'medicine_url' => 'nullable|url|max:500',
            'medicine_comments' => 'nullable|string',
            'medicine_description' => 'nullable|string',
        ]);

        MedicineTemplate::create([
            'medicine_name' => $request->medicine_name,
            'generic_name' => $request->generic_name,
            'company_name' => $request->company_name,
            'medicine_type' => $request->medicine_type,
            'medicine_dose' => $request->medicine_dose,
            'medicine_day' => $request->medicine_day,
            'medicine_mg' => $request->medicine_mg,
            'medicine_url' => $request->medicine_url,
            'medicine_comments' => $request->medicine_comments,
            'medicine_description' => $request->medicine_description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medicine template added successfully'
        ]);
    }

    public function show($id)
    {
        $template = MedicineTemplate::findOrFail($id);
        return response()->json($template);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'medicine_type' => 'required|string|max:100',
            'medicine_dose' => 'required|string|max:100',
            'medicine_day' => 'required|string|max:100',
            'medicine_mg' => 'nullable|string|max:100',
            'medicine_url' => 'nullable|url|max:500',
            'medicine_comments' => 'nullable|string',
            'medicine_description' => 'nullable|string',
        ]);

        $template = MedicineTemplate::findOrFail($id);
        $template->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medicine template updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $template = MedicineTemplate::findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medicine template deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', '');

        $templates = MedicineTemplate::where(function($query) use ($searchTerm) {
            $query->where('medicine_name', 'like', "%{$searchTerm}%")
                  ->orWhere('generic_name', 'like', "%{$searchTerm}%")
                  ->orWhere('company_name', 'like', "%{$searchTerm}%");
        })->latest()->get();

        return response()->json($templates);
    }



public function jsonMedicines()
{
    $url = 'https://www.doctorsprofile.xyz/medicines.json';

    try {
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            \Log::error('Failed to fetch medicines JSON. Status: ' . $response->status());
            return response()->json([]);
        }

        $json = $response->json();

        if (!isset($json['results'])) {
            \Log::warning('No results found in medicines JSON');
            return response()->json([]);
        }

        $medicines = collect($json['results'])
            ->map(function ($item) {
                if (!isset($item['data'])) {
                    return null;
                }

                $data = $item['data'];

                return [
                    'medicine_name' => $data['medicine_name'] ?? '',
                    'med_group_id' => $data['med_group_id'] ?? '',
                    'med_type' => $data['med_type'] ?? '',
                    'med_dose' => $data['med_dose'] ?? '',
                    'med_day' => $data['med_day'] ?? '',
                    'med_mg' => $data['med_mg'] ?? '',
                    'med_comments' => $data['med_comments'] ?? '',
                    'med_description' => $data['med_description'] ?? '',
                ];
            })
            ->filter()
            ->values();

        return response()->json($medicines);

    } catch (\Exception $e) {
        \Log::error('Error fetching medicines JSON: ' . $e->getMessage());
        return response()->json([]);
    }
}

}
