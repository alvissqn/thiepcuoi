<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng trung gian 1 chức vụ có nhiều thông báo
        Schema::create('role_notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id'); // Id chức vụ
            $table->bigInteger('notification_id')->nullable(); // Id thông báo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_notifications');
    }
}
