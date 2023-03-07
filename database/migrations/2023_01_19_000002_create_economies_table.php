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
        Schema::create('economies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('city_id');
            $table->decimal('price', 14, 6)->default(0);
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('good_id')->references('id')->on('goods');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('economies');
    }
};
