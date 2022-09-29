<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });

         \DB::table('property_types')->insert([     
            ['id'=> 1, 'name' => 'Single Family'],
            ['id'=> 2, 'name' => '2 Units'],
            ['id'=> 3, 'name' => '3 Units'],
            ['id'=> 4, 'name' => '4 Units'],
            ['id'=> 5, 'name' => 'Condos'],
            ['id'=> 6, 'name' => 'Townhomes'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_types');
    }
}
