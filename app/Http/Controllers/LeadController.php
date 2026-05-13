<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()->get();
        $leadStats = [
            'total' => $leads->count(),
            'new' => $leads->where('status', 'new')->count(),
            'contacted' => $leads->where('status', 'contacted')->count(),
            'converted' => $leads->where('status', 'converted')->count(),
        ];

        return view('leads.index', compact('leads', 'leadStats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        Lead::create($request->only([
            'name','phone','email','source','status','notes'
        ]));

        return back()->with('success', 'Lead created successfully');
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $lead->update($request->only([
            'name','phone','email','source','status','notes'
        ]));

        return back()->with('success', 'Lead updated successfully');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return back()->with('success', 'Lead deleted successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return back()->withErrors(['csv_file' => 'Unable to read the uploaded CSV file.']);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'The CSV file is empty.']);
        }

        $normalizedHeader = collect($header)
            ->map(fn ($column) => strtolower(trim((string) $column)))
            ->all();

        $supportedColumns = ['name', 'phone', 'email', 'source', 'status', 'notes'];
        if (!in_array('name', $normalizedHeader, true)) {
            fclose($handle);
            return back()->withErrors(['csv_file' => 'The CSV must include a name column.']);
        }

        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $mapped = [];

            foreach ($normalizedHeader as $index => $column) {
                if (!in_array($column, $supportedColumns, true)) {
                    continue;
                }

                $mapped[$column] = isset($row[$index]) ? trim((string) $row[$index]) : null;
            }

            $name = Arr::get($mapped, 'name');
            $email = Arr::get($mapped, 'email');
            $status = Arr::get($mapped, 'status') ?: 'new';

            if (!$name || ($email && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
                $skipped++;
                continue;
            }

            if (!in_array($status, ['new', 'contacted', 'converted'], true)) {
                $status = 'new';
            }

            Lead::create([
                'name' => $name,
                'phone' => Arr::get($mapped, 'phone') ?: null,
                'email' => $email ?: null,
                'source' => Arr::get($mapped, 'source') ?: 'CSV Import',
                'status' => $status,
                'notes' => Arr::get($mapped, 'notes') ?: null,
            ]);

            $created++;
        }

        fclose($handle);

        return back()->with(
            'success',
            "CSV import completed. {$created} lead(s) created, {$skipped} row(s) skipped."
        );
    }
}
