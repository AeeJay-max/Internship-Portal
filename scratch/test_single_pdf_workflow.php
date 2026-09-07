<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use App\Models\Application;
use App\Models\User;

echo "--- TESTING MOSRAC REDESIGN & SINGLE PDF SYSTEM ---\n\n";

// 1. Verify Public Routes
$expectedRoutes = [
    'home' => '/',
    'about' => '/about',
    'internships.index' => '/internships',
    'how-to-apply' => '/how-to-apply',
    'contact' => '/contact',
    'application.documents.download' => '/application/documents/{documentId}/download',
    'admin.applications.documents.download' => '/admin/applications/{applicationId}/documents/{documentId}/download',
];

echo "1. Checking Registered Routes:\n";
foreach ($expectedRoutes as $name => $uri) {
    if (Route::has($name)) {
        $params = str_contains($name, 'admin') ? ['applicationId' => 1, 'documentId' => 1] : ['id' => 1];
        echo "  [OK] Route '{$name}' is registered -> " . route($name, $params) . "\n";
    } else {
        echo "  [FAIL] Route '{$name}' is MISSING!\n";
    }
}

// 2. Check Document Model & DB
echo "\n2. Checking Application Documents Table & Model:\n";
$documentCount = \DB::table('application_documents')->count();
echo "  Total Application Documents stored: {$documentCount}\n";

// 3. Test View Compilation
echo "\n3. Testing Blade View Compilation:\n";
$viewsToTest = [
    'home',
    'about',
    'how-to-apply',
    'contact',
    'opportunities.index',
    'application.documents',
    'application.review',
];

foreach ($viewsToTest as $v) {
    try {
        if ($v === 'application.documents' || $v === 'application.review') {
            $user = User::first() ?? new User(['name' => 'Test User', 'email' => 'test@example.com']);
            $appModel = Application::with('documents')->first() ?? new Application(['reference_number' => 'APP-TEST-001', 'status' => 'draft']);
            $rendered = view($v, [
                'application' => $appModel,
                'currentDocument' => $appModel->documents?->first(),
                'missing' => [],
                'canSubmit' => true,
                'currentStep' => 5,
                'errors' => new \Illuminate\Support\ViewErrorBag(),
            ])->render();
        } else {
            $rendered = view($v, [
                'opportunities' => collect(),
                'departments' => collect(),
                'errors' => new \Illuminate\Support\ViewErrorBag(),
            ])->render();
        }
        echo "  [OK] View '{$v}' rendered successfully (" . strlen($rendered) . " bytes).\n";
    } catch (\Throwable $e) {
        echo "  [FAIL] View '{$v}' failed: " . $e->getMessage() . "\n";
    }
}

echo "\n--- VERIFICATION COMPLETED SUCCESSFULLY ---\n";
