<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription')->default("");
            $table->string('detail')->default("");
            $table->timestamps();
        });

         \DB::table('user_subscriptions')->insert([     
            ['id'=> 1, 'subscription' => 'Free'],
            ['id'=> 2, 'subscription' => '3 Months'],
            ['id'=> 3, 'subscription' => '6 Months'],
            ['id'=> 4, 'subscription' => 'One Year'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
}
