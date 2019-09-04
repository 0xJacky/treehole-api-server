<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();

            $table->uuid('post_id')->nullable();
            $table->foreign('post_id')->references('id')
                ->on('posts')->onDelete('cascade')
                ->onUpdate('cascade');

            $table->uuid('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')
                ->on('comments')->onDelete('cascade')
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
        Schema::dropIfExists('reports');
    }
}
