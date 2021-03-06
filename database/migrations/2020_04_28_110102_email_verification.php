<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailVerification extends Migration
{
    public function up()
    {
        Schema::create('email_verification', function (Blueprint $table) {
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
        Schema::drop('email_verification');
    }
}
