<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineTemplate extends Model
{
    protected $fillable = [
        'medicine_name',
        'company_name',
        'generic_name',
        'medicine_type',
        'medicine_dose',
        'medicine_day',
        'medicine_mg',
        'medicine_comments',
        'medicine_description',
        'medicine_url',
    ];

    public function prescriptions()
    {
        return $this->belongsToMany(PrescriptionTemplate::class);
    }
    public function prescriptionstemplate()
    {
        return $this->belongsToMany(Prescription::class);
    }

}
