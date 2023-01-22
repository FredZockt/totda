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
        Schema::create('economy', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('good_id');
            $table->unsignedBigInteger('kingdom_id');
            $table->double('price');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('good_id')->references('id')->on('goods');
            $table->foreign('kingdom_id')->references('id')->on('kingdoms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('economy');
    }
};
