<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\Admin\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$admin = User::first();
Auth::login($admin);

$controller = new NewsController();

try {
    $response = $controller->index();
    $viewContent = $response->render();
    echo "Rendered /admin/news successfully! (HTML length: " . strlen($viewContent) . ")\n";
} catch (\Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
