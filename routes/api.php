<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Api\DoctorRegistrationController;
use App\Http\Controllers\Api\DoctorProfileApiController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostTypeController;
use App\Http\Controllers\Api\MedicineTemplateController;
use App\Http\Controllers\Api\InvestigationController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\MedicineCompanyController;
use App\Http\Controllers\Api\ComorbidityController;
use App\Http\Controllers\Api\PlanTemplateController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\FollowUpTemplateController;
use App\Http\Controllers\Api\PrescriptionTemplateController;
use App\Http\Controllers\Api\DoctorPostController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\ChamberController;
use App\Http\Controllers\Api\DoctorAppointmentController;
use App\Http\Controllers\Api\PatientEmrController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\DoctorBillingController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\NotificationMessageController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PatientController;

use App\Http\Controllers\UserController;




/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/
Route::post(
    '/doctor/profile/update',
    [UserController::class, 'updateProfileUpdateByTenant']
);
// ->middleware('static.api');

Route::get('/divisions', [LocationController::class, 'divisions']);
Route::get('/districts/{division_id}', [LocationController::class, 'districts']);
Route::get('/upazilas/{district_id}', [LocationController::class, 'upazilas']);
Route::get('/unions/{upazila_id}', [LocationController::class, 'unions']);
Route::get('/pourasovas/{district_id}', [LocationController::class, 'pourasovas']);
Route::get('/city-corporations/{district_id}', [LocationController::class, 'cityCorporations']);

Route::get('/entities', [TenantController::class, 'index']);

Route::post('/registers', [AuthController::class, 'register']);
// Route::middleware(['resolve.tenant'])->get('/test-tenant', function () {
//     return response()->json(['ok' => true]);
// });
Route::middleware('auth:sanctum')->get('/v1/auth-debug', function () {
    return [
        'auth_check' => auth()->check(),
        'user' => auth()->user(),
        'headers' => request()->headers->all(),
    ];
});

/*
|--------------------------------------------------------------------------
| Versioned API (v1)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->group(function () {

Route::prefix('v1')->group(function () {

    /*
    |--------------------
    | Auth
    |--------------------
    */
    Route::post('/logins', [AuthController::class, 'login']);

    /*
    |--------------------
    | Public Doctor Registration APIs
    |--------------------
    */
    Route::post('/check-subdomain', [DoctorController::class, 'checkSubdomain']);
    Route::get('/packages', [DoctorRegistrationController::class, 'getPackages']);
    Route::post('/check-domain', [DoctorRegistrationController::class, 'checkDomain']);
    Route::post('/validate-coupon', [DoctorRegistrationController::class, 'validateCouponApi']);
    Route::post('/calculate-registration', [DoctorRegistrationController::class, 'calculateRegistration']);
    Route::post('/doctor/register', [DoctorRegistrationController::class, 'register']);

    /*
    |--------------------
    | Payment Callbacks / Webhooks (Public)
    |--------------------
    */
    Route::post('/sslcommerz/ipn', [DoctorRegistrationController::class, 'sslcommerzIpn']);
    Route::post('/sslcommerz/success', [DoctorRegistrationController::class, 'sslcommerzSuccess']);
    Route::post('/sslcommerz/fail', [DoctorRegistrationController::class, 'sslcommerzFail']);
    Route::post('/sslcommerz/cancel', [DoctorRegistrationController::class, 'sslcommerzCancel']);

    Route::post('/payment/webhook/paypal', [DoctorRegistrationController::class, 'paypalWebhook']);
    Route::post('/payment/webhook/sslcommerz', [DoctorRegistrationController::class, 'sslcommerzWebhook']);

    Route::get('/registration/status/{order_id}', [DoctorRegistrationController::class, 'checkStatus']);

    /*
    |--------------------------------------------------------------------------
    | 🔐 Protected Routes (Sanctum)
    |--------------------------------------------------------------------------
    */


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/create-patient', [DoctorRegistrationController::class, 'createPatient']);
    Route::post('/medicines', [ContactController::class, 'storeMedicine']);
    Route::post('/tests', [ContactController::class, 'storeTest']);
    Route::post('/prescriptions', [ContactController::class, 'storePrescription']);
    Route::get('/prescriptions', [PrescriptionController::class, 'index']);
    Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show']);

    Route::get('/social-media', [ContactController::class, 'socialMedia']);
    Route::post('/social-media/update', [ContactController::class, 'socialMediaUpdate']);
    Route::get('/seo-settings', [ContactController::class, 'seoSetting']);
    Route::post('/seo-settings/update', [ContactController::class, 'seoSettingUpdate']);
    Route::post('/settings/update/{type}', [ContactController::class, 'update_email_sms_payment_Settings']);
    Route::get('/settings/{type}', [ContactController::class, 'email_sms_payment_Settings']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('post-types', PostTypeController::class);
    Route::apiResource('medicine-templates', MedicineTemplateController::class);
    Route::apiResource('sliders', SliderController::class);
    Route::apiResource('galleries', GalleryController::class);
    Route::apiResource('investigations', InvestigationController::class);
    Route::apiResource('medicine-companies', MedicineCompanyController::class);
    Route::apiResource('comorbidities', ComorbidityController::class);
    Route::apiResource('plan-templates', PlanTemplateController::class);
    Route::apiResource('follow-up-templates', FollowUpTemplateController::class);
    Route::apiResource('doctor-posts', DoctorPostController::class);
    Route::apiResource('prescription-templates', PrescriptionTemplateController::class);
    Route::apiResource('chambers', ChamberController::class);
    
    Route::get('/patients', [PatientController::class,'index']);

    Route::post('/patients', [PatientController::class,'store']);

    Route::get('/patients/{id}', [PatientController::class,'show']);

    Route::put('/patients/{id}', [PatientController::class,'update']);

    Route::delete('/patients/{id}', [PatientController::class,'destroy']);

    Route::get('/patients/{id}/history', [PatientController::class,'history']);

    Route::get('/patients/{id}/records', [PatientController::class,'records']);
    
    // event
    Route::apiResource('events', EventController::class);

    Route::get('chambers/{id}/available-slots', [ChamberController::class, 'availableSlots']);
    // online-slots
    Route::get('doctors/online-slots/{date}', [ChamberController::class, 'getOnlineSlots']);
    Route::get('doctor/appointments', [DoctorAppointmentController::class,'index']);
    Route::get('doctor/appointments/online', [DoctorAppointmentController::class,'onlineAppointments']);
    Route::get('doctor/appointments/{id}', [DoctorAppointmentController::class,'show']);
    Route::post('doctor/appointments/{id}/status', [DoctorAppointmentController::class,'updateStatus']);
    Route::post('doctor/appointments/{id}/reschedule', [DoctorAppointmentController::class,'reschedule']);
    Route::get('doctor/appointments/calendar', [DoctorAppointmentController::class,'calendar']);
    //Route::post('patient-emr', [PatientEmrController::class, 'store']);
    Route::apiResource('patient-emr', PatientEmrController::class);

    Route::apiResource('invoices', InvoiceController::class);
    Route::get('doctor/billing', [DoctorBillingController::class, 'index']);
    Route::get('doctor/billing/report', [DoctorBillingController::class, 'report']);
    Route::post('/appointments/book', [AppointmentController::class, 'store']);

   // Notifications
    Route::post('/notifications/send', [NotificationMessageController::class, 'sendNotification']);
    Route::get('/notifications/doctor', [NotificationMessageController::class, 'doctorNotifications']);
    Route::get('/notifications/patient', [NotificationMessageController::class, 'patientNotifications']);
    Route::post('/notifications/read/{id}', [NotificationMessageController::class, 'markNotificationRead']);

    // Messages
    Route::post('/messages/send', [NotificationMessageController::class, 'sendMessage']);
    Route::post('/messages/reply', [NotificationMessageController::class, 'patientReply']);
    Route::get('/messages/thread', [NotificationMessageController::class, 'messageThread']);
    Route::get('/messages/inbox/doctor', [NotificationMessageController::class, 'doctorInbox']);
    Route::get('/messages/inbox/patient', [NotificationMessageController::class, 'patientInbox']);
        Route::get('/me', function (\Illuminate\Http\Request $request) {
            return response()->json($request->user());
        });

        Route::post('/doctor/update', [DoctorProfileApiController::class, 'update']);

        Route::get(
            '/doctor/profile/single-data/{type}',
            [DoctorProfileApiController::class, 'get_profile_single_data']
        );
        // Route::post(
        //     '/doctor/profile/update-single-data/{type}',
        //     [DoctorProfileApiController::class, 'update_profile_single_data']

        // );
         Route::put('/doctor/profile/update-profile/{type}', [DoctorProfileApiController::class, 'update_profile_single_data']);
    });
});

