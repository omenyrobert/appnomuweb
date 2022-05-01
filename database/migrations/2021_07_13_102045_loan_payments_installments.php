<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoanPaymentsInstallments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LoanPaymentsInstallments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('ULoan_Id');
            $table->integer('Installement_No');
            $table->integer('Amount_Paid');
            $table->integer('pay_day');
            $table->integer('status');
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
        Schema::dropIfExists('LoanPaymentsInstallments');
    }
}
