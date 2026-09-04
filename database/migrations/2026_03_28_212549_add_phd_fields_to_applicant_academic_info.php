<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_academic_info', 'bachelor_gpa')) {
                $table->string('bachelor_gpa')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_university')) {
                $table->string('master_university')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_degree_obtained')) {
                $table->string('master_degree_obtained')->nullable();
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_gpa')) {
                $table->string('master_gpa')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            $cols = ['bachelor_gpa', 'master_university', 'master_degree_obtained', 'master_gpa'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_academic_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
