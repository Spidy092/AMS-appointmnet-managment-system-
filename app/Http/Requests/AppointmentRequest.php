<?php

namespace App\Http\Requests;

use App\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $doctorTable = Constants::DB_PREFIX . '_doctor_details';
        $clinicTable = Constants::DB_PREFIX . '_clinic_details';
        return [
            'patient_name' => 'required|string|max:255',
            'contact_number' => 'required|regex:/^(\+?[0-9]{1,3}[- ]?)?[0-9]{10}$/|max:15',
            'email_address' => 'required|email|max:255',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'sel_clinic' => "required|exists:$clinicTable,id",
            'appointment_duration' => 'required|integer', 
            'sel_doctor' => "required|exists:$doctorTable,id",
        ];
    }

    /**
     * Get the custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'patient_name.required' => 'Patient Name is required.',
            'contact_number.required' => 'Contact Number is required.',
            'contact_number.regex' => 'Contact Number must be a valid 10-digit number.',
            'email_address.required' => 'Email ID is required.',
            'email_address.email' => 'Email ID must be a valid email address.',
            'appointment_date.required' => 'Scheduled Date is required.',
            'appointment_date.date' => 'Please select a valid date for the appointment.',
            'start_time.required' => 'Start Time is required.',
            'start_time.date_format' => 'Start Time must be in the format HH:MM.',
            'sel_clinic.required' => 'Clinic is required.',
            'sel_clinic.exists' => 'The selected clinic does not exist.',
            'durationSelect.required' => 'Duration is required.',
            'sel_doctor.required' => 'Doctor/Consultation is required.',
            'sel_doctor.exists' => 'The selected doctor does not exist.',
        ];
    }
}
