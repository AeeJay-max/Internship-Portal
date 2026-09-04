<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_academic_info', 'institution_city')) {
                $table->string('institution_city')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'institution_country')) {
                $table->string('institution_country')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'graduation_date')) {
                $table->date('graduation_date')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'specialization')) {
                $table->string('specialization')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'language_of_instruction')) {
                $table->string('language_of_instruction')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'additional_certifications')) {
                $table->json('additional_certifications')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            $cols = ['institution_city', 'institution_country', 'graduation_date', 'specialization', 'language_of_instruction', 'additional_certifications'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_academic_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
