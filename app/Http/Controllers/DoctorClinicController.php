<?php

namespace App\Http\Controllers;

use App\Models\ClinicDetail;
use App\Models\ClinicTiming;
use App\Models\DoctorTiming;
use App\Models\DoctorClinic;
use App\Models\DoctorsDetail;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DoctorClinicController extends Controller
{
    public function showForm(Request $request)
    {
        $doctor = User::find(5);  

        $doctorTimings = null;
        $selectedClinic = null;
        $clinicTimings = null;
        if ($request->clinic_id) {
            $doctorDetail =  DoctorsDetail::where('user_id', $doctor->id)->select('id')->firstOrFail();
            $clinicDoctorRelation = DoctorClinic::where("doctor_id", $doctorDetail->id )->where("clinic_id", $request->clinic_id)->first();
            $doctorTimings = DoctorTiming::where("doctor_clinic_id", $clinicDoctorRelation->id)->get();
            $selectedClinic = ClinicDetail::findOrFail($request->clinic_id);
            $selectedClinic = $selectedClinic->load('clinicTimings')->toArray();
        }

        
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }

        $doctorProfile = DoctorsDetail::where("user_id", $doctor->id)->first();
    
        
        if (!$doctorProfile) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }

        $clinics = $doctorProfile->clinics;  


        return view('timings', compact('doctor', 'clinics', "selectedClinic" , "doctorTimings"));
    }

    public function updateDoctorTimings(Request $request)
    {
        $doctor = User::find(5); // Assuming the doctor's ID is 2
        $doctorDetail = DoctorsDetail::where("user_id", $doctor->id)->first();
        
        // Get the relation between doctor and clinic
        $relationClinicDoctor = DoctorClinic::where("doctor_id", $doctorDetail->id)
                                        ->where("clinic_id", $request->clinic_id)
                                        ->first();

        $clinicTimings = ClinicTiming::where("clinic_detail_id", $request->clinic_id)->get();

        $weeks = ['sun' => "sunday", 'mon' => "monday", 'tue' => "tuesday", 'wed' => "wednesday", 'thu' => "thursday", 'fri' => "friday", 'sat' => "saturday"];
        $doctorTimingsArr = []; 

        foreach ($weeks as $day => $fullDay) {
            if ($request->has("doctor-timing-{$day}")) {

                $clinicTiming = $clinicTimings->firstWhere('day', $day);

                if (!$clinicTiming) {
                    throw ValidationException::withMessages([
                        "doctor-timing-{$day}" => ["The clinic is not open on {$fullDay}."],
                    ]);
                }

                // Parse the clinic's working hours
                $clinicMorningFrom = Carbon::parse($clinicTiming->morning_from);
                $clinicMorningTo = Carbon::parse($clinicTiming->morning_to);
                $clinicEveningFrom = Carbon::parse($clinicTiming->evening_from);
                $clinicEveningTo = Carbon::parse($clinicTiming->evening_to);

                // Parse the doctor's input timings
                $inputMorningFrom = $request->input("morning_from_{$day}") ? Carbon::createFromFormat('h:i A', $request->input("morning_from_{$day}")) : null;
                $inputMorningTo = $request->input("morning_to_{$day}") ? Carbon::createFromFormat('h:i A', $request->input("morning_to_{$day}")) : null;
                $inputEveningFrom = $request->input("evening_from_{$day}") ? Carbon::createFromFormat('h:i A', $request->input("evening_from_{$day}")) : null;
                $inputEveningTo = $request->input("evening_to_{$day}") ? Carbon::createFromFormat('h:i A', $request->input("evening_to_{$day}")) : null;

                // Validate the input timings against the clinic's timings
                $errors = [];

                if ($clinicMorningFrom && $inputMorningFrom && $clinicMorningFrom->greaterThan($inputMorningFrom)) {
                    $errors["doctor-timing-{$day}"][] = "The morning start time must be later than the clinic's opening time on {$fullDay}.";
                }
                
                if ($clinicMorningTo && $inputMorningTo && $clinicMorningTo->lessThan($inputMorningTo)) {
                    $errors["doctor-timing-{$day}"][] = "The morning end time must be less than the clinic's end time on {$fullDay}.";
                }
                
                if ($clinicEveningFrom && $inputEveningFrom && $clinicEveningFrom->greaterThan($inputEveningFrom)) {
                    $errors["doctor-timing-{$day}"][] = "The evening start time must be later than the clinic's evening opening time on {$fullDay}.";
                }
                
                if ($clinicEveningTo && $inputEveningTo && $clinicEveningTo->lessThan($inputEveningTo)) {
                    $errors["doctor-timing-{$day}"][] = "The evening end time must be later than tthe clinic's end time on {$fullDay}.";
                }

                if (!empty($errors)) {
                    throw ValidationException::withMessages($errors);
                }

                // Get all the clinics the doctor is associated with
                $doctorClinics = DoctorClinic::where('doctor_id', $doctorDetail->id)->get();

                // Check for overlapping timings across all clinics of the same doctor
                foreach ($doctorClinics as $doctorClinic) {
                    if ($doctorClinic->clinic_id == $request->clinic_id) {
                        continue; // Skip checking for the current clinic
                    }

                    // Get the doctor's timings for the other clinics
                    $existingTimings = DoctorTiming::where('doctor_clinic_id', $doctorClinic->id)
                                                ->where('day', $fullDay)
                                                ->get();

                    foreach ($existingTimings as $existingTiming) {
                        $existingMorningFrom = Carbon::parse($existingTiming->morning_from);
                        $existingMorningTo = Carbon::parse($existingTiming->morning_to);
                        $existingEveningFrom = Carbon::parse($existingTiming->evening_from);
                        $existingEveningTo = Carbon::parse($existingTiming->evening_to);

                        // Get the clinic name for the error message
                        $clinicName = ClinicDetail::find($doctorClinic->clinic_id)->clinic_name;

                        // Check for overlap in morning timings
                        if (($inputMorningFrom && $inputMorningTo) &&
                            (($inputMorningFrom->between($existingMorningFrom, $existingMorningTo)) || 
                            ($inputMorningTo->between($existingMorningFrom, $existingMorningTo)))) {
                            throw ValidationException::withMessages([
                                "doctor-timing-{$day}" => [
                                    "The morning timings overlap with clinic <strong> '{$clinicName}</strong>'s timings on <strong>{$fullDay}</strong>."
                                ],
                            ]);
                        }

                        // Check for overlap in evening timings
                        if (($inputEveningFrom && $inputEveningTo) &&
                            (($inputEveningFrom->between($existingEveningFrom, $existingEveningTo)) || 
                            ($inputEveningTo->between($existingEveningFrom, $existingEveningTo)))) {
                            throw ValidationException::withMessages([
                                "doctor-timing-{$day}" => ["The evening timings overlap with clinic <strong> '{$clinicName}</strong>'s timings on <strong>{$fullDay}</strong>."],
                            ]);
                        }
                    }
                }

                // If there are no overlaps, create or update the timings
                $timing = DoctorTiming::where('doctor_clinic_id', $relationClinicDoctor->id)
                                    ->firstWhere('day', $fullDay);

                if (!$timing) {
                    // Create new timing for this doctor and clinic
                    $timing = new DoctorTiming([
                        'day' => $fullDay,
                        'morning_from' => $inputMorningFrom ? $inputMorningFrom->format('H:i:s') : null,
                        'morning_to' => $inputMorningTo ? $inputMorningTo->format('H:i:s') : null,
                        'evening_from' => $inputEveningFrom ? $inputEveningFrom->format('H:i:s') : null,
                        'evening_to' => $inputEveningTo ? $inputEveningTo->format('H:i:s') : null,
                        "doctor_clinic_id" => $relationClinicDoctor->id,
                    ]);
                } else {
                    // Update existing timings
                    $timing->morning_from = $inputMorningFrom ? $inputMorningFrom->format('H:i:s') : null;
                    $timing->morning_to = $inputMorningTo ? $inputMorningTo->format('H:i:s') : null;
                    $timing->evening_from = $inputEveningFrom ? $inputEveningFrom->format('H:i:s') : null;
                    $timing->evening_to = $inputEveningTo ? $inputEveningTo->format('H:i:s') : null;
                }

                $doctorTimingsArr[] = $timing;
            } else {
                // If no timings are provided, delete the existing timing for the doctor and clinic
                $timing = DoctorTiming::where('doctor_clinic_id', $relationClinicDoctor->id)
                                    ->firstWhere('day', $fullDay);
                if ($timing) {
                    $timing->delete();
                }
            }
        }

        // Save the doctor's timings for each clinic
        foreach ($doctorTimingsArr as $timing) {
            $timing->save();
        }

        return redirect()->to(route('doctor.timings.form', ['doctorId' => 2]) . "?clinic_id=$request->clinic_id")
                        ->with('success', 'Doctor timings updated successfully.');
    }

    public function searchDoctors(Request $request) // for select2 search doctor
    {
        // if (Gate::denies('staff-admin-access', ['clinics', "edit"])) {
        //     abort(403, "You have no access to add the clinic");
        // }
        $search = $request->input('term');
        $doctors = User::where('user_type', 'doctor')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone_no', 'LIKE', '%' . $search . '%');
            })
            ->get();
        $formattedDoctors = $doctors->map(function ($doctor) {
            return ['id' => $doctor->doctorDetail->id, 'text' => "$doctor->name - ✉️ $doctor->email - ☎ $doctor->phone_no"];
        });
        return response()->json($formattedDoctors);
    }

    public function getSpecializationsClinicDoctor(Request $request) {
        
        $clinicId = $request->clinic;
        $doctorId = $request->doctor;
    
        $clinicSpecializationsId = json_decode(ClinicDetail::find($clinicId)->specialization_ids, true) ?? [];
        $doctorSpecializationsId = json_decode(DoctorsDetail::find($doctorId)->specializations, true) ?? [];
    
        if (empty($clinicSpecializationsId) || empty($doctorSpecializationsId)) {
            return response()->json(['message' => 'No common specializations found', 'data' => []], 200);
        }
    
        $commonSpecializationIds = array_intersect($clinicSpecializationsId, $doctorSpecializationsId);
    
        if (empty($commonSpecializationIds)) {
            return response()->json(['message' => 'No common specializations found', 'data' => []], 200);
        }
    
        $commonSpecializations = DB::table('mf_specialization_master')
            ->whereIn('id', $commonSpecializationIds)
            ->get();
    
        return response()->json(['message' => 'Common specializations found', 'data' => $commonSpecializations], 200);
        

    }

    public function storeDoctorClinicMapping(Request $request){
        $validator = Validator::make($request->all(), [
            'clinic' => 'required|exists:mf_clinic_details,id',
            'doctor' => 'required|exists:mf_doctor_details,id',
            'specializations' => 'required|array',
            'specializations.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exists = DoctorClinic::where('clinic_id', $request->clinic)
            ->where('doctor_id', $request->doctor)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['doctor' => ['This doctor is already assigned to the selected clinic.']]
            ], 422);
        }

        $doctorClinicRelation = new DoctorClinic();
        $doctorClinicRelation->clinic_id = $request->clinic;
        $doctorClinicRelation->doctor_id = $request->doctor;
        $doctorClinicRelation->specializations = json_encode($request->input('specializations', []));
        $doctorClinicRelation->save();

        $doctor = DoctorsDetail::with('user')
            ->where('id', $request->doctor)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Doctor added successfully!',
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
                'image' => asset('public/assets/images/doctor/doctor.jpg'),
            ],
            'clinic_id' => $request->clinic,
            'delete_url' => route('destroyDoctorClinicMapping'),
            'edit_url' => route('updateDoctorClinicMapping'),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function updateDoctorClinicMapping(Request $request){
        $validator = Validator::make($request->all(), [
            'clinic' => 'required|exists:mf_clinic_details,id',
            'doctor' => 'required|exists:mf_doctor_details,id',
            'specializations' => 'required|array',
            'specializations.*' => 'integer',
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        Log::info($request);
        $doctorClinicRelation = DoctorClinic::where('clinic_id', $request->clinic)->where('doctor_id', $request->doctor)->firstOrFail();
        $doctorClinicRelation->specializations = json_encode($request->input('specializations', []));
        $doctorClinicRelation->save();
        return response()->json(['success'=> true,'message'=> 'Data Updated successfully'],200);
    }

    public function getClinicDoctorData(Request $request){
        $clinicId = $request->clinic;
        $doctorId = $request->doctor;

        $relation = DoctorClinic::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        
        $selectedSpecializationsId = json_decode($relation->specializations, true) ?? [];

        $doctor = DoctorsDetail::with(['user:id,name,email,phone_no'])
            ->select('id', 'user_id', "specializations")
            ->findOrFail($doctorId);

        $clinicSpecializationsId = json_decode(ClinicDetail::findOrFail($clinicId)->specialization_ids, true) ?? [];
        $doctorSpecializationsId = json_decode($doctor->specializations, true) ?? [];

        if (empty($clinicSpecializationsId) || empty($doctorSpecializationsId)) {
            return response()->json(['message' => 'No common specializations found', 'data' => []], 200);
        }

        $commonSpecializationIds = array_intersect($clinicSpecializationsId, $doctorSpecializationsId);

        if (empty($commonSpecializationIds)) {
            return response()->json(['message' => 'No common specializations found', 'data' => []], 200);
        }

        $commonSpecializations = Specialization::whereIn('id', $commonSpecializationIds)->get();
        $selectedSpecializations = Specialization::whereIn('id', $selectedSpecializationsId)->get();

        $data = [
            'doctor'=> $doctor,
            'clinicId'=> $clinicId,
            'selectedSpecializations' => $selectedSpecializations,
            'commonSpecializations' => $commonSpecializations,
        ];


        return response()->json([
            'message' => 'data successfully fetched',
            'data' => $data,
        ], 200);
        
        

    }



    public function destroyDoctorClinicMapping(Request $request) {
        $clinicId = $request->clinic;
        $doctorId = $request->doctor;

        // Find and delete the specific DoctorClinic record
        $doctorClinic = DoctorClinic::where('clinic_id', $clinicId)
                                    ->where('doctor_id', $doctorId)
                                    ->first();

        if ($doctorClinic) {
            // Delete the relationship record
            $doctorClinic->delete();

            // Optionally, return a success response
            return response()->json(['message' => 'Doctor removed from the clinic successfully']);
        }

        // If the relationship does not exist, return an error response
        return response()->json(['message' => 'Doctor not found in the clinic'], 404);


    }



}
