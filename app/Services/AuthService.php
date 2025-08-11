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

    public function login($request)
    {
        try{
            $data = $request->validate(
                [
                    'email-username' => 'required|string',
                    'password' => 'required|string',
                    'remeber' => 'nullable',
                ],
                [
                    'email-username.required' => 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم',
                    'password.required' => 'يرجى إدخال كلمة المرور',
                ]
            );

            $loginInput = $data['email-username'];
            $password = $data['password'];

            $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

            $credentials = [
                $fieldType => $loginInput,
                'password' => $password,
            ];
            $admin = Admin::where("email" , $data['email-username'])->first();
        

            if ($admin && Hash::check($password, $admin->password)) {
                Auth::guard('admin')->login($admin, !empty($data['remember']));

                toastr()->success("login successfully");
                return redirect()->route('dashboard-analytics');
            }
            toastr()->error(trns("error in login field"));
            return back();
        }catch(Exception $e){
            toastr()->error("login field");
            return redirect()->back();
        }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        toastr()->info(trns("logout successfully"));
        return redirect()->route('dashboard-analytics');
    }
}
