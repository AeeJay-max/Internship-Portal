<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$application = App\Models\Application::find(4);
if ($application) {
    echo "Application #4 ID: " . $application->id . "\n";
    echo "Status: " . $application->status . "\n";
    echo "User ID: " . $application->user_id . "\n";
    if ($application->user) {
        echo "User Email: " . $application->user->email . "\n";
    }
} else {
    echo "Application #4 not found.\n";
}
