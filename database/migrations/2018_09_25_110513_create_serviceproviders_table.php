<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceprovidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceproviders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fName');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('address');
            $table->char('mobile');
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->char('zip_code');
            $table->string('profile_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serviceproviders');
    }
}
