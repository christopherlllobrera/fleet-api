<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corrective_work_order', function (Blueprint $table) {
            $table->id();
            $table->string('job_order_no')->unique()->nullable();
            $table->string('job_order_sap_no')->unique()->nullable();
            $table->string('billing_invoice_no')->unique()->nullable();
            $table->string('charge_account_no')->nullable();
            $table->foreignId('plate_no_id')->constrained('vehicles');
            $table->string('vehicle_location')->nullable();
            $table->string('odometer_reading')->nullable();
            $table->string('requisition_office')->nullable();
            $table->longText('vehicle_trouble_report')->nullable();
            $table->longText('initial_assessment')->nullable();
            $table->json('actual_work_time')->nullable();
            $table->json('issuance_of_materials')->nullable();
            $table->json('return_of_materials')->nullable();
            $table->json('vehicle_date_released')->nullable();
            $table->string('status')->nullable();

            $table->foreignId('driver_name_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('contact_person_id')->constrained('employees')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_work_order');
    }
};
