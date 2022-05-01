<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('lname');
            $table->string('class_name');
            $table->string('sch_admission_num');
            $table->string('gender');
            $table->string('school_name');
            $table->string('school_id_card');
            $table->string('phone')->nullable();
            $table->string('school_report')->nullable();
            $table->string('school_receipt')->nullable();
            $table->date('dob');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('sysusers')
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
        Schema::dropIfExists('students');
    }
}
