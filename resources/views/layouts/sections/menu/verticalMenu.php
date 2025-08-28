<?php

return [
    (object)[
        'name' => 'Dashboard',
        'icon' => 'bx bx-home-circle',
        'url' => 'dashboard-analytics',
        "permissions" => "dashboard",
        'slug' => 'dashboard',
    ],
    (object)[
        'menuHeader' => 'User Management',
    ],
    (object)[
        'name' => 'Users',
        'icon' => 'bx bx-user',
        'url' => 'users.index',
        "permissions" => "user_read",
        'slug' => 'users',
        'submenu' => [
            (object)[
                'name' => 'All Users',
                'url' => 'users',
                "permissions" => "user_read",
                'slug' => 'users',
            ],
            (object)[
                'name' => 'Create User',
                'url' => 'users/create',
                "permissions" => "user_create",
                'slug' => 'users.create',
            ]
        ]
    ],
    (object)[
        'menuHeader' => 'Admin Management',
    ],
    (object)[
        'name' => 'Admin',
        'icon' => 'bx bx-user',
        'url' => 'admins.index',
        "permissions" => "admins_read",
        'slug' => 'admins',
        'submenu' => [
            (object)[
                'name' => 'All Admins',
                'url' => 'admins',
                "permissions" => "admins_read",
                'slug' => 'admins',
            ],
            (object)[
                'name' => 'Create Admin',
                'url' => 'admins/create',
                "permissions" => "admins_create",
                'slug' => 'admins.create',
            ]
        ]
    ]
];