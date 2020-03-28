<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialRequestToCleanersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_request_to_cleaners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('Job_id');
            $table->integer('customer_id');
            $table->integer('Provider_id');
            $table->string('packing_instrustion');
            $table->string('special_Request');
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
        Schema::dropIfExists('special_request_to_cleaners');
    }
}
