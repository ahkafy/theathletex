<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ...existing routes...
    Route::get('/events/{event}/categories', [DashboardController::class, 'getEventCategories'])->name('events.categories');
});
