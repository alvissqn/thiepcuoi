<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng thông báo đến người dùng
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề thông báo
            $table->text('content'); // Nội dung thông báo
            $table->bigInteger('created_user_id')->nullable(); // Id người tạo
            $table->integer('type')->default(0); // Phân loại thông báo
            $table->timestamp('expired')->nullable(); // Ngày hết hạn
            $table->tinyInteger('send_mail')->default(0); // Gửi mail hay không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
