<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Loan\LoanOption;

class AddPropertyValueToLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {

             $table->float('property_value')->default(0.0);
             $table->unsignedBigInteger('loan_option_id')->default(LoanOption::LowRate);
             $table->unsignedBigInteger('loan_parent_id')->nullable();


             $table->foreign('loan_option_id')->references('id')->on('loan_options')->onDelete('cascade');
             $table->foreign('loan_parent_id')->references('id')->on('loans')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            //
        });
    }
}
