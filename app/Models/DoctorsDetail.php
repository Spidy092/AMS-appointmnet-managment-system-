<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DoctorsDetail extends Model
{
    use HasFactory;

    protected $table = Constants::DB_PREFIX . '_doctor_details';
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';

    protected $casts = [
        'specializations' => 'array', 
    ];

    protected $fillable = [
        // 'first_name',
        // 'last_name',
        'clinic_ids',
        'about_me',
        'gender',
        // 'email',
        'alt_email',
        // 'phone',
        'alt_phone',
        'dob',
        'pan_number',
        'education',
        'registration_no',
        'role',
        'years_of_experience',
        'specialization_id',
        'address',
        'country',
        'state',
        'district',
        'locality',
        'pincode',
        'last_login',
        'status',
        'added_by',
        'modified_by',
        'ip_added',
        'ip_modified',
        'http_user_agent',
    ];

    public function specializations()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
    public function clinics()
    {
        return $this->belongsToMany(ClinicDetail::class, 'mf_clinic_doctor', 'doctor_id', 'clinic_id');
    }

    public function timings()
    {
        return $this->hasManyThrough(DoctorTiming::class, DoctorClinic::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
