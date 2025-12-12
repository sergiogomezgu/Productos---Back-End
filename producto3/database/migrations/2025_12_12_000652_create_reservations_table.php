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
         Schema::create('reservations', function (Blueprint $table) {
        $table->id();

        // Relaciones
        $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
        $table->foreignId('hotel_user_id')->nullable()->constrained()->onDelete('set null');

        // Datos de la reserva
        $table->date('check_in');
        $table->date('check_out');
        $table->integer('guests')->default(1);
        $table->decimal('total_price', 10, 2)->nullable();

        // Estado de la reserva
        $table->enum('status', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
