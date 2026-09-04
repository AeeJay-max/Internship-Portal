<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('preferred_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('second_preferred_department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('area_of_internship')->nullable();
            $table->string('specialisation')->nullable();
            $table->date('preferred_start_date')->nullable();
            $table->date('preferred_end_date')->nullable();
            $table->string('required_duration')->nullable();
            $table->string('institution_required_duration')->nullable();
            $table->string('preferred_location')->nullable();
            $table->boolean('flexible_department')->default(true);
            $table->text('motivation_statement')->nullable();
            $table->text('career_objectives')->nullable();
            $table->text('internship_objectives')->nullable();
            $table->text('skills_to_develop')->nullable();
            $table->text('why_selected_department')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_preferences');
    }
};
