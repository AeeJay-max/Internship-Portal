<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')
                ->constrained('student_subject_enrollments')
                ->cascadeOnDelete();
            $table->decimal('midterm', 4, 1)->nullable();       // out of 40
            $table->decimal('final', 4, 1)->nullable();         // out of 60
            $table->decimal('participation', 4, 1)->nullable(); // bonus points
            $table->decimal('total', 5, 1)->nullable();         // midterm + final + participation
            $table->string('letter_grade')->nullable();         // A / B+ / B / C+ / C / D / F
            $table->decimal('gpa_points', 3, 2)->nullable();   // 4.00 / 3.50 / 3.00 etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
