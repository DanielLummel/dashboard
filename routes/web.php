<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\TimerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/search', GlobalSearchController::class)->name('search.global');

    Route::resource('projects', ProjectController::class);
    Route::resource('notes', NoteController::class);
    Route::post('/notes/preview', [NoteController::class, 'preview'])->name('notes.preview');

    Route::resource('snippets', SnippetController::class);

    Route::resource('time-entries', TimeEntryController::class)->except(['show']);
    Route::get('/time/export/csv', [TimeEntryController::class, 'export'])->name('time-entries.export');
    Route::post('/time/start', [TimerController::class, 'start'])->name('timer.start');
    Route::post('/time/stop', [TimerController::class, 'stop'])->name('timer.stop');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::view('/modules/tasks', 'modules.placeholder', [
        'title' => 'Tasks / Mini-Kanban',
        'description' => 'Vorbereitet: Routing + DB-Tabellen vorhanden, UI folgt im nächsten Schritt.',
    ])->name('modules.tasks');

    Route::view('/modules/daily-log', 'modules.placeholder', [
        'title' => 'Daily Log',
        'description' => 'Vorbereitet: Routing + DB-Tabellen vorhanden, UI folgt im nächsten Schritt.',
    ])->name('modules.daily-log');

    Route::view('/modules/bookmarks', 'modules.placeholder', [
        'title' => 'Bookmarks',
        'description' => 'Vorbereitet: Routing + DB-Tabellen vorhanden, UI folgt im nächsten Schritt.',
    ])->name('modules.bookmarks');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
