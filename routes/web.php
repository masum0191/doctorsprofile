<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\DocumentationController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostTypeController;
use App\Http\Controllers\Admin\DoctorPostController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\CompanyIncomeController;
use App\Http\Controllers\Admin\AdminSubscriptionController;



foreach (config('tenancy.central_domains') as $domain) {
   // dd($domain);
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
  return view('sass');
});
Route::get('/about', function () {
    $setting = \App\Models\CompanySetting::first() ?? new \App\Models\CompanySetting();

    return view('about', compact('setting'));
})->name('about');
Route::get('/login', function(){
     return redirect()->route('admin.login');
});

Route::match(['get', 'post'], '/sslcommerz/success', [\App\Http\Controllers\Api\DoctorRegistrationController::class, 'sslcommerzSuccess'])
    ->name('sslcommerz.success');
Route::match(['get', 'post'], '/sslcommerz/fail', [\App\Http\Controllers\Api\DoctorRegistrationController::class, 'sslcommerzFail'])
    ->name('sslcommerz.fail');
Route::match(['get', 'post'], '/sslcommerz/cancel', [\App\Http\Controllers\Api\DoctorRegistrationController::class, 'sslcommerzCancel'])
    ->name('sslcommerz.cancel');
Route::match(['get', 'post'], '/sslcommerz/ipn', [\App\Http\Controllers\Api\DoctorRegistrationController::class, 'sslcommerzIpn'])
    ->name('sslcommerz.ipn');

//
// updateProfileUpdateByTenant


Route::get('/test-sslcommerz-integration', function () {
    try {
        // Test configuration
        $config = config('sslcommerz');
        Log::info('SSLCommerz Config Test', [
            'store_id' => substr($config['apiCredentials']['store_id'] ?? '', 0, 4) . '...',
            'api_domain' => $config['apiDomain'] ?? 'N/A',
            'is_localhost' => $config['connect_from_localhost'] ?? false
        ]);

        // Test service initialization
        $sslc = new App\Services\SSLCommerzService();

        // Test payment data
        $testData = [
            'total_amount' => '10.00',
            'currency' => 'BDT',
            'tran_id' => 'TEST_' . date('YmdHis') . '_' . uniqid(),
            'success_url' => url('/test-success'),
            'fail_url' => url('/test-fail'),
            'cancel_url' => url('/test-cancel'),
            'cus_name' => 'Test Doctor',
            'cus_email' => 'test@example.com',
            'cus_phone' => '01712345678',
            'product_name' => 'Test Doctor Registration',
            'product_category' => 'Healthcare'
        ];

        Log::info('Test Payment Data', $testData);

        // Try to initiate payment
        $paymentUrl = $sslc->initiatePayment($testData);

        if ($paymentUrl) {
            return response()->json([
                'success' => true,
                'message' => 'SSLCommerz integration test passed!',
                'payment_url' => $paymentUrl,
                'test_data' => $testData
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'SSLCommerz returned no payment URL'
            ], 400);
        }

    } catch (\Exception $e) {
        Log::error('SSLCommerz Integration Test Failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'SSLCommerz integration test failed: ' . $e->getMessage(),
            'config' => [
                'store_id_exists' => !empty(config('sslcommerz.apiCredentials.store_id')),
                'store_password_exists' => !empty(config('sslcommerz.apiCredentials.store_password')),
                'api_domain' => config('sslcommerz.apiDomain'),
                'is_test_mode' => config('sslcommerz.apiDomain', '') === 'https://sandbox.sslcommerz.com'
            ]
        ], 500);
    }
});
// finds
    Route::get('/all-doctors', function () {
        return view('finds');
    })->name('finds');

    Route::get('/doc-details', function () {
        return redirect()->route('finds');
    });
    Route::get('/doc-details/{doctor}/{slug?}', [BrowseController::class, 'showDoctor'])->name('doc-details');
// blog routes
    Route::get('all-articles', [BlogController::class,'allindex'])->name('articles.index');
    Route::get('singles-article/{slug}', [BlogController::class,'singleshow']);
    Route::get('/api', [DocumentationController::class, 'index']);
// packages
    Route::get('/package', [DoctorController::class, 'package'])->name('package.index');

     Route::get('/doc-register', [DoctorController::class, 'register'])->name('doc-register');

     Route::get('/doc-login', [DoctorController::class, 'login'])->name('doc-login');

     Route::get('/doc-profile', [DoctorController::class, 'profile'])->name('doc-profile');

Route::match(['get', 'post'], '/check-subdomain', [DoctorController::class, 'checkSubdomain'])->name('check.subdomain');

Route::match(['get', 'post'], '/check-domain', [DoctorController::class, 'checkDomain'])->name('check.domain');
// package api
Route::get('/api/coupons/available', [CouponController::class, 'available'])->name('coupons.available');
Route::post('/api/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
Route::get('/api/packages', [\App\Http\Controllers\Admin\PackageController::class, 'getPackages']);
Route::middleware(['tenancy', 'auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])
        ->name('tenant.dashboard');
});

Route::get('/geo/forward', function (Request $request) {
    $city = $request->query('city');

    if (!$city) {
        return response()->json(['error' => 'City parameter required'], 400);
    }

    try {
        $response = Http::timeout(10)
            ->withHeaders([
                'User-Agent' => config('app.name') . ' (' . config('mail.from.address') . ')'
            ])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $city . ', Bangladesh',
                'format' => 'json',
                'limit' => 1,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data[0])) {
                return response()->json([
                    'lat' => $data[0]['lat'],
                    'lng' => $data[0]['lon'],
                    'display_name' => $data[0]['display_name']
                ]);
            }
        }

        return response()->json(['error' => 'City not found'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Geocoding service unavailable'], 503);
    }
});
//doctors.online.slots

// Slots
Route::get(
    'chambers/{doctor}/{chamber}/slots/{date}',
    [BrowseController::class, 'getAvailableSlots']
)->name('chambers.slots');

Route::get(
    'doctors/{doctor}/online-slots/{date}',
    [BrowseController::class, 'getOnlineSlots']
)->name('doctors.online.slots');

// Chambers list
Route::get(
    'doctors/{doctor}/chambers',
    [BrowseController::class, 'chamberList']
)->name('doctors.chambers');

Route::get(
    'doctors/{doctor}/payment-methods',
    [BrowseController::class, 'paymentMethods']
)->name('doctors.payment-methods');

// Appointment
Route::post(
    'appointments',
    [BrowseController::class, 'store']
)->name('appointments.store');

// Confirmation
Route::get(
    'appointment/confirmation/{appointment}',
    [BrowseController::class, 'confirmation']
)->name('appointment.confirmation');
Route::get(
    'appointment/{id}/confirmation',
    [BrowseController::class, 'confirmation']
)->name('appointment.confirmation.legacy');

// ->name('ssl.initiate');
Route::post('/appointments/{appointment}/ssl-initiate', 'BrowseController@initiateSSLCommerce')
    ->name('ssl.initiate');
Route::get('/doctors/nearby', [BrowseController::class, 'nearby'])->name('doctors.nearby'); // AJAX
Route::get('/geo/reverse', [GeoController::class, 'reverse'])->name('geo.reverse');
Route::get('/specialty/{slug}', [BrowseController::class, 'bySpecialty'])
    ->name('specialty.doctors');
// articals
Route::resource('categories', CategoryController::class)->middleware('auth');
Route::resource('post-types', PostTypeController::class)->middleware('auth');
Route::get('posts',           [DoctorPostController::class, 'index'])->name('posts.index')->middleware('auth');
Route::get('posts/create',     [DoctorPostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('posts',           [DoctorPostController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('posts/{post}/edit',[DoctorPostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('posts/{post}',     [DoctorPostController::class, 'update'])->name('posts.update')->middleware('auth');
Route::delete('posts/{post}',  [DoctorPostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');
    // SSL

// admin login route
Route::get('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/password/forgot', [\App\Http\Controllers\AdminAuthController::class, 'showLinkRequestForm'])->name('admin.password.request');
Route::post('/admin/password/email', [\App\Http\Controllers\AdminAuthController::class, 'sendResetLinkEmail'])->name('admin.password.email');

Route::get('/admin/password/reset/{token}', [\App\Http\Controllers\AdminAuthController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/password/reset', [\App\Http\Controllers\AdminAuthController::class, 'reset'])->name('admin.password.update');
// admin dashboard route
// Route::get('/superadmin/dashboard', function () {
//     return view('admin.dashboard');
Route::get('/superadmin/dashboard', [\App\Http\Controllers\AdminAuthController::class, 'dashboard'])->middleware('auth')->name('admin.dashboard');
// tenant user list
Route::get('/users', [\App\Http\Controllers\TenantController::class, 'userIndex'])->name('user.index')->middleware('auth');
Route::get('/users/{id}', [\App\Http\Controllers\TenantController::class, 'userShow'])->name('user.show')->middleware('auth');
Route::match(['put', 'patch'], '/users/{id}', [\App\Http\Controllers\TenantController::class, 'userUpdate'])->name('user.update')->middleware('auth');
Route::delete('/users/{id}', [\App\Http\Controllers\TenantController::class, 'destroy'])->name('user.destroy')->middleware('auth');
// routes/web.php
Route::post('/tenant/{id}/{status}', [\App\Http\Controllers\TenantController::class, 'toggleUserStatus'])
    ->name('tenant.toggle')
    ->middleware('auth');
Route::post('/doctor/feature-toggle/{id}', [\App\Http\Controllers\TenantController::class, 'toggleFeature'])
    ->name('doctor.feature.toggle')->middleware('auth');

Route::post('/purchase.store', [\App\Http\Controllers\PurchaseController::class, 'store'])
    ->name('purchase.store')
    ->middleware('auth');
Route::resource('packages', PackageController::class)->middleware('auth');
Route::resource('coupons', CouponController::class)->middleware('auth');
Route::put('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle')->middleware('auth');
;
// template create

Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {

    // The base URL for this resource is now /superadmin/templates
    Route::resource('templates', TemplateController::class)->except([
        'destroy',
        'show',
]);
Route::get('/company-incomes', [CompanyIncomeController::class, 'index'])
        ->name('company.incomes');

Route::post('/company-incomes/status/{id}', [CompanyIncomeController::class, 'updateStatus'])
        ->name('company.income.status');
Route::resource('leads', LeadController::class)
    ->except(['create','edit','show']);

Route::get('/settings/company', [CompanySettingController::class, 'edit'])
    ->name('company.settings');

Route::post('/settings/company', [CompanySettingController::class, 'update'])
    ->name('company.settings.update');

Route::prefix('marketing')->name('marketing.')->group(function () {

    // Contacts (Doctors)
    Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class,'index'])->name('contacts.index');
    Route::get('/contacts/create', [\App\Http\Controllers\Admin\ContactController::class,'create'])->name('contacts.create');
    Route::post('/contacts', [\App\Http\Controllers\Admin\ContactController::class,'store'])->name('contacts.store');
    // edit
    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class,'edit'])->name('contacts.edit');

    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class,'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\ContactController::class,'destroy'])->name('contacts.destroy');

    // Templates
    Route::get('/templates', [\App\Http\Controllers\Admin\TemplateController::class,'index'])->name('templates.index');
    Route::get('/templates/create', [\App\Http\Controllers\Admin\TemplateController::class,'create'])->name('templates.create');
    Route::post('/templates', [\App\Http\Controllers\Admin\TemplateController::class,'store'])->name('templates.store');
    Route::get('/templates/{template}/edit', [\App\Http\Controllers\Admin\TemplateController::class,'edit'])->name('templates.edit');
    Route::put('/templates/{template}', [\App\Http\Controllers\Admin\TemplateController::class,'update'])->name('templates.update');
    Route::delete('/templates/{template}', [\App\Http\Controllers\Admin\TemplateController::class,'destroy'])->name('templates.destroy');

    // Segments
    Route::get('/segments', [\App\Http\Controllers\Admin\SegmentController::class,'index'])->name('segments.index');
    Route::get('/segments/create', [\App\Http\Controllers\Admin\SegmentController::class,'create'])->name('segments.create');
    Route::post('/segments', [\App\Http\Controllers\Admin\SegmentController::class,'store'])->name('segments.store');
    Route::get('/segments/{segment}/edit', [\App\Http\Controllers\Admin\SegmentController::class,'edit'])->name('segments.edit');
    Route::put('/segments/{segment}', [\App\Http\Controllers\Admin\SegmentController::class,'update'])->name('segments.update');
    Route::delete('/segments/{segment}', [\App\Http\Controllers\Admin\SegmentController::class,'destroy'])->name('segments.destroy');

    // Campaigns
    Route::get('/campaigns', [\App\Http\Controllers\Admin\CampaignController::class,'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [\App\Http\Controllers\Admin\CampaignController::class,'create'])->name('campaigns.create');
    Route::post('/campaigns', [\App\Http\Controllers\Admin\CampaignController::class,'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [\App\Http\Controllers\Admin\CampaignController::class,'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [\App\Http\Controllers\Admin\CampaignController::class,'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [\App\Http\Controllers\Admin\CampaignController::class,'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [\App\Http\Controllers\Admin\CampaignController::class,'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{campaign}/build', [\App\Http\Controllers\Admin\CampaignController::class,'buildRecipients'])->name('campaigns.build');
    Route::post('/campaigns/{campaign}/start', [\App\Http\Controllers\Admin\CampaignController::class,'start'])->name('campaigns.start');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class,'edit'])->name('settings.edit');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class,'update'])->name('settings.update');
});

Route::resource('specialties',SpecialtyController::class)->except(['create','edit','show']);

Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])
        ->name('subscriptions.index');

    Route::post('/subscriptions/{id}/approve', [AdminSubscriptionController::class, 'approve'])
        ->name('subscriptions.approve');

    Route::post('/subscriptions/{id}/cancel', [AdminSubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');

    Route::post('/subscriptions/{id}/extend', [AdminSubscriptionController::class, 'extend'])
        ->name('subscriptions.extend');
    Route::post('/subscriptions/{id}/send-mail',
            [AdminSubscriptionController::class, 'sendMail'])
            ->name('subscriptions.sendMail');

});
// set template
Route::post('superadmin/template/set', [\App\Http\Controllers\TenantController::class, 'setTemplate'])->name('templates.set')->middleware('auth');
// superadmin.templates.preview
Route::post('superadmin/templates/preview', [\App\Http\Controllers\TenantController::class, 'viewTemplate'])->name('templates.preview')->middleware('auth');
// templates.activate
Route::post('superadmin/templates/activate', [\App\Http\Controllers\TemplateController::class, 'activateTemplate'])->name('templates.activate')->middleware('auth');
// tenant create
Route::get('/doctor/create', [\App\Http\Controllers\TenantController::class, 'createView'])->name('doctor.create');
 Route::post('/doctor/store', [\App\Http\Controllers\DoctorController::class, 'storeall'])->name('doctor.store');
Route::delete('/tenant/{id}', [\App\Http\Controllers\TenantController::class, 'destroy'])->name('tenant.destroy');

    });
}

Route::get('/directory', [\App\Http\Controllers\TenantDirectoryController::class, 'index'])
     ->name('tenants.index');

Route::get('/directory/{slug}', [\App\Http\Controllers\TenantDirectoryController::class, 'show'])
     ->name('tenants.show');


Route::middleware('auth')->group(function () {
    // tenantadmin/templates
    Route::get('/tenantadmin/templates', [\App\Http\Controllers\TenantAdmin\TemplateController::class, 'create'])->name('tenantadmin.templates.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Services\PricingService;

Route::get('/test-rates', function () {
    $service = app(PricingService::class);
    
    // Force refresh live rates
    $liveRates = $service->refreshLiveRates();
    
    // Check rates for all currencies
    $currencies = config('pricing.currencies');
    $results = [];
    
    foreach (array_keys($currencies) as $currencyCode) {
        $meta = $service->currencyMeta($currencyCode);
        $results[$currencyCode] = [
            'static_rate' => $currencies[$currencyCode]['rate'],
            'live_rate' => $liveRates[$currencyCode] ?? null,
            'used_rate' => $meta['rate'],
            'is_live' => $meta['is_live'] ?? false,
            'symbol' => $meta['symbol'],
            'name' => $meta['name'],
        ];
    }
    
    // Test conversion
    $testAmount = 100; // 100 USD
    $conversions = [];
    foreach (array_keys($currencies) as $currencyCode) {
        $conversions[$currencyCode] = $service->convertFromUsd($testAmount, $currencyCode);
    }
    
    return response()->json([
        'live_rates_enabled' => config('pricing.rates.enabled'),
        'live_rates' => $liveRates,
        'currency_details' => $results,
        'test_conversion_usd_100' => $conversions,
        'timestamp' => now()->toDateTimeString(),
    ]);
});
