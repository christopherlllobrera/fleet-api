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
        Schema::create('vehicle_energy_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->string('reference_no')->nullable();
            $table->foreignId('power_type_id')->constrained('vehicle_power_types');
            $table->date('date');
            $table->decimal('cost', 10, 2);
            $table->json('attachment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_energy_logs');
    }
};
