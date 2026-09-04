<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicOpportunityController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Language Switcher
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang.switch')
    ->where('locale', 'en|ru|hy');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function() {
    $news = \App\Models\News::published()->limit(6)->get();
    $departments = \App\Models\Department::where('is_active', true)->limit(6)->get();
    $openOpportunities = \App\Models\InternshipOpportunity::with('department')->where('status', 'open')->latest()->limit(6)->get();
    return view('home', compact('news', 'departments', 'openOpportunities'));
})->name('home');

Route::get('/opportunities',        [PublicOpportunityController::class, 'index'])->name('opportunities.index');
Route::get('/opportunities/{id}',   [PublicOpportunityController::class, 'show'])->name('opportunities.show');

Route::get('/news',        [App\Http\Controllers\PublicNewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [App\Http\Controllers\PublicNewsController::class, 'show'])->name('news.show');

Route::get('/apply', fn() => redirect()->route('application.selectType'))->name('apply.start');
Route::get('/about', fn() => view('about'))->name('about');

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Applicant Portal Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::post('/notifications/{id}/read', function ($id) {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    // ── Application Wizard ──────────────────────────────────────────────
    Route::get('/application/type',        [ApplicationController::class, 'selectType'])->name('application.selectType');
    Route::post('/application/type',       [ApplicationController::class, 'createFromType'])->name('application.createFromType');
    Route::delete('/application/draft/{id}', [ApplicationController::class, 'destroyDraft'])->name('application.destroyDraft');

    // Step 1 — Personal Info
    Route::get('/application/personal',    [ApplicationController::class, 'personal'])->name('application.personal');
    Route::post('/application/personal',   [ApplicationController::class, 'storePersonal'])->name('application.personal.store');

    // Step 2 — Academic Info
    Route::get('/application/academic',    [ApplicationController::class, 'academic'])->name('application.academic');
    Route::post('/application/academic',   [ApplicationController::class, 'storeAcademic'])->name('application.academic.store');

    // Step 3 — Internship Preferences
    Route::get('/application/preferences',  [ApplicationController::class, 'preferences'])->name('application.preferences');
    Route::post('/application/preferences', [ApplicationController::class, 'storePreferences'])->name('application.preferences.store');

    // Step 4 — Motivation
    Route::get('/application/motivation',  [ApplicationController::class, 'motivation'])->name('application.motivation');
    Route::post('/application/motivation', [ApplicationController::class, 'storeMotivation'])->name('application.motivation.store');

    // Step 5 — Documents
    Route::get('/application/documents',   [ApplicationController::class, 'documents'])->name('application.documents');
    Route::post('/application/documents',  [ApplicationController::class, 'storeDocuments'])->name('application.documents.store');

    // Step 6 — Review & Submit
    Route::get('/application/review',      [ApplicationController::class, 'review'])->name('application.review');
    Route::post('/application/submit',     [ApplicationController::class, 'submit'])->name('application.submit');
    Route::post('/application/autosave',   [ApplicationController::class, 'autosave'])->name('application.autosave');
    Route::get('/application/progress',   [ApplicationController::class, 'progressData'])->name('application.progress');

    // Specific submitted application detail
    Route::get('/application/{id}',           [ApplicationController::class, 'show'])->name('application.show');
    Route::post('/application/{id}/reupload', [ApplicationController::class, 'reuploadDocuments'])->name('application.reupload');

    // Banner clear session helper
    Route::post('/verification/banner-clear', function() {
        session()->forget('show_verify_banner');
        return response()->json(['ok' => true]);
    })->name('verification.banner.clear');

    // User Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin / Placement Officer Role Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Application Review Workflow
        Route::get('/applications',                            [Admin\ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}',                       [Admin\ApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{id}/under-review',        [Admin\ApplicationController::class, 'markUnderReview'])->name('applications.underReview');
        Route::post('/applications/{id}/request-documents',   [Admin\ApplicationController::class, 'requestDocuments'])->name('applications.requestDocuments');
        Route::post('/applications/{id}/shortlist',           [Admin\ApplicationController::class, 'shortlist'])->name('applications.shortlist');
        Route::post('/applications/{id}/request-interview',   [Admin\ApplicationController::class, 'requestInterview'])->name('applications.requestInterview');
        Route::post('/applications/{id}/approve',             [Admin\ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('/applications/{id}/reject',              [Admin\ApplicationController::class, 'reject'])->name('applications.reject');

        // Department Management
        Route::resource('departments', Admin\DepartmentController::class);

        // Internship Opportunities Management
        Route::resource('opportunities', Admin\InternshipOpportunityController::class);

        // Placement Management
        Route::get('/placements',                [Admin\PlacementController::class, 'index'])->name('placements.index');
        Route::post('/placements/{id}',          [Admin\PlacementController::class, 'store'])->name('placements.store');

        // Applicants / CRM
        Route::get('/applicants',                [Admin\ApplicantController::class, 'index'])->name('applicants.index');
        Route::post('/applicants/{id}/restore',  [Admin\ApplicantController::class, 'restore'])->name('applicants.restore');

        // News Management
        Route::get('/news',                      [Admin\NewsController::class, 'index'])->name('news.index');
        Route::get('/news/create',               [Admin\NewsController::class, 'create'])->name('news.create');
        Route::post('/news',                     [Admin\NewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit',          [Admin\NewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}',               [Admin\NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}',            [Admin\NewsController::class, 'destroy'])->name('news.destroy');
        Route::post('/news/{news}/toggle-featured', [Admin\NewsController::class, 'toggleFeatured'])->name('news.toggleFeatured');

        // Admin Users Management
        Route::get('/admins',                    [Admin\AdminUserController::class, 'index'])->name('users.index');
        Route::get('/admins/create',             [Admin\AdminUserController::class, 'create'])->name('users.create');
        Route::post('/admins',                   [Admin\AdminUserController::class, 'store'])->name('users.store');
        Route::delete('/admins/{user}',          [Admin\AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::post('/admins/{user}/reset-password', [Admin\AdminUserController::class, 'resetPassword'])->name('users.resetPassword');
    });

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
