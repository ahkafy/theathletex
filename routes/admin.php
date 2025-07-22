<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\EventResultController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Reports Routes
        Route::get('/reports/participants', [DashboardController::class, 'participants'])->name('reports.participants');
        Route::get('/reports/transactions', [DashboardController::class, 'transactions'])->name('reports.transactions');
        Route::get('/reports/events', [DashboardController::class, 'events'])->name('reports.events');
        Route::get('/reports/revenue', [DashboardController::class, 'revenue'])->name('reports.revenue');
        Route::get('/reports/analytics', [DashboardController::class, 'analytics'])->name('reports.analytics');

        // Export Routes
        Route::get('/export/participants', [DashboardController::class, 'exportParticipants'])->name('export.participants');
        Route::get('/export/transactions', [DashboardController::class, 'exportTransactions'])->name('export.transactions');

        // Events Routes
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

        Route::post('/events/event/fees', [EventController::class, 'storeFees'])->name('fees.store');
        Route::post('/events/event/category', [EventController::class, 'storeCategories'])->name('categories.store');

        // Event Results Import Routes (before main routes to avoid conflicts)
        Route::get('/events/{event}/results/import', [EventResultController::class, 'showImport'])->name('events.results.import.show');
        Route::post('/events/{event}/results/import', [EventResultController::class, 'import'])->name('events.results.import');
        Route::get('/results/sample-download', [EventResultController::class, 'downloadSample'])->name('events.results.download-sample');

        // Event Results Routes
        Route::get('/events/{event}/results', [EventResultController::class, 'adminIndex'])->name('events.results.index');
        Route::get('/events/{event}/results/create', [EventResultController::class, 'create'])->name('events.results.create');
        Route::post('/events/{event}/results', [EventResultController::class, 'store'])->name('events.results.store');
        Route::get('/events/{event}/results/{result}/edit', [EventResultController::class, 'edit'])->name('events.results.edit');
        Route::put('/events/{event}/results/{result}', [EventResultController::class, 'update'])->name('events.results.update');
        Route::delete('/events/{event}/results/{result}', [EventResultController::class, 'destroy'])->name('events.results.destroy');

        // Add other admin routes here
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    });
});
