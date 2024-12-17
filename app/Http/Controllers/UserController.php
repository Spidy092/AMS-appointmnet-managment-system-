<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AccessGroup;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Rules\StrongPasswordRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{

    public function getSidebarPermissions()
    {
        $user = Auth::user();
        $access_group_table = Constants::DB_PREFIX . '_access_group_permissions';
        $permissions = DB::table($access_group_table)
            ->join('bah_dynamic_links', 'bah_access_group_permissions.link_id', '=', 'bah_dynamic_links.id')
            ->where('access_group_id', $user->access_group_id)
            ->where('view', 1)
            ->select('bah_dynamic_links.id', 'bah_dynamic_links.link_name', 'bah_dynamic_links.link_href', 'bah_dynamic_links.parent_id', 'bah_dynamic_links.level', 'bah_dynamic_links.icons')
            ->get();

        $groupedPermissions = $permissions->groupBy('parent_id');

        return view('admin.dashboard', compact('groupedPermissions'));
    }

    protected function generateStrongPassword($length = 12)
    {
        $uppercase = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $lowercase = str_split('abcdefghijklmnopqrstuvwxyz');
        $numbers = str_split('0123456789');
        $specialCharacters = str_split('!@#$%^&*()-_=+[]{}|;:,.<>?');

        $password = [
            $uppercase[array_rand($uppercase)],
            $lowercase[array_rand($lowercase)],
            $numbers[array_rand($numbers)],
            $specialCharacters[array_rand($specialCharacters)]
        ];

        $allCharacters = array_merge($uppercase, $lowercase, $numbers, $specialCharacters);
        for ($i = count($password); $i < $length; $i++) {
            $password[] = $allCharacters[array_rand($allCharacters)];
        }

        shuffle($password);

        return implode('', $password);
    }

    public function profileView(){
        $uid = session()->get('user_id');
        if(User::where('id',$uid)->exists()){
            $user = User::find($uid);
            return view('profile',compact('user'));
        }else{
            return redirect('dashboard')->with('error', 'No Such User Found') ;
        }
    }

    public function changepass() {
        $userId = session()->get('user_id');
        $user = User::find($userId);
        return view('changePassword', ['user' => $user]);
    }

    public function updatePassword(Request $request) {
        $incomingFields = $request->validate([
            'OldPassword' => 'required',
            'NewPassword' => ['required','min:5', new StrongPasswordRule],
            'ConfirmPassword' => ['required','min:5', new StrongPasswordRule]
        ],[
            'OldPassword.required' => 'Old Password is required',
            'NewPassword.required' => 'New Password is required',
            'ConfirmPassword.email' => 'Confirm Password is required'
        ]);

        // $current_user = Auth::user();

        // if (Hash::check($incomingFields['OldPassword'], $current_user->password)){
        //     if($incomingFields['NewPassword'] == $incomingFields['ConfirmPassword']){
        //         $new_pass = bcrypt($incomingFields['NewPassword']);
        //         $current_user->password = $new_pass;
        //         $current_user->modified_by = Auth::user()->id;
        //         $current_user->ip_modified = request()->ip();
        //         $current_user->date_modified = now();
        //         $current_user->save();
        //         return redirect()->back()->with('success', 'Password successfully updated') ;
        //     }else{
        //         return redirect()->back()->with('error', 'New Password and Confirm Password do not match') ;
        //     }
        // }

        $current_user = Auth::user();

        if ($current_user instanceof User) {
            if (Hash::check($incomingFields['OldPassword'], $current_user->password)) {
                if ($incomingFields['NewPassword'] == $incomingFields['ConfirmPassword']) {
                    $new_pass = bcrypt($incomingFields['NewPassword']);
                    $current_user->password = $new_pass;
                    $current_user->modified_by = Auth::user()->id;
                    $current_user->ip_modified = request()->ip();
                    $current_user->date_modified = now();
                    $current_user->save(); // Should work now

                    return redirect()->back()->with('success', 'Password successfully updated');
                } else {
                    return redirect()->back()->with('error', 'New Password and Confirm Password do not match');
                }
            }
        }

        else{
            return redirect()->back()->with('error', 'Old password does not match') ;
        }
    }

    public function updateProfile(Request $request){
        $incomingFields = $request->validate([
            'txtName' => ['required' ,'min:3', 'regex:/^[a-zA-Z\s]+$/'] ,
            'txtEmail' => ['required' , 'email'],
            'txtPhone' => ['required', 'numeric'],
        ],[
            'txtName.required' => 'The name field is required',
            'txtName.min' => 'The name field must be at least 3 characters.',
            'txtEmail.required' => 'The email field is required',
            'txtEmail.email' => 'The email field must be a valid email address.',
        ]);
        $userId = session()->get('user_id');
        $user = User::find($userId);
        $user->name = $incomingFields['txtName'];
        if($incomingFields['txtEmail'] != $user->email){
            $user->email = $incomingFields['txtEmail'];
        }
        $user->phone_no = $incomingFields['txtPhone'];
        $user->modified_by = Auth::user()->id;
        $user->ip_modified = request()->ip();
        $user->date_modified = now();
        $user->save();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function showAddUserForm()
    {
        // $accessGroups = AccessGroup::all();
        return view('user');
        // return view('user', compact('accessGroups'));
    }


    public function addUserFunction(Request $request)
    {
        Log::info('Function called');

        $userTable = Constants::DB_PREFIX . '_users';



        // Validation
        $request->validate([
            'txtName' => 'required|max:60|regex:/^[a-zA-Z\s]+$/',
            'txtEmail' => 'required|email|max:60|unique:'.$userTable.',email|regex:/^[A-Za-z]+[A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            'txtPhone' => 'required|max:60|unique:'.$userTable.',phone_no',
            // 'access_group_id' => 'required|exists:bah_access_groups,id',
        ]);

        Log::info('Validation passed');

        try {
            // Generate password
            $password = $this->generateStrongPassword();
            Log::info('Password generated');

            // Create user
            $user = User::create([
                'name' => $request->txtName,
                'email' => $request->txtEmail,
                'phone_no' => $request->txtPhone,
                'password' =>  bcrypt($password),
                'status' => 1,
                'access_group_id' => $request->access_group_id,
                'ip_added' => $request->ip(),
                'added_by' => 1,
                'http_user_agent' => $request->userAgent(),
            ]);

            Log::info('User created: ', ['user_id' => $user->id]);

            // Send email
            Mail::send('emailTemplates.user-password', [
                'name' => $request->txtName,
                'websitePrefix' => Constants::URL_PREFIX['GLOBAL'],
                'applicationPrefix' => Constants::URL_PREFIX['APP'],
                'password' => $password,
                'username' => $request->txtEmail,
            ], function ($message) use ($request) {
                $message->to($request->txtEmail);
                $message->subject('Reset Password');
            });

            Log::info('Email sent to: ' . $request->txtEmail);

            return redirect()->back()->with('success', 'User added successfully and password sent via email.');
        } catch (\Exception $e) {
            Log::error('Error adding user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while adding the user.');
        }
    }


    public function getUserData(Request $request) {
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
            $columnName = 'date_created';
        }

        $total = User::count();
        $userQuery = User::orderBy($columnName == 'custom_id' ? 'id' : $columnName, $columnSortOrder);
        if (!empty($searchValue)) {
            $userQuery->where(function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhere('date_created', 'LIKE', '%' . $searchValue . '%');
            });
        }
        $totalFilter = $userQuery->count();
        $userData = $userQuery->skip($start)->take($rowPerPage)->get();

        // Add custom serial number and action buttons
        $dataWithActions = [];
        foreach ($userData as $item) {
            $id = $item->id;
            $custom_sl = "U0012" . $id;
            $item->custom_id = $custom_sl; // Add a new field for custom serial number
            $suspend = $item->status;

            $item->date_addeda = Carbon::parse($item->date_created)->format('d-m-Y');

            // $accessGroup = AccessGroup::where('id', $item->access_group_id)->first();

            // Check if access group exists
            // if ($accessGroup) {
            //     $item->role = $accessGroup->name;
            // } else {
            //     $item->role = 'No Role';
            // }

            if ($suspend == '0') {
                $suspend_state = 'activate';
                $suspend_color = '#808080';
            } else {
                $suspend_state = 'suspend';
                $suspend_color = '#47a46e';
            }

            $encrypted_id = Crypt::encrypt($id);

            $UserExists = false;

            if ( User::where('added_by', $id)->exists()) {
                $UserExists = true;
            }

            $item->action ="";
            if (Auth::id() == $id) {
                $item->action ="";
            }else{
                $item->action = "<div class='user-action-btns'>
                        <button id='{$suspend_state}' value='{$id}' title='{$suspend_state}'>
                            <i class='fa-solid fa-ban' style='color: {$suspend_color};'></i>
                        </button>
                        <button id='edit' value='{$encrypted_id}' title='edit'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </button>";
                if(!$UserExists){
                    $item->action .=  "<button id='delete' value='{$id}' title='delete'>
                                            <i class='fa-solid fa-trash'></i>
                                        </button>
                                     </div>";
            }

            }

            $dataWithActions[] = $item;
        }

        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $totalFilter,
            "data" => $dataWithActions
        );

        return response()->json($response);
    }

    public function userActionFunc(Request $request) {
        $action = $request->currentAction;
        $userId = $request->currentUserId;
        $user = User::find($userId);

        if ($user) {
            if ($action == 'activate') {
                $user->status = '1';
                $user->modified_by = Auth::user()->id;
                $user->ip_modified = request()->ip();
                $user->date_modified = now();
                $user->save();
                return response()->json('User status updated to active.');
            } elseif ($action == 'suspend') {
                $user->status = '0';
                $user->modified_by = Auth::user()->id;
                $user->ip_modified = request()->ip();
                $user->date_modified = now();
                $user->save();
                return response()->json('User status updated to suspend.');
            }
        } else {
            return response()->json('User not found.', 404);
        }
    }

    public function userDeleteFunc(Request $request) {
        $userId = $request->currentUserId;
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            return response()->json('User successfully deleted.');
        } else {
            return response()->json('User not found.', 404);
        }
    }

    public function manageUser(Request $request) {
        $userId = $request->session()->get('user_id');
        $user = User::find($userId);
        return view('manageUser', ['user' => $user]);
    }

    public function editUser($encryptedId)
    {
        Log::info('Encrypted ID: ' . $encryptedId);

        try {
            $userId = Crypt::decrypt($encryptedId);
            Log::info('Decrypted ID: ' . $userId);
        } catch (DecryptException $e) {
            Log::error('Decryption failed: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Invalid user ID');
        }

        // Prevent user from editing themselves
        if (Auth::id() == $userId) {
            return redirect()->back()->with('error', 'You do not have permission to edit yourself.');
        }

        if (User::where('id', $userId)->exists()) {
            $user = User::find($userId);
            // $accessGroups = AccessGroup::all();
            return view('user', compact('user', 'accessGroups'));
        } else {
            return redirect()->route('home')->with('error', 'No such user found');
        }
    }


    public function editUserfunc(Request $request, $encryptedId)
    {
        // Decrypt the encrypted user ID
        try {
            $user_id = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            return redirect()->route('home')->with('error', 'Invalid user ID');
        }

        // Prevent user from editing themselves
        if (Auth::id() == $user_id) {
            return redirect()->back()->with('error', 'You do not have permission to edit yourself.');
        }

        // Validate the incoming request
        $request->validate([
            'txtName' => 'required|max:60|regex:/^[a-zA-Z\s]+$/',
            'txtEmail' => 'required|email|max:60',
            'txtPhone' => 'required|max:60',
            'access_group_id' => 'required|exists:bah_access_groups,id',
        ]);

        // Check if user exists
        $user = User::find($user_id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Update user details
        $user->name = $request->txtName;

        // If the email is being changed, check for uniqueness
        if ($request->txtEmail !== $user->email) {
            if (User::where('email', $request->txtEmail)->exists()) {
                return redirect()->back()->with('error', 'Email already exists');
            }
            $user->email = $request->txtEmail;
        }

        // Update the phone number and access group
        $user->phone_no = $request->txtPhone;
        $user->access_group_id = $request->access_group_id;
        $user->modified_by = Auth::user()->id;
        $user->ip_modified = request()->ip();
        $user->date_modified = now();
        $user->save();

        return redirect()->back()->with('success', 'User successfully updated');
    }

}



