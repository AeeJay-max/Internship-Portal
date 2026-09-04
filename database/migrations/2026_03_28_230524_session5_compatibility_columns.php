<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── applicant_academic_info ───────────────────────────────────────
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_academic_info', 'research_topic')) {
                $table->text('research_topic')->nullable()->after('language_of_instruction');
            }
            if (!Schema::hasColumn('applicant_academic_info', 'bachelor_gpa')) {
                $table->decimal('bachelor_gpa', 4, 2)->nullable()->after('gpa');
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_university')) {
                $table->string('master_university')->nullable()->after('previous_university');
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_degree_obtained')) {
                $table->string('master_degree_obtained')->nullable()->after('master_university');
            }
            if (!Schema::hasColumn('applicant_academic_info', 'master_gpa')) {
                $table->decimal('master_gpa', 4, 2)->nullable()->after('master_degree_obtained');
            }
        });

        // ── applicant_personal_info ───────────────────────────────────────
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_personal_info', 'armenian_language_proficiency')) {
                $table->string('armenian_language_proficiency')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('applicant_personal_info', 'other_languages')) {
                $table->string('other_languages')->nullable()->after('armenian_language_proficiency');
            }
            if (!Schema::hasColumn('applicant_personal_info', 'ngo_membership')) {
                $table->text('ngo_membership')->nullable()->after('other_languages');
            }
        });

        // ── users ─────────────────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('is_active');
            }
        });

        // ── applications ──────────────────────────────────────────────────
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'needs_reupload_review')) {
                $table->boolean('needs_reupload_review')->default(false)->after('review_notes');
            }
        });

        // ── application_documents ─────────────────────────────────────────
        Schema::table('application_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('application_documents', 'cert_name')) {
                $table->string('cert_name')->nullable()->after('document_type_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            foreach (['research_topic', 'bachelor_gpa', 'master_university', 'master_degree_obtained', 'master_gpa'] as $col) {
                if (Schema::hasColumn('applicant_academic_info', $col)) $table->dropColumn($col);
            }
        });

        Schema::table('applicant_personal_info', function (Blueprint $table) {
            foreach (['armenian_language_proficiency', 'other_languages', 'ngo_membership'] as $col) {
                if (Schema::hasColumn('applicant_personal_info', $col)) $table->dropColumn($col);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_photo')) $table->dropColumn('profile_photo');
        });

        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'needs_reupload_review')) $table->dropColumn('needs_reupload_review');
        });

        Schema::table('application_documents', function (Blueprint $table) {
            if (Schema::hasColumn('application_documents', 'cert_name')) $table->dropColumn('cert_name');
        });
    }
};
