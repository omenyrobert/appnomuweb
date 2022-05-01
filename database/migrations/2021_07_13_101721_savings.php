<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Savings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Savings', function (Blueprint $table) {
            $table->id();
            $table->string('SubCateId')->unique();
            $table->string('saving_id');
            $table->string('user_id');
            $table->integer('amount');
            $table->integer('status');
            $table->integer('Interest');
            $table->integer('duedate');
            $table->integer('savingdate');
            $table->integer('processing_fees');
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
        Schema::dropIfExists('Savings');
    }
}
