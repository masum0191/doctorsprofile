<?php

namespace Tests\Feature;

use Tests\TestCase;

class FixedLocationSearchUiTest extends TestCase
{
    public function test_home_search_uses_fixed_location_ui(): void
    {
        $html = view('sass', ['specialties' => collect()])->render();

        $this->assertStringContainsString('data-location-fixed="true"', $html);
        $this->assertStringContainsString('Detecting location...', $html);
        $this->assertStringContainsString('ri-lock-line', $html);
        $this->assertStringNotContainsString('id="near-me"', $html);
        $this->assertStringNotContainsString('id="countrySelect"', $html);
        $this->assertStringNotContainsString('id="citySelect"', $html);
        $this->assertStringNotContainsString('id="map"', $html);
        $this->assertStringNotContainsString('Select Your Location', $html);
    }

    public function test_find_doctors_search_uses_fixed_location_ui(): void
    {
        $html = view('finds')->render();

        $this->assertStringContainsString('data-location-fixed="true"', $html);
        $this->assertStringContainsString('Detecting location...', $html);
        $this->assertStringContainsString('ri-lock-line', $html);
        $this->assertStringNotContainsString('id="near-me"', $html);
        $this->assertStringNotContainsString('id="countrySelect"', $html);
        $this->assertStringNotContainsString('id="citySelect"', $html);
        $this->assertStringNotContainsString('id="map"', $html);
        $this->assertStringNotContainsString('Select Your Location', $html);
    }
}
