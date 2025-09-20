<?php

use App\Http\Controllers\GroceryController;
use App\Http\Controllers\GroceryListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/grocery-lists', [GroceryListController::class, 'index'])->name('grocery-list.index');
    Route::get('/grocery-lists/{id}', [GroceryListController::class, 'show'])->name('grocery-list.show');
    Route::post('/grocery-lists', [GroceryListController::class, 'store'])->name('grocery-list.store');

    Route::post('/grocery', [GroceryController::class, 'store'])->name('grocery.store');
    Route::delete('/grocery/{grocery}', [GroceryController::class, 'destroy'])
        ->name('groceries.destroy');
    Route::patch('/grocery/{grocery}', [GroceryController::class, 'update'])
        ->name('groceries.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
