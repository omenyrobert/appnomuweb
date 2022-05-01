<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLogIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('UserLogins', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('device_fingerprint');
            $table->integer('last_login');
            $table->string('Device');
            $table->string('Device_Ip');
            $table->string('status');
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
        //
        
        Schema::dropIfExists('UserLogins');
    }
}
