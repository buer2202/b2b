<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAuthRoleRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_auth_role_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_auth_role_id')->comment('角色id，关联admin_auth_roles.id');
            $table->unsignedInteger('admin_auth_rule_id')->comment('权限id，关联admin_auth_rules.id');
            $table->index('admin_auth_role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_auth_role_rules');
    }
}
