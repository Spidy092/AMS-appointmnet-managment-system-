<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;

class DoctorClinic extends Model
{

    protected $table = Constants::DB_PREFIX . "_clinic_doctor";

    protected $fillable = ['doctor_id', 'clinic_id'];


    public function doctor()
    {
        return $this->belongsTo(DoctorsDetail::class);
    }

    public function clinic()
    {
        return $this->belongsTo(ClinicDetail::class);
    }
}
