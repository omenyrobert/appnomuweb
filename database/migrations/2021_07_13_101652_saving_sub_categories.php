<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SavingSubCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('SavingSubCategories', function (Blueprint $table) {
            $table->id();
            $table->string('SubCateId')->unique();
            $table->string('cate_id');
            $table->integer('Saving_Period');
            $table->integer('Interest');
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
        Schema::dropIfExists('SavingSubCategories');
    }
}
