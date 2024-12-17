<?php

namespace App\Models;

use App\Constants\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClinicSetting extends Model
{
    use HasFactory;

    protected $table = Constants::DB_PREFIX . '_clinic_settings';
    // protected $table = 'mf_clinic_settings';

    protected $fillable = [
        'clinic_id',
        'time_slot_minutes',
        'remainder_to_patient',
    ];

    // Define the one-to-one relationship with the ClinicDetail model
    public function clinicDetails()
    {
        return $this->belongsTo(ClinicDetail::class);
    }

    public static function validateSettings($data)
{
    $rules = [
        'clinic_id' => 'required|exists:mf_clinic_details,id',
        'event' => 'required|in:confirmation,cancellation,remainder',
        'is_enabled' => 'required|boolean',
    ];

    if (isset($data['contact_number'])) {
        $rules['contact_number'] = 'required|string|max:15';
    } elseif (isset($data['email'])) {
        $rules['email'] = 'required|email';
    }

    return Validator::make($data, $rules);
}
}


