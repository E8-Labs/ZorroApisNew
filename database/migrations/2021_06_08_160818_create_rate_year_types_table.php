<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateYearTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_year_types', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default("");
            $table->timestamps();
        });

        \DB::table('rate_year_types')->insert([     
            ['id'=> 1, 'type' => '30 YR Fixed'],
            ['id'=> 2, 'type' => '15 YR Fixed'],
            ['id'=> 3, 'type' => '10-1 ARM'],
            ['id'=> 4, 'type' => '7-1 ARM'],
            ['id'=> 5, 'type' => '5-1 ARM'],
            ['id'=> 6, 'type' => 'Customize'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_year_types');
    }
}
