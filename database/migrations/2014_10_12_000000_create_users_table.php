<?php
/*
 * Tạo bảng chứa thông tin người dùng
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /*
     * Lệnh tạo
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên
            $table->string('email')->unique(); // Email
            $table->string('phone_number')->nullable(); // Số điện thoại
            $table->string('password'); // Mật khẩu
            $table->tinyInteger('gender')->default(0); // Giới tính
            $table->integer('role_id')->default(2); // Id chức vụ
            $table->tinyInteger('status')->default(1); // Trạng thái kích hoạt tài khoản
            $table->timestamps();
        });
    }

    /*
     * Lệnh khôi phục
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
