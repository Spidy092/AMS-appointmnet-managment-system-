<?php

namespace App\Http\Requests;

use App\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;

class ClinicDetailsRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'clinic_name' => 'required|max:60',
            'clinic_tag_line' => 'required',
            'contact_no_1'=> "required|regex:/^(?:\+?[0-9]{1,3})?[0-9]{10,14}$/|max:15|min:10|unique:".Constants::DB_PREFIX."_clinic_details,contact_no_1",
            'contact_no_2'=> "nullable|regex:/^(?:\+?[0-9]{1,3})?[0-9]{10,14}$/|max:15|min:10|unique:".Constants::DB_PREFIX."_clinic_details,contact_no_2",
            "gstin"=> "size:15|string",
            "about_clinic"=>"string|max:255|nullable",
            "web_address"=>"url|nullable",
            "address"=>"required|string",
            'country' => 'required|max:60|string',
            'state' => 'required|max:60|string',
            'district' => 'required|max:60|string',
            'locality' => 'required|max:60|string',
            'pincode' => 'required|digits:6|numeric',
            'fees_based_on' => 'required|in:nofee,specificationBased,clinicBased',
            'consultation_fee' => 'nullable|numeric',



            // 'clinicImage1' => 'nullable|file|mimes:jpeg,jpg,png',
            // 'clinicImage2' => 'nullable|file|mimes:jpeg,jpg,png',
            // 'clinicImage3' => 'nullable|file|mimes:jpeg,jpg,png',
            // 'clinicImage4' => 'nullable|file|mimes:jpeg,jpg,png',
            // 'clinicImage5' => 'nullable|file|mimes:jpeg,jpg,png',
        ];
    }
}
