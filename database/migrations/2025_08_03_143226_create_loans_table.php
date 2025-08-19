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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            //Por las relaciones:
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            //status
            $table->enum('status', ['reservado','prestado','devuelto','vencido'])->default('reservado');

            //fechas importantes

            $table->date('fecha_prestamo')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->date('returned_at')->nullable();
             $table->timestamp('reminder_sent_at')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
