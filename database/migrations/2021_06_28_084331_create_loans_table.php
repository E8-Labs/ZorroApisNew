<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('lender_id');

            $table->unsignedBigInteger('loan_type_id')->nullable();
            $table->unsignedBigInteger('proprety_type_id')->nullable();
            $table->unsignedBigInteger('use_type_id')->nullable();


            $table->float('monthly_payment')->default(0.0);
            $table->float('rate')->default(0.0);
            $table->float('total_amount_to_pay')->default(0.0);
            $table->float('down_payment')->default(0.0);
            $table->float('credit_score')->default(0.0);
            $table->float('loan_amount')->default(0.0);
            $table->integer('zip_code')->default(0);



            // loan_categories

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('categories_id')->references('id')->on('loan_categories')->onDelete('cascade');
            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');

            $table->foreign('loan_type_id')->references('id')->on('rate_year_types')->onDelete('set null');
            $table->foreign('proprety_type_id')->references('id')->on('property_types')->onDelete('set null');
            $table->foreign('use_type_id')->references('id')->on('loan_use_types')->onDelete('set null');




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}

    
    
