<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'type',
            'status',
            'entity_id',
            'division_id',
            'district_id',
            'upazilla_id',
            'package_id',
            'billing_cycle',
            'monthly_price',
            'yearly_price',
            'storage_gb',
            'created_at',
            'updated_at',
        ];
    }

    protected $fillable = [
        'id',
        'data',
        'type',
        'status',
        'entity_id',
        'division_id',
        'district_id',
        'upazilla_id',
        'package_id',
        'billing_cycle',
        'monthly_price',
        'yearly_price',
        'storage_gb',
    ];
    // App\Models\Tenant
public function domains() {
    return $this->hasMany(\App\Models\Domain::class, 'tenant_id');
}
}
