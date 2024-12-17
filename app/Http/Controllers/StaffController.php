<?php

namespace App\Http\Controllers;

use App\Models\ClinicDetail;
use App\Models\StaffAccess;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function createStaff(){
        $clinics = Auth::user()->clinics;
        return view("settingsAddStaff",compact("clinics")); 
    }

    public function storeStaff(Request $request){

        $request->validate([
            'staff_name' => 'required|string|max:255',
            'staff_email' => 'required|email|unique:mf_users,email',
            'staff_number' => 'required|numeric|digits_between:10,15|unique:mf_users,phone_no',
            'staff_password' => 'required|string|min:8',
            'ip_added' => 'nullable|ip',
            'http_user_agent' => 'nullable|string|max:255',
        ]);

        $user = new User();

        $user->name = $request->staff_name;
        $user->email = $request->staff_email;
        $user->phone_no = $request->staff_number;
        $user->password = Hash::make($request->staff_password);
        $user->ip_added = $request->ip();
        $user->http_user_agent = $request->userAgent();
        $user->user_type = "staff";
        $user->added_by = Auth::user()->id;
        $user->save();

        $staffProfile = new StaffProfile();
        $staffProfile->user_id = $user->id; 
        $staffProfile->clinic_detail_id = $request->clinic_id;
        $staffProfile->save();

        return redirect()->back()->with("success","Staff is created successfully");
    }


    public function createStaffAccess(Request $request){
        $clinics = Auth::user()->clinics;
        $selectedClinic = null;
        $staffs = null;
        $selectedStaff = null;
        $staffAccess = null;
        if( $request->clinic_id){
            $selectedClinic = ClinicDetail::find($request->clinic_id);
            $staffs = $selectedClinic->staffProfiles->load([
                'user' => function ($query) {
                    $query->select('id', 'name'); 
                }
            ]);
        }
        if ($request->staff_id){
            $selectedStaff = StaffProfile::find($request->staff_id);
            $staffAccess = StaffAccess::where('staff_profile_id', $selectedStaff->id)->get();
        }
        return view("settingsStaffAccess", compact("clinics", "selectedClinic", 'staffs', 'selectedStaff', "staffAccess") );
    }

    public function storeStaffAccess(Request $request)
    {
        $categories = [
            'dashboard',
            'appointments',
            'clinics',
            'doctors',
            'reports',
            'settings',
        ];

        foreach ($categories as $category) {
            $view = $request->input("{$category}_view") == '1' ||
                    $request->input("{$category}_add") == '1' ||
                    $request->input("{$category}_edit") == '1' ||
                    $request->input("{$category}_delete") == '1';

            StaffAccess::updateOrCreate(
                [
                    'staff_profile_id' => $request->staff_profile_id,
                    'categories'       => $category,
                ],
                [
                    'view'   => $view, 
                    'add'    => $request->input("{$category}_add") == '1',
                    'edit'   => $request->input("{$category}_edit") == '1',
                    'delete' => $request->input("{$category}_delete") == '1',
                ]
            );
        }

        return redirect()->back()->with("success", "Staff Access updated successfully");
    }


    public function editStaff($clinic, $staff){
        $staff = StaffProfile::with('user')->findOrFail($staff);
        $clinicId = 1; // for now after authentcation logic should change

        return view('settingsAddStaff', compact('staff', "clinicId"));

    }
    public function updateStaff(Request $request){
        $validated = $request->validate([
            'staff_name'     => 'required|string|max:255',
            'staff_number'   => 'required|numeric|digits:10|unique:mf_users,phone_no,' . $request->staff_user_id,
            'staff_email'    => 'required|email|unique:mf_users,email,' . $request->staff_user_id, 
            'staff_password' => 'nullable|string|min:8', 
            'staff_user_id'  => 'required|numeric|exists:mf_users,id',
            'staff_id'  => 'required|numeric', 
            'clinic_id'  => 'required|numeric', 
        ]);
    
        $staffUser = User::findOrFail($request->staff_user_id);
    
        $staffUser->name = $validated['staff_name'];
        $staffUser->email = $validated['staff_email'];
        $staffUser->phone_no = $validated['staff_number'];
    
        if ($request->staff_password) {
            $staffUser->password = Hash::make($validated['staff_password']);
        }
    
        $staffUser->save();
    
        return redirect()->route('editClinicStaff', ['clinic_id' => $request->clinic_id, 'staff_id' => $request->staff_id])->with('success', 'Staff updated successfully!');

    }

}
