<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content');

            $table->bigInteger('post_id')->nullable()->unsigned();
            $table->foreign('post_id')->references('id')
                ->on('posts')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->bigInteger('parent')->nullable()->unsigned();
            $table->foreign('parent')->references('id')
                ->on('comments')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->bigInteger('upload_id')->nullable()->unsigned();
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
        Schema::dropIfExists('comments');
    }
}
