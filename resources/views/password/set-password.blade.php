@extends('layouts.forntend')
@section('title', 'পাসওয়ার্ড রিসেট')
@section('content')
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
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

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            font-weight: 500;
            border-radius: 8px;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
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
            background-color: var(--success-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 10px;
        }

        .step-line {
            height: 2px;
            background-color: var(--success-color);
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
                    <div class="step">2</div>
                </div>

                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i> আপনার পরিচয় যাচাই সম্পন্ন হয়েছে
                </div>

                <form action="{{ route('set.password.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="new_password" class="form-label">নতুন পাসওয়ার্ড <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required
                               placeholder="নতুন পাসওয়ার্ড লিখুন">
                        <small class="input-hint">অন্তত ৮ অক্ষর দীর্ঘ হতে হবে</small>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                               placeholder="পাসওয়ার্ড আবার লিখুন">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="forget-password" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> পূর্ববর্তী ধাপ
                        </a>
                        <button type="submit" class="btn btn-primary" id="reset-btn">
                            পাসওয়ার্ড পরিবর্তন করুন <i class="fas fa-check ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Simple password match validation
        document.getElementById('reset-btn').addEventListener('click', function(e) {
            const pass1 = document.getElementById('new_password').value;
            const pass2 = document.getElementById('confirm_password').value;

            if (pass1.length < 8) {
                alert('পাসওয়ার্ড অন্তত ৮ অক্ষর দীর্ঘ হতে হবে');
                e.preventDefault();
            } else if (pass1 !== pass2) {
                alert('পাসওয়ার্ড মেলেনি! দয়া করে একই পাসওয়ার্ড লিখুন');
                e.preventDefault();
            }
        });
    </script>
    @endsection
