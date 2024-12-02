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
            $table->unsignedBigInteger('room_id'); // Reference to exam_rooms
            $table->unsignedBigInteger('subject_id'); // Reference to subjects
            $table->string('time_slot'); // Time slot column
            $table->date('exam_date')->nullable(); // Optional if only room and time slot are needed
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('room_id')->references('id')->on('exam_rooms')->onDelete('cascade');
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
