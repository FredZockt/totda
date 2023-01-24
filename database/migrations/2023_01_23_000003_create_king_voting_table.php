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
        Schema::create('king_voting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kings_applicant_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('kings_applicant_id')->references('user_id')->on('king_application');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('governor_application');
    }
};
