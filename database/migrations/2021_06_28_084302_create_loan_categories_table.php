<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });

         \DB::table('loan_categories')->insert([     
            ['id'=> 1, 'name' => 'Free New'],
            ['id'=> 2, 'name' => 'Free Existing'],
            ['id'=> 3, 'name' => 'Premium New'],
            ['id'=> 4, 'name' => 'Premium Existing'],
            ['id'=> 5, 'name' => 'Any'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_categories');
    }
}
