<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('blogs')) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('photo_id')->nullable()->index();
                $table->boolean('published')->default(true);
                $table->timestamp('published_at');
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('photo_id')->on('photos')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('blog_details')) {
            Schema::create('blog_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('blog_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('blog_id')->on('blogs')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('blog_images')) {
            Schema::create('blog_images', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('blog_id')->index();
                $table->string('image');
                $table->string('thumb');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('blog_id')->on('blogs')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('blog_images');
        Schema::dropIfExists('blog_details');
        Schema::dropIfExists('blogs');
    }
}
