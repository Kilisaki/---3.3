<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Ресурсный маршрут для продуктов
Route::resource('products', ProductController::class);

// Дополнительные маршруты для soft deletes
Route::prefix('products')->group(function () {
    Route::get('/trashed', [ProductController::class, 'trashed'])
        ->name('products.trashed');
    Route::patch('/{id}/restore', [ProductController::class, 'restore'])
        ->name('products.restore');
    Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.force-delete');
});

// Страница с баннерами
Route::get('/home', function () {
    return view('home');
})->name('home');