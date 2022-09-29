<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateDayTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_day_types', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default("");
            $table->timestamps();        
        });

         \DB::table('rate_day_types')->insert([     
            ['id'=> 1, 'type' => '15 Day'],
            ['id'=> 2, 'type' => '20 Day'],
            ['id'=> 3, 'type' => '30 Day'],
            ['id'=> 4, 'type' => '45 Day'],
            ['id'=> 5, 'type' => '60 Day'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rate_day_types');
    }
}
