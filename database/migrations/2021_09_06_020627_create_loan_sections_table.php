<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Loan\LoanSection;

class CreateLoanSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });

       \DB::table('loan_sections')->insert([     
            ['id'=> LoanSection::ConformingFixed, 'name' => 'Conforming Fixed'],
            ['id'=> LoanSection::HighBalanceFixed, 'name' => 'High Balance Fixed'],
            ['id'=> LoanSection::ManhattanJumbo, 'name' => 'Manhattan Jumbo'],
            ['id'=> LoanSection::JumboPlus, 'name' => 'Jumbo Plus'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_sections');
    }
}