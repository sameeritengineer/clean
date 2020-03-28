<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTypesSpanishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_types_spanishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('servicetype_id')->nullable()->unsigned();            
            $table->foreign('servicetype_id')->references('id')->on('service_types')->onDelete('cascade');
            $table->char('name')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('service_types_spanishes');
    }
}
