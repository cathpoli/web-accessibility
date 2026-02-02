<?php

use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Client\AuditController;
use App\Http\Controllers\Client\Billing\BillingController;
use App\Http\Controllers\Client\Billing\CardController;
use App\Http\Controllers\Client\Billing\InvoiceController;
use App\Http\Controllers\Client\Billing\ProductController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\DomainController;
use App\Http\Controllers\Client\InstallationController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\SampleController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\FeedbackController;
use App\Http\Controllers\Client\DocumentationController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /** if user is on register.aceada.com, show the register page */
    if (config('app.url') == config('app.register_domain')) {
        return Inertia::render('Client/Auth/Register/BasicInformation', [
            'loginDomain' => config('app.dashboard_domain')
        ]);
    }

    return Redirect::to('login');
    // return Inertia::render('Welcome-old', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
})->name('/');

Route::get('sample-page', function () {
    return Inertia::render('Welcome');
});


/** Feeback */
Route::prefix('feedback/')->name('feedback.')->group(function () {
    Route::get('', [FeedbackController::class, 'index'])->name('index');
    Route::post('', [FeedbackController::class, 'send'])->name('send')->middleware('recaptcha');
});

Route::middleware('auth')->group(function () {
    /** Account Setup - Select Plan */
    Route::get('/plans', [ProductController::class, 'index'])->name('products.index');
    Route::post('/plans', [ProductController::class, 'select'])->name('products.select');

    /** Account Setup - Card Details */
    Route::get('/card-details', [CardController::class, 'index'])->name('cards.index');
    Route::post('/card-details', [CardController::class, 'store'])->name('cards.store');

    /** Account Setup - Finish */
    Route::get('/finish', [RegisteredUserController::class, 'finish'])->name('register.finish');

    /** Account Setup - Thank You */
    Route::get('/thank-you', [RegisteredUserController::class, 'thankYou'])->name('register.thank-you');

    /** Account - Profile */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** Accounts */
    Route::prefix('accounts/')->name('accounts.')->group(function () {
        Route::get('', [AccountController::class, 'index'])->name('index');
        Route::post('', [AccountController::class, 'store'])->name('store');
        Route::delete('member/{id}', [AccountController::class, 'destroy'])->name('member.destroy');
        Route::patch('member/{id}/{status}', [AccountController::class, 'updateMemberStatus'])->name('member.updateMemberStatus');
    });


    /** Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware('verified')->name('dashboard');
    Route::get('/dashboard/domains', [DashboardController::class, 'domains'])->name('dashboard-domains');

    /** Domains */
    Route::prefix('domains/')->name('domains.')->group(function () {
        Route::get('', [DomainController::class, 'index'])->name('index');
        Route::get('{domain}', [DomainController::class, 'show'])->name('show');

        Route::post('subscribe', [DomainController::class, 'subscribe'])->name('subscribe');
        Route::post('{domain}/cancel', [DomainController::class, 'cancel'])->name('cancel');
        Route::post('{domain}/resume', [DomainController::class, 'resume'])->name('resume');
        Route::post('{domain}/change-plan', [DomainController::class, 'changePlan'])->name('change-plan');
        
         /** Plans */
         Route::get('{domain}/get-plans', [DomainController::class, 'getPlans'])->name('get-plans'); 

        // API: called on debounce
        Route::post('validate', [DomainController::class, 'validateNewDomain'])->name('validate');
        
    });

    Route::get('get-plans', [DomainController::class, 'getPlans'])->name('get-plans');

    /** Billing */
    Route::prefix('billing/')->name('billing.')->group(function () {
        Route::get('', [BillingController::class, 'index'])->name('index');
        Route::get('invoices', [BillingController::class, 'invoices'])->name('billing.invoices');

        /** Billing - Cards */
        Route::get('cards/create', [CardController::class, 'index'])->name('cards.create'); /** Note: this url is used on if statements */
        Route::patch('cards/{card}/set-as-default', [CardController::class, 'setAsDefault'])->name('cards.set-as-default');
        Route::delete('cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');

        /** Billing - Invoices */
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{stripe_invoice_id}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    });

    /** Installation */
    Route::prefix('installation/')->name('installation.')->group(function () {
        Route::get('', [InstallationController::class, 'index'])->name('index');
    });

    /** Documention */
    Route::prefix('documentation/')->name('documentation.')->group(function () {
        Route::get('', [DocumentationController::class, 'index'])->name('index');
    });

    /** Sample Page */
    Route::get('/sample', [SampleController::class, 'index'])->name('sample.index');

    /** Audit Page */
    Route::get('/audit', [AuditController::class, 'index'])->name('audit');
    Route::post('/crawl', [AuditController::class, 'crawl'])->name('crawl');
    Route::get('/audit/report/download/{domain}', [AuditController::class, 'generateAuditPDF'])->name('audit.report.download');

     /** Validate Coupon */
     Route::post('/coupon/validate', [CouponController::class, 'validateCoupon'])->name('coupon.validate');
});

/** We can create custom route files and put them here once they got bloated */
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
include __DIR__.'/shopify.php';
