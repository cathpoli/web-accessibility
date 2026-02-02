<?php

use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\ShopifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('verify', [VerificationController::class, 'verify'])->name('api.verify');

/** widget configuration */
Route::get('{domain}/widget-configurations', [WidgetController::class, 'show'])->name('widget-configurations.show');
Route::post('{domain}/widget-configurations', [WidgetController::class, 'store'])->name('widget-configurations.store');
Route::post('{domain}/widget-configurations/take-domain-browsershot', [WidgetController::class, 'takeDomainBrowsershot'])->name('take-domain-browsershot');
