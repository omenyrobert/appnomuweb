<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoanChart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LoanChart', function (Blueprint $table) {
            $table->id();
            $table->string('loan_id');
            $table->integer('loan_amount');
            $table->integer('loan_period');
            $table->integer('interest_rate');
            $table->integer('processing_fees');
            $table->integer('status');
            $table->integer('installments');
            $table->string('installement_period');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('LoanChart');
    }
}
