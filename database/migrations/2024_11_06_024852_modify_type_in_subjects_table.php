<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->enum('type', ['Lec', 'Lab'])->change();
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('type')->change(); // Adjust this to the original type if needed
        });
    }
};
