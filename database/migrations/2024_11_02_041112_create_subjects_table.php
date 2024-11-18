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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('subject_id')->unique(); // Unique Subject ID
            $table->string('subject_code');
            $table->string('subject_description');
            $table->enum('type', ['Lec', 'Lab']); // Enum for subject type with options 'Lec' and 'Lab'
            $table->integer('credit_units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
