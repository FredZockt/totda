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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('kingdom_id');
            $table->unsignedBigInteger('mayor_id')->nullable();
            $table->decimal('tax_rate',4,2)->default(0.15);
            $table->integer('x');
            $table->integer('y');
            $table->timestamps();

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
        Schema::dropIfExists('cities');
    }
};
