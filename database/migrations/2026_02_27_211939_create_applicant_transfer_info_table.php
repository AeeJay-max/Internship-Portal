<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_transfer_info', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained()
                ->onDelete('cascade');

            // Previous Institution Details
            $table->string('previous_institution_name')->nullable();
            $table->string('previous_institution_country')->nullable();
            $table->string('previous_degree_program')->nullable();

            // Attendance Period
            $table->date('enrollment_date')->nullable();
            $table->date('last_attendance_date')->nullable();

            // Academic Progress
            $table->integer('completed_semesters')->nullable();
            $table->integer('earned_credits')->nullable();

            // Disciplinary History
            $table->boolean('disciplinary_actions')->default(false);
            $table->text('disciplinary_details')->nullable();

            // Motivation
            $table->text('reason_for_transfer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_transfer_info');
    }
};
