<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddMoneyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_add_money_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no', 30)->comment('编号');
            $table->string('external_order_id', 50)->nullable()->default(null)->comment('外部订单号');
            $table->tinyInteger('pay_type')->default(1)->comment('支付类型: 1.线下加款 2.支付宝');
            $table->string('receive_account', 100)->default(1)->comment('收款账号');
            $table->tinyInteger('status')->comment('状态：1.创建加款单 2.财务审核 3.财务拒绝');
            $table->decimal('fee', 10, 2)->comment('金额');
            $table->unsignedInteger('user_id')->comment('加款用户id，关联users.id');
            $table->unsignedInteger('created_by')->comment('创建人，关联admins.id');
            $table->unsignedInteger('auditd_by')->nullable()->comment('审核人，关联admins.id');
            $table->datetime('auditd_at')->nullable()->comment('审核人，关联admins.id');
            $table->string('remark', 200)->comment('备注说明');
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
        Schema::dropIfExists('user_add_money_orders');
    }
}
