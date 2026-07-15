<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('relationship', ['spouse', 'child', 'parent', 'sibling', 'other']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_dependents');
    }
};
