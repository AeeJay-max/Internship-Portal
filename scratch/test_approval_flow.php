<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Application;
use App\Models\User;
use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

$applicant = User::first();
$testApp = Application::create([
    'user_id' => $applicant->id,
    'reference_number' => 'MoSRAC-TEST-' . time(),
    'status' => Application::STATUS_SUBMITTED,
    'current_step' => 4,
    'completion_percentage' => 100,
    'submitted_at' => now(),
]);

echo "Created Test Application ID: " . $testApp->id . " with status: " . $testApp->status . "\n";

$controller = new ApplicationController();

// Test approve
try {
    $response = $controller->approve($testApp->id);
    $testApp->refresh();
    echo "Approval succeeded! New status: " . $testApp->status . "\n";
} catch (\Throwable $e) {
    echo "Approval FAILED: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

$testApp->delete();
echo "Cleaned up test application.\n";
