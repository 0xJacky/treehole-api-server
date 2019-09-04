<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->longText('content');

            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('upload_id')->nullable();
            $table->foreign('upload_id')->references('id')
                ->on('uploads')->onDelete('cascade')
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
        Schema::dropIfExists('posts');
    }
}
