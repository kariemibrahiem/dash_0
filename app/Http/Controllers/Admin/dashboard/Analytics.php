<?php

namespace App\Http\Controllers\Admin\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Traits\WeatherTrait;
class Analytics extends Controller
{
    use WeatherTrait;
    protected User $user;
    protected Admin $admin;

    public function __construct(User $user, Admin $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }

    public function index()
    {

        $usersCount = $this->user->count();
        $adminsCount = $this->admin->count();

        $data = $this->GetWeather(30.5503, 31.0106);
        // dd($data);

        return view('content.dashboard.dashboards-analytics', compact('usersCount', 'adminsCount' , 'data'));
    }
}
