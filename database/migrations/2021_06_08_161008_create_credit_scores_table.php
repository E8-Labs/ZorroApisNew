<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_scores', function (Blueprint $table) {
            $table->id();

            $table->float('value')->default(0.0);
            $table->float('cs_from')->default(0.0);
            $table->float('cs_to')->default(0.0);
             $table->float('ltv_from')->default(0.0);
            $table->float('ltv_to')->default(0.0);
            $table->string('detail')->default("");



            $table->unsignedBigInteger('lender_id');
            $table->timestamps();
            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');

            $table->unsignedBigInteger('loan_category');
            $table->foreign('loan_category')->references('id')->on('loan_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_scores');
    }
}
