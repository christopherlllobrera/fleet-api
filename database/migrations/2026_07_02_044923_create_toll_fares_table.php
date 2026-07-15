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
        Schema::create('toll_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toll_road_id')->constrained();
            $table->foreignId('entry_point_id')->constrained('toll_points');
            $table->foreignId('exit_point_id')->constrained('toll_points');
            $table->string('class');
            $table->decimal('fare', 10, 2);
            $table->decimal('discount', 3, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Create a unique constraint for the road, entry and exit combination
            $table->unique(['toll_road_id', 'entry_point_id', 'exit_point_id'], 'unique_toll_fare');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toll_fares');
    }
};
