<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('slides')) {
            Schema::create('slides', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('published')->default(true);
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->text('link')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('slide_details')) {
            Schema::create('slide_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('slide_id')->index();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('slide_id')->on('slides')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('slide_details');
        Schema::dropIfExists('slides');
    }
}
