<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Appointment;
use App\Models\Prescription;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var arrayint, string>
     */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    // protected $fillable = ['name','type',
    // 'username','phone','email','photo','remember_token','status','password','otp','fullname','accounttpe','membertype','salertype','package_id','birthdate','division_id','district_id','thana_id','area_id','shop','shoptitle','shopdescripiton','googleloaction','saturday','sunday','monday','tuesday','wednesday','thuresday','friday','profilephoto','coverphoto','shopexpirydate','path','email_verified_at','salepost' ];
  protected $fillable = [
    'name', 'email', 'password', 'role', 'type', 'nid', 'mobile',
    'qualification', 'reg_no', 'acount_type', 'extra_data', 'tenant_id', 'phone',
    'photo',
    'city',
    'latitude',
    'longitude',
    'is_available_today',
    'accepts_virtual_visits',
    'accepts_insurance',
    'rating','specialization',
    'licence',
    'country',

    'present_address',
    'permanent_address',
    'zip_code',
    'state',
    'age',
    'gender',
    'address',
    'vitality',
    'medical_history',
    'emergency_contact',
    'basic_details',
    'status',
    'feature',
    'package'
   ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAvailableToday(): bool
{

    // Implement your availability logic here
    return true; // Temporary - replace with real logic

}
/** Nearby by Haversine (meters/km) */
public function scopeNearby($q, float $lat, float $lng, int $radiusKm = 25)
{
    $haversine = "
      (6371 * acos(
        cos(radians(?)) * cos(radians(latitude)) *
        cos(radians(longitude) - radians(?)) +
        sin(radians(?)) * sin(radians(latitude))
      ))
    ";

    return $q->select('*')
        ->selectRaw("$haversine AS distance_km", [$lat, $lng, $lat])
        ->orderBy('distance_km')
        ->having('distance_km','<=',$radiusKm);
}

protected $casts = [
        'is_available_today' => 'boolean',
        'accepts_virtual_visits' => 'boolean',
        'accepts_insurance' => 'boolean',
        'rating' => 'float',
        'emergency_contact' => 'array',
    'basic_details'     => 'array',
    ];

/**
     * Appointments where this user is the DOCTOR
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Appointments where this user is the PATIENT
     */
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // ... your existing doctor relations (educations, services, etc.) ...

/**
 * Appointments where this user is the PATIENT
 */
public function appointments()
{
    // if this user is a patient, usually we mean patient appointments
    return $this->hasMany(Appointment::class, 'patient_id');
}




public function doctorPrescriptions()
{
    return $this->hasMany(Prescription::class, 'doctor_id');
}

public function patientPrescriptions()
{
    return $this->hasMany(Prescription::class, 'patient_id');
}

public function tenant(): BelongsTo
{
    return $this->belongsTo(Tenant::class, 'tenant_id');
}

public function specializationList(): array
{
    $specialization = $this->specialization;

    if (is_array($specialization)) {
        return array_values(array_filter(array_map('trim', $specialization)));
    }

    if (blank($specialization)) {
        return [];
    }

    $decoded = json_decode((string) $specialization, true);
    if (is_array($decoded)) {
        return array_values(array_filter(array_map('trim', $decoded)));
    }

    return array_values(array_filter(array_map('trim', explode(',', (string) $specialization))));
}

public function specializationLabel(string $fallback = 'General Practice'): string
{
    $specializations = $this->specializationList();

    return $specializations ? implode(', ', $specializations) : $fallback;
}
/**
 * Get the attributes that should be cast.
 *
 * @return array<string, string>
 */
public function educations()
{
    return $this->hasMany(\App\Models\DoctorEducation::class)->orderBy('order_column');
}
public function experiences() { return $this->hasMany(\App\Models\DoctorExperience::class)->orderBy('order_column'); }
public function certifications() { return $this->hasMany(\App\Models\DoctorCertification::class)->orderBy('order_column'); }
public function affiliations() { return $this->hasMany(\App\Models\DoctorAffiliation::class)->orderBy('order_column'); }
public function specialties() { return $this->hasMany(\App\Models\DoctorSpecialty::class)->orderBy('order_column'); }
public function services() { return $this->hasMany(\App\Models\DoctorService::class)->orderBy('order_column'); }
public function galleries() { return $this->hasMany(\App\Models\DoctorGallery::class)->orderBy('order_column'); }
public function testimonials() { return $this->hasMany(\App\Models\DoctorTestimonial::class)->orderBy('order_column'); }
public function faqs() { return $this->hasMany(\App\Models\DoctorFaq::class)->orderBy('order_column'); }
public function telemedicinePlatforms() { return $this->hasMany(\App\Models\DoctorTelemedicinePlatform::class)->orderBy('order_column'); }
public function profile() { return $this->hasOne(\App\Models\DoctorProfile::class); }
public function companyIncomes()
{
    return $this->hasMany(CompanyIncome::class);
}

public function activeSubscription()
{
    return $this->hasOne(Subscription::class)
        ->where('status','active')
        ->latest();
}



}
