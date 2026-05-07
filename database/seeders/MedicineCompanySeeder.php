<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCompany;
use Illuminate\Support\Facades\Http;

class MedicineCompanySeeder extends Seeder
{
    public function run(): void
    {
        $url = 'https://doctorsprofile.xyz/medicine_companies.json';

        $response = Http::timeout(60)->get($url);

        if (!$response->successful()) {
            $this->command->error('Failed to fetch company JSON');
            return;
        }

        $json = $response->json();

        if (!isset($json['companies']) || !is_array($json['companies'])) {
            $this->command->error('Invalid JSON structure');
            return;
        }

        $batch = [];

        foreach ($json['companies'] as $company) {
            if (empty($company['name'])) {
                continue;
            }

            $batch[] = [
                'company_name' => trim($company['name']),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            // Insert in chunks to save memory
            if (count($batch) >= 500) {
                MedicineCompany::insertOrIgnore($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            MedicineCompany::insertOrIgnore($batch);
        }

        $this->command->info('Medicine companies imported successfully.');
    }
}
