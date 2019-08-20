<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2019_08_13_181216472860_Transformers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_transformers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class');
            $table->json('options');
            $table->integer('weight')->unsigned();
            $table->integer('image_style_id')->unsigned()->index();
            $table->foreign('image_style_id')->references('id')->on('image_styles')->onDelete('cascade');
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
        Schema::dropIfExists('media_transformers');
    }
}
