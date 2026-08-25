<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preventive_work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('job_order_no')->nullable();
            $table->date('job_order_date')->nullable();

            $table->string('preventive_maintenance_type')->nullable();
            $table->date('job_order_assigned_date')->nullable();
            $table->date('job_order_accomplished_date')->nullable();

            // Re-add the foreign key relationships
            $table->foreignId('supervisor_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leadman_id')->constrained('employees')->onDelete('cascade');
            $table->json('engine_item')->nullable();
            $table->json('steering_item')->nullable();
            $table->json('brake_item')->nullable();
            $table->json('exhaust_item')->nullable();
            $table->json('front_suspension_item')->nullable();
            $table->json('rear_axle_item')->nullable();
            $table->json('clutch_item')->nullable();
            $table->json('transmission_item')->nullable();
            $table->json('propeller_item')->nullable();
            $table->json('tire_item')->nullable();
            $table->json('electrical_item')->nullable();
            $table->json('body_item')->nullable();
            $table->boolean('pms_tag_format')->nullable();
            $table->boolean('pms_next_schedule')->nullable();
            $table->boolean('odometer_reading')->nullable();
            $table->boolean('plate_number_id')->nullable();
            $table->boolean('driver_id')->nullable();
            $table->boolean('date_of_pms')->nullable();
            $table->json('pms_tagging')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preventive_work_orders');
    }
};
