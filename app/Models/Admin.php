<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'code',
        'image',
        'phone',
    ];

    protected $guard_name = 'admin';

    // تشفير الباسورد تلقائي عند التحديث
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
