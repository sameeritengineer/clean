<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateTimeToMonthlyScheduledJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_scheduled_jobs', function (Blueprint $table) {
            $table->char('Zipcode')->after('time');
            $table->string('customer_address')->after('Zipcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monthly_scheduled_jobs', function (Blueprint $table) {
            //
        });
    }
}
