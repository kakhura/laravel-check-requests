<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('category_id')->index()->nullable();
                $table->boolean('published')->default(true);
                $table->unsignedBigInteger('ordering')->nullable()->index();
                $table->string('image');
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();
                $table->decimal('price', 28, 4)->nullable()->index();
                $table->decimal('discounted_price', 28, 4)->nullable()->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('category_id')->on('categories')->references('id')->onDelete('set null')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('product_details')) {
            Schema::create('product_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('product_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('product_id')->index();
                $table->string('image');
                $table->string('thumb');

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('product_id')->on('products')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_details');
        Schema::dropIfExists('products');
    }
}
