<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('champions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('champion_Id')->unsigned();
            $table->string('name');
            $table->string('key');
            $table->text('allytips');
            $table->text('enemytips');
            $table->text('blurb');
            $table->text('lore');
            $table->text('passive');
            $table->text('spells_q');
            $table->text('spells_w');
            $table->text('spells_e');
            $table->text('spells_r');
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
        Schema::drop('champions');
    }
}
