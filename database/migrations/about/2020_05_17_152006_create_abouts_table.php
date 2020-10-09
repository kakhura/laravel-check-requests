<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('abouts')) {
            Schema::create('abouts', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->string('image')->nullable();
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('about_details')) {
            Schema::create('about_details', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->unsignedTinyInteger('about_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('about_id')->on('abouts')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_details');
        Schema::dropIfExists('abouts');
    }
}
