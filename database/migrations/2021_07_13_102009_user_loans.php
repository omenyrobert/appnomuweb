<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserLoans', function (Blueprint $table) {
            $table->id();
            $table->string('ULoan_Id')->unique();
            $table->string('user_id');
            $table->integer('loan_amount');
            $table->integer('amount_paid');
            $table->integer('approved_at');
            $table->integer('dueDate');
            $table->integer('status');
            $table->string('approved_by');
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
        Schema::dropIfExists('UserLoans');
    }
}
