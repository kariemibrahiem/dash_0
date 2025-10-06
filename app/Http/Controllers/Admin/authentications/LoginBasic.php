<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginBasic extends Controller
{
  public function __construct(protected AuthService $service){}
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function login(Request $request){
    return $this->service->login($request);
  }

  public function logout(){
    return $this->service->logout();
  }
}
