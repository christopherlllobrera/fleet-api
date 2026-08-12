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
            $table->foreignId('charge_account_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('business_unit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('ownership')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('device_sn')->nullable();
            $table->string('init_odo')->nullable();
            $table->foreignId('maker_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('vehicle_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_power_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_group_id')->nullable()->constrained()->cascadeOnDelete();
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
