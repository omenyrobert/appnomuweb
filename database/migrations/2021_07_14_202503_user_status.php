<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_account', function (Blueprint $table) {
            $table->id();
            $table->integer('available_balance');
            $table->integer('Ledger_Balance');
            $table->integer('Total_Saved');
            $table->integer('Amount_Withdrawn');
            $table->integer('Loan_Balance');
            $table->integer('Outstanding_Balance');
            $table->integer('Total_Paid');
            $table->integer('Loan_Limit');
            $table->string('user_id');
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
        Schema::dropIfExists('user_account');
    }
}
