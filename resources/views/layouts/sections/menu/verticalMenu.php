<?php

return [
    (object)[
        'name' => 'Dashboard',
        'icon' => 'bx bx-home-circle',
        'url' => 'dashboard-analytics',
        'slug' => 'dashboard',
    ],
    (object)[
        'menuHeader' => 'User Management',
    ],
    (object)[
        'name' => 'Users',
        'icon' => 'bx bx-user',
        'url' => 'dashboard-analytics',
        'slug' => 'users',
        'submenu' => [
            (object)[
                'name' => 'All Users',
                'url' => 'dashboard-analytics',
                'slug' => 'users.index',
            ],
            (object)[
                'name' => 'Create User',
                'url' => 'dashboard-analytics',
                'slug' => 'users.create',
            ]
        ]
    ]
];
