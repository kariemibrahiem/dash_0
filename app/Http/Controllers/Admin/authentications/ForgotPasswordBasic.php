<?php

namespace App\Http\Controllers\Admin\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-forgot-password-basic');
  }
}
