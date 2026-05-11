<?php

declare(strict_types=1);

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\DoctorSettingController;

use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SettingController;
use App\Models\PrescriptionTemplate;
use App\Models\MedicineTemplate;
use App\Models\Test;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\ChamberController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\ServiceController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UpgradeController;
// DoctorPostController
use App\Http\Controllers\Doctor\DoctorPostController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\TelemedicineController;
use App\Http\Controllers\Doctor\DoctorTestimonialController;
use App\Http\Controllers\Doctor\DoctorFaqController;
use App\Http\Controllers\Doctor\DoctorBillingController;


use App\Http\Controllers\AIContentController;
use App\Http\Controllers\Setting\CategoryController;
use App\Http\Controllers\Setting\PostTypeController;
use App\Http\Controllers\Setting\MedicineTemplateController;
use App\Http\Controllers\Setting\InvestigationController;
use App\Http\Controllers\Setting\SliderController;
use App\Http\Controllers\Setting\GalleryController;
use App\Http\Controllers\Setting\EventController;

use App\Http\Controllers\Setting\MedicineCompanyController;
use App\Http\Controllers\Setting\SettingController as AllsettingController;
use App\Http\Controllers\Setting\ComorbidityController;
use App\Http\Controllers\Setting\PlanTemplateController;
use App\Http\Controllers\Setting\FollowUpTemplateController;
use App\Http\Controllers\Setting\MedicineController;
use App\Http\Controllers\Setting\TestController;
use App\Http\Controllers\Setting\InvoiceController;
use App\Http\Controllers\PrescriptionTemplateController;
use App\Http\Controllers\Api\NotificationMessageController;



/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
*/


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
Route::get(
    '/admin/medicine-templates/json-medicines',
    [MedicineTemplateController::class, 'jsonMedicines']
)->name('admin.medicine-templates.json');

    // Available Chambers
    Route::get('/api/available-chambers', [ChamberController::class, 'index'])->name('available-chambers');
    Route::get('/api/chambers/{chamber}', [ChamberController::class, 'show'])->name('chambers.show');

     // SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

Route::get('services', [ServiceController::class, 'index'])->name('services.index');
// all blogs,
// routes/web.php (public)

Route::get('articles', [BlogController::class,'index'])->name('articles.index');
Route::get('articles/{slug}', [BlogController::class,'show'])->name('articles.show');


// login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
// contact

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/submit-form', [SubmissionsController::class, 'store'])->name('submit.form');

// forget_passworrd
Route::get('/forget-password', [AuthController::class, 'showForgetForm'])->name('forget.password');
Route::get('/verify-nid-mobile', [AuthController::class, 'verifyNidMobile'])->name('verify.nid.mobile');

Route::get('/password-set', [AuthController::class, 'showResetForm'])->name('password.set');
Route::post('/set-password', [AuthController::class, 'resetPassword'])->name('set.password.submit');

 Route::get('/packages', [UpgradeController::class,'index'])
        ->name('package.upgrade');

Route::post('/packages/upgrade', [UpgradeController::class,'process'])
        ->name('package.upgrade.process');

// notice
Route::get('/notices', [\App\Http\Controllers\NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{slug}', [\App\Http\Controllers\NoticeController::class, 'show'])->name('notices.show');
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('feature:appointment_booking')->name('appointments.store');
Route::get('/appointment/confirmation/{appointment}', [AppointmentController::class, 'confirmation'])->name('appointment.confirmation');

Route::get('chambers/{chamber}/slots/{date}', [ChamberController::class, 'getAvailableSlots'])->name('chambers.slots');

Route::get(
    '/doctors/{doctor}/online-slots/{date}',
    [ChamberController::class, 'getOnlineSlots']
)->name('doctors.online.slots');


// Route::get('/', function () {
//   return 'welcome';
// });
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

// ✅ Routes accessible to both admin and user

Route::middleware(['auth', 'role:admin,user'])->prefix('admin')->name('admin.')->group(function () {
Route::get('setting-page', [AllsettingController::class, 'setting_page'])
            ->name('setting-page');
Route::post('setting-update', [AllsettingController::class, 'setting_update'])
            ->name('setting.update');
// onlineSchedulePage
Route::get('settings/online-schedule',
    [AllsettingController::class,'onlineSchedulePage']
)->name('settings.online-schedule');
Route::post('settings/online-schedule',
    [AllsettingController::class,'updateOnlineSchedule']
)->name('settings.online-schedule.update');


Route::prefix('prescriptions-template')->name('prescriptions-template.')->group(function () {
    Route::get('/', [PrescriptionTemplateController::class, 'index'])->name('index');
    Route::post('/', [PrescriptionTemplateController::class, 'store'])->name('store');
    Route::get('/{id}', [PrescriptionTemplateController::class, 'show'])->name('show');
    Route::put('/{id}', [PrescriptionTemplateController::class, 'update'])->name('update');
    Route::delete('/{id}', [PrescriptionTemplateController::class, 'destroy'])->name('destroy');
    Route::get('/search/medicines', [PrescriptionTemplateController::class, 'searchMedicines'])->name('search.medicines');

    Route::get('tests/search', [PrescriptionTemplateController::class, 'searchTests'])->name('tests.search');
    Route::get('tests/{id}', [PrescriptionTemplateController::class, 'getTest'])->name('tests.show');
});

Route::resource('tests', TestController::class);
   // ->except(['create','edit','show']);
    // add new prescription
 Route::get('add-new-prescriptions', [PrescriptionController::class, 'add_new'])
            ->name('add.prescriptions.new');
Route::resource('categories', CategoryController::class);
Route::resource('post-types', PostTypeController::class);
Route::resource('medicine-templates', MedicineTemplateController::class);
    //->except(['create','edit','show']);
Route::get('medicine-template/search', [MedicineTemplateController::class, 'search'])->name('medicine-template.search');
Route::resource('investigations', InvestigationController::class);
Route::delete('events/{event}/gallery-image',[EventController::class, 'deleteGalleryImage']
)->name('events.gallery-image.delete');

   // ->except(['create','edit','show']);
Route::resource('sliders', SliderController::class);
    //->except(['create','edit','show']);

Route::resource('galleries', GalleryController::class);
   // ->except(['create','edit','show']);
Route::resource('events', EventController::class);
   // ->except(['create','edit','show']);
Route::resource('medicine-companies', MedicineCompanyController::class)
    ->except(['create','edit','show']);
Route::resource('comorbidities', ComorbidityController::class)
    ->except(['create','edit','show']);
Route::resource('plan-templates', PlanTemplateController::class)
    ->except(['create','edit','show']);
Route::resource('follow-up-templates', FollowUpTemplateController::class)
    ->except(['create','edit','show']);
Route::resource('medicines', MedicineController::class)
    ->except(['create','edit','show']);
Route::resource('invoices', InvoiceController::class)
    ->except(['create','edit','show']);
Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
     ->name('invoices.show');

Route::post('/prescriptions', [PrescriptionController::class, 'prescriptionstore'])->name('prescriptions.store');
// allmassege
Route::get('/messages', [NotificationMessageController::class, 'allmassege']);
// routes/web.php
Route::get('/notifications/count', [NotificationMessageController::class, 'notificationCount'])
    ->name('notifications.count');

     // Create a route for loading templates
Route::get('/prescription-templates/{id}', function($id) {
    $template = PrescriptionTemplate::findOrFail($id);

    // Parse the medicine IDs array
    $medicineIds = $template->medicine_ids ?? [];
    $testIds = $template->investigation_ids ?? [];

    // Load medicines and tests
    $medicines = MedicineTemplate::whereIn('id', $medicineIds)->get();
    $tests = Test::whereIn('test_name', $testIds)->get();

    return response()->json([
        'id' => $template->id,
        'name' => $template->template_name,
        'description' => $template->description ?? '',
        'diagnosis' => $template->advice ?? '',
        'chief_complaint' => '',
        'instructions' => $template->advice ?? '',
        'diet_advice' => '',
        'next_visit_date' => $template->next_visit ? $template->next_visit->format('Y-m-d') : null,
        'medicines' => $medicines->map(function($med) {
            return [
                'id' => $med->id,
                'name' => $med->medicine_name,
                'dosage' => $med->medicine_dose,
                'frequency' => $med->medicine_dose,
                'duration' => $med->medicine_day,
                'instructions' => $med->medicine_description,
                'type' => $med->medicine_type,
                'custom' => false
            ];
        }),
        'tests' => $tests->map(function($test) {
            return [
                'id' => $test->id,
                'name' => $test->test_name,
                'custom' => false
            ];
        })
    ]);
})->name('prescription-templates.show');

    //Route::middleware(['auth', 'role:admin,user'])->prefix('admin')->name('admin.')->group(function ()  Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('feature:doctor');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('feature:doctor');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.password.update');
// routes/web.php
//Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::resource('patients', PatientController::class)->only([
        'index','store','update'
    ])->middleware('feature:patients');

// Patient actions
Route::prefix('patients')->name('patients.')->middleware(['auth', 'feature:patients'])->group(function () {

    Route::get('{patient}/profile', [PatientController::class, 'profile'])->name('profile');
    Route::get('{patient}/history', [PatientController::class, 'history'])->name('history');
    Route::get('{patient}/medical-records', [PatientController::class, 'medicalRecords'])->name('records');

});

 Route::get('patients/{patient}/prescriptions/create', [PrescriptionController::class, 'create'])
            ->name('patients.prescriptions.create');

        Route::post('patients/{patient}/prescriptions', [PrescriptionController::class, 'store'])
            ->name('patients.prescriptions.store');

            // Prescription list for a patient
        Route::get('patients/prescriptions', [PrescriptionController::class, 'index'])
            ->name('patients.prescriptions.index');
        // Show single prescription
        Route::get('patients/prescriptions/{prescription}', [PrescriptionController::class, 'show'])
            ->name('patients.prescriptions.show');
        Route::get('telemedicine-platforms', [TelemedicineController::class, 'index'])
            ->name('telemedicine.index');
        Route::delete('telemedicine-platforms/{platform}', [TelemedicineController::class, 'destroy'])->middleware('feature:services')
            ->name('telemedicine.destroy');
        Route::get('testimonials', [DoctorTestimonialController::class, 'index'])->middleware('feature:content')
         ->name('testimonials.index');
        //  status update
 Route::post('testimonials/{testimonial}/{status}', [DoctorTestimonialController::class, 'updateStatus'])->middleware('feature:content');
        // ->name('testimonials.updateStatus');
         Route::delete('testimonials/{testimonial}', [DoctorTestimonialController::class, 'destroy'])->middleware('feature:content')
         ->name('testimonials.destroy');
 Route::get('faqs', [DoctorFaqController::class, 'index'])->middleware('feature:content')->name('faqs.index');
    Route::delete('faqs/{faq}', [DoctorFaqController::class, 'destroy'])->middleware('feature:content')->name('faqs.destroy');
     Route::get('billing', [DoctorBillingController::class, 'index'])->middleware('feature:online_payments')->name('billing.index');
    Route::get('billing/report', [DoctorBillingController::class, 'report'])->middleware('feature:analytics_advanced')->name('billing.report');

    // Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    // Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    // Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update');
    // Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // // optional: bulk upsert + reorder (single form for everything)
    // Route::post('services/bulk', [ServiceController::class, 'bulkUpsert'])->name('services.bulk');
    // Route::post('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');

    Route::get('posts',           [DoctorPostController::class, 'index'])->middleware('feature:content')->name('posts.index');
    Route::get('posts/create',     [DoctorPostController::class, 'create'])->middleware('feature:content')->name('posts.create');
    Route::post('posts',           [DoctorPostController::class, 'store'])->middleware('feature:content')->name('posts.store');
    Route::get('posts/{post}/edit',[DoctorPostController::class, 'edit'])->middleware('feature:content')->name('posts.edit');
    Route::put('posts/{post}',     [DoctorPostController::class, 'update'])->middleware('feature:content')->name('posts.update');
    Route::delete('posts/{post}',  [DoctorPostController::class, 'destroy'])->middleware('feature:content')->name('posts.destroy');
    // SSL
    Route::post('/user/payment/initiate', [SslCommerzPaymentController::class, 'initiate'])->name('user.payment.initiate');
    Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);


        Route::get('/profile/edit', [\App\Http\Controllers\Doctor\ProfilePublishController::class,'edit'])->middleware('feature:doctor')
             ->name('profile.edit');
        Route::put('/profile/{id}', [\App\Http\Controllers\Doctor\ProfilePublishController::class,'update'])->middleware('feature:doctor')
             ->name('profile.update');
    Route::post('/ai/generate-content', [AIContentController::class, 'generateContent'])->middleware('feature:content')->name('admin.ai.generate-content');
    Route::resource('chambers', ChamberController::class)->middleware('feature:services');
    Route::get('chambers/{chamber}/custom-dates', [ChamberController::class, 'customDates'])->name('chambers.custom-dates');
    Route::post('chambers/{chamber}/custom-dates', [ChamberController::class, 'storeCustomDate'])->name('chambers.custom-dates.store');
    Route::put('chambers/custom-dates/{customDate}', [ChamberController::class, 'updateCustomDate'])->name('chambers.custom-dates.update');
    Route::get('chambers/{chamber}/slots/{date}', [ChamberController::class, 'getAvailableSlots'])->name('chambers.slots');
    Route::delete('chambers/custom-dates/{customDate}', [ChamberController::class, 'destroyCustomDate'])->name('chambers.custom-dates.update');
    Route::delete('chambers/custom-dates/{customDate}', [ChamberController::class, 'destroyCustomDate'])->name('chambers.custom-dates.destroy');

    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->middleware('feature:appointments')->name('appointments.index');
    Route::get('/appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->middleware('feature:appointments')->name('appointments.show');
    Route::put('/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->middleware('feature:appointment_management')->name('appointments.updateStatus');
    Route::post('/appointments/{appointment}/reschedule', [DoctorAppointmentController::class, 'reschedule'])->middleware('feature:appointment_management')->name('appointments.reschedule');
    Route::get('/appointments-calendar', [DoctorAppointmentController::class, 'calendar'])->middleware('feature:appointments')->name('appointments.calendar');
    Route::get('/appointments/upcoming', [DoctorAppointmentController::class, 'upcoming'])->middleware('feature:appointments')->name('appointments.upcoming');
    Route::get('/appointment/today', [DoctorAppointmentController::class, 'today'])->middleware('feature:appointments')->name('appointment.today');
    Route::get('/appointment/online', [DoctorAppointmentController::class, 'onlineAppointments'])->middleware('feature:appointment_booking')->name('appointment.online');

    // setting routes
   // Route::get('/settings/categories', [DoctorSettingController::class, 'categories'])->name('settings.categories');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('company-settings', CompanySettingController::class);











});


});
