<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->bigInteger('account_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('sysusers')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('account_id')->references('id')->on('user_account')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
}
