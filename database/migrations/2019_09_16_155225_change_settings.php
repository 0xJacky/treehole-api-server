<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Webpatser\Uuid\Uuid;

class ChangeSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['option_name', 'option_value']);
            $table->longText('notice')->nullable();
            $table->timestamps();
        });
        DB::table('settings')->insert(['id' => (string)Uuid::generate(4)]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        DB::table('settings')->truncate();
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['notice', 'created_at', 'updated_at']);
            $table->text('option_name', 255);
            $table->longText('option_value');
        });
    }
}
