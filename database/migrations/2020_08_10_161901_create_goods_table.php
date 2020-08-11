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
            $table->unsignedInteger('goods_category_id')->comment('分类id，关联goods_categories.id');
            $table->string('name', 100)->comment('名称');
            $table->integer('face_value')->comment('面值');
            $table->tinyInteger('status')->comment('状态：0.禁用 1.正常');
            $table->timestamps();

            $table->index('goods_category_id');
        });

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE goods AUTO_INCREMENT=' . rand(10000, 99999));
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
