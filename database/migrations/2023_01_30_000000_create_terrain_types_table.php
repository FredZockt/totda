<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terrain_types', function (Blueprint $table) {
            $table->id();
            $table->string('terrain_type');
            $table->json('resources')->nullable();
            $table->timestamps();
        });

        DB::table('terrain_types')->insert([
            [
                'terrain_type' => 'deep_water',
                'resources' => null
            ],
            [
                'terrain_type' => 'shallow_water',
                'resources' => null
            ],
            [
                'terrain_type' => 'coast',
                'resources' => json_encode(['fish', 'salt', 'water'])
            ],
            [
                'terrain_type' => 'grassland',
                'resources' => json_encode(['wheat'])
            ],
            [
                'terrain_type' => 'meadows',
                'resources' => json_encode(['wool', 'leather'])
            ],
            [
                'terrain_type' => 'forest',
                'resources' => json_encode(['timber', 'honey', 'herbs'])
            ],
            [
                'terrain_type' => 'hills',
                'resources' => json_encode(['coal'])
            ],
            [
                'terrain_type' => 'mountains',
                'resources' => json_encode(['stone'])
            ],
            [
                'terrain_type' => 'high_mountains',
                'resources' => json_encode(['iron_ore', 'stone'])
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terrain_types');
    }
};
