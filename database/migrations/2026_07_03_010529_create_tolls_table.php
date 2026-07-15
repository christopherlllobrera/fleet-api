<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_id')->nullable();
            $table->foreignId('toll_road_id')->nullable()->constrained('toll_roads')->nullOnDelete();
            $table->string('vehicle_class')->nullable();
            $table->foreignId('entry_point_id')->nullable()->constrained('toll_points')->nullOnDelete();
            $table->foreignId('exit_point_id')->nullable()->constrained('toll_points')->nullOnDelete();
            $table->string('payment_method')->nullable();
            $table->decimal('toll_fare', 10, 2)->nullable();
            $table->longText('toll_attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tolls');
    }
};
