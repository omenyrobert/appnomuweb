<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSomaLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soma_loans', function (Blueprint $table) {
            $table->id();
            $table->string('SLN_id')->unique()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('loan_category_id')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->float('interest_rate',8,2,true);
            $table->integer('interest')->unsigned()->nullable();
            $table->integer('principal')->unsigned()->nullable();
            $table->integer('payment_period')->unsigned()->nullable();
            $table->integer('installments')->unsigned()->nullable();
            $table->integer('payment_amount')->unsigned()->nullable();
            $table->integer('paid_amount')->unsigned()->default(0);
            $table->enum('status',['pending','approved','declined','held','late'])->default('pending');
            $table->date('due_date')->nullable();
            $table->foreign('user_id')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('loan_category_id')->references('id')->on('loanchart')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('soma_loans');
    }
}
