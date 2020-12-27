<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->string('title');
            $table->text('description');
            $table->integer('visited')->default(0);
            $table->timestamps();
        });
//        Schema::table('create_this_table_after_users', function($table) {
//            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
//        });
//        Schema::table('create_this_table_after_categories', function($table) {
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
