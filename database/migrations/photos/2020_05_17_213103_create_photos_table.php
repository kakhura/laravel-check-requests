<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('photos')) {
            Schema::create('photos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('published')->default(true);
                $table->timestamp('published_at');
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('photo_details')) {
            Schema::create('photo_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('photo_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('photo_id')->on('photos')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('photo_images')) {
            Schema::create('photo_images', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('photo_id')->index();
                $table->string('image');
                $table->string('thumb');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('photo_id')->on('photos')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('photo_images');
        Schema::dropIfExists('photo_details');
        Schema::dropIfExists('photos');
    }
}
