<?php
/*
 * Bảng lưu các dữ liệu thiết lập web
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->string('name')->unique(); // Tên option
            $table->longText('value')->nullable(); // Giá trị
            $table->string('group_name')->nullable(); // Tên nhóm
            $table->tinyInteger('is_array'); // Dữ liệu là array hay không
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
