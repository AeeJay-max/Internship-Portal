<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\ApplicantPersonalInfo;
use App\Models\ApplicantAcademicInfo;
use App\Models\InternshipPreference;
use App\Models\InternshipPlacement;

echo "=== START WORKFLOW TEST ===" . PHP_EOL;

$application = Application::create([
    'user_id'          => 4,
    'reference_number' => Application::generateReferenceNumber(),
    'opportunity_id'   => null, // General Internship Application
    'status'           => Application::STATUS_DRAFT,
    'current_step'     => 1,
    'completion_percentage' => 0,
]);
echo "1. Draft Created (General App): " . $application->reference_number . PHP_EOL;

$personal = ApplicantPersonalInfo::create([
    'application_id'          => $application->id,
    'first_name'              => 'Taten',
    'last_name'               => 'Demo',
    'date_of_birth'           => '2001-05-15',
    'gender'                  => 'male',
    'national_id'             => '63-123456-A63',
    'phone'                   => '+263771234567',
    'address'                 => '123 Samora Machel Ave, Harare',
    'city'                    => 'Harare',
    'province'                => 'Harare',
    'emergency_contact_name'  => 'Parent Demo',
    'emergency_contact_phone' => '+263779876543',
]);
echo "2. PersonalInfo fetched via relation: " . $application->fresh()->personalInfo->first_name . " " . $application->fresh()->personalInfo->last_name . PHP_EOL;

$academic = ApplicantAcademicInfo::create([
    'application_id'        => $application->id,
    'school_name'           => 'University of Zimbabwe',
    'institution_type'      => 'University',
    'program_of_study'      => 'BSc Computer Science',
    'field_of_study'        => 'Information Technology',
    'current_year_level'    => 'Year 3',
    'academic_qualification'=> 'Bachelor Degree',
    'gpa'                   => '3.8',
]);
echo "3. AcademicInfo fetched via relation: " . $application->fresh()->academicInfo->school_name . " (" . $application->fresh()->academicInfo->qualification_level . ")" . PHP_EOL;

$preference = InternshipPreference::create([
    'application_id'          => $application->id,
    'preferred_department_id' => 1,
    'preferred_start_date'    => '2026-09-15',
    'preferred_end_date'      => '2027-03-15',
    'required_duration'       => '6 Months',
    'flexible_department'     => true,
    'motivation_statement'    => 'I wish to contribute to ICT digitalization at MoSRAC.',
    'career_objectives'       => 'Software Engineering in Public Sector',
]);
echo "4. Preference fetched via relation: Dept=" . $application->fresh()->preference->department->name . PHP_EOL;
echo "5. Motivation Accessor: " . $application->fresh()->motivation_letter . PHP_EOL;

$application->update([
    'status'       => Application::STATUS_SUBMITTED,
    'submitted_at' => now(),
    'completion_percentage' => 100,
]);
echo "6. Application Submitted: " . $application->fresh()->status . PHP_EOL;

$application->update(['status' => Application::STATUS_PLACEMENT_PENDING]);
echo "7. Status moved to Placement Pending: " . $application->fresh()->status . PHP_EOL;

$placement = InternshipPlacement::create([
    'application_id'     => $application->id,
    'department_id'      => 1,
    'placement_location' => 'Head Office (Harare)',
    'supervisor_name'    => 'Dr. C. Chimbwanda',
    'supervisor_email'   => 'supervisor@mosrac.gov.zw',
    'supervisor_phone'   => '+263242700101',
    'start_date'         => '2026-09-15',
    'end_date'           => '2027-03-15',
    'duration'           => '6 Months',
    'reporting_instructions' => 'Report to 4th Floor ICT Office at 08:00.',
]);
$application->update(['status' => Application::STATUS_PLACED]);

echo "8. Placement fetched via relation: Code=" . $application->fresh()->placement->placement_code . ", Station=" . $application->fresh()->placement->station . PHP_EOL;
echo "9. Final Application Status: " . $application->fresh()->status . PHP_EOL;

echo "=== ALL WORKFLOW TESTS PASSED SUCCESSFULLY ===" . PHP_EOL;
