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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_no');
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requesting_office_id')->constrained()->cascadeOnDelete();
            $table->string('from_location');
            $table->string('to_location');
            $table->string('purpose');
            $table->string('priority_level');
            $table->dateTime('departure_time');
            $table->dateTime('en_route_time');
            $table->dateTime('complete_time');
            $table->dateTime('cancel_time');
            $table->string('reason');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
