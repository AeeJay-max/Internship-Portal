<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_personal_info', 'armenian_language_proficiency')) {
                $table->string('armenian_language_proficiency')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'other_languages')) {
                $table->string('other_languages')->nullable();
            }
            if (!Schema::hasColumn('applicant_personal_info', 'ngo_membership')) {
                $table->text('ngo_membership')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            $cols = ['armenian_language_proficiency', 'other_languages', 'ngo_membership'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_personal_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
