<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->enum('day', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']);
            $table->time('time_start');
            $table->time('time_end');
            $table->string('building')->nullable();    // e.g. "Building A"
            $table->string('room')->nullable();        // e.g. "Room 301"
            $table->enum('type', ['lecture','seminar','lab'])->default('lecture');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_slots');
    }
};
