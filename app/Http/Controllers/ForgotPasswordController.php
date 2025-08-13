<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        $otp = rand(100000, 999999);

        Admin::where('email', $request->email)->update(['otp' => $otp]);

        try {
            Mail::send("content.mails.forgotPassword", ["otp" => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            toastr()->success('If the email exists, you will receive an email with the OTP.');
        } catch (\Exception $e) {
            toastr()->error('Unable to send OTP email. Please try again later.');
            return redirect()->back();
        }

        return redirect()->route("show-check-otp", ['email' => $request->email]);
    }

    public function showCheckOtp(Request $request)
    {
        return view('content/authentications/otp_check', ['email' => $request->email]);
    }

    public function CheckOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && hash_equals((string) $admin->otp, (string) $request->otp)) {
            $admin->update([
                'password' => Hash::make($request->password),
                'otp' => null
            ]);

            toastr()->success(trns('Password reset successfully. Please log in with your new password.'));
            return redirect("/"); 
        }

        toastr()->error(trns('Invalid OTP. Please try again.'));
        return redirect()->back()->withErrors(['otp' => trns('Invalid OTP')]);
    }

}
