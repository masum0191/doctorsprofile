<?php
namespace App\Models;
use Stancl\Tenancy\Database\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
class Domain extends Model
{
    protected $fillable = [
    'domain',
    'type',
    'registration_fee',
    'is_primary',
    'status',
    'tree_subdomain',
    'tree_domain'
];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
