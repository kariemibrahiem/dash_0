<?php

namespace App\Http\Controllers\Admin\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Family;
use App\Models\Order;
use App\Models\Teacher;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\WeatherTrait;

class Analytics extends Controller
{
        use WeatherTrait;

    protected User $user;
    protected Admin $admin;

    public function __construct(User $user, Admin $admin )
    {
        $this->user = $user;
        $this->admin = $admin;
        
    }

    public function index()
    {

      
        $data = $this->GetWeather(30.5503, 31.0106);

    // end charts
        $usersCount = $this->user->count();
        $adminsCount = $this->admin->count();



        return view('content.dashboard.dashboards-analytics', compact('usersCount' , "data" , 'adminsCount',));
    }
}
