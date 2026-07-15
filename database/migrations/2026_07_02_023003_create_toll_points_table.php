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
        Schema::create('toll_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toll_road_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['entry', 'exit', 'both'])->default('entry');
            $table->double('latitude', 10, 7);
            $table->double('longitude', 10, 7);
            $table->json('payment_method')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toll_points');
    }
};
