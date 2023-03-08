<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('initiator_id')->nullable();
            $table->integer('bid')->default(Constants::BASIC_BUILDING_COST);
            $table->timestamps();

            $table->foreign('building_id')->references('id')->on('buildings');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('initiator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auction');
    }
};
