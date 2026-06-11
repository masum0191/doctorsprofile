<?php

namespace Tests\Unit;

use App\Models\Package;
use Tests\TestCase;

class PackageTest extends TestCase
{
    public function test_preset_key_is_detected_from_slug_or_name(): void
    {
        $this->assertSame('premium', (new Package(['slug' => 'premium']))->presetKey());
        $this->assertSame('standard', (new Package(['name' => 'Standard Plan']))->presetKey());
        $this->assertSame('free', (new Package(['slug' => 'free-starter']))->presetKey());
        $this->assertSame('standard', (new Package(['slug' => 'custom']))->presetKey());
    }

    public function test_preset_package_uses_configured_preset_features(): void
    {
        $package = new Package([
            'slug' => 'free',
            'features' => ['custom_domain' => true],
        ]);

        $this->assertTrue($package->hasFeature('doctor'));
        $this->assertTrue($package->hasFeature('subdomain'));
        $this->assertFalse($package->hasFeature('custom_domain'));
        $this->assertFalse($package->hasFeature('managed_seo'));
    }

    public function test_custom_package_features_can_override_default_preset(): void
    {
        $package = new Package([
            'slug' => 'clinic-growth',
            'features' => [
                'custom_domain' => false,
                'managed_seo' => true,
            ],
        ]);

        $this->assertFalse($package->hasFeature('custom_domain'));
        $this->assertTrue($package->hasFeature('managed_seo'));
        $this->assertFalse($package->hasFeature('unknown_feature'));
    }

    public function test_feature_map_contains_catalog_keys_even_when_no_features_are_set(): void
    {
        $package = new Package(['slug' => 'standard']);

        $featureMap = $package->featureMap();

        $this->assertArrayHasKey('doctor', $featureMap);
        $this->assertArrayHasKey('online_payments', $featureMap);
        $this->assertArrayHasKey('managed_seo', $featureMap);
    }
}
