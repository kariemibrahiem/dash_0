<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إنشاء جدول notifications
        Schema::create('notifications', function (Blueprint $table) {

            $table->id(); // المفتاح الأساسي للجدول

            $table->unsignedBigInteger('user_id')->index();
            // ربط الإشعار بالمستخدم المستهدف
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade'); 
            // لو المستخدم اتحذف، الإشعارات تتحذف تلقائي

            $table->string('title'); 
            // عنوان الإشعار

            $table->text('message'); 
            // محتوى الإشعار

            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info'); 
            // نوع الإشعار

            $table->boolean('is_read')->default(false); 
            // هل المستخدم قرأ الإشعار أم لا

            $table->timestamps(); // created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الجدول عند الرجوع عن الميجريشن
        Schema::dropIfExists('notifications');
    }
};
