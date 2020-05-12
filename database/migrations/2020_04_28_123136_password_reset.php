<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PasswordReset extends Migration
{
    public function up()
    {
        Schema::create('password_reset', function (Blueprint $table) {
            $table->bigInteger('uid');
            $table->string('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_reset');
    }
}
