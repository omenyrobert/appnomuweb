<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('Contacts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('List_id');
            $table->string('name');
            $table->string('telephone');
            $table->string('email');
            $table->string('Designation');
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
        Schema::dropIfExists('Contacts');
    }
}
