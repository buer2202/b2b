<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_url', 500)->comment('回调网址');
            $table->text('request_data')->comment('请求数据');
            $table->text('data_format')->comment('数据格式');
            $table->integer('request_number')->comment('最多请求次数');
            $table->integer('request_times')->default(0)->comment('已请求次数');
            $table->tinyInteger('status')->default(1)->comment('状态: 1.处理中 2.已结束');
            $table->integer('last_http_code')->nullable()->default(null)->comment('最后响应状态码');
            $table->text('last_response')->nullable()->default(null)->comment('最后响应内容');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
