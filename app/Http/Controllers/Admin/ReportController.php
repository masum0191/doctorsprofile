<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ward;
use App\Models\SecondMarriagePermission;
use App\Models\CitizenshipApplication;
use App\Models\MarriageApplication;
use App\Models\UnionMemberCertificate;
use App\Models\RoadExcavationPermit;
use App\Models\ConstructionExcavationApplication;
use App\Models\LandUseClearance;
use Illuminate\Http\Request;
use Carbon\Carbon;



class ReportController extends Controller
{
   public function certificatePaymentReport($type, Request $request)
{
    // Start building the query
    $query = Payment::where('payments.type', $type)
        ->join('certificate_applications', 'payments.form_id', '=', 'certificate_applications.id')
        ->select(
            'payments.*',
            'certificate_applications.application_number',
            'certificate_applications.applicant_name',
            'certificate_applications.mobile_number',
            'certificate_applications.type as certificate_type',
            'certificate_applications.status as application_status',
            'certificate_applications.created_at as application_date'
        );

    // Application ID filter
    if ($request->filled('application_id')) {
        $query->where('certificate_applications.application_number', 'like', '%'.$request->application_id.'%');
    }

    // Phone number filter
    if ($request->filled('phone')) {
        $query->where('certificate_applications.mobile_number', 'like', '%'.$request->phone.'%');
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    return view('admin.reports.certificate-payments', compact('payments', 'type'));
}
public function tradeLicensePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'trade_license_application')
        ->join('trade_licenses', 'payments.form_id', '=', 'trade_licenses.id')
        ->select(
            'payments.*',
            'trade_licenses.license_no',
            'trade_licenses.name_bn',
            'trade_licenses.name_en',
            'trade_licenses.mobile',
            'trade_licenses.business_name_bn',
            'trade_licenses.business_name_en',
            'trade_licenses.ward_no',
            'trade_licenses.status as license_status'
        );

    // License number filter
    if ($request->filled('license_no')) {
        $query->where('trade_licenses.license_no', 'like', '%'.$request->license_no.'%');
    }

    // Business name filter
    if ($request->filled('business_name')) {
        $query->where(function($q) use ($request) {
            $q->where('trade_licenses.business_name_bn', 'like', '%'.$request->business_name.'%')
              ->orWhere('trade_licenses.business_name_en', 'like', '%'.$request->business_name.'%');
        });
    }

    // Mobile number filter
    if ($request->filled('mobile')) {
        $query->where('trade_licenses.mobile', 'like', '%'.$request->mobile.'%');
    }

    // Ward number filter
    if ($request->filled('ward_no')) {
        $query->where('trade_licenses.ward_no', $request->ward_no);
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // License status filter
    if ($request->filled('license_status')) {
        $query->where('trade_licenses.status', $request->license_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get wards for filter dropdown
    $wards = Ward::all();

    return view('admin.reports.trade-license-payments', compact('payments', 'wards'));
}

public function exportTradeLicensePayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'trade_license')
        ->join('trade_licenses', 'payments.form_id', '=', 'trade_licenses.id')
        ->select(
            'payments.*',
            'trade_licenses.license_no',
            'trade_licenses.name_bn',
            'trade_licenses.name_en',
            'trade_licenses.mobile',
            'trade_licenses.business_name_bn',
            'trade_licenses.business_name_en',
            'trade_licenses.ward_no',
            'trade_licenses.status as license_status'
        );

    // License number filter
    if ($request->filled('license_no')) {
        $query->where('trade_licenses.license_no', 'like', '%'.$request->license_no.'%');
    }

    // Business name filter
    if ($request->filled('business_name')) {
        $query->where(function($q) use ($request) {
            $q->where('trade_licenses.business_name_bn', 'like', '%'.$request->business_name.'%')
              ->orWhere('trade_licenses.business_name_en', 'like', '%'.$request->business_name.'%');
        });
    }

    // Mobile number filter
    if ($request->filled('mobile')) {
        $query->where('trade_licenses.mobile', 'like', '%'.$request->mobile.'%');
    }

    // Ward number filter
    if ($request->filled('ward_no')) {
        $query->where('trade_licenses.ward_no', $request->ward_no);
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // License status filter
    if ($request->filled('license_status')) {
        $query->where('trade_licenses.status', $request->license_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    return Excel::download(new TradeLicensePaymentsExport($query), 'trade-license-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function applicationPaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'vgf_application')
        ->join('applications', 'payments.form_id', '=', 'applications.id')
        ->select(
            'payments.*',
            'applications.applicant_number',
            'applications.head_name',
            'applications.mobile_number',
            'applications.ward_number',
            'applications.card_type',
            'applications.created_at as application_date'
        );

    // Applicant number filter
    if ($request->filled('applicant_number')) {
        $query->where('applications.applicant_number', 'like', '%'.$request->applicant_number.'%');
    }

    // Head name filter
    if ($request->filled('head_name')) {
        $query->where('applications.head_name', 'like', '%'.$request->head_name.'%');
    }

    // Mobile number filter
    if ($request->filled('mobile_number')) {
        $query->where('applications.mobile_number', 'like', '%'.$request->mobile_number.'%');
    }

    // Ward number filter
    if ($request->filled('ward_number')) {
        $query->where('applications.ward_number', $request->ward_number);
    }

    // Card type filter
    if ($request->filled('card_type')) {
        $query->where('applications.card_type', $request->card_type);
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get card types for filter dropdown
    $cardTypes = [
        'widow' => 'বিধবা ভাতা কার্ড',
        'disabled' => 'প্রতিবন্ধী ভাতা কার্ড',
        'elderly' => 'বয়স্ক ভাতা কার্ড',
        'other' => 'অন্যান্য'
    ];

    return view('admin.reports.vgf-payment', compact('payments', 'cardTypes'));
}
public function exportApplicationPayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where(...);

    return Excel::download(new ApplicationPaymentsExport($query), 'application-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function deathCertificatePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'death_certificate_application')
        ->join('death_certificate_applications', 'payments.form_id', '=', 'death_certificate_applications.id')
        ->select(
            'payments.*',
            'death_certificate_applications.application_no',
            'death_certificate_applications.applicant_name_bn',
            'death_certificate_applications.applicant_name_en',
            'death_certificate_applications.mobile_no',
            'death_certificate_applications.deceased_name_bn',
            'death_certificate_applications.deceased_name_en',
            'death_certificate_applications.death_date',
            'death_certificate_applications.status as application_status',
            'death_certificate_applications.created_at as application_date'
        );

    // Application number filter
    if ($request->filled('application_no')) {
        $query->where('death_certificate_applications.application_no', 'like', '%'.$request->application_no.'%');
    }

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where(function($q) use ($request) {
            $q->where('death_certificate_applications.applicant_name_bn', 'like', '%'.$request->applicant_name.'%')
              ->orWhere('death_certificate_applications.applicant_name_en', 'like', '%'.$request->applicant_name.'%');
        });
    }

    // Deceased name filter
    if ($request->filled('deceased_name')) {
        $query->where(function($q) use ($request) {
            $q->where('death_certificate_applications.deceased_name_bn', 'like', '%'.$request->deceased_name.'%')
              ->orWhere('death_certificate_applications.deceased_name_en', 'like', '%'.$request->deceased_name.'%');
        });
    }

    // Mobile number filter
    if ($request->filled('mobile_no')) {
        $query->where('death_certificate_applications.mobile_no', 'like', '%'.$request->mobile_no.'%');
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('death_certificate_applications.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    return view('admin.reports.death-certificate-payments', compact('payments'));
}
public function exportDeathCertificatePayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'death_certificate')
        ->join(...);

    return Excel::download(new DeathCertificatePaymentsExport($query), 'death-certificate-payments-'.now()->format('Y-m-d').'.xlsx');
}
public function citizenshipPaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'citizen_application')
        ->join('citizenship_applications', 'payments.form_id', '=', 'citizenship_applications.id')
        ->select(
            'payments.*',
            'citizenship_applications.name_bangla',
            'citizenship_applications.name_english',
            'citizenship_applications.father_name',
            'citizenship_applications.mobile',
            'citizenship_applications.nid_number',
            'citizenship_applications.district',
            'citizenship_applications.thana',
            'citizenship_applications.ward',
            'citizenship_applications.status as application_status',
            'citizenship_applications.created_at as application_date'
        );

    // Name filter (Bangla or English)
    if ($request->filled('name')) {
        $query->where(function($q) use ($request) {
            $q->where('citizenship_applications.name_bangla', 'like', '%'.$request->name.'%')
              ->orWhere('citizenship_applications.name_english', 'like', '%'.$request->name.'%');
        });
    }

    // Father name filter
    if ($request->filled('father_name')) {
        $query->where('citizenship_applications.father_name', 'like', '%'.$request->father_name.'%');
    }

    // Mobile number filter
    if ($request->filled('mobile')) {
        $query->where('citizenship_applications.mobile', 'like', '%'.$request->mobile.'%');
    }

    // NID filter
    if ($request->filled('nid_number')) {
        $query->where('citizenship_applications.nid_number', 'like', '%'.$request->nid_number.'%');
    }

    // District filter
    if ($request->filled('district')) {
        $query->where('citizenship_applications.district', $request->district);
    }

    // Thana filter
    if ($request->filled('thana')) {
        $query->where('citizenship_applications.thana', $request->thana);
    }

    // Ward filter
    if ($request->filled('ward')) {
        $query->where('citizenship_applications.ward', $request->ward);
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('citizenship_applications.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique districts for filter dropdown
    $districts = CitizenshipApplication::select('district')->distinct()->pluck('district');

    return view('admin.reports.citizenship-payments', compact('payments', 'districts'));
}
public function exportCitizenshipPayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'citizenship_application')
        ->join(...);

    return Excel::download(new CitizenshipPaymentsExport($query), 'citizenship-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function marriagePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'marriage_application')
        ->join('marriage_applications', 'payments.form_id', '=', 'marriage_applications.id')
        ->select(
            'payments.*',
            'marriage_applications.application_number',
            'marriage_applications.applicant_name',
            'marriage_applications.spouse_name',
            'marriage_applications.mobile_number',
            'marriage_applications.district',
            'marriage_applications.upazila',
            'marriage_applications.ward_number',
            'marriage_applications.marriage_date',
            'marriage_applications.status as application_status',
            'marriage_applications.created_at as application_date'
        );

    // Application number filter
    if ($request->filled('application_number')) {
        $query->where('marriage_applications.application_number', 'like', '%'.$request->application_number.'%');
    }

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('marriage_applications.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // Spouse name filter
    if ($request->filled('spouse_name')) {
        $query->where('marriage_applications.spouse_name', 'like', '%'.$request->spouse_name.'%');
    }

    // Mobile number filter
    if ($request->filled('mobile_number')) {
        $query->where('marriage_applications.mobile_number', 'like', '%'.$request->mobile_number.'%');
    }

    // District filter
    if ($request->filled('district')) {
        $query->where('marriage_applications.district', $request->district);
    }

    // Upazila filter
    if ($request->filled('upazila')) {
        $query->where('marriage_applications.upazila', $request->upazila);
    }

    // Ward filter
    if ($request->filled('ward_number')) {
        $query->where('marriage_applications.ward_number', $request->ward_number);
    }

    // Marriage date range filter
    if ($request->filled('marriage_from_date') && $request->filled('marriage_to_date')) {
        $query->whereBetween('marriage_applications.marriage_date', [
            Carbon::parse($request->marriage_from_date)->startOfDay(),
            Carbon::parse($request->marriage_to_date)->endOfDay()
        ]);
    }

    // Application date range filter
    if ($request->filled('application_from_date') && $request->filled('application_to_date')) {
        $query->whereBetween('marriage_applications.created_at', [
            Carbon::parse($request->application_from_date)->startOfDay(),
            Carbon::parse($request->application_to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('marriage_applications.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique districts and upazilas for filter dropdowns
    $districts = MarriageApplication::select('district')->distinct()->pluck('district');
    $upazilas = MarriageApplication::select('upazila')->distinct()->pluck('upazila');

    return view('admin.reports.marriage-payments', compact('payments', 'districts', 'upazilas'));
}
public function exportMarriagePayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'marriage_application')
        ->join(...);

    return Excel::download(new MarriagePaymentsExport($query), 'marriage-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function secondMarriagePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'second_marriage_application')
        ->join('second_marriage_permissions', 'payments.form_id', '=', 'second_marriage_permissions.id')
        ->select(
            'payments.*',
            'second_marriage_permissions.application_number',
            'second_marriage_permissions.applicant_name',
            'second_marriage_permissions.first_spouse_name',
            'second_marriage_permissions.second_spouse_name',
            'second_marriage_permissions.mobile_number',
            'second_marriage_permissions.district',
            'second_marriage_permissions.upazila',
            'second_marriage_permissions.ward_number',
            'second_marriage_permissions.second_marriage_date',
            'second_marriage_permissions.status as application_status',
            'second_marriage_permissions.created_at as application_date'
        );

    // Application number filter
    if ($request->filled('application_number')) {
        $query->where('second_marriage_permissions.application_number', 'like', '%'.$request->application_number.'%');
    }

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('second_marriage_permissions.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // First spouse name filter
    if ($request->filled('first_spouse_name')) {
        $query->where('second_marriage_permissions.first_spouse_name', 'like', '%'.$request->first_spouse_name.'%');
    }

    // Second spouse name filter
    if ($request->filled('second_spouse_name')) {
        $query->where('second_marriage_permissions.second_spouse_name', 'like', '%'.$request->second_spouse_name.'%');
    }

    // Mobile number filter
    if ($request->filled('mobile_number')) {
        $query->where('second_marriage_permissions.mobile_number', 'like', '%'.$request->mobile_number.'%');
    }

    // District filter
    if ($request->filled('district')) {
        $query->where('second_marriage_permissions.district', $request->district);
    }

    // Upazila filter
    if ($request->filled('upazila')) {
        $query->where('second_marriage_permissions.upazila', $request->upazila);
    }

    // Ward filter
    if ($request->filled('ward_number')) {
        $query->where('second_marriage_permissions.ward_number', $request->ward_number);
    }

    // Second marriage date range filter
    if ($request->filled('marriage_from_date') && $request->filled('marriage_to_date')) {
        $query->whereBetween('second_marriage_permissions.second_marriage_date', [
            Carbon::parse($request->marriage_from_date)->startOfDay(),
            Carbon::parse($request->marriage_to_date)->endOfDay()
        ]);
    }

    // Application date range filter
    if ($request->filled('application_from_date') && $request->filled('application_to_date')) {
        $query->whereBetween('second_marriage_permissions.created_at', [
            Carbon::parse($request->application_from_date)->startOfDay(),
            Carbon::parse($request->application_to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('second_marriage_permissions.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique districts and upazilas for filter dropdowns
    $districts = SecondMarriagePermission::select('district')->distinct()->pluck('district');
    $upazilas = SecondMarriagePermission::select('upazila')->distinct()->pluck('upazila');

    return view('admin.reports.second-marriage-payments', compact('payments', 'districts', 'upazilas'));
}
public function exportSecondMarriagePayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'second_marriage_permission')
        ->join(...);

    return Excel::download(new SecondMarriagePaymentsExport($query), 'second-marriage-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function unmarriedCertificatePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'unmarried_certificate')
        ->join('union_member_certificates', 'payments.form_id', '=', 'union_member_certificates.id')
        ->select(
            'payments.*',
            'union_member_certificates.application_number',
            'union_member_certificates.applicant_name',
            'union_member_certificates.gender',
            'union_member_certificates.father_or_husband_name',
            'union_member_certificates.mobile_number',
            'union_member_certificates.nid_number',
            'union_member_certificates.district',
            'union_member_certificates.upazila',
            'union_member_certificates.ward_number',
            'union_member_certificates.birth_date',
            'union_member_certificates.status as application_status',
            'union_member_certificates.created_at as application_date'
        );

    // Application number filter
    if ($request->filled('application_number')) {
        $query->where('union_member_certificates.application_number', 'like', '%'.$request->application_number.'%');
    }

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('union_member_certificates.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // Father/Husband name filter
    if ($request->filled('father_name')) {
        $query->where('union_member_certificates.father_or_husband_name', 'like', '%'.$request->father_name.'%');
    }

    // Mobile number filter
    if ($request->filled('mobile_number')) {
        $query->where('union_member_certificates.mobile_number', 'like', '%'.$request->mobile_number.'%');
    }

    // NID filter
    if ($request->filled('nid_number')) {
        $query->where('union_member_certificates.nid_number', 'like', '%'.$request->nid_number.'%');
    }

    // District filter
    if ($request->filled('district')) {
        $query->where('union_member_certificates.district', $request->district);
    }

    // Upazila filter
    if ($request->filled('upazila')) {
        $query->where('union_member_certificates.upazila', $request->upazila);
    }

    // Ward filter
    if ($request->filled('ward_number')) {
        $query->where('union_member_certificates.ward_number', $request->ward_number);
    }

    // Gender filter
    if ($request->filled('gender')) {
        $query->where('union_member_certificates.gender', $request->gender);
    }

    // Age range filter
    if ($request->filled('age_from') && $request->filled('age_to')) {
        $currentDate = now();
        $minBirthDate = $currentDate->subYears($request->age_to)->format('Y-m-d');
        $maxBirthDate = $currentDate->subYears($request->age_from)->format('Y-m-d');
        $query->whereBetween('union_member_certificates.birth_date', [$maxBirthDate, $minBirthDate]);
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('union_member_certificates.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique districts and upazilas for filter dropdowns
    $districts = UnionMemberCertificate::select('district')->distinct()->pluck('district');
    $upazilas = UnionMemberCertificate::select('upazila')->distinct()->pluck('upazila');
    $genders = ['male' => 'পুরুষ', 'female' => 'মহিলা', 'other' => 'অন্যান্য'];

    return view('admin.reports.unmarried-certificate-payments', compact('payments', 'districts', 'upazilas', 'genders'));
}
public function exportUnmarriedCertificatePayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'unmarried_certificate')
        ->join(...);
    
    return Excel::download(new UnmarriedCertificatePaymentsExport($query), 'unmarried-certificate-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function roadExcavationPaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'road_excavation_application')
        ->join('road_excavation_permits', 'payments.form_id', '=', 'road_excavation_permits.id')
        ->select(
            'payments.*',
            'road_excavation_permits.applicant_name',
            'road_excavation_permits.nid',
            'road_excavation_permits.phone',
            'road_excavation_permits.road_location',
            'road_excavation_permits.purpose',
            'road_excavation_permits.start_date',
            'road_excavation_permits.end_date',
            'road_excavation_permits.status as application_status',
            'road_excavation_permits.created_at as application_date'
        );

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('road_excavation_permits.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // NID filter
    if ($request->filled('nid')) {
        $query->where('road_excavation_permits.nid', 'like', '%'.$request->nid.'%');
    }

    // Phone filter
    if ($request->filled('phone')) {
        $query->where('road_excavation_permits.phone', 'like', '%'.$request->phone.'%');
    }

    // Road location filter
    if ($request->filled('road_location')) {
        $query->where('road_excavation_permits.road_location', 'like', '%'.$request->road_location.'%');
    }

    // Purpose filter
    if ($request->filled('purpose')) {
        $query->where('road_excavation_permits.purpose', 'like', '%'.$request->purpose.'%');
    }

    // Date range filter (application date)
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Excavation date range filter
    if ($request->filled('excavation_from_date') && $request->filled('excavation_to_date')) {
        $query->where(function($q) use ($request) {
            $q->whereBetween('road_excavation_permits.start_date', [
                Carbon::parse($request->excavation_from_date)->startOfDay(),
                Carbon::parse($request->excavation_to_date)->endOfDay()
            ])
            ->orWhereBetween('road_excavation_permits.end_date', [
                Carbon::parse($request->excavation_from_date)->startOfDay(),
                Carbon::parse($request->excavation_to_date)->endOfDay()
            ]);
        });
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('road_excavation_permits.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique purposes for filter dropdown
    $purposes = RoadExcavationPermit::select('purpose')->distinct()->pluck('purpose');

    return view('admin.reports.road-excavation-payments', compact('payments', 'purposes'));
}
public function exportRoadExcavationPayments(Request $request)
{
    // Build the same query as the report function
    $query = Payment::where('payments.type', 'road_excavation_permit')
        ->join(...);
    
    return Excel::download(new RoadExcavationPaymentsExport($query), 'road-excavation-payments-'.now()->format('Y-m-d').'.xlsx');
}

public function constructionExcavationPaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'constraction_application')
        ->join('construction_excavation_applications', 'payments.form_id', '=', 'construction_excavation_applications.id')
        ->select(
            'payments.*',
            'construction_excavation_applications.applicant_name',
            'construction_excavation_applications.nid',
            'construction_excavation_applications.phone',
            'construction_excavation_applications.application_type',
            'construction_excavation_applications.location',
            'construction_excavation_applications.status as application_status',
            'construction_excavation_applications.created_at as application_date'
        );

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('construction_excavation_applications.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // NID filter
    if ($request->filled('nid')) {
        $query->where('construction_excavation_applications.nid', 'like', '%'.$request->nid.'%');
    }

    // Phone filter
    if ($request->filled('phone')) {
        $query->where('construction_excavation_applications.phone', 'like', '%'.$request->phone.'%');
    }

    // Application type filter
    if ($request->filled('application_type')) {
        $query->where('construction_excavation_applications.application_type', $request->application_type);
    }

    // Location filter
    if ($request->filled('location')) {
        $query->where('construction_excavation_applications.location', 'like', '%'.$request->location.'%');
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('construction_excavation_applications.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique application types for filter dropdown
    $applicationTypes = ConstructionExcavationApplication::select('application_type')
        ->distinct()
        ->pluck('application_type');

    return view('admin.reports.construction-excavation-payments', compact('payments', 'applicationTypes'));
}
public function landUseClearancePaymentReport(Request $request)
{
    $query = Payment::where('payments.type', 'land_application')
        ->join('land_use_clearances', 'payments.form_id', '=', 'land_use_clearances.id')
        ->select(
            'payments.*',
            'land_use_clearances.applicant_name',
            'land_use_clearances.father_name',
            'land_use_clearances.nid',
            'land_use_clearances.phone',
            'land_use_clearances.khotian_no',
            'land_use_clearances.dag_no',
            'land_use_clearances.mouza',
            'land_use_clearances.upazila',
            'land_use_clearances.district',
            'land_use_clearances.purpose',
            'land_use_clearances.status as application_status',
            'land_use_clearances.created_at as application_date'
        );

    // Applicant name filter
    if ($request->filled('applicant_name')) {
        $query->where('land_use_clearances.applicant_name', 'like', '%'.$request->applicant_name.'%');
    }

    // Father name filter
    if ($request->filled('father_name')) {
        $query->where('land_use_clearances.father_name', 'like', '%'.$request->father_name.'%');
    }

    // NID filter
    if ($request->filled('nid')) {
        $query->where('land_use_clearances.nid', 'like', '%'.$request->nid.'%');
    }

    // Phone filter
    if ($request->filled('phone')) {
        $query->where('land_use_clearances.phone', 'like', '%'.$request->phone.'%');
    }

    // Khotian number filter
    if ($request->filled('khotian_no')) {
        $query->where('land_use_clearances.khotian_no', 'like', '%'.$request->khotian_no.'%');
    }

    // Dag number filter
    if ($request->filled('dag_no')) {
        $query->where('land_use_clearances.dag_no', 'like', '%'.$request->dag_no.'%');
    }

    // Mouza filter
    if ($request->filled('mouza')) {
        $query->where('land_use_clearances.mouza', 'like', '%'.$request->mouza.'%');
    }

    // Upazila filter
    if ($request->filled('upazila')) {
        $query->where('land_use_clearances.upazila', $request->upazila);
    }

    // District filter
    if ($request->filled('district')) {
        $query->where('land_use_clearances.district', $request->district);
    }

    // Purpose filter
    if ($request->filled('purpose')) {
        $query->where('land_use_clearances.purpose', 'like', '%'.$request->purpose.'%');
    }

    // Date range filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('payments.created_at', [
            Carbon::parse($request->from_date)->startOfDay(),
            Carbon::parse($request->to_date)->endOfDay()
        ]);
    }

    // Payment status filter
    if ($request->filled('payment_status')) {
        $query->where('payments.payment_status', $request->payment_status);
    }

    // Application status filter
    if ($request->filled('application_status')) {
        $query->where('land_use_clearances.status', $request->application_status);
    }

    // Order and paginate results
    $payments = $query->orderBy('payments.created_at', 'desc')
        ->paginate(20)
        ->appends($request->query());

    // Get unique districts and upazilas for filter dropdowns
    $districts = LandUseClearance::select('district')->distinct()->pluck('district');
    $upazilas = LandUseClearance::select('upazila')->distinct()->pluck('upazila');
    $purposes = LandUseClearance::select('purpose')->distinct()->pluck('purpose');

    return view('admin.reports.land-use-clearance-payments', compact('payments', 'districts', 'upazilas', 'purposes'));
}
}
