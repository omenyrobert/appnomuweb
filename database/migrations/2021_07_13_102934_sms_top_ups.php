<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SmsTopUps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SmsTopUps', function (Blueprint $table) {
            $table->id();
            $table->string('Ext_txref');
            $table->string('trans_id');
            $table->string('user_id');
            $table->integer('amount');
            $table->integer('status');
            $table->integer('charge');
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
        Schema::dropIfExists('SmsTopUps');
    }
}
