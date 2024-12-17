<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClinicTiming extends Model
{
    use HasFactory;

    protected $table = Constants::DB_PREFIX . '_clinic_timings';
    // protected $table = 'mf_clinic_timings'; // Explicitly specify table name

    protected $fillable = [
        'day',
        'morning_from',
        'morning_to',
        'evening_from',
        'evening_to',
    ];

    public function clinicDetails()
    {
        return $this->belongsTo(ClinicDetail::class);
    }
}
