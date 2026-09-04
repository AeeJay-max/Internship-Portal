<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // How many class slots per week this subject needs
            // 1 = minor/general (2hrs), 2 = core major (4hrs), 3 = heavy major (6hrs)
            $table->unsignedTinyInteger('hours_per_week')->default(1)->after('credits');

            // Subject classification
            // major_core    = heavily related to the major, most important
            // major_elective = related to major but elective/secondary
            // general        = general education (Armenian, PE, Russian, Life Safety etc.)
            $table->enum('subject_type', ['major_core', 'major_elective', 'general'])
                ->default('major_core')
                ->after('hours_per_week');

            // Year of study this subject belongs to (1st, 2nd, 3rd, 4th year)
            $table->unsignedTinyInteger('year_of_study')->default(1)->after('subject_type');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['hours_per_week', 'subject_type', 'year_of_study']);
        });
    }
};
