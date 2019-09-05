<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->uuid('upload_uuid')
                ->nullable()
                ->after('upload_id');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->uuid('upload_uuid')
                ->nullable()
                ->after('upload_id');
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
            $table->dropColumn('upload_uuid');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('upload_uuid');
        });
    }
}
