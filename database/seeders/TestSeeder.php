<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use Illuminate\Support\Facades\Http;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $url = 'https://doctorsprofile.xyz/medical_tests.json';

        $response = Http::timeout(60)->get($url);

        if (!$response->successful()) {
            $this->command->error('Failed to fetch tests JSON');
            return;
        }

        $json = $response->json();

        if (!isset($json['categories']) || !is_array($json['categories'])) {
            $this->command->error('Invalid JSON structure');
            return;
        }

        $batch = [];

        foreach ($json['categories'] as $category => $tests) {
            foreach ($tests as $test) {
                if (empty($test['name'])) {
                    continue;
                }

                $batch[] = [
                    'test_name'  => trim($test['name']),
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ];

                // Insert in chunks (memory safe)
                if (count($batch) >= 500) {
                    Test::insertOrIgnore($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            Test::insertOrIgnore($batch);
        }

        $this->command->info('Tests imported successfully.');
    }
}
