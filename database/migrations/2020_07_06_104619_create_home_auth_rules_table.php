<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeAuthRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_auth_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('规则唯一标识');
            $table->string('title', 100)->comment('规则中文名称');
            $table->tinyInteger('status')->comment('状态: 0.禁用, 1.正常');
            $table->string('group', 100)->comment('分组名');
            $table->string('group_name', 100)->comment('分组中文名');
            $table->tinyInteger('menu_show')->comment('是否菜单显示:0.不显示 1.显示');
            $table->tinyInteger('menu_click')->comment('是否可点击:0.不可点击 1.可点击');
            $table->integer('sortord')->comment('排序');
            $table->timestamps();
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_auth_rules');
    }
}
