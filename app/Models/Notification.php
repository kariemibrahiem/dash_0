<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    // الجدول المرتبط بالـ Model
    protected $table = 'notifications';

    // الحقول اللي ممكن تتعبأ مباشرة (Mass Assignment)
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    /**
     * علاقة الـ Notification بالمستخدم
     * كل إشعار تابع لمستخدم واحد
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
