<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('videos')) {
            Schema::create('videos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('published')->default(true);
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->text('video_url')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('video_details')) {
            Schema::create('video_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('video_id')->index();
                $table->string('title');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('video_id')->on('videos')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('video_details');
        Schema::dropIfExists('videos');
    }
}
