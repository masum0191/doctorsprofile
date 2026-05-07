<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'test_name',
    ];
    public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class);
    }

    public function prescriptionTemplates()
    {
        return $this->belongsToMany(PrescriptionTemplate::class);
    }
}

