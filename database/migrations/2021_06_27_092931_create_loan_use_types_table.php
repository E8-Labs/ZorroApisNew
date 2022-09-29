<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanUseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_use_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });

        \DB::table('loan_use_types')->insert([     
            ['id'=> 1, 'name' => 'Primary'],
            ['id'=> 2, 'name' => 'Secondary'],
            ['id'=> 3, 'name' => 'Investment']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_use_types');
    }
}
