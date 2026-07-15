<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('type', ['present', 'permanent']);
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('municipality_id')->nullable()->constrained('municipalities')->onDelete('set null');
            $table->foreignId('barangay_id')->nullable()->constrained('barangays')->onDelete('set null');
            $table->text('address');
            $table->boolean('is_same_as_permanent')->default(false)->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_addresses');
    }
};
