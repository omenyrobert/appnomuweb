<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Withdraws extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('Withdraws', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('trans_id');
            $table->string('flw_txtref');
            $table->string('flw_id');
            $table->integer('amount');
            $table->string('withdraw_from');
            $table->integer('status');
            $table->string('mode');
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
        Schema::dropIfExists('Withdraws');
    }
}
