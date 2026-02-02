<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentationController;
use App\Http\Controllers\Admin\InstallationController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SampleController;
use App\Http\Controllers\Admin\SiteWidgetController;
use App\Http\Controllers\Admin\AuditsController;
use App\Http\Controllers\Admin\WhitelistController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /** Profile */
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

        /** Clients */
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/list', [ClientController::class, 'list'])->name('clients.list');
        Route::get('clients/{id}', [ClientController::class, 'show'])->name('clients.show');
        Route::get('clients/domain/list', [ClientController::class, 'domainList'])->name('clients.domainList');
        Route::get('clients/member/list', [ClientController::class, 'memberList'])->name('clients.memberList');
        Route::get('clients/domain/{domain}', [ClientController::class, 'domainDetails'])->name('clients.domain');

        /** Payments */
        Route::get('payments/index', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/list', [PaymentController::class, 'list'])->name('payments.list');

        /** Invoices */
        Route::get('invoices/index', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/list', [InvoiceController::class, 'list'])->name('invoices.list');

        /** Coupons */
        Route::get('coupons/index', [CouponController::class, 'index'])->name('coupons.index');
        Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('coupons/store', [CouponController::class, 'store'])->name('coupons.store');
        Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

        Route::post('coupons/store-period-off', [CouponController::class, 'storePeriodOff'])->name('coupons.store-period-off');


        Route::get('coupons/list', [CouponController::class, 'list'])->name('coupons.list');

        /** Whitelists */
        Route::resource('whitelists', WhitelistController::class)
                ->except(['show', 'edit', 'update'])
                ->parameters(['whitelists' => 'domain']);

        Route::get('whitelists/{domain}/installation', [WhitelistController::class, 'installation'])->name('whitelists.installation');

        /**  */
        Route::get('sample/index', [SampleController::class, 'index'])->name('sample.index');

        /** Site Widget */
        Route::get('site-widget', [SiteWidgetController::class, 'index'])->name('site-widget.index');

        /** Audits */
        Route::get('audits', [AuditsController::class, 'index'])->name('audits.index');
        
        /** Installation */
        Route::get('installation', [InstallationController::class, 'index'])->name('installation.index');

        /** Documention */
        Route::prefix('documentation/')->name('documentation.')->group(function () {
            Route::get('', [DocumentationController::class, 'index'])->name('index');

        });
    });
});
