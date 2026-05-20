<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['Profile Template 1', 'one', 'Classic doctor profile with clear appointment flow.'],
            ['Profile Template 2', 'two', 'Modern blue profile layout for a polished practice page.'],
            ['Profile Template 3', 'three', 'Fresh teal layout with strong service and booking sections.'],
            ['Profile Template 4', 'four', 'Calm clinical profile with balanced content blocks.'],
            ['Profile Template 5', 'five', 'Editorial specialist profile with warm patient guidance.'],
            ['Profile Template 6', 'six', 'Compact consultation layout for fast appointment discovery.'],
            ['Profile Template 7', 'seven', 'Trust-focused profile with credentials and service highlights.'],
            ['Profile Template 8', 'eight', 'Bright care layout with approachable section rhythm.'],
            ['Profile Template 9', 'nine', 'Structured practice page for scanning schedules and expertise.'],
            ['Profile Template 10', 'ten', 'Premium profile presentation with confident hero messaging.'],
            ['Profile Template 11', 'eleven', 'Minimal specialist page with crisp booking emphasis.'],
            ['Profile Template 12', 'twelve', 'Soft healthcare layout for a personal patient experience.'],
            ['Profile Template 13', 'thirteen', 'Detailed medical profile with prominent chamber information.'],
            ['Profile Template 14', 'fourteen', 'Contemporary practice page for services and testimonials.'],
            ['Profile Template 15', 'fifteen', 'Refined appointment-first template for established doctors.'],
        ];

        foreach ($templates as $index => [$title, $value, $description]) {
            $number = $index + 1;

            Template::updateOrCreate(
                ['value' => $value],
                [
                    'title' => $title,
                    'image' => "preview/shared/template-preview.svg",
                    'url' => url("/preview/profile-{$number}/index.html"),
                    'status' => true,
                ]
            );
        }
    }
}
