<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Loan\LoanOption;

class CreateLoanOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });

         \DB::table('loan_options')->insert([     
            ['id'=> LoanOption::LowRate, 'name' => 'Low Rate'],
            ['id'=> LoanOption::Optimal, 'name' => 'Optimal'],
            ['id'=> LoanOption::LowCost, 'name' => 'Low Cost'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_options');
    }
}
