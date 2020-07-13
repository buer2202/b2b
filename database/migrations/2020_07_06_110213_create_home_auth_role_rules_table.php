<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeAuthRoleRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_auth_role_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('home_auth_role_id')->comment('角色id，关联home_auth_roles.id');
            $table->unsignedInteger('home_auth_rule_id')->comment('权限id，关联home_auth_rules.id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_auth_role_rules');
    }
}
