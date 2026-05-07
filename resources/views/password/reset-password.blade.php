@extends('layouts.forntend')
@section('title', 'পাসওয়ার্ড রিসেট')
@section('content')
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-bg: #f8fafc;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'SolaimanLipi', Arial, sans-serif;
        }

        .auth-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .auth-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .auth-body {
            padding: 30px;
            background-color: white;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--secondary-color);
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .input-hint {
            font-size: 13px;
            color: #6c757d;
            margin-top: 5px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            border-radius: 8px;
        }

        .back-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .step {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 10px;
        }

        .step.inactive {
            background-color: #eee;
            color: #95a5a6;
        }

        .step-line {
            height: 2px;
            background-color: #eee;
            flex-grow: 1;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">পাসওয়ার্ড রিসেট</h1>
            </div>

            <div class="auth-body">
                <div class="step-indicator">
                    <div class="step">1</div>
                    <div class="step-line"></div>
                    <div class="step inactive">2</div>
                </div>

        <form action="{{ route('verify.nid.mobile') }}" method="GET">
            @csrf

                    <div class="mb-3">
                        <label for="nid" class="form-label">জাতীয় পরিচয়পত্র নম্বর (NID) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nid" name="nid" required
                               placeholder="আপনার NID নম্বর লিখুন">
                        <small class="input-hint">উদাহরণ: 1234567890123</small>
                    </div>

                    <div class="mb-4">
                        <label for="mobile" class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="mobile" name="mobile" required
                               placeholder="আপনার মোবাইল নম্বর লিখুন">
                        <small class="input-hint">NID এ রেজিস্টার্ড মোবাইল নম্বর</small>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                       <a href="/set-password">
                           <button type="submit" class="btn btn-primary">
                               পরবর্তী ধাপ <i class="fas fa-arrow-right ms-2"></i>
                           </button>
                       </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
