@extends('layouts.admin')
@section('title', 'Prescription #' . $prescription->id)

@section('content')
<style>
    :root {
        --primary: #1a4d3e;
        --primary-light: rgba(26, 77, 62, 0.06);
        --primary-dark: #0f352a;
        --primary-soft: #e8f0ed;
        --accent: #c6a43b;
        --border-light: #e8e8e8;
        --border-medium: #d4d4d4;
        --text-dark: #1a1a2e;
        --text-medium: #4a4a5a;
        --text-light: #7a7a8a;
        --bg-page: #f0f2f5;
        --bg-card: #ffffff;
        --shadow-card: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    /* Header Actions */
    .compact-header {
        background: white;
        border-bottom: 2px solid var(--primary);
        padding: 16px 24px;
        margin-bottom: 30px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-left {
        display: flex;
        gap: 18px;
        align-items: center;
    }

    .header-icon {
        width: 44px;
        height: 44px;
        background: var(--primary-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 20px;
    }

    .header-title h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: var(--text-dark);
        line-height: 1.3;
    }

    .header-subtitle {
        font-size: 13px;
        color: var(--text-light);
        margin-top: 3px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-left: 12px;
    }

    .status-active { background: #e6f7e6; color: #2e7d32; }
    .status-completed { background: #e3f2fd; color: #1565c0; }
    .status-pending { background: #fff3e0; color: #e65100; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        font-family: inherit;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 77, 62, 0.25);
    }

    .btn-outline {
        background: white;
        color: var(--text-medium);
        border: 1px solid var(--border-medium);
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* ======================================== */
    /* PROFESSIONAL PRESCRIPTION CARD */
    /* ======================================== */
    
    .prescription-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px 30px 40px;
        background: var(--bg-page);
        min-height: calc(100vh - 100px);
    }

    .prescription-card {
        max-width: 1100px;
        width: 100%;
        background: var(--bg-card);
        border-radius: 20px;
        box-shadow: var(--shadow-card);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Decorative Border */
    .prescription-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 50%, var(--primary) 100%);
    }

    .prescription-inner {
        padding: 45px 50px 40px;
    }

    /* ===== HEADER SECTION ===== */
    .prescription-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .clinic-info {
        flex: 1;
    }

    .clinic-logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .logo-mark {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        box-shadow: 0 6px 14px rgba(26, 77, 62, 0.2);
    }

    .clinic-name {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.3px;
        margin: 0;
    }

    .clinic-tagline {
        font-size: 11px;
        color: var(--text-light);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    /* Doctor Info - Top Right */
    .doctor-info-top {
        text-align: right;
    }

    .doctor-name-top {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
        margin: 0 0 4px 0;
    }

    .doctor-qualification-top {
        font-size: 13px;
        color: var(--text-medium);
        margin: 0;
    }

    .doctor-reg-top {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 3px;
    }

    /* ===== TOP INFO BAR ===== */
    .top-info-bar {
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-item i {
        color: var(--primary);
        font-size: 14px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
    }

    /* ===== PATIENT INFO BAR ===== */
    .patient-info-bar {
        background: var(--primary-soft);
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid var(--border-light);
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .patient-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .patient-item i {
        color: var(--primary);
        font-size: 13px;
        margin-bottom: 2px;
    }

    .patient-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .patient-value {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-dark);
    }

    /* ===== DIAGNOSIS SECTION ===== */
    .diagnosis-section {
        margin-bottom: 25px;
    }

    .diagnosis-box {
        background: #fafbfc;
        padding: 15px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-light);
    }

    .diagnosis-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .diagnosis-text {
        font-size: 13px;
        color: var(--text-dark);
        line-height: 1.5;
    }

    .chief-complaint {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed var(--border-light);
        font-size: 12px;
        color: var(--text-medium);
    }

    /* ===== RX SECTION WITH TWO COLUMNS ===== */
    .rx-main-section {
        margin: 20px 0 25px;
    }

    .rx-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .rx-symbol-large {
        font-size: 32px;
        font-weight: 600;
        color: var(--primary);
        font-family: 'Georgia', 'Times New Roman', serif;
        margin-right: 20px;
        text-shadow: 2px 2px 4px rgba(26, 77, 62, 0.1);
    }

    .rx-header-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--border-medium), transparent);
    }

    /* Two Column Layout */
    .prescription-two-columns {
        display: flex;
        gap: 35px;
        margin-top: 10px;
    }

    .medicines-column {
        flex: 1.5;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .tests-column {
        flex: 1;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .column-header {
        background: var(--primary-soft);
        padding: 12px 16px;
        border-bottom: 2px solid var(--primary);
    }

    .column-header h5 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .column-header h5 i {
        font-size: 14px;
    }

    .column-body {
        padding: 16px;
        max-height: 400px;
        overflow-y: auto;
    }

    /* Medicines Table inside Column */
    .medicine-item {
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px dotted var(--border-light);
    }

    .medicine-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .medicine-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .medicine-details {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 14px;
        color: var(--text-light);
    }

    .medicine-details span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .medicine-details i {
        font-size: 10px;
        color: var(--primary);
    }

    /* Tests List inside Column */
    .test-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px dotted var(--border-light);
    }

    .test-item:last-child {
        border-bottom: none;
    }

    .test-marker {
        width: 20px;
        height: 20px;
        background: var(--primary-soft);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 10px;
    }

    .test-name {
        font-size: 15px;
        color: var(--text-medium);
        flex: 1;
    }

    .test-category {
        font-size: 10px;
        color: var(--text-light);
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .empty-state-column {
        text-align: center;
        padding: 30px 20px;
        color: var(--text-light);
        font-style: italic;
        font-size: 13px;
    }

    .empty-state-column i {
        font-size: 30px;
        margin-bottom: 10px;
        opacity: 0.5;
        display: block;
    }

    /* ===== INSTRUCTIONS SECTION ===== */
    .instructions-section {
        margin: 25px 0;
    }

    .instruction-box {
        background: #fefef5;
        border-left: 3px solid var(--accent);
        padding: 14px 18px;
        border-radius: 8px;
    }

    .instruction-text {
        font-size: 12px;
        color: var(--text-medium);
        line-height: 1.6;
        margin: 0;
    }

    .diet-advice {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px dashed var(--border-light);
        font-size: 12px;
        color: var(--text-medium);
    }

    /* ===== FOLLOW UP SECTION ===== */
    .followup-section {
        margin-bottom: 30px;
        display: inline-block;
        background: var(--primary-soft);
        padding: 10px 18px;
        border-radius: 30px;
    }

    .followup-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .followup-date {
        font-size: 13px;
        font-weight: 500;
        color: var(--primary-dark);
        margin-left: 8px;
    }

    /* ===== SIGNATURE SECTION ===== */
    .signature-section {
        display: flex;
        justify-content: flex-end;
        margin: 30px 0 20px;
        padding-top: 20px;
        border-top: 1px dashed var(--border-medium);
    }

    .signature-box {
        text-align: center;
        min-width: 220px;
    }

    .signature-line {
        width: 200px;
        height: 1px;
        background: var(--text-dark);
        margin-bottom: 10px;
        margin-left: auto;
    }

    .signature-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2px;
    }

    .signature-title {
        font-size: 10px;
        color: var(--text-light);
    }

    /* ===== FOOTER ===== */
    .prescription-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
        text-align: center;
    }

    .footer-address {
        font-size: 13px;
        color: var(--text-light);
        line-height: 1.5;
    }

    .footer-contact {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 8px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .footer-contact span i {
        margin-right: 5px;
        color: var(--primary);
    }

    /* ===== ENHANCED PRINT STYLES ===== */
    @media print {
        @page {
            margin: 0.75in;
            size: A4;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background: white !important;
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .prescription-wrapper {
            padding: 0 !important;
            background: white !important;
        }

        .prescription-card {
            box-shadow: none !important;
            border-radius: 0 !important;
            border: none !important;
        }

        .prescription-card::before {
            display: none;
        }

        .prescription-inner {
            padding: 0 !important;
        }

        .top-info-bar,
        .patient-info-bar {
            break-inside: avoid;
        }

        .medicines-column,
        .tests-column {
            break-inside: avoid;
            border: 1px solid #e0e0e0 !important;
        }

        .column-header {
            background: #f5f5f5 !important;
            break-inside: avoid;
        }

        .signature-line {
            border-bottom: 1px solid black !important;
        }

        .instruction-box {
            break-inside: avoid;
        }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .prescription-two-columns {
            flex-direction: column;
            gap: 20px;
        }
        
        .prescription-inner {
            padding: 25px;
        }
        
        .patient-info-bar {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .prescription-header {
            flex-direction: column;
            gap: 15px;
        }

        .doctor-info-top {
            text-align: left;
        }
        
        .rx-header {
            flex-wrap: wrap;
        }
        
        .top-info-bar {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 550px) {
        .patient-info-bar {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .signature-section {
            justify-content: center;
        }

        .signature-line {
            margin-left: auto;
            margin-right: auto;
        }
        
        .footer-contact {
            flex-direction: column;
            gap: 8px;
        }
        
        .prescription-inner {
            padding: 15px;
        }
    }
</style>

<!-- Header Actions (No Print) -->
<div class="compact-header no-print">
    <div class="header-content">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-prescription-bottle"></i>
            </div>
            <div class="header-title">
                <h1>
                    Prescription Details
                    <span class="status-badge status-active">
                        {{ ucfirst($prescription->status) }}
                    </span>
                </h1>
                <div class="header-subtitle">
                    Manage and view prescription information
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <button type="button" onclick="window.print()" class="btn-action btn-outline">
                <i class="fas fa-print"></i> Print
            </button>
            <button type="button" onclick="downloadPDF()" class="btn-action btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </button>
        </div>
    </div>
</div>

<!-- Professional Prescription Card -->
<div class="prescription-wrapper">
    <div class="prescription-card" id="prescriptionContent">
        <div class="prescription-inner">
            
            <!-- Header: Clinic Info (Left) + Doctor Info (Right) -->
            <div class="prescription-header">
                <div class="clinic-info">
                    <div class="clinic-logo-area">
                        <div class="logo-mark">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div>
                            <h1 class="clinic-name">
                                {{ $settingModel->clinic_name ?? $doctor->clinic_name ?? 'HEALTHCARE PLUS' }}
                            </h1>
                            <div class="clinic-tagline">Excellence in Medical Care</div>
                        </div>
                    </div>
                </div>
                <div class="doctor-info-top">
                    <h3 class="doctor-name-top">Dr. {{ $doctor->name }}</h3>
                    <p class="doctor-qualification-top">{{ $doctor->qualification ?? $doctor->specialization ?? 'MBBS, FCPS (Internal Medicine)' }}</p>
                    <p class="doctor-reg-top">Reg. No: {{ $doctor->reg_no ?? 'BMDC-12345' }}</p>
                </div>
            </div>

            <!-- Top Info Bar: Issue Date & Patient ID -->
            <div class="top-info-bar">
                <div class="info-item">
                    <span class="info-label">Issue Date: </span>
                    <span class="info-value">{{ $prescription->prescribed_date ? $prescription->prescribed_date->format('d/m/Y') : now()->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Patient ID: </span>
                    <span class="info-value">PT{{ str_pad($prescription->patient->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Patient Information Bar -->
            <div class="patient-info-bar">
                <div class="patient-item">
                    <span class="patient-label">Patient Name</span>
                    <span class="patient-value">{{ $prescription->patient->name }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Age / Gender</span>
                    <span class="patient-value">
                        @if($prescription->patient->age)
                            {{ $prescription->patient->age }} Years
                        @elseif($prescription->patient->date_of_birth)
                            {{ \Carbon\Carbon::parse($prescription->patient->date_of_birth)->age }} Years
                        @else
                            N/A
                        @endif
                        / {{ ucfirst($prescription->patient->gender ?? 'N/A') }}
                    </span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Weight / Blood</span>
                    <span class="patient-value">{{ $prescription->patient->weight ?? '—' }} kg / {{ $prescription->patient->blood_group ?? '—' }}</span>
                </div>
                <div class="patient-item">
                    <span class="patient-label">Contact</span>
                    <span class="patient-value">{{ $prescription->patient->phone ?? '—' }}</span>
                </div>
            </div>

            <!-- Diagnosis Section -->
            @if($prescription->diagnosis)
            <div class="diagnosis-section">
                <div class="diagnosis-box">
                    <div class="diagnosis-label">
                        <i class="fas fa-stethoscope"></i> Diagnosis
                    </div>
                    <div class="diagnosis-text">
                        {{ $prescription->diagnosis }}
                    </div>
                    @if($prescription->chief_complaint)
                    <div class="chief-complaint">
                        <strong>Chief Complaint:</strong> {{ $prescription->chief_complaint }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- RX Section with Two Columns: Medicines + Tests -->
            <div class="rx-main-section">
                <div class="rx-header">
                    <div class="rx-symbol-large">Rx</div>
                    <div class="rx-header-line"></div>
                </div>

                <div class="prescription-two-columns">
                     <!-- RIGHT COLUMN: TESTS -->
                    <div class="tests-column">
                        <div class="column-header">
                            <h5>
                                <i class="fas fa-flask"></i>
                                Recommended Tests
                            </h5>
                        </div>
                        <div class="column-body">
                            @php
                                $testsArray = [];
                                if ($prescription->tests) {
                                    if (is_array($prescription->tests)) {
                                        $testsArray = $prescription->tests;
                                    } elseif (is_string($prescription->tests)) {
                                        $decoded = json_decode($prescription->tests, true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $testsArray = $decoded;
                                        }
                                    }
                                }
                            @endphp

                            @if(!empty($testsArray) && is_array($testsArray))
                                @foreach($testsArray as $test)
                                <div class="test-item">
                                    <div class="test-marker">
                                        <i class="fas fa-vial"></i>
                                    </div>
                                    <div class="test-name">{{ $test['name'] ?? ($test['test_name'] ?? 'Test') }}</div>
                                    @if(isset($test['category']))
                                    <div class="test-category">{{ $test['category'] }}</div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="empty-state-column">
                                    <i class="fas fa-microscope"></i>
                                    No tests recommended
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- LEFT COLUMN: MEDICINES -->
                    <div class="medicines-column">
                        <div class="column-header">
                            <h5>
                                <i class="fas fa-pills"></i>
                                Prescribed Medications
                            </h5>
                        </div>
                        <div class="column-body">
                            @php
                                $medicinesArray = [];
                                if ($prescription->medicines) {
                                    if (is_array($prescription->medicines)) {
                                        $medicinesArray = $prescription->medicines;
                                    } elseif (is_string($prescription->medicines)) {
                                        $decoded = json_decode($prescription->medicines, true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $medicinesArray = $decoded;
                                        }
                                    }
                                }
                            @endphp

                            @if(!empty($medicinesArray) && is_array($medicinesArray))
                                @foreach($medicinesArray as $medicine)
                                <div class="medicine-item">
                                    <div class="medicine-name">{{ $medicine['name'] ?? ($medicine['medicine_name'] ?? 'Medicine') }}</div>
                                    <div class="medicine-details">
                                        <span><i class="fas fa-tachometer-alt"></i> {{ $medicine['dosage'] ?? ($medicine['medicine_dose'] ?? '—') }}</span>
                                        <span><i class="fas fa-clock"></i> {{ $medicine['frequency'] ?? '—' }}</span>
                                        <span><i class="fas fa-calendar-alt"></i> {{ $medicine['duration'] ?? ($medicine['medicine_day'] ?? '—') }}</span>
                                    </div>
                                    @if(isset($medicine['instructions']) && $medicine['instructions'])
                                    <div style="font-size: 11px; color: var(--text-light); margin-top: 5px;">
                                        <i class="fas fa-info-circle"></i> {{ $medicine['instructions'] }}
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="empty-state-column">
                                    <i class="fas fa-prescription"></i>
                                    No medications prescribed
                                </div>
                            @endif
                        </div>
                    </div>

                   
                </div>
            </div>

            <!-- Instructions Section -->
            @if($prescription->instructions || $prescription->diet_advice)
            <div class="instructions-section">
                <div class="instruction-box">
                    <div class="instruction-text">
                        <strong><i class="fas fa-info-circle"></i> Instructions:</strong><br>
                        {{ $prescription->instructions ?? 'Follow the medication schedule as prescribed.' }}
                    </div>
                    @if($prescription->diet_advice)
                    <div class="diet-advice">
                        <strong><i class="fas fa-apple-alt"></i> Dietary Advice:</strong> {{ $prescription->diet_advice }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Follow-up -->
            @if($prescription->next_visit_date)
            <div class="followup-section">
                <span class="followup-label">
                    <i class="fas fa-calendar-check"></i> Next Follow-up:
                </span>
                <span class="followup-date">
                    {{ \Carbon\Carbon::parse($prescription->next_visit_date)->format('l, F d, Y') }}
                </span>
            </div>
            @endif

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Dr. {{ $doctor->name }}</div>
                    <div class="signature-title">{{ $doctor->specialization ?? 'Consultant Physician' }}</div>
                    <div class="signature-title" style="font-size: 9px;">{{ $doctor->reg_no ? 'Reg. No: ' . $doctor->reg_no : '' }}</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="prescription-footer">
                <div class="footer-address">
                    <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                    {{ $settingModel->address ?? $doctor->address ?? '123 Healthcare Avenue, Dhaka, Bangladesh' }}
                </div>
                <div class="footer-contact">
                    <span><i class="fas fa-phone-alt"></i> {{ $settingModel->phone ?? $doctor->phone ?? '+880 1234 567890' }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $settingModel->email ?? $doctor->email ?? 'info@healthcare.com' }}</span>
                    <span><i class="fas fa-globe"></i> www.healthcareplus.com</span>
                </div>
                <div style="margin-top: 12px; font-size: 12px; color: #aaa;">
                    This is an electronically generated prescription - Valid for 30 days
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    function downloadPDF() {
        // Show loading indicator
        const overlay = document.createElement('div');
        overlay.id = 'pdfLoadingOverlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;justify-content:center;align-items:center;z-index:10000;flex-direction:column;';
        overlay.innerHTML = '<div style="background:white;padding:20px 40px;border-radius:12px;text-align:center;"><div style="width:40px;height:40px;border:3px solid #f3f3f3;border-top:3px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 15px;"></div><div style="color:var(--primary);font-weight:500;">Generating PDF...</div><div style="font-size:12px;color:#666;margin-top:5px;">Please wait</div></div>';
        document.body.appendChild(overlay);
        
        const content = document.getElementById('prescriptionContent');
        const clone = content.cloneNode(true);
        clone.style.width = '1100px';
        clone.style.position = 'absolute';
        clone.style.left = '-9999px';
        clone.style.top = '0';
        clone.style.backgroundColor = 'white';
        clone.style.padding = '20px';
        
        document.body.appendChild(clone);
        
        html2canvas(clone, {
            scale: 3,
            useCORS: true,
            logging: false,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 210;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            const pdf = new jspdf.jsPDF({
                orientation: imgHeight > 297 ? 'portrait' : 'portrait',
                unit: 'mm',
                format: 'a4'
            });
            
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            
            const patientName = "{{ $prescription->patient->name }}".replace(/[^a-zA-Z0-9]/g, '_');
            const filename = `Prescription_{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}_${patientName}.pdf`;
            
            pdf.save(filename);
            document.body.removeChild(clone);
            document.body.removeChild(overlay);
        }).catch(error => {
            console.error('Error:', error);
            document.body.removeChild(clone);
            document.body.removeChild(overlay);
            alert('Error generating PDF. Please use print function instead.');
        });
    }
    
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
    document.head.appendChild(style);
</script>

@endsection