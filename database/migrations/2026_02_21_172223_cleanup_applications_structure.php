<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'school_name',
            'school_address',
            'school_country',
            'school_city',
            'school_province',
            'school_postal_code',
            'school_phone',
            'school_email',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('applications', $column)) {
                Schema::table('applications', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    public function down(): void
    {
        // This migration only removes obsolete columns.
        // No restoration is required for a clean installation.
    }
};
