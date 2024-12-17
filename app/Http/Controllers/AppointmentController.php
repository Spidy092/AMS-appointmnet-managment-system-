<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\ClinicDetail;
use App\Models\DoctorClinic;
use App\Models\DoctorsDetail;
use App\Models\DoctorTiming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth ;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(){
        $clinics = collect(); 
    
        if (Auth::user()->user_type == "admin") {
            $clinics = Auth::user()->clinics; 
        } elseif (Auth::user()->user_type == "staff") {
            $staffProfile = Auth::user()->staffProfile;
    
            
            $clinic = ClinicDetail::where("id", $staffProfile->clinic_detail_id)->first();
    
            if ($clinic) {
                $clinics = collect([$clinic]); 
            }
        }
    
        return view("appointmentBooking", compact("clinics"));
    }

    public function getDoctorsAndDuration(Request $request){

        $doctors = ClinicDetail::findOrFail($request->clinicId)
            ->doctors()
            ->addSelect('mf_doctor_details.id', 'mf_doctor_details.user_id')
            ->with(['user' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        $clinicSetting = ClinicDetail::find($request->clinicId)->clinicSetting;

        if ($clinicSetting && $clinicSetting->time_slot_minutes) {
            $duration = $clinicSetting->time_slot_minutes;
        } else {
            $duration = 30;
        }
        $data = ['doctors'=> $doctors,'duration'=> $duration];

        return response()->json($data);

    }

    public function getDoctorAvailableTimings(Request $request){
        $clinicId = $request->clinic;
        $doctorId = $request->doctor; 
        $date = $request->date; 
        
        
        $day = Carbon::parse($date)->format('l'); 
        
        $relationId = DoctorClinic::where('clinic_id', $clinicId)->where('doctor_id', $doctorId)->first()->id;
        
        $doctorTimings = DoctorTiming::where('doctor_clinic_id', $relationId)
            ->where('day', $day)
            ->get(); 
        if ($doctorTimings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor is not available on the selected date.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $doctorTimings
        ], 200);

    }

    public function storeAppointment(AppointmentRequest $request){

        $doctorId =  $request->sel_doctor;
        $date = $request->appointment_date;
        $day = Carbon::parse($date)->format('l'); 
       

        return response()->json(["success" => 'updated successfully'] , 200);
    }
}
