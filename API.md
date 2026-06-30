# Doctor Profiles API Reference

This file documents the HTTP API and JSON/AJAX endpoints registered in this Laravel app.

Routes were checked from:

- `routes/api.php`
- API-style routes in `routes/web.php`
- API-style routes in `routes/tenant.php`

## Base URLs

Use the correct host for the route group:

| Area | Base URL |
| --- | --- |
| Central API | `https://doctorsprofile.xyz/api` |
| Versioned central API | `https://doctorsprofile.xyz/api/v1` |
| Tenant/site routes | `https://{tenant-domain}` |

Most examples below show only the path. Prepend the correct host.

## Headers

Public routes do not require a token.

Protected routes use Laravel Sanctum:

```http
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

For file upload routes, use `multipart/form-data`.

## Common Responses

| Status | Meaning |
| --- | --- |
| `200` | Success |
| `201` | Created |
| `400` | Invalid callback/payment request |
| `401` | Missing or invalid Sanctum token |
| `403` | Forbidden or feature/package unavailable |
| `404` | Model, tenant, doctor, account, or payment session not found |
| `422` | Validation error |
| `500` | Server error |

## Auth

### Login

`POST /api/v1/logins`

Body:

```json
{
  "email": "doctor@example.com",
  "password": "secret"
}
```

Returns `user` and `token`. The login controller accepts users with role `tenant`.

### Register Basic User

`POST /api/registers`

Body:

```json
{
  "name": "Example User",
  "email": "user@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

### Current User

`GET /api/v1/me`

Protected. Returns the authenticated user.

### Auth Debug

`GET /api/v1/auth-debug`

Protected. Returns auth state, current user, and headers.

### Change Password

`POST /api/v1/change-password`

Protected.

Body:

```json
{
  "current_password": "old-password",
  "new_password": "new-password",
  "new_password_confirmation": "new-password"
}
```

## Public API Routes

### Location

| Method | Path | Description |
| --- | --- | --- |
| `GET` | `/api/divisions` | List divisions |
| `GET` | `/api/districts/{division_id}` | Districts by division |
| `GET` | `/api/upazilas/{district_id}` | Upazilas by district |
| `GET` | `/api/unions/{upazila_id}` | Unions by upazila |
| `GET` | `/api/pourasovas/{district_id}` | Pourasovas by district |
| `GET` | `/api/city-corporations/{district_id}` | City corporations by district |

### Entities

`GET /api/entities`

Query parameters:

| Parameter | Description |
| --- | --- |
| `q` | Search by tenant/site setting name |
| `type` | `unions`, `pourasovas`, or `city_corporations` |
| `division` | Division ID |
| `district` | District ID |
| `upazila` | Upazila ID |
| `status` | Status value |
| `include` | Comma separated, supports `domains` and `settings` |
| `per_page` | Page size, default `12`, max `100` |
| `page` | Page number |

### Profile Update By Tenant

`POST /api/doctor/profile/update`

Body:

```json
{
  "email": "doctor@example.com",
  "name": "Doctor Name",
  "photo": "optional",
  "password": "optional-new-password",
  "password_confirmation": "optional-new-password"
}
```

## Public Registration And Payment Routes

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `POST` | `/api/v1/check-subdomain` | `subdomain` | Check platform subdomain availability |
| `GET` | `/api/v1/packages` | none | List registration packages and pricing |
| `POST` | `/api/v1/check-domain` | `type`, `domain`, optional `extension` | Check subdomain, new domain, or existing domain availability |
| `POST` | `/api/v1/validate-coupon` | `code`, `amount` | Validate registration coupon |
| `POST` | `/api/v1/calculate-registration` | `package_id`, `billing_cycle`, `domain_type`, optional `coupon_code`, optional `domain_extension` | Calculate registration total |
| `POST` | `/api/v1/doctor/register` | See body below | Register doctor, tenant, domain, package, and payment |
| `GET` | `/api/v1/registration/status/{order_id}` | path `order_id` | Check registration/payment session status |

### Doctor Register

`POST /api/v1/doctor/register`

Use `multipart/form-data` if sending `photo`.

Required:

| Field | Rule |
| --- | --- |
| `email` | required email, unique in central users |
| `phone` | required string |
| `password` | required, min 8, confirmed |
| `password_confirmation` | required with password |
| `package_id` | required, exists in packages |
| `selected_billing_cycle` | `monthly`, `yearly`, or `free` |
| `domain_type` | `new`, `subdomain`, or `existing` |
| `payment_method` | `paypal`, `sslcommerz`, `stripe`, `bank_transfer`, or `credit_card` |
| `payment_option` | `online` or `offline` |
| `terms` | accepted |
| `package_price` | numeric, min 0 |
| `domain_price` | numeric, min 0 |
| `discount_amount` | numeric, min 0 |
| `total_amount` | numeric, min 0 |

Conditional:

| Field | Required when |
| --- | --- |
| `subdomain_name` | `domain_type=subdomain` |
| `new_domain_name` | `domain_type=new` |
| `new_domain_extension` | `domain_type=new` |
| `existing_domain` | `domain_type=existing` |

Optional:

`photo`, `name`, `qualification`, `specialty`, `country`, `reg_no`, `latitude`, `longitude`, `city`, `coupon_code`, `card_number`, `expiry_date`, `cvv`, `card_holder`.

## Public Payment Callback Routes

These routes are intended for payment gateway redirects, IPNs, and webhooks.

| Method | Path | Description |
| --- | --- | --- |
| `POST` | `/api/v1/sslcommerz/ipn` | SSLCommerz IPN |
| `POST` | `/api/v1/sslcommerz/success` | SSLCommerz success callback |
| `POST` | `/api/v1/sslcommerz/fail` | SSLCommerz failed callback |
| `POST` | `/api/v1/sslcommerz/cancel` | SSLCommerz cancel callback |
| `GET`, `POST` | `/api/v1/stripe/success` | Stripe success callback, expects `session_id` |
| `GET`, `POST` | `/api/v1/stripe/cancel` | Stripe cancel callback, accepts `session_id` |
| `POST` | `/api/v1/payment/webhook/paypal` | PayPal webhook |
| `POST` | `/api/v1/payment/webhook/sslcommerz` | SSLCommerz webhook |

## Protected API Routes

All routes in this section require `Authorization: Bearer <token>`.

### Profile And Settings

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `POST` | `/api/v1/doctor/update` | Doctor/profile fields | Update doctor profile |
| `GET` | `/api/v1/doctor/profile/single-data/{type}` | path `type` | Get one profile section |
| `PUT` | `/api/v1/doctor/profile/update-profile/{type}` | depends on `type` | Update one profile section |
| `GET` | `/api/v1/social-media` | none | Get social media links |
| `POST` | `/api/v1/social-media/update` | social URL fields | Update social media links |
| `GET` | `/api/v1/seo-settings` | none | Get SEO settings |
| `POST` | `/api/v1/seo-settings/update` | SEO fields, optional `ogimage` file | Update SEO settings |
| `GET` | `/api/v1/settings/{type}` | `type=email`, `sms`, or `payment` | Get email/SMS/payment settings |
| `POST` | `/api/v1/settings/update/{type}` | settings payload | Update email/SMS/payment settings |

Supported profile `type` values include:

`profile_photo`, `specialty`, `country`, `licence`, `profile_info`, `qualification`, `availability`, `location`, `educations`, `experiences`, `certifications`, `affiliations`, `specialties`, `services`, `testimonials`, `faqs`, `telemedicine_platforms`, `gallery`, `website_content`, `theme_data`.

### Settings Payloads

Social media:

`facebook_url`, `twitter_url`, `instagram_url`, `linkedin_url`, `whatsapp_number`, `telegram_url`, `tiktok_url`.

SEO:

`meta_title`, `meta_description`, `keywords`, `robots`, `ogtitle`, `ogdescription`, `ogtype`, `ogurl`, `ogimage`, `google_analytics_id`, `facebook_pixel_id`, `tagline`.

Email settings update with `type=email` accepts:

`mail_from`, `mail_sender_name`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`.

SMS/payment settings update with `type=sms` or `type=payment` expects nested `extra_data.sms` or `extra_data.payment`.

## Resource Route Pattern

The following resources use Laravel `apiResource` style routes:

| Method | Pattern | Description |
| --- | --- | --- |
| `GET` | `/api/v1/{resource}` | List |
| `POST` | `/api/v1/{resource}` | Create |
| `GET` | `/api/v1/{resource}/{id}` | Show |
| `PUT`, `PATCH` | `/api/v1/{resource}/{id}` | Update |
| `DELETE` | `/api/v1/{resource}/{id}` | Delete |

## Protected Resources

| Resource | Route base | ID parameter | Main create/update fields |
| --- | --- | --- | --- |
| Categories | `/api/v1/categories` | `{category}` | `name`, optional `parent_id` |
| Post types | `/api/v1/post-types` | `{post_type}` | `name` |
| Medicine templates | `/api/v1/medicine-templates` | `{medicine_template}` | `medicine_name`, optional `medicine_url` |
| Sliders | `/api/v1/sliders` | `{slider}` | `title`, optional `sub_title`, optional `image`, optional `video_url`, `status` |
| Galleries | `/api/v1/galleries` | `{gallery}` | optional `image` file, optional `video_url` |
| Investigations | `/api/v1/investigations` | `{investigation}` | `investigation_name` |
| Medicine companies | `/api/v1/medicine-companies` | `{medicine_company}` | `company_name` |
| Comorbidities | `/api/v1/comorbidities` | `{comorbidity}` | `comorbidity_name` |
| Plan templates | `/api/v1/plan-templates` | `{plan_template}` | `plan_name`, optional `plan_details` |
| Follow-up templates | `/api/v1/follow-up-templates` | `{follow_up_template}` | `template_name` or controller-specific follow-up fields |
| Doctor posts | `/api/v1/doctor-posts` | `{doctor_post}` | `title`, optional `slug`, `category_id`, `type_id`, `cover_image`, `excerpt`, `body`, `read_minutes`, `is_published`, `published_at`, SEO fields |
| Prescription templates | `/api/v1/prescription-templates` | `{prescription_template}` | `template_name`, optional `medicine_ids`, `investigation_ids`, `advice`, `next_visit` |
| Chambers | `/api/v1/chambers` | `{chamber}` | `name`, `address`, `city`, `fees`, `type`, optional `phone`, `email`, `website`, `schedule`, `is_active` on update |
| Events | `/api/v1/events` | `{event}` | `title`, optional `sub_title`, optional `image_gallery[]`, optional `video_url`, optional `description`, optional `publish_date`, `status`, optional `venue` |
| Patient EMR | `/api/v1/patient-emr` | `{patient_emr}` | `patient_id`, `visit_date`, optional complaint/history/comorbidities/vitals/exam/diagnosis/notes |
| Invoices | `/api/v1/invoices` | `{invoice}` | `patient_id` on create, `amount`, `date`, `purpose` |

## Patients

| Method | Path | Description |
| --- | --- | --- |
| `GET` | `/api/v1/patients` | List patients, paginated |
| `POST` | `/api/v1/patients` | Create patient |
| `GET` | `/api/v1/patients/{id}` | Show patient |
| `PUT` | `/api/v1/patients/{id}` | Update patient |
| `DELETE` | `/api/v1/patients/{id}` | Delete patient |
| `GET` | `/api/v1/patients/{id}/history` | Patient appointment history |
| `GET` | `/api/v1/patients/{id}/records` | Patient prescriptions/medical records |
| `POST` | `/api/v1/create-patient` | Alternate patient create endpoint |

Patient create body:

```json
{
  "name": "Patient Name",
  "age": "35",
  "mobile": "01700000000",
  "gender": "male",
  "address": "Dhaka",
  "email": "patient@example.com",
  "vitality": "optional",
  "emergency_contact": {
    "name": "Contact Name",
    "relationship": "Spouse",
    "contact": "01700000001"
  },
  "basic_details": {
    "blood_group": "A+",
    "height": 170,
    "weight": 70
  },
  "medical_history": "optional"
}
```

## Medicines, Tests, And Prescriptions

| Method | Path | Body | Description |
| --- | --- | --- | --- |
| `POST` | `/api/v1/medicines` | `name`, optional `dosage`, `frequency`, `duration`, `instruction`, `type` | Create medicine |
| `POST` | `/api/v1/tests` | `test_name` | Create test |
| `GET` | `/api/v1/prescriptions` | optional query filters from controller | List prescriptions |
| `POST` | `/api/v1/prescriptions` | prescription body | Create prescription |
| `GET` | `/api/v1/prescriptions/{id}` | path `id` | Show prescription |

Prescription create body:

```json
{
  "doctor_id": 1,
  "patient_id": 2,
  "appointment_id": 10,
  "prescribed_date": "2026-06-25",
  "chief_complaint": "Headache",
  "diagnosis": "Migraine",
  "instructions": "Take rest",
  "next_visit_date": "2026-07-02",
  "status": "active",
  "medicines": [
    {
      "medicine_id": 1,
      "dosage": "500mg",
      "frequency": "twice daily",
      "duration": "5 days",
      "instruction": "after meal"
    }
  ],
  "tests": [1, 2]
}
```

## Chambers And Slots

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `GET` | `/api/v1/chambers` | none | List authenticated tenant chambers |
| `POST` | `/api/v1/chambers` | chamber body | Create chamber |
| `GET` | `/api/v1/chambers/{chamber}` | path `chamber` | Show chamber |
| `PUT`, `PATCH` | `/api/v1/chambers/{chamber}` | chamber update body | Update chamber |
| `DELETE` | `/api/v1/chambers/{chamber}` | path `chamber` | Delete chamber |
| `GET` | `/api/v1/chambers/{id}/available-slots?date=YYYY-MM-DD` | query `date` | Available chamber slots |
| `GET` | `/api/v1/doctors/online-slots/{date}` | path `date` | Online slots |

Chamber create body:

```json
{
  "name": "Main Chamber",
  "phone": "01700000000",
  "email": "chamber@example.com",
  "website": "https://example.com",
  "address": "123 Road",
  "city": "Dhaka",
  "fees": 800,
  "type": "fixed",
  "schedule": {
    "sunday": {
      "enabled": true,
      "start_time": "09:00",
      "end_time": "17:00",
      "slot_duration": 30
    }
  }
}
```

## Appointments

### Book Appointment

`POST /api/v1/appointments/book`

Protected.

Body:

```json
{
  "doctor_id": 1,
  "chamber_id": 1,
  "patient_id": 2,
  "consultation_type": "offline",
  "service_type": "New Patient Visit",
  "appointment_date": "2026-06-25",
  "appointment_time": "09:30:00",
  "patient_first_name": "Patient",
  "patient_last_name": "Name",
  "patient_email": "patient@example.com",
  "patient_phone": "01700000000",
  "patient_dob": "1990-01-01",
  "notes": "optional",
  "terms_agreed": true
}
```

If `patient_id` is present, patient name/email/phone fields are not required. If `patient_id` is absent, they are required.

### Doctor Appointment Management

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `GET` | `/api/v1/doctor/appointments` | optional `status`, `date`, `search` | List doctor appointments |
| `GET` | `/api/v1/doctor/appointments/online` | none | List online appointments |
| `GET` | `/api/v1/doctor/appointments/calendar` | none | Calendar events |
| `GET` | `/api/v1/doctor/appointments/{id}` | path `id` | Show appointment |
| `POST` | `/api/v1/doctor/appointments/{id}/status` | `status`, optional `cancellation_reason` | Update status |
| `POST` | `/api/v1/doctor/appointments/{id}/reschedule` | `appointment_date`, `appointment_time`, optional `reason` | Reschedule |

Allowed status update values:

`confirmed`, `completed`, `cancelled`, `no_show`.

## Billing And Invoices

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `GET` | `/api/v1/doctor/billing` | optional `status`, `payment_status`, `from`, `to`, `consultation_type` | Billing list and stats |
| `GET` | `/api/v1/doctor/billing/report` | optional `from`, `to` | Billing summary report |
| `GET` | `/api/v1/invoices` | none | List invoices |
| `POST` | `/api/v1/invoices` | `patient_id`, `amount`, `date`, `purpose` | Create invoice |
| `GET` | `/api/v1/invoices/{invoice}` | path `invoice` | Show invoice |
| `PUT`, `PATCH` | `/api/v1/invoices/{invoice}` | `amount`, `date`, `purpose` | Update invoice |
| `DELETE` | `/api/v1/invoices/{invoice}` | path `invoice` | Delete invoice |

## Notifications And Messages

| Method | Path | Body or Query | Description |
| --- | --- | --- | --- |
| `POST` | `/api/v1/notifications/send` | `patient_id`, `title`, `message` | Send notification to patient |
| `GET` | `/api/v1/notifications/doctor` | none | Doctor notifications |
| `GET` | `/api/v1/notifications/patient` | none | Patient notifications |
| `POST` | `/api/v1/notifications/read/{id}` | path `id` | Mark notification read |
| `POST` | `/api/v1/messages/send` | `patient_id`, `message` | Doctor sends message |
| `POST` | `/api/v1/messages/reply` | `doctor_id`, `message` | Patient replies |
| `GET` | `/api/v1/messages/thread?doctor_id=1&patient_id=2` | `doctor_id`, `patient_id` query | Message thread |
| `GET` | `/api/v1/messages/inbox/doctor` | none | Doctor inbox |
| `GET` | `/api/v1/messages/inbox/patient` | none | Patient inbox |

## Tenant And Frontend JSON Endpoints

These routes are not all inside `routes/api.php`, but they return JSON and are used by the frontend. Use the correct central or tenant domain.

| Method | Path | Host/Area | Description |
| --- | --- | --- | --- |
| `GET` | `/api` | central | API documentation page controller |
| `GET` | `/api/packages` | central | Package list |
| `GET` | `/api/coupons/available?amount=1000` | central | Active available coupons |
| `POST` | `/api/coupons/validate` | central | Validate coupon with `code`, `amount` |
| `GET` | `/api/available-chambers` | tenant | Tenant available chambers |
| `GET` | `/api/chambers/{chamber}` | tenant | Tenant chamber details |
| `GET` | `/geo/forward?city=Dhaka&country=Bangladesh` | central | Forward geocode city |
| `GET` | `/geo/reverse?lat=23.8103&lng=90.4125` | central | Reverse geocode coordinates |
| `GET` | `/chambers/{doctor}/{chamber}/slots/{date}` | central doctor details | Offline slots for doctor and chamber |
| `GET` | `/doctors/{doctor}/online-slots/{date}` | central doctor details | Online slots |
| `GET` | `/doctors/{doctor}/chambers` | central doctor details | Doctor chamber list |
| `GET` | `/doctors/{doctor}/payment-methods` | central doctor details | Available appointment payment methods |
| `POST` | `/appointments` | central doctor details | Public/frontend appointment booking |
| `POST` | `/appointments/{appointment}/ssl-initiate` | central doctor details | Start SSLCommerz payment for appointment |
| `GET` | `/doctors/nearby` | central | AJAX doctor search |

### Frontend Appointment Booking

`POST /appointments`

Body:

```json
{
  "doctor_id": 1,
  "consultation_type": "offline",
  "chamber_id": 1,
  "appointment_date": "2026-06-25",
  "appointment_time": "09:30",
  "patient_first_name": "Patient",
  "patient_last_name": "Name",
  "patient_email": "patient@example.com",
  "patient_phone": "01700000000",
  "patient_symptoms": "optional",
  "terms_agreed": true,
  "payment_method": "cod",
  "total_amount": 800
}
```

`payment_method` can be `cod` or `ssl_commerce` when SSLCommerz is enabled for the doctor.

## Full Registered API Route Inventory

This inventory is the expanded `php artisan route:list --path=api --except-vendor` route set, grouped for readability.

### Public And Web API Routes

| Method | Path | Controller |
| --- | --- | --- |
| `GET` | `/api` | `Api\DocumentationController@index` |
| `GET` | `/api/available-chambers` | `ChamberController@index` |
| `GET` | `/api/chambers/{chamber}` | `ChamberController@show` |
| `GET` | `/api/city-corporations/{district_id}` | `Api\LocationController@cityCorporations` |
| `GET` | `/api/coupons/available` | `CouponController@available` |
| `POST` | `/api/coupons/validate` | `CouponController@validateCoupon` |
| `GET` | `/api/districts/{division_id}` | `Api\LocationController@districts` |
| `GET` | `/api/divisions` | `Api\LocationController@divisions` |
| `POST` | `/api/doctor/profile/update` | `UserController@updateProfileUpdateByTenant` |
| `GET` | `/api/entities` | `TenantController@index` |
| `GET` | `/api/packages` | `Admin\PackageController@getPackages` |
| `GET` | `/api/pourasovas/{district_id}` | `Api\LocationController@pourasovas` |
| `POST` | `/api/registers` | `Api\AuthController@register` |
| `GET` | `/api/unions/{upazila_id}` | `Api\LocationController@unions` |
| `GET` | `/api/upazilas/{district_id}` | `Api\LocationController@upazilas` |

### Public Versioned Routes

| Method | Path | Controller |
| --- | --- | --- |
| `POST` | `/api/v1/logins` | `Api\AuthController@login` |
| `POST` | `/api/v1/check-subdomain` | `DoctorController@checkSubdomain` |
| `GET` | `/api/v1/packages` | `Api\DoctorRegistrationController@getPackages` |
| `POST` | `/api/v1/check-domain` | `Api\DoctorRegistrationController@checkDomain` |
| `POST` | `/api/v1/validate-coupon` | `Api\DoctorRegistrationController@validateCouponApi` |
| `POST` | `/api/v1/calculate-registration` | `Api\DoctorRegistrationController@calculateRegistration` |
| `POST` | `/api/v1/doctor/register` | `Api\DoctorRegistrationController@register` |
| `POST` | `/api/v1/sslcommerz/ipn` | `Api\DoctorRegistrationController@sslcommerzIpn` |
| `POST` | `/api/v1/sslcommerz/success` | `Api\DoctorRegistrationController@sslcommerzSuccess` |
| `POST` | `/api/v1/sslcommerz/fail` | `Api\DoctorRegistrationController@sslcommerzFail` |
| `POST` | `/api/v1/sslcommerz/cancel` | `Api\DoctorRegistrationController@sslcommerzCancel` |
| `GET`, `POST` | `/api/v1/stripe/success` | `Api\DoctorRegistrationController@stripeSuccess` |
| `GET`, `POST` | `/api/v1/stripe/cancel` | `Api\DoctorRegistrationController@stripeCancel` |
| `POST` | `/api/v1/payment/webhook/paypal` | `Api\DoctorRegistrationController@paypalWebhook` |
| `POST` | `/api/v1/payment/webhook/sslcommerz` | `Api\DoctorRegistrationController@sslcommerzWebhook` |
| `GET` | `/api/v1/registration/status/{order_id}` | `Api\DoctorRegistrationController@checkStatus` |

### Protected Versioned Non-Resource Routes

| Method | Path | Controller |
| --- | --- | --- |
| `GET` | `/api/v1/auth-debug` | Closure |
| `GET` | `/api/v1/me` | Closure |
| `POST` | `/api/v1/change-password` | `Api\AuthController@changePassword` |
| `POST` | `/api/v1/create-patient` | `Api\DoctorRegistrationController@createPatient` |
| `POST` | `/api/v1/medicines` | `Api\ContactController@storeMedicine` |
| `POST` | `/api/v1/tests` | `Api\ContactController@storeTest` |
| `GET` | `/api/v1/prescriptions` | `Api\PrescriptionController@index` |
| `POST` | `/api/v1/prescriptions` | `Api\ContactController@storePrescription` |
| `GET` | `/api/v1/prescriptions/{id}` | `Api\PrescriptionController@show` |
| `GET` | `/api/v1/social-media` | `Api\ContactController@socialMedia` |
| `POST` | `/api/v1/social-media/update` | `Api\ContactController@socialMediaUpdate` |
| `GET` | `/api/v1/seo-settings` | `Api\ContactController@seoSetting` |
| `POST` | `/api/v1/seo-settings/update` | `Api\ContactController@seoSettingUpdate` |
| `GET` | `/api/v1/settings/{type}` | `Api\ContactController@email_sms_payment_Settings` |
| `POST` | `/api/v1/settings/update/{type}` | `Api\ContactController@update_email_sms_payment_Settings` |
| `GET` | `/api/v1/patients` | `Api\PatientController@index` |
| `POST` | `/api/v1/patients` | `Api\PatientController@store` |
| `GET` | `/api/v1/patients/{id}` | `Api\PatientController@show` |
| `PUT` | `/api/v1/patients/{id}` | `Api\PatientController@update` |
| `DELETE` | `/api/v1/patients/{id}` | `Api\PatientController@destroy` |
| `GET` | `/api/v1/patients/{id}/history` | `Api\PatientController@history` |
| `GET` | `/api/v1/patients/{id}/records` | `Api\PatientController@records` |
| `GET` | `/api/v1/chambers/{id}/available-slots` | `Api\ChamberController@availableSlots` |
| `GET` | `/api/v1/doctors/online-slots/{date}` | `Api\ChamberController@getOnlineSlots` |
| `GET` | `/api/v1/doctor/appointments` | `Api\DoctorAppointmentController@index` |
| `GET` | `/api/v1/doctor/appointments/online` | `Api\DoctorAppointmentController@onlineAppointments` |
| `GET` | `/api/v1/doctor/appointments/calendar` | `Api\DoctorAppointmentController@calendar` |
| `GET` | `/api/v1/doctor/appointments/{id}` | `Api\DoctorAppointmentController@show` |
| `POST` | `/api/v1/doctor/appointments/{id}/status` | `Api\DoctorAppointmentController@updateStatus` |
| `POST` | `/api/v1/doctor/appointments/{id}/reschedule` | `Api\DoctorAppointmentController@reschedule` |
| `GET` | `/api/v1/doctor/billing` | `Api\DoctorBillingController@index` |
| `GET` | `/api/v1/doctor/billing/report` | `Api\DoctorBillingController@report` |
| `POST` | `/api/v1/appointments/book` | `Api\AppointmentController@store` |
| `POST` | `/api/v1/notifications/send` | `Api\NotificationMessageController@sendNotification` |
| `GET` | `/api/v1/notifications/doctor` | `Api\NotificationMessageController@doctorNotifications` |
| `GET` | `/api/v1/notifications/patient` | `Api\NotificationMessageController@patientNotifications` |
| `POST` | `/api/v1/notifications/read/{id}` | `Api\NotificationMessageController@markNotificationRead` |
| `POST` | `/api/v1/messages/send` | `Api\NotificationMessageController@sendMessage` |
| `POST` | `/api/v1/messages/reply` | `Api\NotificationMessageController@patientReply` |
| `GET` | `/api/v1/messages/thread` | `Api\NotificationMessageController@messageThread` |
| `GET` | `/api/v1/messages/inbox/doctor` | `Api\NotificationMessageController@doctorInbox` |
| `GET` | `/api/v1/messages/inbox/patient` | `Api\NotificationMessageController@patientInbox` |
| `POST` | `/api/v1/doctor/update` | `Api\DoctorProfileApiController@update` |
| `GET` | `/api/v1/doctor/profile/single-data/{type}` | `Api\DoctorProfileApiController@get_profile_single_data` |
| `PUT` | `/api/v1/doctor/profile/update-profile/{type}` | `Api\DoctorProfileApiController@update_profile_single_data` |

### Protected Resource Route Bases

Each base below expands to list, create, show, update, and delete routes unless noted otherwise.

| Base path | Controller |
| --- | --- |
| `/api/v1/categories` | `Api\CategoryController` |
| `/api/v1/post-types` | `Api\PostTypeController` |
| `/api/v1/medicine-templates` | `Api\MedicineTemplateController` |
| `/api/v1/sliders` | `Api\SliderController` |
| `/api/v1/galleries` | `Api\GalleryController` |
| `/api/v1/investigations` | `Api\InvestigationController` |
| `/api/v1/medicine-companies` | `Api\MedicineCompanyController` |
| `/api/v1/comorbidities` | `Api\ComorbidityController` |
| `/api/v1/plan-templates` | `Api\PlanTemplateController` |
| `/api/v1/follow-up-templates` | `Api\FollowUpTemplateController` |
| `/api/v1/doctor-posts` | `Api\DoctorPostController` |
| `/api/v1/prescription-templates` | `Api\PrescriptionTemplateController` |
| `/api/v1/chambers` | `Api\ChamberController` |
| `/api/v1/events` | `Api\EventController` |
| `/api/v1/patient-emr` | `Api\PatientEmrController` |
| `/api/v1/invoices` | `Api\InvoiceController` |

## Implementation Notes

- `GET /api/v1/doctors/online-slots/{date}` is registered with only `{date}`, but `Api\ChamberController@getOnlineSlots` currently declares `(User $doctor, $date)`. Verify this route before relying on it.
- `POST /api/v1/doctor/update` is registered without an `{id}` parameter, but `DoctorProfileApiController@update` declares `(Request $request, $id)`. Verify this route before relying on it.
- Several controllers initialize tenant context and call `tenancy()->end()` after a `return`; those calls are unreachable. The documented behavior above follows the current route/controller intent.
