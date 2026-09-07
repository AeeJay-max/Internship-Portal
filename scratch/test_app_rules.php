<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Application;

$user = User::where('role', 'applicant')->first() ?? User::first();

echo "Testing User Model Application Rules:\n";
echo "User ID: " . $user->id . "\n";
echo "User Name: " . $user->name . "\n";
echo "Has General Application: " . ($user->hasGeneralApplication() ? 'YES' : 'NO') . "\n";
echo "Has Opportunity Application: " . ($user->hasOpportunityApplication() ? 'YES' : 'NO') . "\n";
echo "Has Reached Max Applications: " . ($user->hasReachedMaxApplications() ? 'YES' : 'NO') . "\n";
$dept = $user->getAppliedDepartment();
echo "Applied Department: " . ($dept ? $dept->name . ' (ID: ' . $dept->id . ')' : 'NONE') . "\n";

echo "SUCCESS: Logic parsed with zero errors!\n";
