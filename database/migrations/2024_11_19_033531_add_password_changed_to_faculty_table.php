<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->boolean('password_changed')->default(false)->after('password');
        });
    }
    
    public function down()
    {
        Schema::table('faculty', function (Blueprint $table) {
            $table->dropColumn('password_changed');
        });
    }
    
};
