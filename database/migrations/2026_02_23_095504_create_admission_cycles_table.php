<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_cycles', function (Blueprint $table) {
            $table->id();

            // Link to program
            $table->foreignId('program_id')
                ->constrained()
                ->onDelete('cascade');

            // Human readable intake name
            $table->string('intake_name'); // e.g. Fall 2026

            // Application window
            $table->date('starts_at');
            $table->date('deadline_at');

            // Optional seat limit
            $table->integer('capacity')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_cycles');
    }
};
