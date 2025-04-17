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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // nastavnik
            $table->string('title_hr');
            $table->string('title_en');
            $table->text('description');
            $table->enum('study_type', ['stručni', 'preddiplomski', 'diplomski']);
            $table->foreignId('accepted_student_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
