<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Hash;


class AuthService
{
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login( $request)
    {
        try {
            $data = $request->validate(
                [
                    'email_username' => 'required|string',
                    'password' => 'required|string',
                    'remember' => 'nullable',
                ],
                [
                    'email_username.required' => 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم',
                    'password.required' => 'يرجى إدخال كلمة المرور',
                ]
            );

            $loginInput = $data['email_username'];
            $password   = $data['password'];
            $remember   = !empty($data['remember']);

            $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
            // dd($loginInput , $password , $fieldType);
            // dd(Auth::guard('admin')->attempt([$fieldType => $loginInput, 'password' => $password], $remember));

            if (Auth::guard('admin')->attempt([$fieldType => $loginInput, 'password' => $password], $remember)) {
                return response()->json([
                    'status' => 200,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'redirect' => route('dashboard-analytics')
                ]);
            }


            return response()->json([
                'status' => 401,
                'message' => 'خطأ في البريد الإلكتروني/اسم المستخدم أو كلمة المرور'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'حدث خطأ أثناء تسجيل الدخول',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function logout()
    {
        Auth::guard('admin')->logout();
        toastr()->info(trns("logout successfully"));
        return redirect()->route('dashboard-analytics');
    }
}
