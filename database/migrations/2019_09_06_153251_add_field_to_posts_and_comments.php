<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToPostsAndComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('likes')
                ->default(0)
                ->after('upload_uuid');
            $table->integer('dislikes')
                ->default(0)
                ->after('likes');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->integer('likes')
                ->default(0)
                ->after('upload_uuid');
            $table->integer('dislikes')
                ->default(0)
                ->after('likes');
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
            $table->dropColumn('likes');
            $table->dropColumn('dislikes');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('dislikes');
        });
    }
}
