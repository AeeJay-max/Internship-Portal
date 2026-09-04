<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Admin\ApplicantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

$controller = new ApplicantController();
$request = Request::create('/admin/applicants', 'GET');

try {
    $response = $controller->index($request);
    $viewContent = $response->render();
    echo "Rendered /admin/applicants successfully! (HTML length: " . strlen($viewContent) . ")\n";
} catch (\Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
