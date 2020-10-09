<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('published')->default(true);
                $table->boolean('in_main_menu')->default(false);
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image')->nullable();
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('page_details')) {
            Schema::create('page_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('page_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('page_id')->on('pages')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('page_images')) {
            Schema::create('page_images', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('page_id')->index();
                $table->string('image');
                $table->string('thumb');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('page_id')->on('pages')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('page_images');
        Schema::dropIfExists('page_details');
        Schema::dropIfExists('pages');
    }
}
