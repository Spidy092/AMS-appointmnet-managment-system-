<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationSetting extends Model
{

    // Default table name, but this will be dynamically set.
    protected $table;

    // If we are working with the SMS table
    const TYPE_SMS = 'sms';

    // If we are working with the Email table
    const TYPE_EMAIL = 'email';

    // Fillable columns (you can add more if necessary)
    protected $fillable = [
        'clinic_id', 
        'event',       
        'subject',        // For email type
        'body',           // For email type
        'is_enabled', 
        'include_patient_name',   // for sms type
        'include_clinic_name',  // for sms type
        'include_contact_number',   // for sms type
    ];

    // Set the correct table based on type
    public function setType(string $type)
    {
        if ($type == self::TYPE_SMS) {
            $this->table = 'mf_sms_communication_settings';
        } elseif ($type == self::TYPE_EMAIL) {
            $this->table = 'mf_email_communication_settings';
        }
        return $this;
    }

    // Optionally, you can add a method to return the communication settings based on type
    public static function getSettingsByType(string $type)
    {
        $model = new self();
        $model->setType($type);
        return $model;
    }

    /**
     * Relation to Clinic (Assuming you want to link communication settings to the clinic)
     */
    public function clinicDetails()
    {
        return $this->belongsTo(ClinicDetail::class);
    }
}


// // Get SMS communication settings for a clinic
// $smsSettings = CommunicationSetting::getSettingsByType(CommunicationSetting::TYPE_SMS)
//     ->where('clinic_id', $clinicId)
//     ->get();


// // Get Email communication settings for a clinic
// $emailSettings = CommunicationSetting::getSettingsByType(CommunicationSetting::TYPE_EMAIL)
//     ->where('clinic_id', $clinicId)
//     ->get();
