<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tạo bảng lưu trữ thông tin của từng tài khoản
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // Id tài khoản
            $table->string('key'); // Key
            $table->text('value')->nullable(); // Giá trị
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_data');
    }
}
