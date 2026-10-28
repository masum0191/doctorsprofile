<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\MedicineTemplate;
use App\Models\MedicineCompany;
use App\Models\Test;

class TenantDataInitializer
{
    public function run(): void
    {
        $this->insertMedicines();
        $this->insertCompanies();
        $this->insertTests();
    }

    /**
     * Read a data file from public/, falling back to the live site if it is missing.
     */
    protected function loadJson(string $file): ?array
    {
        $path = public_path($file);

        if (is_file($path)) {
            return json_decode(file_get_contents($path), true);
        }

        return Http::timeout(60)->get("https://doctorsprofile.xyz/{$file}")->json();
    }

    protected function insertMedicines(): void
    {
        $json = $this->loadJson('medicines.json');

        if (!isset($json['results'])) return;

        $batch = [];

        foreach ($json['results'] as $item) {
            $data = $item['data'] ?? [];

            $batch[] = [
                'medicine_name'        => $data['medicine_name'] ?? null,
                'company_name'         => $data['company_name'] ?? null,
                'generic_name'         => $data['generic_name'] ?? null,
                'medicine_type'        => $data['med_type'] ?? null,
                'medicine_dose'        => $data['med_dose'] ?? null,
                'medicine_day'         => $data['med_day'] ?? null,
                'medicine_mg'          => $data['med_mg'] ?? null,
                'medicine_comments'    => $data['med_comments'] ?? null,
                'medicine_description' => $data['med_description'] ?? null,
                'created_at'           => now(),
                'updated_at'           => now(),
            ];

            if (count($batch) >= 500) {
                MedicineTemplate::insertOrIgnore($batch);
                $batch = [];
            }
        }

        if ($batch) {
            MedicineTemplate::insertOrIgnore($batch);
        }
    }

    protected function insertCompanies(): void
    {
        $json = $this->loadJson('medicine_companies.json');

        if (!isset($json['companies'])) return;

        $batch = [];

        foreach ($json['companies'] as $company) {
            $batch[] = [
                'company_name' => trim($company['name']),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        MedicineCompany::insertOrIgnore($batch);
    }

    protected function insertTests(): void
    {
        $json = $this->loadJson('medical_tests.json');

        if (!isset($json['categories'])) return;

        $batch = [];

        foreach ($json['categories'] as $tests) {
            foreach ($tests as $test) {
                $batch[] = [
                    'test_name'  => trim($test['name']),
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ];

                if (count($batch) >= 500) {
                    Test::insertOrIgnore($batch);
                    $batch = [];
                }
            }
        }

        if ($batch) {
            Test::insertOrIgnore($batch);
        }
    }
}
