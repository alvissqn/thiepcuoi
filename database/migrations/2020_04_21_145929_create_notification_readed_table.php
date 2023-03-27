<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationReadedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng trung gian các thông báo đã đọc
        Schema::create('notification_readed', function (Blueprint $table) {
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
        Schema::dropIfExists('notification_readed');
    }
}
