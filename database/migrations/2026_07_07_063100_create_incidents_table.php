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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('reference_no')->nullable();
            $table->foreignId('dispatch_id')->constrained();
            $table->string('type')->nullable();
            $table->string('incident_severity')->nullable();
            $table->foreignId('vehicle_id')->constrained();
            $table->string('reported_by')->nullable();
            $table->string('reported_at')->nullable();
            $table->string('location')->nullable();

            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->longText('description')->nullable();
            $table->longText('attachments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
