<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'reference_number')) {
                $table->string('reference_number')->nullable()->unique()->after('user_id');
            }
            if (!Schema::hasColumn('applications', 'opportunity_id')) {
                $table->unsignedBigInteger('opportunity_id')->nullable()->after('reference_number');
            }
            if (!Schema::hasColumn('applications', 'current_step')) {
                $table->integer('current_step')->default(1)->after('status');
            }
            if (!Schema::hasColumn('applications', 'completion_percentage')) {
                $table->integer('completion_percentage')->default(0)->after('current_step');
            }
            if (!Schema::hasColumn('applications', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('completion_percentage');
            }
            if (!Schema::hasColumn('applications', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('submitted_at');
            }
            if (!Schema::hasColumn('applications', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('applications', 'review_notes')) {
                $table->text('review_notes')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $cols = ['reference_number', 'opportunity_id', 'current_step', 'completion_percentage', 'submitted_at', 'reviewed_by', 'reviewed_at', 'review_notes'];
            $columnsToDrop = array_filter($cols, function ($col) {
                return Schema::hasColumn('applications', $col);
            });
            if (!empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }
        });
    }
};
