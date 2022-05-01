<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('Transactions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('Trans_id');
            $table->string('FLW_Id');
            $table->string('FLW_txref');
            $table->integer('amount');
            $table->integer('flw_charge');
            $table->string('mode');
            $table->string('operation');
            $table->string('op_id');
            $table->string('email');
            $table->string('name');
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
        Schema::dropIfExists('Transactions');
    }
}
