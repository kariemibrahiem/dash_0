<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;

class Analytics extends Controller
{
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

        return view('content.dashboard.dashboards-analytics', compact('usersCount', 'adminsCount'));
    }
}
