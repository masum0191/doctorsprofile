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
        $defaults = [
            'doctor' => true,
            'appointments' => true,
            'patients' => true,
            'services' => true,
            'content' => true,
        ];

        $features = is_array($this->features) ? array_merge($defaults, $this->features) : $defaults;

        return (bool) ($features[$feature] ?? false);
    }

    public function featureMap(): array
    {
        $defaults = [
            'doctor' => true,
            'appointments' => true,
            'patients' => true,
            'services' => true,
            'content' => true,
        ];

        return is_array($this->features) ? array_merge($defaults, $this->features) : $defaults;
    }

}
