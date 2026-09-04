<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('applicant_academic_info')) {
            Schema::create('applicant_academic_info', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
                $table->string('school_name')->nullable();
                $table->string('institution_type')->nullable();
                $table->string('program_of_study')->nullable();
                $table->string('field_of_study')->nullable();
                $table->string('current_year_level')->nullable();
                $table->string('academic_qualification')->nullable();
                $table->date('expected_graduation_date')->nullable();
                $table->string('institution_city')->nullable();
                $table->string('institution_country')->nullable();
                $table->integer('graduation_year')->nullable();
                $table->date('graduation_date')->nullable();
                $table->string('gpa')->nullable();
                $table->string('bachelor_gpa')->nullable();
                $table->string('previous_university')->nullable();
                $table->string('degree_obtained')->nullable();
                $table->string('specialization')->nullable();
                $table->string('language_of_instruction')->nullable();
                $table->json('additional_certifications')->nullable();
                $table->text('research_topic')->nullable();
                $table->string('master_university')->nullable();
                $table->string('master_degree_obtained')->nullable();
                $table->string('master_gpa')->nullable();
                $table->timestamps();
            });
        }
    }
    public function down(): void { Schema::dropIfExists('applicant_academic_info'); }
};
