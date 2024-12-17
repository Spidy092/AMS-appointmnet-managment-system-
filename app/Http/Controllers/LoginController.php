<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Constants\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function loginView(){
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function home() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    public function authenticate(Request $request)
    {

        $attemptTable = Constants::DB_PREFIX . '_login_attempts';
        $userTable = Constants::DB_PREFIX . '_users';


        $credentials = $request->validate([
            'loginName' => ['required', 'email'],
            'loginPassword' => ['required'],
        ]);

        $email = $credentials['loginName'];
        $password = $credentials['loginPassword'];
        $ipAddress = $request->ip();
        $currentTime = Carbon::now();

        $loginAttempt = DB::table($attemptTable)
            ->where('attempt_user', $email)
            ->where('attempt_ip', $ipAddress)
            ->first();

        if ($loginAttempt && $loginAttempt->attempt_count >= 5 && $loginAttempt->attempt_time > $currentTime->subHours(5)) {
            return redirect()->back()->with('error' , 'Too many login attempts. Please try again in 5 hours.')->onlyInput('email');
        }

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
        // if (Auth::attempt(['email' => $email, 'password' => $password, 'status' => '1'])) {

            DB::table($attemptTable)->where('attempt_user', $email)->where('attempt_ip', $ipAddress)->delete();

            $request->session()->regenerate();
            $request->session()->put('user_id', Auth::id());

            return redirect()->intended('dashboard')->with('success', 'You are logged in');
        } else {
            if ($loginAttempt) {
                DB::table($attemptTable)
                    ->where('attempt_user', $email)
                    ->where('attempt_ip', $ipAddress)
                    ->update([
                        'attempt_count' => $loginAttempt->attempt_count + 1,
                        'attempt_time' => $currentTime,
                    ]);
            } else {
                DB::table($attemptTable)->insert([
                    'attempt_ip' => $ipAddress,
                    'attempt_user' => $email,
                    'attempt_count' => 1,
                    'attempt_time' => $currentTime,
                ]);
            }

            // return redirect()->back()->with('error', 'The provided credentials do not match our records.')->onlyInput('email');
            throw ValidationException::withMessages([
                "loginName" => "The Provided Credentials are incorrect",
            ]);
        }
    }


    public function logout() {
        Auth::logout();
        session()->forget('user_id');

        request()->session()->regenerateToken();
        request()->session()->invalidate();


        return redirect()->route('login');
    }


    public function checkSession(Request $request)
    {
        $authenticated = $request->session()->has('user_id');

        return response()->json(['authenticated' => $authenticated]);
    }
}
