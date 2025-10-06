<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthUserController extends Controller
{

    protected User $user;
    protected UserMail $userMail;

    public function __construct(User $user, UserMail $userMail)
    {
        $this->user = $user;
        $this->userMail = $userMail;
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email"    => "required|email",
            "password" => "required"   // use "password" not "pass"
        ]);

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('API Token')->plainTextToken;
            return $this->successResponse(['token' => "Bearer ". $token], 'Login successful');
        }

        return $this->errorResponse('Invalid credentials', 401);
    }

    public function regist(Request $request)
    {
        try {
            $this->validate($request, [
                "email" => "required|email|unique:users,email"
            ]);

            $user = $this->user->where('email', $request->email)->first();

            if ($user) {
                return $this->errorResponse('User already exists', 409);
            }

            $otp = rand(100000, 999999);

            $this->userMail->updateOrCreate(
                ["email" => $request->email],
                ["otp" => $otp, "verified_at" => null, "exp_date" => now()->addMinutes(10)]
            );

            Mail::send("content.mails.sendOtp", ["otp" => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Regist to traders platform');
            });

            return $this->successResponse(['message' => 'OTP sent to email'], 'Registration initiated');
        } catch (Exception $e) {
            return $this->errorResponse("Registration failed: " . $e->getMessage(), 500);
        }
    }

    public function checkOtp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $emailRecord = $this->userMail->where('email', $request->email)->first();

        if (!$emailRecord) {
            return $this->errorResponse('Email not found', 404);
        }

        if ($emailRecord->otp !== $request->otp) {
            return $this->errorResponse('Invalid OTP', 400);
        }

        if ($emailRecord->exp_date < now()) {
            return $this->errorResponse('OTP has expired', 400);
        }

        $code = rand(100000, 999999) . Str::random(5);
        $user = $this->user->create([
            "name" => $request->name ?? 'User',
            "email_id" => $emailRecord->id,
            "password" => $request->password,
            "code" => $code,
            "email" => $request->email,
            "status" => 0,
            "image" => $request->image ?? null,
        ]);

        $emailRecord->update([
            "verified_at" => now(),
        ]);

        return $this->successResponse(['user' => $user], 'User registered successfully');
    }

}
