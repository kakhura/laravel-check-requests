<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('rules')) {
            Schema::create('rules', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->string('image')->nullable();
                $table->string('thumb')->nullable();
                $table->string('video_image')->nullable();
                $table->text('video')->nullable();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('rule_details')) {
            Schema::create('rule_details', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->unsignedTinyInteger('rule_id')->index();
                $table->string('title');
                $table->text('description');
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('rule_id')->on('rules')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('rule_details');
        Schema::dropIfExists('rules');
    }
}
