<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no', 20)->comment('编码');
            $table->unsignedInteger('goods_category_id')->comment('分类id，关联goods_categories.id');
            $table->string('name', 100)->comment('名称');
            $table->integer('parvalue')->comment('面值');
            $table->tinyInteger('status')->comment('状态：0.禁用 1.正常');
            $table->timestamps();

            $table->unique('no');
            $table->index('goods_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
