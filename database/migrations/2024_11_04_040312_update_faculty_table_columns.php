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

            // Check and add columns only if they don't already exist
            if (!Schema::hasColumn('faculty', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            if (!Schema::hasColumn('faculty', 'middle_initial')) {
                $table->string('middle_initial')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('faculty', 'last_name')) {
                $table->string('last_name')->after('middle_initial');
            }
            if (!Schema::hasColumn('faculty', 'position')) {
                $table->string('position')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('faculty', 'semester')) {
                $table->string('semester')->nullable()->after('position');
            }
            if (!Schema::hasColumn('faculty', 'id_number')) {
                $table->string('id_number')->unique()->after('semester');
            }
            if (!Schema::hasColumn('faculty', 'password')) {
                $table->string('password')->after('id_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty', function (Blueprint $table) {
            if (Schema::hasColumn('faculty', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('faculty', 'middle_initial')) {
                $table->dropColumn('middle_initial');
            }
            if (Schema::hasColumn('faculty', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('faculty', 'position')) {
                $table->dropColumn('position');
            }
            if (Schema::hasColumn('faculty', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('faculty', 'id_number')) {
                $table->dropColumn('id_number');
            }
            if (Schema::hasColumn('faculty', 'password')) {
                $table->dropColumn('password');
            }

            // Optionally add back the 'name' column if needed
            if (!Schema::hasColumn('faculty', 'name')) {
                $table->string('name')->nullable();
            }
        });
    }
};
