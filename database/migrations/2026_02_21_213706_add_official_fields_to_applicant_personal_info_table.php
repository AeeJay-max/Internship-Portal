<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            $cols = [
                'place_of_birth',
                'resident_country',
                'citizenship',
                'nationality',
                'marital_status',
                'city',
                'phone' => ['type' => 'string', 'length' => 30]
            ];

            foreach ($cols as $key => $val) {
                $colName = is_array($val) ? $key : $val;
                if (!Schema::hasColumn('applicant_personal_info', $colName)) {
                    $table->string($colName)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicant_personal_info', function (Blueprint $table) {
            $cols = ['place_of_birth', 'resident_country', 'citizenship', 'nationality', 'marital_status', 'city', 'phone'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('applicant_personal_info', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
