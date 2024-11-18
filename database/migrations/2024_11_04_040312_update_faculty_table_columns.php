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
        Schema::table('faculty', function (Blueprint $table) {
            // Drop the 'name' column if it exists
            if (Schema::hasColumn('faculty', 'name')) {
                $table->dropColumn('name');
            }

            // Add the required columns
            $table->string('first_name')->after('id');
            $table->string('middle_initial')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_initial');
            $table->string('position')->nullable()->after('last_name');
            $table->string('semester')->nullable()->after('position');
            $table->string('id_number')->unique()->after('semester');
            $table->string('password')->after('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_initial', 'last_name', 'position', 'semester', 'id_number', 'password']);
            // Optionally add back the 'name' column if needed
            $table->string('name')->nullable();
        });
    }
};
