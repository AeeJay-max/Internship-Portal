<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\User;
use App\Http\Controllers\Admin\ApplicationController as AdminAppController;
use App\Http\Controllers\ApplicationController as ApplicantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

// Create fresh test application
$applicant = User::where('role', 'applicant')->first() ?? User::first();
$testApp = Application::create([
    'user_id' => $applicant->id,
    'reference_number' => 'MoSRAC-ACC-' . time(),
    'status' => Application::STATUS_SUBMITTED,
    'current_step' => 4,
    'completion_percentage' => 100,
    'submitted_at' => now(),
]);

echo "1. Created Test Application #{$testApp->id} with status: {$testApp->status}\n";

// 2. Admin Approve with required onboarding documents
$adminController = new AdminAppController();
$approveReq = Request::create("/admin/applications/{$testApp->id}/approve", "POST", [
    'required_docs' => [
        'Original National ID / Passport & Copies',
        'Official University Recommendation / Introduction Letter',
        'Certified Academic Transcripts & Certificates',
    ],
    'review_notes' => 'Please report to Mukwati Building, 3rd Floor, Harare by 09:00 AM on Monday.',
]);

$response = $adminController->approve($approveReq, $testApp->id);
$testApp->refresh();

echo "2. Admin Approved Application #{$testApp->id}.\n";
echo "   New Status: {$testApp->status}\n";
echo "   Review Notes Saved:\n   ---\n   {$testApp->review_notes}\n   ---\n";

// 3. Test Applicant View rendering
Auth::login($applicant);
$applicantController = new ApplicantController();

$showRequest = Request::create("/application/{$testApp->id}", "GET");
$showRes = $applicantController->show($testApp->id);
$html = $showRes->render();

if (str_contains($html, 'Application Approved') && str_contains($html, 'REQUIRED ONBOARDING DOCUMENTS TO BRING / ATTACH')) {
    echo "3. Applicant View rendered SUCCESSFUL! Acceptance card & onboarding requirements displayed.\n";
} else {
    echo "3. Applicant View rendering FAILED to display requirements.\n";
}

// Clean up
$testApp->delete();
echo "4. Test completed & cleaned up successfully.\n";
