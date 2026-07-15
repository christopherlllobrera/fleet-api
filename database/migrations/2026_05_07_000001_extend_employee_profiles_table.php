<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->string('suffix_name')->nullable()->after('gender');
            $table->string('place_of_birth')->nullable()->after('suffix_name');
            $table->foreignId('nationality_id')->nullable()->constrained('nationalities')->onDelete('set null')->after('place_of_birth');
            $table->string('personal_number')->nullable()->unique()->after('nationality_id');

            // Civil status extensions
            $table->date('date_of_marriage')->nullable()->after('civil_status');
            $table->string('spouse_name')->nullable()->after('date_of_marriage');
            $table->date('spouse_date_of_birth')->nullable()->after('spouse_name');
            $table->string('spouse_place_of_birth')->nullable()->after('spouse_date_of_birth');
            $table->string('mother_name')->nullable()->after('spouse_place_of_birth');
            $table->date('mother_date_of_birth')->nullable()->after('mother_name');
            $table->string('father_name')->nullable()->after('mother_date_of_birth');
            $table->date('father_date_of_birth')->nullable()->after('father_name');
            $table->date('date_of_death')->nullable()->after('father_date_of_birth');
            $table->date('date_of_separation')->nullable()->after('date_of_death');
        });
    }

    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropForeign(['nationality_id']);
            $table->dropUnique(['personal_number']);
            $table->dropColumn([
                'suffix_name',
                'place_of_birth',
                'nationality_id',
                'personal_number',
                'date_of_marriage',
                'spouse_name',
                'spouse_date_of_birth',
                'spouse_place_of_birth',
                'mother_name',
                'mother_date_of_birth',
                'father_name',
                'father_date_of_birth',
                'date_of_death',
                'date_of_separation',
            ]);
        });
    }
};
