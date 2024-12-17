<?php

namespace App\Http\Controllers;

use App\Models\ClinicDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CommunicationSetting;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    public function updateContacts(Request $request){
        $validatedData = $request->validate([
            'communication_email'          => 'required|email',              
            'communication_contact_number' => 'required|regex:/^\+?[0-9]{1,3}?[ ]?[0-9]{5,12}$/',
            'clinicId' => 'required|integer'
            ]);

        $clinic = ClinicDetail::findOrFail($validatedData['clinicId']);
        $clinic->communication_email = $validatedData['communication_email'];
        $clinic->communication_contact_number = $validatedData['communication_contact_number'];
        $clinic->save();    

        return response()->json(["success" => "Communication Contacts is added" , 200]);
    }

    public function updateEmailSettings(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'clinicId'             => 'required|exists:mf_clinic_details,id',
            'confirmation_enabled'  => 'nullable|boolean',
            'cancellation_enabled'  => 'nullable|boolean',
            'remainder_enabled'     => 'nullable|boolean',
            'confirmation_subject'  => 'nullable|string|max:255',
            'confirmation_body'     => 'nullable|string',
            'cancellation_subject'  => 'nullable|string|max:255',
            'cancellation_body'     => 'nullable|string',
            'remainder_subject'     => 'nullable|string|max:255',
            'remainder_body'        => 'nullable|string',
        ]);

        $clinicId = $validatedData['clinicId'];

        // Set the type to email communication settings
        $emailSettings = new CommunicationSetting();
        $emailSettings->setType(CommunicationSetting::TYPE_EMAIL);

        // Appointment Confirmation Email
        $emailSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'confirmation'],
            [
                'subject' => $validatedData['confirmation_subject'] ?? null,
                'body' => $validatedData['confirmation_body'] ?? null,
                'is_enabled' => isset($validatedData['confirmation_enabled']) ? $validatedData['confirmation_enabled'] : false,
            ]
        );

        // Appointment Cancellation Email
        $emailSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'cancellation'],
            [
                'subject' => $validatedData['cancellation_subject'] ?? null,
                'body' => $validatedData['cancellation_body'] ?? null,
                'is_enabled' => isset($validatedData['cancellation_enabled']) ? $validatedData['cancellation_enabled'] : false,
            ]
        );

        // Appointment Remainder Email
        $emailSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'remainder'],
            [
                'subject' => $validatedData['remainder_subject'] ?? null,
                'body' => $validatedData['remainder_body'] ?? null,
                'is_enabled' => isset($validatedData['remainder_enabled']) ? $validatedData['remainder_enabled'] : false,
            ]
        );

        // Return success response
        return response()->json([
            'message' => 'Communication settings updated successfully.'
        ]);
    }



    public function updateSmsSettings(Request $request){
        
    // Validate input data
    $validatedData = $request->validate([
        'clinicId'               => 'required|exists:mf_clinic_details,id',
        'confirmation_enabled'         => 'nullable|boolean',
        'cancellation_enabled'     => 'nullable|boolean',
        'remainder_enabled'       => 'nullable|boolean',
        'confirmation_include_patient' => 'nullable|boolean',    
        'confirmation_include_clinic'  => 'nullable|boolean',
        'confirmation_include_contact' => 'nullable|boolean',
        'cancellation_include_patient' => 'nullable|boolean',
        'cancellation_include_clinic'  => 'nullable|boolean',
        'cancellation_include_contact' => 'nullable|boolean',
        'remainder_include_patient' => 'nullable|boolean',
        'remainder_include_clinic'  => 'nullable|boolean',
        'remainder_include_contact' => 'nullable|boolean',
    ]);

    

    // Retrieve clinic ID from validated data
    $clinicId = $validatedData['clinicId'];

    // Appointment Confirmation SMS
    $smsSettings = new CommunicationSetting();
    $smsSettings->setType(CommunicationSetting::TYPE_SMS); // Set the table to 'mf_sms_communication_settings'

    // Handle Appointment Confirmation SMS
    if (isset($validatedData['confirmation_enabled']) && $validatedData['confirmation_enabled'] == true) {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'confirmation'],
            [
                'include_patient_name' => $validatedData['confirmation_include_patient'] ?? false,
                'include_clinic_name'  => $validatedData['confirmation_include_clinic'] ?? false,
                'include_contact_number' => $validatedData['confirmation_include_contact'] ?? false,
                'is_enabled' => true,
            ]
        );
    } else {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'confirmation'],
            ['is_enabled' => false]
        );
    }

    // Appointment Cancellation SMS
    $smsSettings = new CommunicationSetting();
    $smsSettings->setType(CommunicationSetting::TYPE_SMS); // Set the table to 'mf_sms_communication_settings'

    // Handle Appointment Cancellation SMS
    if (isset($validatedData['cancellation_enabled']) && $validatedData['cancellation_enabled'] == true) {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'cancellation'],
            [
                'include_patient_name' => $validatedData['cancellation_include_patient'] ?? false,
                'include_clinic_name'  => $validatedData['cancellation_include_clinic'] ?? false,
                'include_contact_number' => $validatedData['cancellation_include_contact'] ?? false,
                'is_enabled' => true,
            ]
        );
    } else {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'cancellation'],
            ['is_enabled' => false]
        );
    }

    // Appointment Remainder SMS
    $smsSettings = new CommunicationSetting();
    $smsSettings->setType(CommunicationSetting::TYPE_SMS); // Set the table to 'mf_sms_communication_settings'

    // Handle Appointment Remainder SMS
    if (isset($validatedData['remainder_enabled']) && $validatedData['remainder_enabled'] == true) {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'remainder'],
            [
                'include_patient_name' => $validatedData['remainder_include_patient'] ?? false,
                'include_clinic_name'  => $validatedData['remainder_include_clinic'] ?? false,
                'include_contact_number' => $validatedData['remainder_include_contact'] ?? false,
                'is_enabled' => true,
            ]
        );
    } else {
        $smsSettings->updateOrCreate(
            ['clinic_id' => $clinicId, 'event' => 'remainder'],
            ['is_enabled' => false]
        );
    }

    // Return success response
    return response()->json([
        'message' => 'SMS Communication settings updated successfully.'
    ]);
    
    }

    public function show(Request $request){
        $clinics = Auth::user()->clinics;  // change in feture for staff

        // Find the selected clinic with its related clinic setting
        $smsSettings = null;
        $emailSettings = null;
        $selectedClinic = null;
        if ($request->clinic_id) {
            $smsSettings = CommunicationSetting::getSettingsByType(CommunicationSetting::TYPE_SMS)
                    ->where('clinic_id', $request->clinic_id)
                    ->get();
            $emailSettings =  CommunicationSetting::getSettingsByType(CommunicationSetting::TYPE_EMAIL)
                    ->where('clinic_id', $request->clinic_id)
                    ->get();
            $selectedClinic = ClinicDetail::findOrFail($request->clinic_id);
        }

        Log::info($emailSettings);



        return view('settingsCommunication', compact('clinics', 'smsSettings', 'emailSettings' , 'selectedClinic'));
    }
}
