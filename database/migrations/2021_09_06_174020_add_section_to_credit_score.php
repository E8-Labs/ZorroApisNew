<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Loan\LoanSection;
use App\Models\Loan\LoanSubSection;

class AddSectionToCreditScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_scores', function (Blueprint $table) {

             $table->unsignedBigInteger('loan_section_id')->default(LoanSection::ConformingFixed);
             $table->unsignedBigInteger('loan_sub_section_id')->default(LoanSubSection::Others);

             $table->foreign('loan_section_id')->references('id')->on('loan_sections')->onDelete('cascade');
             $table->foreign('loan_sub_section_id')->references('id')->on('loan_sub_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_scores', function (Blueprint $table) {
            //
        });
    }
}
