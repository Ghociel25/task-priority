<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('token')->index(); // Kolom token untuk unik URL per perangkat
            $table->string('course');         // Langsung string course sesuai inputan form
            $table->string('title');
            $table->dateTime('deadline');
            $table->float('weight')->default(10.0);
            $table->integer('difficulty')->default(3);
            $table->float('estimated_hours')->default(2.0);
            $table->float('priority_score')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};