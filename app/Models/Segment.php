<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $fillable = ['tenant_id','name','rules'];
    protected $casts = ['rules' => 'array'];
}
