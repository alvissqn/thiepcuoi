<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng trung gian 1 user có nhiều thông báo
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // Id người dùng
            $table->bigInteger('notification_id'); // Id thông báo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifications');
    }
}
