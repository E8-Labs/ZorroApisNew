<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->float('rate')->default(0.0);
            $table->float('value')->default(0.0);

            $table->unsignedBigInteger('lender_id');
            $table->unsignedBigInteger('year_type_id');
            $table->unsignedBigInteger('day_type_id');

            $table->timestamps();

            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');
            $table->foreign('year_type_id')->references('id')->on('rate_year_types')->onDelete('cascade');
            $table->foreign('day_type_id')->references('id')->on('rate_day_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
