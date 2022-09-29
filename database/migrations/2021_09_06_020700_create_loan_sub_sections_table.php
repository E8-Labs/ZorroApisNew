<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Loan\LoanSubSection;

class CreateLoanSubSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_sub_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });
      \DB::table('loan_sub_sections')->insert([     
            ['id'=> LoanSubSection::PropertyType, 'name' => 'Property Type'],
            ['id'=> LoanSubSection::Occupancy, 'name' => 'Occupancy'],
            ['id'=> LoanSubSection::Others, 'name' => 'Others'],
            ['id'=> LoanSubSection::LoanAmountType, 'name' => 'LoanAmountType'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_sub_sections');
    }
}