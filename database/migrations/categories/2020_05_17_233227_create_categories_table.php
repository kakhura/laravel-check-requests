<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('parent_id')->index()->nullable();
                $table->boolean('published')->default(true);
                $table->unsignedBigInteger('ordering')->nullable()->index();
                $table->string('image')->nullable();
                $table->string('thumb')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('parent_id')->on('categories')->references('id')->onDelete('set null')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('category_details')) {
            Schema::create('category_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('category_id')->index();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('category_details');
        Schema::dropIfExists('categories');
    }
}
