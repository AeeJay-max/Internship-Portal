<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_personal_info', 'middle_name')) {
                $table->string('middle_name')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'national_id')) {
                $table->string('national_id')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'province')) {
                $table->string('province')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
        });

        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_academic_info', 'institution_type')) {
                $table->string('institution_type')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'program_of_study')) {
                $table->string('program_of_study')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'field_of_study')) {
                $table->string('field_of_study')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'current_year_level')) {
                $table->string('current_year_level')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'academic_qualification')) {
                $table->string('academic_qualification')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'expected_graduation_date')) {
                $table->date('expected_graduation_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            $cols = ['middle_name', 'national_id', 'province', 'emergency_contact_name', 'emergency_contact_phone'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_personal_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });

        Schema::table('applicant_academic_info', function (Blueprint $table) {
            $cols = ['institution_type', 'program_of_study', 'field_of_study', 'current_year_level', 'academic_qualification', 'expected_graduation_date'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_academic_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
