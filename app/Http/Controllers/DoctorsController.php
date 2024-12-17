<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Constants\Constants;

use Illuminate\Http\Request;
use App\Models\DoctorsDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\DoctorDetailsRequest;
use App\Models\Specialization;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DoctorsController extends Controller
{
    public function showAddDoctorForm()
    {
        if (Gate::denies('admin-staff-access', ['doctors', "add"])) {
            abort(403, "You have no access to add the doctors");
        }     
        $specializations = Specialization::all();
        $specializations = $specializations->whereNull('parent_id');
        return view('addDoctor', compact('specializations'));
    }

    public function addDoctorFunction(DoctorDetailsRequest $request)
    {
        // if (Gate::denies('admin-staff-access', ['doctors', "add"])) {
        //     abort(403, "You have no access to add the doctors");
        // }   
        $data = $request->validated();
        $request->validate([
            'doctor_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'password.confirmed' => 'Passwords do not match.',
        ]);
        

        // $data = $request->validated(); // Using validated data

        // Correct data handling
        $user = new User();
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->phone_no = $request->phone;
        $user->password = Hash::make($request->doctor_password);
        $user->ip_added = $request->ip();
        $user->http_user_agent = $request->userAgent();
        $user->user_type = "doctor";
        $user->added_by = Auth::user()->id;
        $user->save();

        $doctor = new DoctorsDetail($data);
        $doctor->user_id = $user->id;
        $doctor->added_by = $request->user()->id;
        $doctor->ip_added = $request->ip();
        $doctor->http_user_agent = $request->header('User-Agent');
        $doctor->specializations = json_encode($request->input('specializations', [])); 
        $doctor->save();

        return redirect()->back()->with('success', 'Doctor added successfully!');
    }

    public function editDoctor($id){
        if (Gate::denies('admin-staff-access', ['doctors', "add"])) {
            abort(403, "You have no access to add the doctors");
        } 
          
        // $doctor = DoctorsDetail::with('specializations')->with('user')->findOrFail($id);
        $doctor = DoctorsDetail::findOrFail($id);
        $specializations = $doctor->specializations;
        Log::info($specializations);
        $specializations = Specialization::all();
        $specializations = $specializations->whereNull('parent_id');
        $doctorSpecializations = json_decode($doctor->specializations, true); 
        return view('addDoctor', compact('doctor', 'specializations', 'doctorSpecializations'));
    }

    public function updateDoctor(DoctorDetailsRequest $request, $id)
    {
        $data = $request->validated();

        $doctor = DoctorsDetail::findOrFail($id);

        $user = User::findOrFail($doctor->user_id);

        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->phone_no = $request->phone;

        if ($request->filled('doctor_password')) {
            $user->password = Hash::make($request->doctor_password);
        }

        $user->ip_added = $request->ip();
        $user->http_user_agent = $request->userAgent();
        $user->modified_by = Auth::user()->id;  
        $user->save();

        $doctor->fill($data);  
        $doctor->added_by = $request->user()->id; 
        $doctor->ip_added = $request->ip(); 
        $doctor->http_user_agent = $request->header('User-Agent');  
        $doctor->save();

        $doctor->specializations = json_encode($request->input('specializations', []));
        $doctor->save();
        return redirect()->back()->with('success', 'Doctor updated successfully!');
    }

    public function getDoctorsData (Request $request)
    {
        $table = Constants::DB_PREFIX . '_doctor_details';

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowPerPage = $request->get("length");
        $orderArray = $request->get('order');
        $columnNameArray = $request->get('columns');
        $searchArray = $request->get('search');
        $columnIndex = $orderArray[0]['column'];
        $columnName = $columnNameArray[$columnIndex]['data'];
        $columnSortOrder = $orderArray[0]['dir'];
        $searchValue = $searchArray['value'];

        if ($columnName == 'date_addeda') {
            $columnName = 'date_added';
        }

        $tableData = User::where('user_type', 'doctor');
        
       
        $total = $tableData->count();

        if (!empty($searchValue)) {
            $tableData->where(function ($query) use ($searchValue, $table) {
                $query->where($table . '.id', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere($table . '.date_added', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('addedUser.name', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('modifiedUser.name', 'LIKE', '%' . $searchValue . '%');
            });
        }

        $totalFilter = $tableData->count();

        $tableData->skip($start)
            ->take($rowPerPage)
            ->orderBy($columnName, $columnSortOrder);

        $arrData = $tableData->get();

        $dataWithActions = [];

        foreach ($arrData as $item) {
            $id = $item->id;
            $custom_sl = "SER00" . $id;
            $item->id = $custom_sl;
            $suspend = $item->status;

            $item->date_addeda = Carbon::parse($item->date_added)->format('d-m-Y');

            if ($suspend == 0) {
                $suspend_state = 'activate';
                $suspend_color = '#808080';
            } else {
                $suspend_state = 'suspend';
                $suspend_color = '#47a46e';
            }

            $editUrl = url("editDoctor/{$id}");
            // $statusExists = Specialization::where('service_id', $id)->exists();
            $item->action = "<div class='user-action-btns'>";
            // if (!$statusExists) {
            // }
            $item->action .= "<button id='{$suspend_state}' value='{$id}' title='{$suspend_state}'><i class='fa-solid fa-ban' style='color: {$suspend_color};'></i></button>";
            $item->action .= "<a href='{$editUrl}' id='edit' value='{$id}' title='edit'><i class='fa-solid fa-pen-to-square'></i></a>";
            $item->action .= "<button id='delete' value='{$id}' title='delete'><i class='fa-solid fa-trash'></i></button>";
            $item->action .= "</div>";

            $dataWithActions[] = $item;
        }

        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFilter,
            "data" => $dataWithActions,
        );

        return response()->json($response);
    }


    public function doctorDeleteFunction(Request $request)
    {
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        $currentAction = $request->get('currentAction');
        if ($currentAction == 'delete') {
            $service_del = User::find($currentServiceId)->delete();
            if (!$service_del) {
                return response()->json(['message' =>  'Something went wrong while deleting record.'], 404);
            }
            $message = 'Specialization deleted Successfully.';
        } else {
            $message = 'Something went wrong.';
        }
        return response()->json(['message' => $message]);
    }

    public function doctorActionFunc(Request $request)
    {
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        // log::info($currentServiceId);
        $currentAction = $request->get('currentAction');
        $service = DoctorsDetail::find($currentServiceId);
        if (!$service) {
            return response()->json(['message' => 'Service not found.'], 404);
        }
        if ($currentAction == 'suspend') {
            $service->status = '0';
            // $service->modified_by = Auth::user()->id;
            $service->ip_modified = request()->ip();
            $service->date_modified = now();
            $service->save();
            $message = 'Service suspended Successfully.';
        } else if ($currentAction == 'activate') {
            $service->status = '1';
            // $service->modified_by = Auth::user()->id;
            // $service->modified_by = auth()->user()->id;
            $service->ip_modified = request()->ip();
            $service->date_modified = now();
            $service->save();
            $message = 'Service activated Successfully.';
        } else {
            $message = 'Something went wrong.';
        }
        return response()->json(['message' => $message]);
    }
    public function manageDoctorView(){
        return view('manageDoctor');
    }
    public function showeditDoctorForm(){
        return view('editDoctor');
    }

}
