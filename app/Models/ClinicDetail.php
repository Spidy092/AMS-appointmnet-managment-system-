<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClinicDetail extends Model
{
    use HasFactory;
    protected $table = Constants::DB_PREFIX . '_clinic_details';
    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'clinic_name',
        'clinic_tag_line',
        'contact_no_1',
        'contact_no_2',
        'gstin',
        'about_clinic',
        'web_address',

        'address',
        'country',
        'state',
        'district',
        'locality',
        'pincode',
        'longitude',
        'latitude',

        'fees_based_on',
        'consultation_fee',

        'logo_url',
        'clinic_image1',
        'clinic_image1_thumb',
        'clinic_image2',
        'clinic_image2_thumb',
        'clinic_image3',
        'clinic_image3_thumb',
        'clinic_image4',
        'clinic_image4_thumb',
        'clinic_image5',
        'clinic_image5_thumb',

        'specialization_ids',

        'status',
        'added_by',
        'modified_by',
        'ip_address',
        'ip_modified',
    ];


    public function clinicTimings()
    {
        return $this->hasMany(ClinicTiming::class);
    }

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class);
    }

    public function clinicSetting()
    {
        return $this->hasOne(ClinicSetting::class, 'clinic_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function staffProfiles()
    {
        return $this->hasMany(StaffProfile::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(DoctorsDetail::class, 'mf_clinic_doctor', 'clinic_id', 'doctor_id');
    }
    public function doctorTimings()
    {
        return $this->hasMany(DoctorTiming::class, 'clinic_id', 'id');
    }

}
