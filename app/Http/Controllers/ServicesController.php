<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Service;
use App\Constants\Constants;
use App\Models\ClinicDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    public function viewServices(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $user = User::find($userId);
        return view('services', ['user' => $user]);
    }

    public function getServicesData(Request $request)
    {
        $table = Constants::DB_PREFIX . '_services_master';

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

        $usersTable = 'users';


        $services = DB::table($table)
            ->leftJoin($usersTable . ' as addedUser', $table . '.added_by', '=', 'addedUser.id')
            ->leftJoin($usersTable . ' as modifiedUser', $table . '.modified_by', '=', 'modifiedUser.id')
            ->select($table . '.*', 'addedUser.name as created_by_name', 'modifiedUser.name as modified_by_name');

        $total = $services->count();

        if (!empty($searchValue)) {
            $services->where(function ($query) use ($searchValue, $table) {
                $query->where($table . '.id', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere($table . '.service_name', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere($table . '.date_added', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('addedUser.name', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('modifiedUser.name', 'LIKE', '%' . $searchValue . '%');
            });
        }

        $totalFilter = $services->count();

        $services->skip($start)
            ->take($rowPerPage)
            ->orderBy($columnName, $columnSortOrder);

        $arrData = $services->get();

        $dataWithActions = [];
        foreach ($arrData as $item) {
            $id = $item->id;
            $custom_sl = "SER00" . $id;
            $item->id = $custom_sl;
            $suspend = $item->status;

            $item->date_addeda = Carbon::parse($item->date_added)->format('d-m-Y');

            if ($suspend == 1) {
                $suspend_state = 'activate';
                $suspend_color = '#808080';
            } else {
                $suspend_state = 'suspend';
                $suspend_color = '#47a46e';
            }

            $statusExists = ClinicDetail::where('service_id', $id)->exists();
            $item->action = "<div class='user-action-btns'>";
            if (!$statusExists) {
                $item->action .= "<button id='{$suspend_state}' value='{$id}' title='{$suspend_state}'><i class='fa-solid fa-ban' style='color: {$suspend_color};'></i></button>";
            }
            $item->action .= "<button id='edit' value='{$id}' title='edit'><i class='fa-solid fa-pen-to-square'></i></button>";
            if (!$statusExists) {
                $item->action .= "<button id='delete' value='{$id}' title='delete'><i class='fa-solid fa-trash'></i></button>";
            }
            $item->action .= "</div>";

            $dataWithActions[] = $item;
        }

        // Return the response in JSON format for DataTables
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFilter,
            "data" => $dataWithActions,
        );

        return response()->json($response);
    }

    public function addService(Request $request)
    {
        $alert = "";

        $request->validate([
            'new_service' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',
        ]);

        $new_service = $request->get('new_service');
        $existingService = Service::where('service_name', $new_service)->first();

        if ($existingService) {
            $alert = 'Service already exists.';
            return response()->json($alert);
        }

        $addedBy = session()->get('user_id');
        $ipAddress = request()->ip();

        // Get the highest current priority and increment it by 1
        $highestPriority = Service::max('priority') ?? 0;
        $newPriority = $highestPriority + 1;

        $service_data = [
            'service' => ucfirst($new_service),
            'priority' => $newPriority,
            'ip_address' => $ipAddress,
            'ip_modified' => $ipAddress,
            'added_by' => $addedBy,
        ];

        Service::create($service_data);
        $alert = "Service added Successfully.";

        // return response()->json($alert);
        return response()->json(['message' => $alert]);
    }

    public function getServiceDetails(Request $request)
    {
        $currentServiceId = $request->get('currentServiceId');
        $service = Service::find($currentServiceId);
        return response()->json($service);
    }

    public function serviceActionFunc(Request $request)
    {
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        $currentAction = $request->get('currentAction');
        $service = Service::find($currentServiceId);
        if ($currentAction == 'suspend') {
            $service->status = '1';
            $service->modified_by = Auth::user()->id;
            $service->ip_modified = request()->ip();
            $service->date_modified = now();
            $service->save();
            $message = 'Service suspended Successfully.';
        } else if ($currentAction == 'activate') {
            $service->status = '0';
            $service->modified_by = Auth::user()->id;
            // $service->modified_by = auth()->user()->id;
            $service->ip_modified = request()->ip();
            $service->date_modified = now();
            $service->save();
            $message = 'Service activated Successfully.';
        } else {
            $message = 'Something went wrong.';
        }
        // return response()->json($message);
        return response()->json(['message' => $message]);
    }

    public function editService(Request $request)
    {
        $request->validate([
            'new_service' => 'required|string|max:50|regex:/^[a-zA-Z\s]+$/',
        ]);

        $serviceId = $request->get('id');
        $newService = $request->get('new_service');

        $service = Service::find($serviceId);

        if ($service) {
            $service->service = ucfirst($newService);
            $service->modified_by = Auth::user()->id;
            // $service->modified_by = auth()->user()->id;
            $service->ip_modified = request()->ip();
            $service->date_modified = now();
            $service->save();

            return response()->json(['message' => 'Service updated successfully.']);
        }

        return response()->json(['message' => 'Service not found.'], 404);
    }



    public function serviceDeleteFunction(Request $request)
    {
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        $currentAction = $request->get('currentAction');
        if ($currentAction == 'delete') {
            $service_del = Service::find($currentServiceId)->delete();
            if (!$service_del) {
                $message = 'Something went wrong while deleting record.';
            }
            $message = 'Service deleted Successfully.';
        } else {
            $message = 'Something went wrong.';
        }
        return response()->json(['message' => $message]);
    }
}
