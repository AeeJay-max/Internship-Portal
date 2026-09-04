<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationLog;
use App\Models\ApplicantPersonalInfo;
use App\Models\ApplicantAcademicInfo;
use App\Models\ApplicantFamilyInfo;
use App\Models\ApplicantTransferInfo;
use App\Models\AdmissionCycle;
use App\Models\DocumentType;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * TestApplicationsSeeder
 *
 * Safe to run multiple times.
 * Each run: deletes only the PREVIOUS test applications for the 5 test accounts,
 * then creates fresh ones. Users are never deleted — they keep their history.
 *
 * One user CAN have multiple applications (history is preserved).
 * The only real restriction in the app is: one DRAFT at a time.
 *
 * Run: php artisan db:seed --class=TestApplicationsSeeder
 */
class TestApplicationsSeeder extends Seeder
{
    private string $placeholder = 'documents/test_placeholder.pdf';

    private array $testEmails = [
        'bachelor.regular@test.am',
        'bachelor.transfer@test.am',
        'master.regular@test.am',
        'master.transfer@test.am',
        'phd.regular@test.am',
    ];

    public function run(): void
    {
        $this->ensureDocumentTypes();
        $this->cleanupPreviousTestApplications();

        $docTypes = DocumentType::all()->keyBy('code');
        $admin    = User::where('role', 'admin')->first();

        $family = [
            'father_full_name'  => 'Armen Vardanyan',
            'father_occupation' => 'Civil Engineer',
            'mother_full_name'  => 'Anahit Vardanyan',
            'mother_occupation' => 'School Teacher',
            'siblings' => [
                ['name' => 'Hayk Vardanyan', 'relation' => 'brother', 'occupation' => 'Software Developer'],
                ['name' => 'Ani Vardanyan',  'relation' => 'sister',  'occupation' => 'Medical Student'],
            ],
        ];

        // ── APP 1: Bachelor Regular — SUBMITTED ──────────────────────
        $u1 = $this->makeUser('bachelor.regular@test.am', 'Ani Petrosyan');
        $c1 = $this->getCycle('Computer Science & Engineering', 'bachelor');
        if ($c1) {
            $a1 = $this->makeApp($u1, $c1, false, Application::STATUS_SUBMITTED, 5, 100, now()->subDays(3));
            ApplicantPersonalInfo::create(['application_id' => $a1->id, 'first_name' => 'Ani', 'last_name' => 'Petrosyan', 'date_of_birth' => '2004-05-14', 'gender' => 'female', 'passport_number' => 'AM1234567', 'country_of_birth' => 'Armenia', 'place_of_birth' => 'Yerevan', 'resident_country' => 'Armenia', 'citizenship' => 'Armenian', 'nationality' => 'Armenian', 'marital_status' => 'single', 'city' => 'Yerevan', 'phone' => '+37491234567', 'address' => '15 Tigranyan Street Yerevan', 'armenian_language_proficiency' => 'Native', 'other_languages' => 'English (B2), Russian (B1)', 'ngo_membership' => 'AIESEC Armenia']);
            ApplicantAcademicInfo::create(['application_id' => $a1->id, 'school_name' => 'Quantum College Yerevan', 'institution_city' => 'Yerevan', 'institution_country' => 'Armenia', 'graduation_date' => '2022-06-01', 'graduation_year' => 2022, 'gpa' => 3.85, 'specialization' => 'Mathematics & Physics', 'language_of_instruction' => 'Armenian', 'additional_certifications' => [['name' => 'IELTS', 'month' => 3, 'year' => 2023], ['name' => 'Python PCEP', 'month' => 9, 'year' => 2023]]]);
            ApplicantFamilyInfo::create(array_merge(['application_id' => $a1->id], $family));
            $this->seedDocuments($a1, $docTypes, false, 'bachelor');
            ApplicationLog::log($a1->id, 'Application submitted by applicant', $u1->id);
            $this->command->info('  OK App 1: Bachelor Regular — Ani Petrosyan — SUBMITTED');
        }

        // ── APP 2: Bachelor Transfer — SUBMITTED ─────────────────────
        $u2 = $this->makeUser('bachelor.transfer@test.am', 'Davit Hovhannisyan');
        $c2 = $this->getCycle('Civil Engineering', 'bachelor');
        if ($c2) {
            $a2 = $this->makeApp($u2, $c2, true, Application::STATUS_SUBMITTED, 6, 100, now()->subDays(5));
            ApplicantPersonalInfo::create(['application_id' => $a2->id, 'first_name' => 'Davit', 'last_name' => 'Hovhannisyan', 'date_of_birth' => '2002-11-20', 'gender' => 'male', 'passport_number' => 'AM7654321', 'country_of_birth' => 'Armenia', 'place_of_birth' => 'Gyumri', 'resident_country' => 'Armenia', 'citizenship' => 'Armenian', 'nationality' => 'Armenian', 'marital_status' => 'single', 'city' => 'Gyumri', 'phone' => '+37494567890', 'address' => '7 Vardanants Square Gyumri', 'armenian_language_proficiency' => 'Native', 'other_languages' => 'English (C1), French (A2)', 'ngo_membership' => null]);
            ApplicantAcademicInfo::create(['application_id' => $a2->id, 'school_name' => 'State Engineering University of Armenia', 'institution_city' => 'Yerevan', 'institution_country' => 'Armenia', 'graduation_date' => '2021-07-01', 'graduation_year' => 2021, 'gpa' => 3.20, 'specialization' => 'Civil Construction', 'language_of_instruction' => 'Armenian']);
            ApplicantFamilyInfo::create(array_merge(['application_id' => $a2->id], $family));
            ApplicantTransferInfo::create(['application_id' => $a2->id, 'previous_institution_name' => 'State Engineering University of Armenia', 'previous_institution_country' => 'Armenia', 'previous_degree_program' => 'Civil Engineering', 'completed_semesters' => 4, 'earned_credits' => 120, 'reason_for_transfer' => 'Seeking a more internationally recognized program. MOSRAC offers better alignment with career goals.']);
            $this->seedDocuments($a2, $docTypes, true, 'bachelor');
            ApplicationLog::log($a2->id, 'Application submitted by applicant', $u2->id);
            $this->command->info('  OK App 2: Bachelor Transfer — Davit Hovhannisyan — SUBMITTED');
        }

        // ── APP 3: Master Regular — UNDER REVIEW ─────────────────────
        $u3 = $this->makeUser('master.regular@test.am', 'Lilit Grigoryan');
        $c3 = $this->getCycle('Information Technology', 'master');
        if ($c3) {
            $a3 = $this->makeApp($u3, $c3, false, Application::STATUS_UNDER_REVIEW, 5, 100, now()->subDays(10));
            $a3->update(['reviewed_by' => $admin?->id, 'reviewed_at' => now()->subDays(2)]);
            ApplicantPersonalInfo::create(['application_id' => $a3->id, 'first_name' => 'Lilit', 'last_name' => 'Grigoryan', 'date_of_birth' => '1999-03-08', 'gender' => 'female', 'passport_number' => 'AM9988776', 'country_of_birth' => 'Armenia', 'place_of_birth' => 'Vanadzor', 'resident_country' => 'Armenia', 'citizenship' => 'Armenian', 'nationality' => 'Armenian', 'marital_status' => 'single', 'city' => 'Yerevan', 'phone' => '+37477112233', 'address' => '42 Komitas Avenue Yerevan', 'armenian_language_proficiency' => 'Native', 'other_languages' => 'English (C2), German (B1)', 'ngo_membership' => 'Armenian Tech Hub']);
            ApplicantAcademicInfo::create(['application_id' => $a3->id, 'school_name' => 'Yerevan State University', 'institution_city' => 'Yerevan', 'institution_country' => 'Armenia', 'graduation_date' => '2021-06-01', 'graduation_year' => 2021, 'gpa' => 3.92, 'previous_university' => 'Yerevan State University', 'degree_obtained' => 'Bachelor of Science (BSc)', 'specialization' => 'Computer Science', 'language_of_instruction' => 'Armenian', 'additional_certifications' => [['name' => 'AWS Cloud Practitioner', 'month' => 6, 'year' => 2022]]]);
            ApplicantFamilyInfo::create(array_merge(['application_id' => $a3->id], $family));
            $this->seedDocuments($a3, $docTypes, false, 'master');
            ApplicationLog::log($a3->id, 'Application submitted by applicant', $u3->id);
            ApplicationLog::log($a3->id, 'Application marked as under review', $admin?->id);
            $this->command->info('  OK App 3: Master Regular — Lilit Grigoryan — UNDER REVIEW');
        }

        // ── APP 4: Master Transfer — SUBMITTED ───────────────────────
        $u4 = $this->makeUser('master.transfer@test.am', 'Saro Asatryan');
        $c4 = $this->getCycle('Engineering Management', 'master');
        if ($c4) {
            $a4 = $this->makeApp($u4, $c4, true, Application::STATUS_SUBMITTED, 6, 100, now()->subDays(1));
            ApplicantPersonalInfo::create(['application_id' => $a4->id, 'first_name' => 'Saro', 'last_name' => 'Asatryan', 'date_of_birth' => '1997-08-15', 'gender' => 'male', 'passport_number' => 'AM3322110', 'country_of_birth' => 'Armenia', 'place_of_birth' => 'Abovyan', 'resident_country' => 'Armenia', 'citizenship' => 'Armenian', 'nationality' => 'Armenian', 'marital_status' => 'married', 'city' => 'Yerevan', 'phone' => '+37455667788', 'address' => '9 Charents Street Yerevan', 'armenian_language_proficiency' => 'Native', 'other_languages' => 'English (B2), Russian (C1)', 'ngo_membership' => null]);
            ApplicantAcademicInfo::create(['application_id' => $a4->id, 'school_name' => 'National Internship Portal of Armenia', 'institution_city' => 'Yerevan', 'institution_country' => 'Armenia', 'graduation_date' => '2019-06-01', 'graduation_year' => 2019, 'gpa' => 3.45, 'previous_university' => 'National Internship Portal of Armenia', 'degree_obtained' => 'Bachelor of Engineering (BEng)', 'specialization' => 'Industrial Engineering', 'language_of_instruction' => 'Armenian']);
            ApplicantFamilyInfo::create(array_merge(['application_id' => $a4->id], $family));
            ApplicantTransferInfo::create(['application_id' => $a4->id, 'previous_institution_name' => 'Russian-Armenian University', 'previous_institution_country' => 'Armenia', 'previous_degree_program' => 'Master of Engineering Management', 'completed_semesters' => 2, 'earned_credits' => 60, 'reason_for_transfer' => 'Lack of practical industry exposure. MOSRAC Engineering Management has stronger industry ties and better career placement support.']);
            $this->seedDocuments($a4, $docTypes, true, 'master');
            ApplicationLog::log($a4->id, 'Application submitted by applicant', $u4->id);
            $this->command->info('  OK App 4: Master Transfer — Saro Asatryan — SUBMITTED');
        }

        // ── APP 5: PhD Draft — for manual wizard testing ──────────────
        // Personal + Academic pre-filled. Family + Docs empty intentionally.
        $u5 = $this->makeUser('phd.regular@test.am', 'Vardan Mkrtchyan');
        $c5 = $this->getCycle('Computer Science', 'phd');
        if ($c5) {
            $a5 = $this->makeApp($u5, $c5, false, Application::STATUS_DRAFT, 3, 40);
            ApplicantPersonalInfo::create(['application_id' => $a5->id, 'first_name' => 'Vardan', 'last_name' => 'Mkrtchyan', 'date_of_birth' => '1995-07-22', 'gender' => 'male', 'passport_number' => 'AM5544332', 'country_of_birth' => 'Armenia', 'place_of_birth' => 'Yerevan', 'resident_country' => 'Armenia', 'citizenship' => 'Armenian', 'nationality' => 'Armenian', 'marital_status' => 'married', 'city' => 'Yerevan', 'phone' => '+37493445566', 'address' => '3 Marshal Baghramyan Ave Yerevan', 'armenian_language_proficiency' => 'Native', 'other_languages' => 'English (C1), Russian (C2)', 'ngo_membership' => null]);
            ApplicantAcademicInfo::create(['application_id' => $a5->id, 'school_name' => 'American University of Armenia', 'institution_city' => 'Yerevan', 'institution_country' => 'Armenia', 'graduation_date' => '2017-05-01', 'graduation_year' => 2017, 'gpa' => 3.75, 'bachelor_gpa' => 3.75, 'previous_university' => 'American University of Armenia', 'degree_obtained' => 'Bachelor of Science (BSc)', 'specialization' => 'Computer Science', 'master_university' => 'National Internship Portal of Armenia', 'master_degree_obtained' => 'Master of Science (MSc)', 'master_gpa' => 3.90, 'research_topic' => 'Deep Learning for NLP in Armenian: Challenges for Low-Resource Language Models', 'language_of_instruction' => 'English']);
            $this->command->info('  OK App 5: PhD Draft — Vardan Mkrtchyan — DRAFT (complete family+docs manually)');
        }

        $this->command->info('');
        $this->command->info('  All passwords: password');
        $this->command->info('  bachelor.regular@test.am   — Bachelor Regular  — Submitted');
        $this->command->info('  bachelor.transfer@test.am  — Bachelor Transfer — Submitted');
        $this->command->info('  master.regular@test.am     — Master Regular    — Under Review');
        $this->command->info('  master.transfer@test.am    — Master Transfer   — Submitted');
        $this->command->info('  phd.regular@test.am        — PhD Draft         — Continue wizard');
    }

    // ── Private Helpers ───────────────────────────────────────────────

    /**
     * Deletes only the test applications from the 5 test accounts.
     * Users are kept. Their application history accumulates across runs
     * (which is correct — one user CAN have multiple applications).
     * We only remove the PREVIOUSLY SEEDED ones to start fresh each run.
     *
     * Because of cascade foreign keys in the schema, deleting an application
     * automatically removes: personal_info, academic_info, family_info,
     * transfer_info, documents, and logs linked to it.
     */
    private function cleanupPreviousTestApplications(): void
    {
        $userIds = User::whereIn('email', $this->testEmails)->pluck('id');
        $deleted = Application::whereIn('user_id', $userIds)->forceDelete();
        if ($deleted > 0) {
            $this->command->info('  Cleaned up ' . $deleted . ' previous test application(s).');
        }
    }

    private function ensureDocumentTypes(): void
    {
        $types = [
            ['name' => 'Passport',               'code' => 'passport',       'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Transcript',              'code' => 'transcript',     'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Birth Certificate',       'code' => 'birth_cert',     'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Health Report',           'code' => 'health_report',  'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Portrait Photo (4x5)',    'code' => 'portrait',       'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png',     'max_size' => 2048],
            ['name' => "Bachelor's Certificate",  'code' => 'edu_cert',       'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Transfer Credit Sheet',   'code' => 'transfer_sheet', 'is_required' => true,  'allowed_mimes' => 'jpg,jpeg,png,pdf', 'max_size' => 5120],
            ['name' => 'Recommendation Letter',   'code' => 'recommendation', 'is_required' => false, 'allowed_mimes' => 'pdf',              'max_size' => 5120],
            ['name' => 'Personal Statement',      'code' => 'statement',      'is_required' => false, 'allowed_mimes' => 'pdf',              'max_size' => 5120],
        ];
        foreach ($types as $dt) {
            DocumentType::firstOrCreate(['code' => $dt['code']], $dt);
        }
    }

    private function makeUser(string $email, string $name): User
    {
        return User::firstOrCreate(['email' => $email], [
            'name'              => $name,
            'password'          => Hash::make('password'),
            'role'              => 'student',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    private function getCycle(string $programName, string $level): ?AdmissionCycle
    {
        $program = Program::where('name', $programName)->where('degree_level', $level)->first();
        if (!$program) { $this->command->warn('  Program not found: ' . $programName); return null; }
        $cycle = AdmissionCycle::where('program_id', $program->id)->first();
        if (!$cycle) { $this->command->warn('  No cycle for: ' . $programName); return null; }
        return $cycle;
    }

    private function makeApp(
        User $user, AdmissionCycle $cycle, bool $isTransfer,
        string $status, int $step, int $pct, $submittedAt = null
    ): Application {
        return Application::create([
            'user_id'               => $user->id,
            'admission_cycle_id'    => $cycle->id,
            'program_id'            => $cycle->program_id,
            'is_transfer'           => $isTransfer,
            'status'                => $status,
            'current_step'          => $step,
            'completion_percentage' => $pct,
            'submitted_at'          => $submittedAt,
        ]);
    }

    /**
     * Seeds documents matching exactly what documents.blade.php shows.
     * Bachelor:   passport, transcript, birth_cert, health_report, portrait
     * Master/PhD: + edu_cert
     * Transfer:   + transfer_sheet
     * Certs:      + one proof per declared cert in academic step
     */
    private function seedDocuments(Application $app, $docTypes, bool $isTransfer, string $degreeLevel): void
    {
        $codes = ['passport', 'transcript', 'birth_cert', 'health_report', 'portrait'];

        if (in_array($degreeLevel, ['master', 'phd'])) {
            $codes[] = 'edu_cert';
        }
        if ($isTransfer) {
            $codes[] = 'transfer_sheet';
        }

        foreach ($codes as $code) {
            if (!isset($docTypes[$code])) {
                $this->command->warn('  Missing doc type in DB: ' . $code);
                continue;
            }
            ApplicationDocument::create([
                'application_id'   => $app->id,
                'document_type_id' => $docTypes[$code]->id,
                'file_path'        => $this->placeholder,
                'uploaded_at'      => now(),
                'is_verified'      => false,
            ]);
        }

        // Cert proof docs — one per declared certification
        foreach ($app->academicInfo?->additional_certifications ?? [] as $cert) {
            $certName = $cert['name'] ?? null;
            if (!$certName) continue;
            ApplicationDocument::create([
                'application_id'   => $app->id,
                'document_type_id' => null,
                'cert_name'        => $certName,
                'file_path'        => $this->placeholder,
                'uploaded_at'      => now(),
            ]);
        }
    }
}
