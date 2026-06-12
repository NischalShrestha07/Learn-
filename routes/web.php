<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public routes
Route::get('/topics/search', [TopicController::class, 'search'])->name('topics.search');
Route::get('/topics/{slug}/status', [TopicController::class, 'status'])->name('topics.status');
Route::get('/topics/{slug}', [TopicController::class, 'show'])->name('topics.show');

// Auth-required routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::post('/topics/generate', [TopicController::class, 'generate'])->name('topics.generate');
    Route::post('/topics/{slug}/session/end', [LearningSessionController::class, 'end'])->name('sessions.end');

    Route::post('/bookmarks/{topic}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
