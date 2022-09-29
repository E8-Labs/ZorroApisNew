<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default("");
            $table->string('detail')->default("");
            $table->timestamps();
        });

         \DB::table('profile_statuses')->insert([     
            ['id'=> 1, 'status' => 'Active'],
            ['id'=> 2, 'status' => 'Blocked'],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_statuses');
    }
}
