<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('photo_id')->nullable()->index();
                $table->boolean('published')->default(true);
                $table->timestamp('published_at');
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();
                $table->string('photo_position')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('photo_id')->on('photos')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('news_details')) {
            Schema::create('news_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('news_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('news_id')->on('news')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('news_images')) {
            Schema::create('news_images', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('news_id')->index();
                $table->string('image');
                $table->string('thumb');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('news_id')->on('news')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('news_images');
        Schema::dropIfExists('news_details');
        Schema::dropIfExists('news');
    }
}
