<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The tables that need audit fields added.
     * created_by already exists on: news
     * updated_by and deleted_by are missing everywhere
     */
    private array $tables = [
        'users',
        'applications',
        'applicant_personal_info',
        'applicant_academic_info',
        'applicant_family_info',
        'applicant_transfer_info',
        'application_documents',
        'application_logs',
        'programs',
        'admission_cycles',
        'students',
        'semesters',
        'subjects',
        'lecturers',
        'schedule_slots',
        'student_subject_enrollments',
        'grades',
        'news',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {

                // created_by — skip news (already has it)
                if ($table !== 'news' && !Schema::hasColumn($table, 'created_by')) {
                    $t->unsignedBigInteger('created_by')->nullable()->default(null)->after('updated_at');
                }

                // updated_by
                if (!Schema::hasColumn($table, 'updated_by')) {
                    $t->unsignedBigInteger('updated_by')->nullable()->default(null)->after('created_by');
                }

                // deleted_by
                if (!Schema::hasColumn($table, 'deleted_by')) {
                    $t->unsignedBigInteger('deleted_by')->nullable()->default(null)->after('updated_by');
                }

            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {
                $cols = [];

                if ($table !== 'news' && Schema::hasColumn($table, 'created_by')) {
                    $cols[] = 'created_by';
                }
                if (Schema::hasColumn($table, 'updated_by')) {
                    $cols[] = 'updated_by';
                }
                if (Schema::hasColumn($table, 'deleted_by')) {
                    $cols[] = 'deleted_by';
                }

                if (!empty($cols)) {
                    $t->dropColumn($cols);
                }
            });
        }
    }
};
