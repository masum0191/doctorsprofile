<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
     protected $connection = 'mysql'; // central DB

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'storage_gb',
        'max_doctors',
        'max_patients',
        'features',
        'is_visible',
        'sort_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'max_doctors' => 'integer',
        'max_patients' => 'integer',
        'features' => 'array',
    ];

    public function hasFeature($feature)
    {
        return (bool) ($this->featureMap()[$feature] ?? false);
    }

    public function featureMap(): array
    {
        $catalogDefaults = array_fill_keys(array_keys(config('package_features.catalog', [])), false);
        $preset = config('package_features.presets.' . $this->presetKey(), []);
        $features = is_array($this->features) ? $this->features : [];

        if ($this->isPresetPackage()) {
            return array_merge($catalogDefaults, $features, $preset);
        }

        return array_merge($catalogDefaults, $preset, $features);
    }

    public function presetKey(): string
    {
        $key = strtolower((string) ($this->slug ?: $this->name));

        if (str_contains($key, 'premium')) {
            return 'premium';
        }

        if (str_contains($key, 'standard')) {
            return 'standard';
        }

        if (str_contains($key, 'free')) {
            return 'free';
        }

        return 'standard';
    }

    public function isPresetPackage(): bool
    {
        $key = strtolower((string) ($this->slug ?: $this->name));

        return in_array($key, ['free', 'standard', 'premium'], true);
    }
}
