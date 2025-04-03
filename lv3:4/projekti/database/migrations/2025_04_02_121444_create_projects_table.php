<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Voditelj projekta
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('tasks_completed')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('projects');
    }
};

