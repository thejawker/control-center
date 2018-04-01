<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulbSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulb_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('scene_id');
            $table->boolean('powered');
            $table->string('color');
            $table->unsignedInteger('bulb_id');

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
        Schema::dropIfExists('bulb_settings');
    }
}
