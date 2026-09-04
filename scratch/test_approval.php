<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== START APPROVAL TEST ===" . PHP_EOL;

$admin = User::where('role', 'admin')->first();
Auth::login($admin);

$application = Application::whereIn('status', [
    Application::STATUS_SUBMITTED,
    Application::STATUS_UNDER_REVIEW,
    Application::STATUS_SHORTLISTED,
    Application::STATUS_INTERVIEW_REQUIRED,
])->first();

if (!$application) {
    $application = Application::create([
        'user_id' => 4,
        'reference_number' => Application::generateReferenceNumber(),
        'status' => Application::STATUS_SUBMITTED,
        'submitted_at' => now(),
    ]);
}

echo "Testing approval on Application ID: {$application->id}" . PHP_EOL;

$controller = new \App\Http\Controllers\Admin\ApplicationController();
$response = $controller->approve($application->id);

echo "1. Status updated to: " . $application->fresh()->status . PHP_EOL;
echo "=== APPROVAL TEST PASSED ===" . PHP_EOL;
