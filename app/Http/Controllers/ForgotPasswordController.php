<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Str;
use App\Constants\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function forgotPasswordView(){
        return view("forgotPassword");
    }

    public function forgotPasswordEmailFunction(Request $request)
    {
        $webPrefix = Constants::URL_PREFIX['GLOBAL'];
        $appPrefix = Constants::URL_PREFIX['APP'];
        $passwordResetTable = Constants::DB_PREFIX . '_password_reset_tokens';
        $userTable = Constants::DB_PREFIX . '_users';

        $request->validate([
            "txtEmail" => ["required", "email", "exists:$userTable,email"],
        ], [
            'txtEmail.exists' => 'The entered email is not valid.',
            'txtEmail.required' => 'The email field is required.',
        ]);

        $token = Str::random(64);

        $forgotPass = DB::table($passwordResetTable)->where('email', $request->txtEmail)->first();

        if ($forgotPass) {
            DB::table($passwordResetTable)->where('email', $request->txtEmail)->update([
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        } else {
            DB::table($passwordResetTable)->insert([
                'email' => $request->txtEmail,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        }

        $user = User::where('email', $request->txtEmail)->first();
        $user_name = $user->name;

        Mail::send('emailTemplates.forgotPasswordEmail', [
            'token' => $token,
            'name' => $user_name,
            'websitePrefix' => $webPrefix,
            'applicationPrefix' => $appPrefix,
            'username' => $request->txtEmail
        ], function ($message) use ($request) {
            $message->to($request->txtEmail);
            $message->subject('Reset Password');
        });

        return redirect('forgotPassword')->with('success', 'We have sent an email to reset the password.');
    }

    public function resetPasswordEmail($token)
    {
        $passwordResetTable = Constants::DB_PREFIX . '_password_reset_tokens';
        $resetToken = DB::table($passwordResetTable)->where('token', $token)->first();

        if (!$resetToken) {
            return redirect()->route('login')->with('error', 'Invalid or expired token. Please try again.');
        }

        $tokenCreationTime = Carbon::parse($resetToken->created_at);
        $now = Carbon::now();

        if ($tokenCreationTime->diffInMinutes($now) > 60) {
            DB::table($passwordResetTable)->where('token', $token)->delete();
 
            return redirect()->route('login')->with('error', 'The reset link has expired. Please try again.');
        }

        return view('resetPassword', compact('token'));
    }

    public function resetPasswordFunc(Request $request)
    {
        $passwordResetTable = Constants::DB_PREFIX . '_password_reset_tokens';
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);

        $updatePassword = DB::table($passwordResetTable)
            ->where('token', $request->token)
            ->first();

        Log::info('Password Reset Token Record: ', (array) $updatePassword);

        if (!$updatePassword) {
            return redirect()->back()->with('error', 'Invalid token');
        }

        $tokenCreationTime = Carbon::parse($updatePassword->created_at);
        $now = Carbon::now();

        if ($tokenCreationTime->diffInMinutes($now) > 60) {
            DB::table($passwordResetTable)->where('email', $updatePassword->email)->delete();

            return redirect()->route('login')->with('error', 'The reset link has expired. Please try again.');
        }

        User::where('email', $updatePassword->email)->update(['password' => bcrypt($request->password)]);

        DB::table($passwordResetTable)->where('email', $updatePassword->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successful');
    }

}

