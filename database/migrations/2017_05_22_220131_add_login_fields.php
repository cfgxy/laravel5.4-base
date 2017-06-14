<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoginFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('users', function (Blueprint $table) {
            $table->char('role', 16);
            $table->integer('avatar_ver')->default(0);
        });

        \Schema::create('user_login_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid');
            $table->char('ip', 40);
            $table->timestamp('logon_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        \Schema::drop('user_login_logs');
    }
}
