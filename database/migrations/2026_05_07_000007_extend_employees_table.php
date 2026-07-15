<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->boolean('data_privacy_consent')->default(false)->after('is_active');
            $table->text('remarks')->nullable()->after('data_privacy_consent');
            $table->string('status')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['data_privacy_consent', 'remarks', 'status']);
        });
    }
};
