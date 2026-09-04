<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This migration fixes the users table to match your codebase needs:
 *
 * 1. The role ENUM in DB uses 'student' as default — your brain doc calls it 'applicant'.
 *    Decision taken: keep 'student' as the DB role for registered users (since a User can
 *    become a Student). If you want to rename to 'applicant', change the ENUM below.
 *
 * 2. Adds role, phone, nationality, is_active to $fillable (they exist in DB but not in
 *    the original User model $fillable — this migration is a no-op on schema, just documents
 *    the intent. The real fix is in the User model file).
 *
 * Run this ONLY if you need to rename the role value to 'applicant':
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('student')->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('nationality');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        // Reverse if you ran the ALTER above
    }
};
