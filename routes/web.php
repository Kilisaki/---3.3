<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public product routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Protected product routes (create/update/delete)
    Route::resource('products', App\Http\Controllers\ProductController::class)->except(['index', 'show']);

    Route::get('products/trashed', [App\Http\Controllers\ProductController::class, 'trashed'])
        ->name('products.trashed');
    Route::patch('products/{id}/restore', [App\Http\Controllers\ProductController::class, 'restore'])
        ->name('products.restore');
    Route::delete('products/{id}/force-delete', [App\Http\Controllers\ProductController::class, 'forceDelete'])
        ->name('products.force-delete');

    // Users listing and user products (by username)
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');
    Route::get('users/{username}/objects', [App\Http\Controllers\UserController::class, 'objects'])
        ->name('users.objects');
});

require __DIR__.'/auth.php';
