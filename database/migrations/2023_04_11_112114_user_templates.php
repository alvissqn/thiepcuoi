<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_templates', function (Blueprint $table) {
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
        //
    }
}
