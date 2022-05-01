<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_loans', function (Blueprint $table) {
            $table->id();
            $table->string('BLN_id')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('business_id')->unsigned();
            $table->bigInteger('loan_category_id')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->float('interest_rate',8,2,true);
            $table->integer('proposed_amount')->unsigned();
            $table->integer('principal')->unsigned()->nullable();
            $table->integer('proposed_period')->unsigned();
            $table->integer('payment_period')->unsigned()->nullable();
            $table->integer('installments')->unsigned()->nullable();
            $table->integer('payment_amount')->unsigned()->nullable();
            $table->enum('status',['pending','approved','denied','on hold']);
            $table->date('due_date')->nullable();
            $table->foreign('user_id')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')
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
        Schema::dropIfExists('business_loans');
    }
}
