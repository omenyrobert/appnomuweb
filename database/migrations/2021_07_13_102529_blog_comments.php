<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlogComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BlogComments', function (Blueprint $table) {
            $table->id();
            $table->string('blog_id');
            $table->string('comment_id')->unique();
            $table->string('parent_comment');
            $table->string('authour');
            $table->string('Comment');
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
        Schema::dropIfExists('BlogComments');
    }
}
