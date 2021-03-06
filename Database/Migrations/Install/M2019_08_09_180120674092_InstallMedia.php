<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2019_08_09_180120674092_InstallMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'media_types', function (Blueprint $table) {
                $table->increments('id');
                $table->string('machineName')->unique();
                $table->string('name')->unique();
                $table->integer('icon_id')->unsigned()->nullable();
                $table->boolean('deletable')->default(true);
                $table->json('extensions');
                $table->string('folder')->unique();
                $table->timestamps();
            }
        );

        Schema::create(
            'media', function (Blueprint $table) {
                $table->increments('id');
                $table->string('class');
                $table->string('name');
                $table->string('disk');
                $table->string('filename');
                $table->unsignedInteger('size');
                $table->integer('media_type_id')->unsigned();
                $table->foreign('media_type_id')->references('id')->on('media_types')->onDelete('cascade');
                $table->timestamps();
            }
        );

        Schema::create(
            'image_styles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->string('machineName')->unique();
                $table->boolean('deletable')->default(true);
                $table->boolean('editable')->default(true);
                $table->timestamps();
            }
        );

        Schema::create(
            'image_image_style', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('image_style_id')->unsigned()->index();
                $table->foreign('image_style_id')->references('id')->on('image_styles')->onDelete('cascade');
                $table->integer('image_id')->unsigned()->index();
                $table->foreign('image_id')->references('id')->on('media')->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_image_style');
        Schema::dropIfExists('media');
        Schema::dropIfExists('image_styles');
        Schema::dropIfExists('media_types');
    }
}
