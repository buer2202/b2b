<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAuthUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_auth_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_id')->comment('管理员id，关联 admin.id');
            $table->unsignedInteger('admin_auth_role_id')->comment('角色id，关联admin_auth_roles.id');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_auth_user_roles');
    }
}
