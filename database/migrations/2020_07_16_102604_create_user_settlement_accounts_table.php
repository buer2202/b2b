<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettlementAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settlement_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('user.id');
            $table->tinyInteger('type')->default(1)->comment('账号类型: 1.支付宝 2.银行卡');
            $table->string('trustee', 50)->default('')->comment('托管方（支付宝或银行名）');
            $table->string('account', 25)->comment('收款账号');
            $table->string('name', 50)->comment('收款人姓名');
            $table->tinyInteger('acc_type')->default(2)->comment('对公对私：1.对私 2.对公');
            $table->tinyInteger('status')->default(1)->comment('状态：1.申请 2.正常 3.禁用');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settlement_accounts');
    }
}
