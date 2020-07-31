<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->char('first_name');
            $table->char('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('address');
            $table->char('mobile',12);
            $table->char('services')->nullable();
            $table->text('image')->nullable();
            $table->char('device_id')->nullable();
            $table->char('device_type')->nullable();
            $table->string('referral_code')->nullable();
            $table->integer('status')->default(0);
            $table->integer('working_status')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
