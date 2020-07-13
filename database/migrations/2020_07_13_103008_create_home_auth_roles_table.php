<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeAuthRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_auth_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('角色名称');
            $table->tinyInteger('status')->comment('状态: 0.禁用, 1.正常');
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
        Schema::dropIfExists('home_auth_roles');
    }
}
