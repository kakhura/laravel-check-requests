<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('partners')) {
            Schema::create('partners', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->boolean('published')->default(true);
                $table->unsignedSmallInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();
                $table->text('link')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('partner_details')) {
            Schema::create('partner_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('partner_id')->index();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('partner_id')->on('partners')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('partner_details');
        Schema::dropIfExists('partners');
    }
}
