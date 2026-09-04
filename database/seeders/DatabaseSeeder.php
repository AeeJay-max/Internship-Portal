<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Document Types ───────────────────────────────────────────────
        $documentTypes = [
            ['name' => 'National ID / Passport',           'code' => 'national_id',        'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Curriculum Vitae (CV)',            'code' => 'cv',                 'is_required' => true,  'allowed_mimes' => 'pdf,doc,docx',     'max_size' => 5120],
            ['name' => 'Academic Transcript',              'code' => 'transcript',         'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Proof of Registration',            'code' => 'proof_registration', 'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Internship Introduction Letter',  'code' => 'intro_letter',       'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Recommendation Letter',            'code' => 'recommendation',     'is_required' => false, 'allowed_mimes' => 'pdf,doc,docx',     'max_size' => 5120],
            ['name' => 'Cover Letter',                     'code' => 'cover_letter',       'is_required' => false, 'allowed_mimes' => 'pdf,doc,docx',     'max_size' => 5120],
            ['name' => 'Passport Photograph',              'code' => 'passport_photo',     'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png',     'max_size' => 5120],
            ['name' => 'Other Supporting Documents',       'code' => 'other',               'is_required' => false, 'allowed_mimes' => 'jpg,jpeg,png,pdf,doc,docx', 'max_size' => 5120],
        ];

        foreach ($documentTypes as $dt) {
            DocumentType::updateOrCreate(['code' => $dt['code']], $dt);
        }

        // ── Users ────────────────────────────────────────────────────────
        User::updateOrCreate(['email' => 'superadmin@mosrac.gov.zw'], [
            'name'      => 'Super Administrator',
            'password'  => Hash::make('password'),
            'role'      => 'super_admin',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'admin@mosrac.gov.zw'], [
            'name'      => 'Internship Administrator',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'placement@mosrac.gov.zw'], [
            'name'      => 'Placement Officer',
            'password'  => Hash::make('password'),
            'role'      => 'placement_officer',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'applicant@mosrac.gov.zw'], [
            'name'      => 'Test Applicant',
            'password'  => Hash::make('password'),
            'role'      => 'student', // default applicant role in DB
            'is_active' => true,
        ]);

        // ── Ministry Departments ─────────────────────────────────────────
        $this->call(DepartmentSeeder::class);

        // ── Internship Opportunities ─────────────────────────────────────
        $this->call(InternshipOpportunitySeeder::class);

        // ── News ─────────────────────────────────────────────────────────
        $this->call(NewsSeeder::class);
    }
}
