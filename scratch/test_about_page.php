<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;

$request = Request::create('/about', 'GET');

try {
    $response = $app->handle($request);
    echo "Rendered /about successfully! Status: " . $response->getStatusCode() . " (Content length: " . strlen($response->getContent()) . ")\n";
} catch (\Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
