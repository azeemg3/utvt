<?php

use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\ProductController;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('invoice')->group(function () {
        Route::get('product/list-active', [ProductController::class, 'listActive'])->name('product.list_active');
        Route::resource('product', ProductController::class)->except(['create', 'show', 'update']);
        Route::resource('invoice', InvoiceController::class);
    });
});
