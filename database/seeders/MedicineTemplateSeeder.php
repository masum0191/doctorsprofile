<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineTemplate;
use Illuminate\Support\Facades\Http;

class MedicineTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $url = 'https://doctorsprofile.xyz/medicines.json';

        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            $this->command->error('Failed to fetch JSON file.');
            return;
        }

        $json = $response->json();

        if (!isset($json['results'])) {
            $this->command->error('Invalid JSON structure!');
            return;
        }

        foreach ($json['results'] as $item) {
            $data = $item['data'];

            MedicineTemplate::updateOrCreate(
                [
                    'medicine_name' => $data['medicine_name'] ?? null
                ],
                [
                    'company_name'         => null,
                    'generic_name'         => null,
                    'medicine_type'        => $data['med_type'] ?? null,
                    'medicine_dose'        => $data['med_dose'] ?? null,
                    'medicine_day'         => $data['med_day'] ?? null,
                    'medicine_mg'          => $data['med_mg'] ?? null,
                    'medicine_comments'    => $data['med_comments'] ?? null,
                    'medicine_description' => $data['med_description'] ?? null,
                    'medicine_url'         => $url,
                ]
            );
        }

        $this->command->info('Medicine templates imported successfully.');
    }
}
