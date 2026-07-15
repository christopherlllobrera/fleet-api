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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_unit_id')->constrained()->cascadeOnDelete();
            $table->string('plate_no');
            $table->foreignId('maker_id')->constrained()->cascadeOnDelete();
            $table->string('model');
            $table->string('year');
            $table->string('status');
            $table->foreignId('vehicle_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_power_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_group_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
