<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FixedLocationSearchUiTest extends TestCase
{
    public function test_home_search_opens_changeable_location_popup_from_map_icon(): void
    {
        $html = view('sass', ['specialties' => collect()])->render();

        $this->assertStringContainsString('data-location-fixed="false"', $html);
        $this->assertStringContainsString('role="button"', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
        $this->assertStringContainsString('id="locationMapButton"', $html);
        $this->assertStringContainsString('Detecting location...', $html);
        $this->assertStringContainsString('id="countrySelect"', $html);
        $this->assertStringContainsString('id="citySelect"', $html);
        $this->assertStringContainsString('id="map"', $html);
        $this->assertStringContainsString('Select Your Location', $html);
        $this->assertStringNotContainsString('id="near-me"', $html);
        $this->assertStringNotContainsString('ri-lock-line', $html);
    }

    public function test_find_doctors_search_opens_changeable_location_popup_from_map_icon(): void
    {
        $html = view('finds')->render();

        $this->assertStringContainsString('data-location-fixed="false"', $html);
        $this->assertStringContainsString('role="button"', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
        $this->assertStringContainsString('id="locationMapButton"', $html);
        $this->assertStringContainsString('Detecting location...', $html);
        $this->assertStringContainsString('id="countrySelect"', $html);
        $this->assertStringContainsString('id="citySelect"', $html);
        $this->assertStringContainsString('id="map"', $html);
        $this->assertStringContainsString('Select Your Location', $html);
        $this->assertStringNotContainsString('id="near-me"', $html);
        $this->assertStringNotContainsString('ri-lock-line', $html);
    }

    public function test_location_script_uses_current_location_and_map_icon_popup(): void
    {
        $script = file_get_contents(resource_path('views/layouts/sass.blade.php'));

        $this->assertMatchesRegularExpression("/let currentLocation = \\{\\R\\s+lat: null,\\R\\s+lng: null,\\R\\s+name: ''/", $script);
        $this->assertStringContainsString('runWhenDomReady', $script);
        $this->assertStringContainsString('function openLocationModal()', $script);
        $this->assertStringContainsString('typeof L === \'undefined\'', $script);
        $this->assertStringContainsString('const locationMapButton = document.getElementById(\'locationMapButton\');', $script);
        $this->assertStringContainsString('locationMapButton?.addEventListener(\'click\', handleLocationPickerOpen);', $script);
        $this->assertStringContainsString('locationSelectorEl?.addEventListener(\'click\', handleLocationPickerOpen);', $script);
        $this->assertStringContainsString('params.set(\'country\', country);', $script);
    }

    public function test_forward_geocode_uses_selected_country_instead_of_forcing_bangladesh(): void
    {
        Http::fake([
            'https://nominatim.openstreetmap.org/search*' => Http::response([
                [
                    'lat' => '51.5072',
                    'lon' => '-0.1276',
                    'display_name' => 'London, Greater London, England, United Kingdom',
                ],
            ]),
        ]);

        $this->getJson('/geo/forward?city=London&country=United%20Kingdom')
            ->assertOk()
            ->assertJson([
                'lat' => '51.5072',
                'lng' => '-0.1276',
            ]);

        Http::assertSent(function ($request) {
            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

            return str_contains($request->url(), 'nominatim.openstreetmap.org/search')
                && ($query['q'] ?? null) === 'London, United Kingdom';
        });
    }
}
