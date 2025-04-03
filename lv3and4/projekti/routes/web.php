<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->resource('projects', ProjectController::class);

Route::middleware('auth')->prefix('projects/{project}')->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');

    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');

    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');

    Route::put('tasks/{task}/complete', [TaskController::class, 'updateCompletion'])->name('tasks.complete');

    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';
