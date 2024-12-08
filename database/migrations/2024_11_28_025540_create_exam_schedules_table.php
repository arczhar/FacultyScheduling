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
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id'); // Reference to subjects
            $table->string('time_slot');
            $table->date('exam_date')->nullable();
            $table->timestamps();
        
            // Foreign key constraint for subject_id only
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('exam_schedules');
    }
};
