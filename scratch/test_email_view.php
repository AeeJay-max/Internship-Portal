<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = new \App\Models\User(['name' => 'Test User', 'email' => 'test@example.com']);
$user->id = 1;
$notification = new \App\Notifications\CustomVerifyEmail();
$mailable = $notification->toMail($user);

$html = $mailable->render();
echo "--- EMAIL HEADER SUBSTRING CHECK ---\n";
if (str_contains($html, 'National internship portal of the Ministry of Sport, Recreation, Arts & Culture')) {
    echo "SUCCESS: Found exact expected text!\n";
} else {
    echo "FAILED: Text not found in rendered email.\n";
    echo $html;
}
