<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Automatically creates an 'id' column as the primary key
            $table->string('subject_code')->unique();
            $table->string('subject_description');
            $table->enum('type', ['Lec', 'Lab']);
            $table->integer('credit_units');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};

