<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceGroupGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_group_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('price_group_id')->comment('售价组ID，关联 price_groups.id');
            $table->unsignedInteger('goods_id')->comment('商品ID');
            $table->decimal('cost_price', 11, 4)->default(0)->comment('成本价');
            $table->decimal('sales_price', 11, 4)->default(0)->comment('销售价');
            $table->timestamps();

            $table->unique(['price_group_id', 'goods_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_group_goods');
    }
}
