<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoanCategoryToCreditScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_scores', function (Blueprint $table) {
            //

            $table->unsignedBigInteger('loan_category')->default(5);;
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
        Schema::table('credit_score', function (Blueprint $table) {
            //
        });
    }
}
