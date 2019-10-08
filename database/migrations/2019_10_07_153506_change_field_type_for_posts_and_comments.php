<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldTypeForPostsAndComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('likes')->change();
            $table->unsignedInteger('dislikes')->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('likes')->change();
            $table->unsignedInteger('dislikes')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('likes')->change();
            $table->integer('dislikes')->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->integer('likes')->change();
            $table->integer('dislikes')->change();
        });
    }
}
