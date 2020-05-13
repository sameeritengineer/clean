<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponAppliedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_applieds', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->nullable()->unsigned();            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('promo_id')->nullable()->unsigned();            
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade');
            $table->integer('job_id')->nullable();
            $table->integer('total_count')->nullable();
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
        Schema::dropIfExists('coupon_applieds');
    }
}
