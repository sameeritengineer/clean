<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZipcodeServiceidsToWeeklyScheduledJobssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weekly_scheduled_jobs', function (Blueprint $table) {
            $table->char('Zipcode')->after('customer_id');
            $table->string('customer_address')->after('services'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weekly_scheduled_jobs', function (Blueprint $table) {
            //
        });
    }
}
