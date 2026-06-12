<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicSectionController;
use App\Http\Controllers\Student\FlashcardController;
use App\Http\Controllers\Student\FocusTimerController;
use App\Http\Controllers\Student\JournalController;
use App\Http\Controllers\Student\NoteController;
use App\Http\Controllers\Student\ResourceController;
use App\Http\Controllers\Student\StudyPlannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Topics
    Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
    Route::get('/topics/create', [TopicController::class, 'create'])->name('topics.create');
    Route::post('/topics', [TopicController::class, 'store'])->name('topics.store');
    Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
    Route::get('/topics/{topic}/edit', [TopicController::class, 'edit'])->name('topics.edit');
    Route::match(['put', 'patch'], '/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

    // Topic Sections
    Route::post('/topics/{topic}/sections', [TopicSectionController::class, 'store'])->name('sections.store');
    Route::match(['put', 'patch'], '/topics/{topic}/sections/{section}', [TopicSectionController::class, 'update'])->name('sections.update');
    Route::delete('/topics/{topic}/sections/{section}', [TopicSectionController::class, 'destroy'])->name('sections.destroy');

    // Learning Sessions
    Route::post('/topics/{topic}/session/end', [LearningSessionController::class, 'end'])->name('sessions.end');

    // Bookmarks
    Route::post('/bookmarks/{topic}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');

    // Notes
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}', [NoteController::class, 'show'])->name('notes.show');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::match(['put', 'patch'], '/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('/notes/tags', [NoteController::class, 'tags'])->name('notes.tags.store');
    Route::delete('/notes/tags/{tag}', [NoteController::class, 'destroyTag'])->name('notes.tags.destroy');

    // Flashcards
    Route::get('/flashcards', [FlashcardController::class, 'index'])->name('flashcards.index');
    Route::get('/flashcards/create', [FlashcardController::class, 'create'])->name('flashcards.create');
    Route::post('/flashcards', [FlashcardController::class, 'store'])->name('flashcards.store');
    Route::get('/flashcards/{flashcardDeck}', [FlashcardController::class, 'show'])->name('flashcards.show');
    Route::match(['put', 'patch'], '/flashcards/{flashcardDeck}', [FlashcardController::class, 'update'])->name('flashcards.update');
    Route::delete('/flashcards/{flashcardDeck}', [FlashcardController::class, 'destroy'])->name('flashcards.destroy');
    Route::get('/flashcards/{flashcardDeck}/review', [FlashcardController::class, 'review'])->name('flashcards.review');
    Route::post('/flashcards/{flashcardDeck}/cards', [FlashcardController::class, 'storeCard'])->name('flashcards.cards.store');
    Route::delete('/flashcards/{flashcardDeck}/cards/{card}', [FlashcardController::class, 'destroyCard'])->name('flashcards.cards.destroy');
    Route::post('/flashcards/{flashcardDeck}/cards/{card}/review', [FlashcardController::class, 'submitReview'])->name('flashcards.review.submit');

    // Study Planner
    Route::get('/planner', [StudyPlannerController::class, 'index'])->name('planner.index');
    Route::post('/planner/goals', [StudyPlannerController::class, 'storeGoal'])->name('planner.goals.store');
    Route::match(['put', 'patch'], '/planner/goals/{studyGoal}', [StudyPlannerController::class, 'updateGoal'])->name('planner.goals.update');
    Route::patch('/planner/goals/{studyGoal}/progress', [StudyPlannerController::class, 'updateGoalProgress'])->name('planner.goals.progress');
    Route::patch('/planner/goals/{studyGoal}/complete', [StudyPlannerController::class, 'completeGoal'])->name('planner.goals.complete');
    Route::delete('/planner/goals/{studyGoal}', [StudyPlannerController::class, 'destroyGoal'])->name('planner.goals.destroy');
    Route::post('/planner/sessions', [StudyPlannerController::class, 'storeSession'])->name('planner.sessions.store');
    Route::match(['put', 'patch'], '/planner/sessions/{plannedSession}', [StudyPlannerController::class, 'updateSession'])->name('planner.sessions.update');
    Route::patch('/planner/sessions/{plannedSession}/complete', [StudyPlannerController::class, 'completeSession'])->name('planner.sessions.complete');
    Route::delete('/planner/sessions/{plannedSession}', [StudyPlannerController::class, 'destroySession'])->name('planner.sessions.destroy');

    // Resources
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::match(['put', 'patch'], '/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    Route::patch('/resources/{resource}/favourite', [ResourceController::class, 'toggleFavourite'])->name('resources.favourite');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');

    // Journal
    Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
    Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
    Route::match(['put', 'patch'], '/journal/{journalEntry}', [JournalController::class, 'update'])->name('journal.update');
    Route::delete('/journal/{journalEntry}', [JournalController::class, 'destroy'])->name('journal.destroy');

    // Focus Timer
    Route::get('/focus', [FocusTimerController::class, 'index'])->name('focus.index');
    Route::post('/focus/sessions', [FocusTimerController::class, 'store'])->name('focus.sessions.store');
    Route::get('/focus/history', [FocusTimerController::class, 'history'])->name('focus.history');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
