<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->timestamps();
        });


         \DB::table('counties')->insert([     
            ['id'=> 1, 'name' => 'ALAMEDA COUNTY'],
            ['id'=> 2, 'name' => 'ALPINE COUNTY'],
            ['id'=> 3, 'name' => 'AMADOR COUNTY'],

            ['id'=> 4, 'name' => 'BUTTE COUNTY'],

            ['id'=> 5, 'name' => 'CALAVERAS COUNTY'],
            ['id'=> 6, 'name' => 'COLUSA COUNTY'],
            ['id'=> 7, 'name' => 'CONTRA COSTA COUNTY'],

            ['id'=> 8, 'name' => 'DEL NORTE COUNTY'],

            ['id'=> 9, 'name' => 'EL DORADO COUNTY'],
            ['id'=> 10, 'name' => 'FRESNO COUNTY'],
            ['id'=> 11, 'name' => 'GLENN COUNTY'],
            ['id'=> 12, 'name' => 'HUMBOLDT COUNTY'],
            ['id'=> 13, 'name' => 'IMPERIAL COUNTY'],
            ['id'=> 14, 'name' => 'INYO COUNTY'],
            ['id'=> 15, 'name' => 'KERN COUNTY'],
            ['id'=> 16, 'name' => 'KINGS COUNTY'],
            ['id'=> 17, 'name' => 'LAKE COUNTY'],
            ['id'=> 18, 'name' => 'LASSEN COUNTY'],
            ['id'=> 19, 'name' => 'LOS ANGELES COUNTY'],
            ['id'=> 20, 'name' => 'MADERA COUNTY'],
            ['id'=> 21, 'name' => 'MARIN COUNTY'],
            ['id'=> 22, 'name' => 'MARIPOSA COUNTY'],
            ['id'=> 23, 'name' => 'MENDOCINO COUNTY'],
            ['id'=> 24, 'name' => 'MERCED COUNTY'],
            ['id'=> 25, 'name' => 'MODOC COUNTY'],
            ['id'=> 26, 'name' => 'MONO COUNTY'],
            ['id'=> 27, 'name' => 'MONTEREY COUNTY'],
            ['id'=> 28, 'name' => 'NAPA COUNTY'],
            ['id'=> 29, 'name' => 'NEVADA COUNTY'],
            ['id'=> 30, 'name' => 'ORANGE COUNTY'],
            ['id'=> 31, 'name' => 'PLACER COUNTY'],
            ['id'=> 32, 'name' => 'PLUMAS COUNTY'],
            ['id'=> 33, 'name' => 'RIVERSIDE COUNTY'],
            ['id'=> 34, 'name' => 'SACRAMENTO COUNTY'],
            ['id'=> 35, 'name' => 'SAN BENITO COUNTY'],
            ['id'=> 36, 'name' => 'SAN BERNARDINO COUNTY'],
            ['id'=> 37, 'name' => 'SAN DIEGO COUNTY'],
            ['id'=> 38, 'name' => 'SAN FRANCISCO COUNTY'],
            ['id'=> 39, 'name' => 'SAN JOAQUIN COUNTY'],
            ['id'=> 40, 'name' => 'SAN LUIS OBISPO COUNTY'],
            ['id'=> 41, 'name' => 'SAN MATEO COUNTY'],
            ['id'=> 42, 'name' => 'SANTA BARBARA COUNTY'],
            ['id'=> 43, 'name' => 'SANTA CLARA COUNTY'],
            ['id'=> 44, 'name' => 'SANTA CRUZ COUNTY'],
            ['id'=> 45, 'name' => 'SHASTA COUNTY'],
            ['id'=> 46, 'name' => 'SIERRA COUNTY'],
            ['id'=> 47, 'name' => 'SISKIYOU COUNTY'],
            ['id'=> 48, 'name' => 'SOLANO COUNTY'],
            ['id'=> 49, 'name' => 'SONOMA COUNTY'],
            ['id'=> 50, 'name' => 'STANISLAUS COUNTY'],
            ['id'=> 51, 'name' => 'SUTTER COUNTY'],
            ['id'=> 52, 'name' => 'TEHAMA COUNTY'],
            ['id'=> 53, 'name' => 'TRINITY COUNTY'],
            ['id'=> 54, 'name' => 'TULARE COUNTY'],
            ['id'=> 55, 'name' => 'TUOLUMNE COUNTY'],
            ['id'=> 56, 'name' => 'VENTURA COUNTY'],
            ['id'=> 57, 'name' => 'YOLO COUNTY'],
            ['id'=> 58, 'name' => 'YUBA COUNTY']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('counties');
    }
}