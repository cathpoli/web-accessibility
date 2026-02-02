<?php

use App\Http\Controllers\Api\ShopifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** shopify configuration */
Route::prefix('shopify')->name('shopify.')->group(function () {
    Route::get('/install', [ShopifyController::class, 'install'])->name('install');
    Route::get('/generate_token', [ShopifyController::class, 'generateToken'])->name('generate_token');
});