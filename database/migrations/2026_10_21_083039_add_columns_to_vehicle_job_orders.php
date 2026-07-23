<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('corrective_work_order', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('assignment')->nullable();
            $table->string('UCR_ref_no')->nullable();
            $table->string('UCR_amount')->nullable();
            $table->string('invoice')->nullable();
            $table->longText('file_attachment')->nullable();


        });
    }

    public function down(): void
    {
        Schema::table('corrective_work_order', function (Blueprint $table) {
            // $table->dropColumn('type');
            $table->dropColumn('assignment');
            $table->dropColumn('UCR_ref_no');
            $table->dropColumn('BCR_amount');
            $table->dropColumn('invoice');
            $table->dropColumn('file_attachment');
        });
    }
};
