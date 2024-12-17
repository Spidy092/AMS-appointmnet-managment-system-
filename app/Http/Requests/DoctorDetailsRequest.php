<?php

namespace App\Http\Requests;

use App\Constants\Constants;
use App\Models\DoctorsDetail;
use Illuminate\Foundation\Http\FormRequest;

class DoctorDetailsRequest extends FormRequest
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
        $userTable = Constants::DB_PREFIX . '_users';
        $specializationTable = Constants::DB_PREFIX . '_specialization_master';
        $profileId = $this->route('id');
        $doctor = DoctorsDetail::find($profileId);
        $userId = null;
        if ($doctor) {
            $userId = $doctor->user->id;
        }


        return [
            'first_name'         => "required|max:50",
            'last_name'          => "required|max:50",
            'email'              => "required|email|unique:$userTable,email,{$userId}",
            'alt_email'          => "nullable|email",
            // 'alt_email'          => "nullable|email|unique:$doctorTable,email,{$id}",
            // 'phone'              => "required|digits:10|unique:$doctorTable,phone,{$id}",
            'alt_phone'          => "nullable|digits:10",
            // 'alt_phone'          => "nullable|digits:10|e:$doctorTable,phone,{$id}",
            'dob'                => "required|date",
            'gender'             => "required|in:M,F",
            'pan_number'         => "nullable|max:10|alpha_num|unique:$doctorTable,pan_number,{$profileId}",
            'about_me'           => "nullable|string|max:500",
            // 'education'          => "nullable|string|max:255",
            // 'years_of_experience'=> "nullable|numeric|min:0",
            'specializations'    => "required|array|",
            'specializations.*' => "integer|exists:$specializationTable,id",
            // 'registration_no'    => "nullable|string|max:100",
            'address'            => "nullable|string|max:255",
            'country'            => "nullable|string|max:100",
            'state'              => "nullable|string|max:100",
            'district'           => "nullable|string|max:100",
            'locality'           => "nullable|string|max:100",
            'pincode'            => "nullable|digits:6",
            'doctor_password'           => 'nullable|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
        ];
    }
}
