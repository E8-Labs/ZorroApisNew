<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default("");
            $table->string('phone')->default("");
            $table->string('address')->default("");
            $table->longText('details')->nullable();
            $table->boolean('isActive')->default(true);
            $table->string('logo_url')->default("");
            $table->string('website')->default("");
            $table->unsignedBigInteger('created_by')->nullable();


            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lenders');
    }
}