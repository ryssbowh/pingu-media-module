<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2020_04_06_175443653855_MediaAddFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->morphs('instance');
        });

        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alt');
            $table->timestamps();
        });

        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('instance_type');
            $table->dropColumn('instance_id');
        });
        Schema::dropIfExists('images');
        Schema::dropIfExists('files');
    }
}
