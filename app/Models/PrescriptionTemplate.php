<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTemplate extends Model
{
    protected $fillable = [
        'template_name',
        'medicine_ids',
        'investigation_ids',
        'advice',
        'next_visit',
    ];

    protected $casts = [
        'medicine_ids' => 'array',
        'investigation_ids' => 'array',
        'next_visit' => 'date',
    ];
    public function medicines()
    {
        return $this->belongsTo(MedicineTemplate::class);
    }
    public function tests()
    {
        return $this->belongsTo(Test::class);
    }

}
