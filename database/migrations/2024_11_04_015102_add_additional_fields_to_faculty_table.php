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
            $table->string('first_name')->after('id');
            $table->string('middle_initial')->nullable()->after('first_name');
            $table->string('last_name')->after('middle_initial');
            $table->string('password')->default(bcrypt('faculty2024'))->after('id_number'); // default password
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_initial', 'last_name', 'password']);
        });
    }
};
