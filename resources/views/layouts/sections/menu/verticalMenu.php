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
        'url' => 'users.index',
        'slug' => 'users',
        'submenu' => [
            (object)[
                'name' => 'All Users',
                'url' => 'users',
                'slug' => 'users',
            ],
            (object)[
                'name' => 'Create User',
                'url' => 'users.create',
                'slug' => 'users.create',
            ]
        ]
    ]
];
