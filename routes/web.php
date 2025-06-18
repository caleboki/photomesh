<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\BookmarkController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = Auth::user();
    $photos = $user->photos()->latest()->get();

    return Inertia::render('Dashboard', [
        'photos' => $photos,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/photos', [PhotoController::class, 'index'])->middleware(['auth', 'verified'])->name('photos.index');
Route::post('/photos', [PhotoController::class, 'store'])->middleware(['auth', 'verified'])->name('photos.store');
Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->middleware(['auth', 'verified'])->name('photos.destroy');
Route::get('/photos/discover', [PhotoController::class, 'discover'])->middleware(['auth', 'verified'])->name('photos.discover');

// Bookmark routes
Route::post('/photos/{photo}/bookmarks', [BookmarkController::class, 'store'])->middleware(['auth', 'verified'])->name('photos.bookmarks.store');
Route::delete('/photos/{photo}/bookmarks', [BookmarkController::class, 'destroy'])->middleware(['auth', 'verified'])->name('photos.bookmarks.destroy');

Route::get('/bookmarks', [BookmarkController::class, 'index'])->middleware(['auth', 'verified'])->name('bookmarks.index');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
