<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'dosage',
        'frequency',
        'duration',
        'instruction',
        'type',
    ];
     public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class)
            ->withPivot(['dosage','frequency','duration','instruction']);
    }
}
