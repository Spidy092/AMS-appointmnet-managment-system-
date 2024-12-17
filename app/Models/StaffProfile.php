<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel's naming convention)
    protected $table = 'mf_staff_profile';


    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(ClinicDetail::class,  'clinic_detail_id');
    }

    public function staffAccess()
    {
        return $this->hasMany(StaffAccess::class);
    }
}

