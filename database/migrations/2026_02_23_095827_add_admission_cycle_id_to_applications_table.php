<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->foreignId('admission_cycle_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {

            $table->dropForeign(['admission_cycle_id']);
            $table->dropColumn('admission_cycle_id');

        });
    }
};
