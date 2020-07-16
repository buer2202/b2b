<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWithdrawOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_withdraw_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no', 30)->comment('提现单号');
            $table->unsignedInteger('user_id')->comment('创建者');
            $table->string('trustee', 50)->default('')->comment('托管方（支付宝或银行名）');
            $table->string('receive_account', 100)->default(1)->comment('收钱账号');
            $table->tinyInteger('receive_account_type')->default(1)->comment('收款账号类型: 1.支付宝 2.银行卡');
            $table->string('name', 50)->comment('收款人姓名');
            $table->tinyInteger('acc_type')->default(2)->comment('对公对私 1.对私 2.对公');
            $table->tinyInteger('status')->comment('状态：1.申请 2.成功 3.拒绝');
            $table->decimal('fee', 10, 2)->comment('申请提现金额');

            $table->unsignedInteger('department_auditor')->default(0)->comment('部门审核人 admin.id');
            $table->datetime('department_audit_at')->nullable()->default(null)->comment('部门审核时间');
            $table->unsignedInteger('finance_auditor')->default(0)->comment('财务审核人 admin.id');
            $table->datetime('finance_audit_at')->nullable()->default(null)->comment('财务审核时间');
            $table->tinyInteger('pay_type')->default(0)->comment('支付类型:0.未确定 1.线下打款 2.线上打款');
            $table->string('external_order_id', 50)->nullable()->default(null)->comment('外部订单号');
            $table->decimal('real_fee', 11, 2)->nullable()->default(null)->comment('实际打款金额');
            $table->string('from_account', 100)->default(1)->comment('出钱账号');
            $table->string('remark', 200)->default('')->comment('备注说明');
            $table->datetime('created_at');
            $table->datetime('updated_at');

            $table->unique('no');
            $table->unique('external_order_id');
            $table->index('status');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_withdraw_orders');
    }
}
