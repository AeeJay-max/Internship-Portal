<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (!Schema::hasColumn('applicant_academic_info', 'research_topic')) {
                $table->text('research_topic')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_academic_info', function (Blueprint $table) {
            if (Schema::hasColumn('applicant_academic_info', 'research_topic')) {
                $table->dropColumn('research_topic');
            }
        });
    }
};
