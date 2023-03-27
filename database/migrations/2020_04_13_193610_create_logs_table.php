<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng chứa lịch sử thao tác
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // Id người thao tác
            $table->string('category'); // Chuyên mục thao tác
            $table->string('action'); // Tiêu đề thao tác
            $table->string('action_color'); // Màu loại thao tác
            $table->string('action_value')->nullable(); // Thông số tiêu đề
            $table->text('old_value'); // Giá trị dữ liệu cũ
            $table->text('new_value'); // Giá trị giữ liệu mới
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
        Schema::dropIfExists('logs');
    }
}
