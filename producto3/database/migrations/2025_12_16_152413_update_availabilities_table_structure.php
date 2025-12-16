<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn(['date', 'available']);
            $table->enum('vehiculo_tipo', ['Turismo', 'Bus'])->after('hotel_id');
            $table->integer('num_vehiculos')->after('vehiculo_tipo');
            $table->date('fecha')->after('num_vehiculos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn(['vehiculo_tipo', 'num_vehiculos', 'fecha']);
            $table->date('date')->after('hotel_id');
            $table->boolean('available')->default(false);
        });
    }
};
