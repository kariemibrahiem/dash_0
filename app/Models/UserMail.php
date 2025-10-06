<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMail extends Model
{
    protected $fillable = [
        "email",
        "otp",
        "verified_at",
        "exp_date"
    ];
}
