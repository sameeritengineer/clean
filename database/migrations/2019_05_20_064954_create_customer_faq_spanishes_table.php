<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerFaqSpanishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_faq_spanishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cust_faqId')->nullable()->unsigned();            
            $table->foreign('cust_faqId')->references('id')->on('customer_faqs')->onDelete('cascade');
            $table->longText('question')->nullable();
            $table->longText('answer')->nullable();
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
        Schema::dropIfExists('customer_faq_spanishes');
    }
}
