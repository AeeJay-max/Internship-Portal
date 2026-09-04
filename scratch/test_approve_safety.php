<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Admin\ApplicationController;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

$controller = new ApplicationController();

try {
    $response = $controller->approve(4);
    echo "Approve response status: " . $response->getStatusCode() . "\n";
    echo "Session message: " . session('info') . session('success') . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
