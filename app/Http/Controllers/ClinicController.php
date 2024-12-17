<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Constants\Constants;
use App\Models\ClinicDetail;
use Illuminate\Http\Request;
use App\Models\DoctorsDetail;
use App\Models\Specialization;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\clinicUpdateRequest;
use App\Http\Requests\ClinicDetailsRequest;
use App\Models\DoctorClinic;

class ClinicController extends Controller
{

    public function showAddClinicForm(){
        if (Gate::denies('admin-staff-access', ['clinics', "add"])) {
            abort(403, "You have no access to add the clinic");
        }
        return view('clinic');
    }

    public function addClinicFunction(ClinicDetailsRequest $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "add"])) {
            abort(403, "You have no access to add the clinic");
        }

        $data = $request->validated();

        $clinic = new ClinicDetail($data);
        $clinic->added_by = $request->user()->id;
        $clinic->ip_address = $request->ip();
        $clinic->user_id = Auth::user()->id;
        $clinic->save();

        return redirect()->route('showAddClinicForm')->with('success', 'Clinic added successfully.');
    }

    public function updateClinicFunction(clinicUpdateRequest $request, $id)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }
        $data = $request->validated();

        $clinic = ClinicDetail::findOrFail($id);

        $clinic->fill($data);
        // $clinic->updated_by = $request->user()->id;
        // $clinic->ip_address = $request->ip();
        $clinic->save();

        return redirect()->route('showUpdateClinicForm', ['clinic' => $clinic->id])
            ->with('success', 'Clinic updated successfully.');
    }

    public function showUpdateClinicForm(ClinicDetail $clinic){
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }
        $clinic = $clinic->load('clinicTimings')->toArray();

        $specializations = Specialization::whereNull('parent_id')->with('children')->get();

        $selectedSpecializations = json_decode($clinic['specialization_ids'], true) ?? [];

        $clinicDoctors = DoctorClinic::where('clinic_id', $clinic['id'])  // Replace $clinicId with the clinic ID you're searching for
            ->with([
                'doctor' => function($query) {
                    $query->select('id', 'user_id');
                },
                'doctor.user' => function($query) {
                    $query->select('id', 'name', 'email');  
                }
            ])
            ->get();

        Log::info($clinicDoctors);

        return view('clinic', compact('clinic', 'specializations', 'selectedSpecializations', 'clinicDoctors'));
    }

    public function manageClinicView(){
        if (Gate::denies('admin-staff-access', ['clinics', "view"])) {
            abort(403, "You have no access to view the clinic");
        }
        return view('manageClinics');
    }

    public function uploadClinicLogo(Request $request, ){
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }

        $validatedData = $request->validate([
            'logo_image' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        $image = $request->file('logo_image');

        if ($image){

            $logoImagesDirectory = public_path("uploads/logo_image");

            if (!file_exists($logoImagesDirectory)) {
                mkdir($logoImagesDirectory, 0777, true);
            }
            $imageName = time() . '_' . $request->file('logo_image')->getClientOriginalName();
            $image->move($logoImagesDirectory, $imageName);

            chmod($logoImagesDirectory . '/' . $imageName, 0777);

            $clinicId = $request->clinic_id;
            $clinic = ClinicDetail::findOrFail( $clinicId );

            if ($clinic->logo_url) {
                $delete_image_path = "public/{$clinic->logo_url}";

                if (file_exists($delete_image_path)) {
                    unlink($delete_image_path);
                }
            }


            $clinic->logo_url = 'uploads/logo_image/'. $imageName ;
            $clinic->save();

            return response()->json(['success'=>'Image uploaded successfully.'], 200);
        } else {
            return response()->json(['error'=>'Please select the image.'], 400);
        }


    }

 
    public function uploadClinicImages(Request $request) {
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }

        $validatedData = $request->validate([
            'clinic_images' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);
        $image1 = $request->image_0;
        $image2 = $request->image_1;
        $image3 = $request->image_2;
        $image4 = $request->image_3;
        $image5 = $request->image_4;

        $clinicId = $request->clinic_id;
        $clinicData = ClinicDetail::findOrFail($clinicId);

        $imagesCount = $this->totalImagesCount([$image1, $image2, $image3, $image4, $image5]);

        $DBImageCount = $this->totalImagesCount([
            $clinicData->clinic_image1,
            $clinicData->clinic_image2,
            $clinicData->clinic_image3,
            $clinicData->clinic_image4,
            $clinicData->clinic_image5
            ]);



        if (($imagesCount > 0) && ($imagesCount <= (5-$DBImageCount)) ) {

            $imagesDirectory = public_path("uploads/clinic_image/clinic_{$clinicId}");

            if (!file_exists($imagesDirectory)) {
                mkdir($imagesDirectory, 0777, true);
            }

            $imageNames = $this->imageNameAndMove([
                $request->file('image_0'),
                $request->file('image_1'),
                $request->file('image_2'),
                $request->file('image_3'),
                $request->file('image_4'),
            ], $imagesDirectory, );

            $thumbImageNames = $this->imageNameAndMove([
                $request->file('thumb_image_0'),
                $request->file('thumb_image_1'),
                $request->file('thumb_image_2'),
                $request->file('thumb_image_3'),
                $request->file('thumb_image_4'),
            ], $imagesDirectory, true);

            // log::info($imagesDirectory);
            $pathOfImage = explode("public/" ,$imagesDirectory);

            $path = $pathOfImage[1];
            log::info($path);

            $this->clinicImageDBupdate($imageNames, $thumbImageNames ,  $clinicData, $path); // for big images

            return response()->json(['success'=>'Image uploaded successfully.'], 200);
        } else {
            return response()->json(['error'=>'Please select the image.'], 400);
        }



    }

    public function destroyclinicImage(Request $request){
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }
        $clinic = ClinicDetail::findOrFail($request->clinicId);
        $clmNames = [$request->imageClmName, $request->thumbIMgClmName];

        foreach($clmNames as $clmName){
            $oldimg = $clinic["{$clmName}"];

            $delete_image_path = public_path($oldimg);

            if (file_exists($delete_image_path)) {
                unlink($delete_image_path);
            }
            $clinic["{$clmName}"] = null;
        }


        $clinic->save();

        $clinic->clinic_image1_thumb = $clinic->clinic_image1_thumb ? asset("public/{$clinic->clinic_image1_thumb}") : "";
        $clinic->clinic_image2_thumb = $clinic->clinic_image2_thumb ? asset("public/{$clinic->clinic_image2_thumb}") : "";
        $clinic->clinic_image3_thumb = $clinic->clinic_image3_thumb ? asset("public/{$clinic->clinic_image3_thumb}") : "";
        $clinic->clinic_image4_thumb = $clinic->clinic_image4_thumb ? asset("public/{$clinic->clinic_image4_thumb}") : "";
        $clinic->clinic_image5_thumb = $clinic->clinic_image5_thumb ? asset("public/{$clinic->clinic_image5_thumb}") : "";



        return response()->json(["success"=> "Image is deleted", "clinicData"=> $clinic, "emptyImgPath" => asset('public/assets/images/clinic/icons/photo.png') ],200);
    }

    public function updateClinicTimings(Request $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to edit the clinic");
        }

        // $validatedData = $request->validate([
        //     'clinicId' => 'required|exists:mf_clinic_Details,id',
        // ]);

        $clinicDetail = ClinicDetail::find($request->clinicId);

        $clinicDetail->clinicTimings()->delete();

        $clinicTimings = $request->dateAndTime;

        foreach ($clinicTimings as $day => $timeSlots) {
            if ($timeSlots) {
                LOG::info($timeSlots);
                $clinicDetail->clinicTimings()->create([
                    'day' => $day,
                    'morning_from' => $timeSlots[0] ? date('H:i:s', strtotime($timeSlots[0])) : null,
                    'morning_to' => $timeSlots[1] ?  date('H:i:s', strtotime($timeSlots[1])) : null,
                    'evening_from' => $timeSlots[2] ? date('H:i:s', strtotime($timeSlots[2])) : null,
                    'evening_to' => $timeSlots[3] ? date('H:i:s', strtotime($timeSlots[3])) : null,
                ]);
            }
        }

        return response()->json(["success" => "Data is received at backend"], 200);
    }


    public function saveClinicSpecializations(Request $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }

        $validated = $request->validate([
            'specializations' => 'required|array',
            'specializations.*' => 'integer|exists:mf_specialization_master,id',
        ]);

        $clinicId = $request->clinic_id;
        $clinic = ClinicDetail::findOrFail($clinicId);

        $clinic->specialization_ids = $request->input('specializations');
        $clinic->save();

        return response()->json([
            'success' => true,
            'message' => 'Specializations saved successfully!',
        ]);
    }

    public function getClinicsData (Request $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "view"])) {
            abort(403, "You have no access to view the clinic");
        }
        $table = Constants::DB_PREFIX . '_clinic_details';

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

        $usersTable = Constants::DB_PREFIX . '_users';


        $tableData = DB::table($table)
            ->leftJoin($usersTable . ' as addedUser', $table . '.added_by', '=', 'addedUser.id')
            ->leftJoin($usersTable . ' as modifiedUser', $table . '.modified_by', '=', 'modifiedUser.id')
            ->select($table . '.*', 'addedUser.name as created_by_name', 'modifiedUser.name as modified_by_name');

        $total = $tableData->count();

        if (!empty($searchValue)) {
            $tableData->where(function ($query) use ($searchValue, $table) {
                $query->where($table . '.id', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere($table . '.service_name', 'LIKE', '%' . $searchValue . '%')
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
            $custom_sl = "CL00" . $id;
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

            $editUrl = url("update-clinic/{$id}");
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

    public function clinicDeleteFunction(Request $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "delete"])) {
            abort(403, "You have no access to delete the clinic");
        }
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        $currentAction = $request->get('currentAction');
        if ($currentAction == 'delete') {
            $service_del = ClinicDetail::find($currentServiceId)->delete();
            if (!$service_del) {
                $message = 'Something went wrong while deleting record.';
            }
            $message = 'Specialization deleted Successfully.';
        } else {
            $message = 'Something went wrong.';
        }
        return response()->json(['message' => $message]);
    }

    public function clinicActionFunc(Request $request)
    {
        if (Gate::denies('admin-staff-access', ['clinics', "edit"])) {
            abort(403, "You have no access to add the clinic");
        }
        $message = "";
        $currentServiceId = $request->get('currentServiceId');
        // log::info($currentServiceId);
        $currentAction = $request->get('currentAction');
        $service = ClinicDetail::find($currentServiceId);
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

    public function getPlaceByPincode(Request $request)
    {
        $pincode = $request->input('pincode');
        $placeInfo = file_get_contents("http://postalpincode.in/api/pincode/".$pincode);
        $placeInfo = json_decode($placeInfo);

        if (isset($placeInfo->PostOffice) && !empty($placeInfo->PostOffice)) {
            $arr = [];
            $arr['city'] = $placeInfo->PostOffice[0]->Taluk;
            $arr['district'] = $placeInfo->PostOffice[0]->District;
            $arr['state'] = $placeInfo->PostOffice[0]->State;
            $arr['country'] = $placeInfo->PostOffice[0]->Country;
            $arr['locality'] = [];

            foreach ($placeInfo->PostOffice as $locality) {
                array_push($arr['locality'], $locality->Name);
            }

            return response()->json($arr);
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid pincode or no data found.']);
        }
    }

    




    // helper functions 

    private function totalImagesCount($imagesArray): int{
        $totalImagesCount = 0;

        foreach ($imagesArray as $image){
            if (!$image == null){
                $totalImagesCount++;
            }
        }
        return $totalImagesCount;
    }

    private function imageNameAndMove($images, $directory, ?bool $isThumbimage = false){
        $imagesNames = [];
        foreach ($images as $image){
            if (!$image == null){
                if ($isThumbimage) {
                    $name = time() .'_ThumbImage_'. $image->getClientOriginalName();
                } else {
                    $name = time() .'_' . $image->getClientOriginalName();
                }
                $imagesNames[] = $name;
                $image->move($directory, $name);

                chmod($directory . '/' . $name, 0777);
            }
        }
        return $imagesNames;
    }

    private function clinicNullImg($clinicInstance){
        $nullImageFiledName = [];


        $imageFields = [
            "clinic_image1" => $clinicInstance->clinic_image1,
            "clinic_image2" => $clinicInstance->clinic_image2,
            "clinic_image3" => $clinicInstance->clinic_image3,
            "clinic_image4" => $clinicInstance->clinic_image4,
            "clinic_image5" => $clinicInstance->clinic_image5,
        ];

        foreach ($imageFields as $fieldName => $value) {
            if (!$value) {
                $nullImageFiledName[] = $fieldName;
            }
        }

        return $nullImageFiledName;

    }

    private function clinicImageDBupdate($imageNames, $thumbImageNames, $clinicInstance,  $imagesDirectory){

        $nullImageFiledName = $this->clinicNullImg($clinicInstance);


        for ($i = 0; $i < count($imageNames); $i++) {

            if ($nullImageFiledName[$i] == "clinic_image1"){
                $clinicInstance->clinic_image1 = "{$imagesDirectory}/{$imageNames[$i]}";
                $clinicInstance->clinic_image1_thumb = "{$imagesDirectory}/{$thumbImageNames[$i]}";
            }
            if ($nullImageFiledName[$i] == "clinic_image2"){
                $clinicInstance->clinic_image2 = "{$imagesDirectory}/{$imageNames[$i]}";
                $clinicInstance->clinic_image2_thumb = "{$imagesDirectory}/{$thumbImageNames[$i]}";
            }
            if ($nullImageFiledName[$i] == "clinic_image3"){
                $clinicInstance->clinic_image3 = "{$imagesDirectory}/{$imageNames[$i]}";
                $clinicInstance->clinic_image3_thumb = "{$imagesDirectory}/{$thumbImageNames[$i]}";
            }
            if ($nullImageFiledName[$i] == "clinic_image4"){
                $clinicInstance->clinic_image4 = "{$imagesDirectory}/{$imageNames[$i]}";
                $clinicInstance->clinic_image4_thumb = "{$imagesDirectory}/{$thumbImageNames[$i]}";
            }
            if ($nullImageFiledName[$i] == "clinic_image5"){
                $clinicInstance->clinic_image5 = "{$imagesDirectory}/{$imageNames[$i]}";
                $clinicInstance->clinic_image5_thumb = "{$imagesDirectory}/{$thumbImageNames[$i]}";
            }

        }
        $clinicInstance->save();

    }

    // waste 

    public function settingsSpecializationView(){
        return view('settingsSpecialization');
    }
    public function appointmentListview(){
        return view('appointmentList');
    }
    public function appointmentReportForm(){
        return view('appointmentReport');
    }
    public function clinicReportForm(){
        return view('clinicReport');
    }
    
}
