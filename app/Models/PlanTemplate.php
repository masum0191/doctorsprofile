<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanTemplate extends Model
{
    protected $fillable = [
        'plan_name',
        'plan_details',
    ];
}
