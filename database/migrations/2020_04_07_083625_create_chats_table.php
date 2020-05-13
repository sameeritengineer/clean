<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
$table->integer('user_id')->nullable()->unsigned();            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('provider_id')->nullable()->unsigned();            
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('message');
            $table->enum('type',['provider','user']);
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
        Schema::dropIfExists('chats');
    }
}
