<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->string('phone');
                $table->string('email');
                $table->string('long')->nullable();
                $table->string('lat')->nullable();
                if (!is_null(config('kakhura.site-bases.contact_socials')) && is_array(config('kakhura.site-bases.contact_socials'))) {
                    foreach (config('kakhura.site-bases.contact_socials') as $social) {
                        $table->string($social)->nullable();
                    }
                }
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('contact_details')) {
            Schema::create('contact_details', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->unsignedTinyInteger('contact_id')->index();
                $table->string('address');
                $table->text('description')->nullable();
                $table->string('locale')->index();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('contact_id')->on('contacts')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('contact_details');
        Schema::dropIfExists('contacts');
    }
}
