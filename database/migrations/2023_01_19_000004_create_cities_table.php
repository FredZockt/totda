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
            $table->unsignedBigInteger('governor_id')->nullable();
            $table->decimal('tax_rate',4,2)->default(0.15);
            $table->decimal('tax_rate_kingdom',4,2)->default(5.00);
            $table->integer('x');
            $table->integer('y');
            $table->decimal('gold', 14, 6)->default(1000);
            $table->json('resources')->nullable();
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
