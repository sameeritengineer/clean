<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderFaqSpanishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_faq_spanishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pro_faqId')->nullable()->unsigned();            
            $table->foreign('pro_faqId')->references('id')->on('provider_faqs')->onDelete('cascade');
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
        Schema::dropIfExists('provider_faq_spanishes');
    }
}
