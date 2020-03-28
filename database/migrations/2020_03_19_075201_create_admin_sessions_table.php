<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->char('url');
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('admin_sessions');
    }
}
