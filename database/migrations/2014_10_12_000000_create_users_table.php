<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->tinyInteger('status');
            $table->string('remark')->default('')->comment('备注');
            $table->string('secret_id', 100)->nullable()->default(null)->comment('API id');
            $table->string('secret_key', 500)->comment('API密钥');
            $table->tinyInteger('type')->default(1)->comment('用户类型: 1.个人 2.企业');
            $table->string('phone', 20)->comment('手机号码');
            $table->string('real_name', 20)->comment('真实姓名');
            $table->string('id_number', 20)->comment('身份证号');
            $table->string('company', 50)->nullable()->default(null)->comment('企业名');
            $table->string('license', 100)->nullable()->default(null)->comment('营业执照号');
            $table->tinyInteger('auth_status')->default(0)->comment('实名认证状态：0.未认证 1.审核中 2.审核未通过 3.审核通过');
            $table->rememberToken();
            $table->timestamps();

            $table->unique('email');
            $table->unique('secret_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
