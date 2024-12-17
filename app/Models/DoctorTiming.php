<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorTiming extends Model
{
    protected $table = "mf_doctor_timings";
    protected $fillable = [
        'doctor_clinic_id', 'day', 'morning_from', 'morning_to', 'evening_from', 'evening_to'
    ];

    // Relationship to DoctorClinic (many-to-one)
    public function doctorClinic()
    {
        return $this->belongsTo(DoctorClinic::class);
    }

    // Accessor for Doctor
    public function doctor()
    {
        return $this->doctorClinic->doctor();
    }

    // Accessor for Clinic
    public function clinic()
    {
        return $this->doctorClinic->clinic();
    }
}
