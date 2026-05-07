<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  
  <!-- Meta Tags (Same as before) -->
  <meta name="description" content="{{@$setting->meta_description}}">
  <meta name="keywords" content="{{@$setting->keywords}}">
  <meta name="robots" content="{{ @$setting->robots }}">
  <meta property="og:title" content="{{@$setting->ogtitle}}" />
  <meta property="og:description" content="{{@$setting->ogdescription}}" />
  <meta property="og:type" content="{{@$setting->ogtype}}" />
  <meta property="og:url" content="{{@$setting->ogurl}}" />
  <meta property="og:image" content="{{ url('storage/' . @$setting->site_logo) }}" />
  <meta name="twitter:title" content="{{@$setting->site_name}}" />
  <meta name="twitter:description" content="{{@$setting->meta_description}}" />
  <meta name="twitter:card" content="{{@$setting->site_name}}" />
  <meta name="twitter:site" content="{{ url('storage/' . @$setting->site_logo) }}" />
  <meta name="twitter:url" content="{{@$setting->ogurl}}" />
  <meta name="twitter:image" content="{{ url('storage/' . @$setting->site_logo) }}" />
  <meta name="theme-color" content="#1e3a8a">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="msapplication-TileColor" content="#1e3a8a">
  <meta name="msapplication-TileImage" content="{{ url('storage/' . @$setting->site_logo) }}">
  
  <link rel="icon" type="image/x-icon" href="{{url('images/LMS-1.jpg')}}">
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://fonts.maateen.me/solaiman-lipi/font.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="{{url('css/lightbox.css')}}">
  <link rel="stylesheet" href="{{url('css/venobox.css')}}">
  <link rel="stylesheet" href="{{url('css/theme2.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @yield('style')
  <style>
    :root {
      --primary-blue: #1e3a8a;
      --secondary-blue: #3b82f6;
      --accent-green: #059669;
      --dark-blue: #1e40af;
      --light-blue: #dbeafe;
      --gold: #d97706;
      --white: #ffffff;
      --gray-light: #f8fafc;
      --gray-dark: #374151;
    }
    ul {
      padding: 0;
      margin: 0;
    }
    li {
      list-style: none;
    }

    body {
      font-family: "SolaimanLipi", Arial, sans-serif;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      min-height: 100vh;
    }

    /* Header Styles */
    .govt-header {
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
      color: white;
      padding: 8px 0;
      border-bottom: 4px solid var(--gold);
    }

    .emergency-alert {
      background: #dc2626;
      color: white;
      padding: 8px 0;
      font-size: 14px;
    }

    .form-container .col-form-label span {
    color: #c33434;
    font-size: 18px;
}

    .marquee-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 8px 0;
    }

    .border-b {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-top: 30px !important;
    font-size: 20px;
}

.form-control {
    background-color: #f7f7f7db;
}
.form-control:focus {
  box-shadow: none;
}

.custom-submit {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 10px 30px;
    border-radius: 5px;
    font-weight: 500;
}
    .marquee-text a {
      color: white;
      text-decoration: none;
      font-weight: 500;
    }

    .marquee-text a:hover {
      color: var(--light-blue);
    }

    /* Auth Buttons */
    .auth-buttons .btn {
      border-radius: 6px;
      font-weight: 600;
      padding: 8px 16px;
      transition: all 0.3s ease;
    }

    .btn-login {
      background: var(--accent-green);
      border: 2px solid var(--accent-green);
      color: white;
    }

    .btn-login:hover {
      background: transparent;
      color: var(--accent-green);
    }

    .btn-register {
      background: transparent;
      border: 2px solid white;
      color: white;
    }

    .btn-register:hover {
      background: white;
      color: var(--primary-blue);
    }

    .btn-dashboard {
      background: var(--gold);
      border: 2px solid var(--gold);
      color: white;
    }

    .btn-dashboard:hover {
      background: transparent;
      color: var(--gold);
    }

    /* Hero Section */
    .hero-banner {
      position: relative;
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      border-radius: 0 0 20px 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      padding: 40px 0;
      text-align: center;
    }

    .hero-logo {
      background: white;
      padding: 20px;
      border-radius: 15px;
      display: inline-block;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .hero-logo img {
      max-height: 100px;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .hero-content h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-content h2 {
      font-size: 1.4rem;
      font-weight: 400;
      opacity: 0.9;
    }

    /* Navigation */
    .main-navigation {
      margin-top: 2px;
      /* position: sticky;
      top: 0;
      z-index: 1000; */
    }

    .nav_list {
      display: flex;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav_list > li {
      position: relative;
      cursor: pointer;
      margin-right: 20px;
    }

    .nav_list > li > a {
      display: block;
      /* padding: 15px 20px; */
      color: #f2f2f2;
      text-decoration: none;
      font-size: 16px;
      font-weight: 400;
      transition: all 0.3s ease;
      border-bottom: 3px solid transparent;
    }

    .nav_list > li > a:hover,
    .nav_list > li > a:focus {
      color: #d97706;
    }

    .sub_down {
      position: absolute;
      top: 100%;
      left: 0;
      background: white;
      min-width: 250px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      border-radius: 0 0 8px 8px;
      display: none;
      z-index: 1000;
      overflow: hidden;
    }

    .nav_list > li:hover .sub_down {
      display: block;
    }

    .sub_down li {
      border-bottom: 1px solid #e5e7eb;
    }

    .sub_down li:last-child {
      border-bottom: none;
    }

    .sub_down li a {
      display: block;
      padding: 12px 20px;
      color: var(--gray-dark);
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .sub_down li a:hover {
      background: var(--light-blue);
      color: var(--primary-blue);
      padding-left: 25px;
    }

    /* Quick Services */
    .quick-services {
      background: white;
      margin: -50px 20px 0 20px;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      position: relative;
      z-index: 10;
    }

    .service-card {
      text-align: center;
      padding: 25px 15px;
      border-radius: 12px;
      background: var(--gray-light);
      transition: all 0.3s ease;
      border: 2px solid transparent;
      height: 100%;
    }

    .service-card:hover {
      transform: translateY(-5px);
      border-color: var(--primary-blue);
      box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
    }

    .service-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      color: white;
      font-size: 28px;
    }

    .service-card h5 {
      color: var(--gray-dark);
      font-weight: 600;
      margin-bottom: 15px;
    }

    /* Main Content */
    .body-cont {
      margin-top: 40px;
    }

    .home-text {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      border-left: 4px solid var(--primary-blue);
    }

    .feature-content {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      height: 100%;
      transition: all 0.3s ease;
      border-top: 4px solid transparent;
    }

    .feature-content:hover {
      transform: translateY(-3px);
      border-top-color: var(--primary-blue);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
    }

    .feature-icon img {
      max-height: 60px;
      margin-bottom: 15px;
    }

    .feature-content h4 {
      color: var(--primary-blue);
      font-weight: 600;
      margin-bottom: 15px;
    }

    .feature-content ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .feature-content ul li {
      margin-bottom: 8px;
    }

    .feature-content ul li a {
      color: var(--gray-dark);
      text-decoration: none;
      transition: all 0.3s ease;
      display: block;
      padding: 5px 0;
    }

    .feature-content ul li a:hover {
      color: var(--primary-blue);
      padding-left: 8px;
    }

    /* Sidebar */
    .guest-item {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .guest-title {
      background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
      color: white;
      padding: 15px 20px;
      font-weight: 600;
      text-align: center;
    }

    .guest-profile {
      padding: 20px;
      text-align: center;
    }

    .guest-profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--light-blue);
      margin-bottom: 15px;
    }

    .help-list {
      padding: 20px;
      list-style: none;
      margin: 0;
    }

    .help-list li {
      padding: 10px 0;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .help-list li:last-child {
      border-bottom: none;
    }

    /* Sub Features */
    .sub-feature-content {
      margin-top: 40px;
    }

    .sub-feature {
      background: white;
      padding: 25px 15px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      height: 100%;
      border: 2px solid transparent;
    }

    .sub-feature:hover {
      transform: translateY(-5px);
      border-color: var(--primary-blue);
      box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
    }

    .sub-feature .feature-icon {
      width: 60px;
      height: 60px;
      background: var(--light-blue);
      color: var(--primary-blue);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 24px;
    }

    .sub-feature h5 {
      color: var(--gray-dark);
      font-weight: 600;
      margin: 0;
      font-size: 14px;
    }

    /* Footer */
    .footer {
      background: linear-gradient(135deg, var(--gray-dark) 0%, #1f2937 100%);
      color: white;
      margin-top: 60px;
    }

    .footer-main {
      padding: 50px 0 30px;
    }

    .footer h4 {
      color: var(--gold);
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1.2rem;
    }

    .footer-nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-nav ul li {
      margin-bottom: 8px;
    }

    .footer-nav ul li a {
      color: #d1d5db;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .footer-nav ul li a:hover {
      color: white;
      padding-left: 5px;
    }

    .footer-bottom {
      background: rgba(0, 0, 0, 0.3);
      padding: 20px 0;
      text-align: center;
    }

    .social-links a {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 5px;
      transition: all 0.3s ease;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .social-links a:hover {
      transform: translateY(-3px);
      border-color: white;
    }

    /* Hero Section */
.hero-main-section {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
    color: white;
    padding: 80px 0;
    border-radius: 0 0 20px 20px;
    position: relative;
    overflow: hidden;
}

.hero-main-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.hero-main-title {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
}

.hero-main-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.hero-main-image img {
    max-height: 300px;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
}

.btn-primary-govt {
    background: linear-gradient(135deg, var(--accent-green), #047857);
    border: none;
    color: white;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary-govt:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(5, 150, 105, 0.4);
    color: white;
}

.btn-outline-govt {
    border: 2px solid white;
    color: white;
    background: transparent;
    padding: 10px 23px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-govt:hover {
    background: white;
    color: var(--primary-blue);
    transform: translateY(-2px);
}

/* Section Styling */
.section-govt-padding {
    padding: 60px 0;
}

.section-govt-header {
    margin-bottom: 3rem;
}

.section-govt-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: 0.5rem;
    position: relative;
}

.section-govt-subtitle {
    color: var(--gray-dark);
    font-size: 1.1rem;
    opacity: 0.8;
}

.bg-govt-light {
    background: var(--gray-light);
}

/* Service Cards */
.service-card-govt {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 2px solid transparent;
}

.service-card-govt:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(30, 58, 138, 0.15);
    border-color: var(--primary-blue);
}

.quick-service-card-govt {
    border-top: 4px solid var(--primary-blue);
}

.service-icon-govt {
    font-size: 3rem;
    color: var(--primary-blue);
    margin-bottom: 1.5rem;
}

.service-title-govt {
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--gray-dark);
    font-size: 1.2rem;
}

.service-description-govt {
    color: #6c757d;
    margin-bottom: 1.5rem;
    flex-grow: 1;
    line-height: 1.6;
}

.btn-service-govt {
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-service-govt:hover {
    background: linear-gradient(135deg, var(--dark-blue), var(--primary-blue));
    color: white;
    text-decoration: none;
    transform: translateX(5px);
}

/* Service Category Cards */
.service-category-card-govt {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    border: 2px solid transparent;
}

.service-category-card-govt:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(30, 58, 138, 0.15);
    border-color: var(--primary-blue);
}

.category-header-govt {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.category-icon-govt {
    margin-bottom: 1rem;
    font-size: 2.5rem;
}

.category-title-govt {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
}

.category-content-govt {
    padding: 1.5rem;
}

.service-list-govt {
    list-style: none;
    padding: 0;
    margin: 0;
}

.service-list-govt li {
    margin-bottom: 0.5rem;
}

.service-list-govt a {
    color: var(--gray-dark);
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    padding: 0.5rem 0;
    border-radius: 4px;
}

.service-list-govt a:hover {
    color: var(--primary-blue);
    padding-left: 0.5rem;
    background: var(--light-blue);
}

.service-list-govt i {
    margin-right: 0.5rem;
    font-size: 0.8rem;
    color: var(--primary-blue);
}

/* Certificate Cards */
.certificate-card-govt {
    background: white;
    border-radius: 10px;
    padding: 1.5rem 1rem;
    text-align: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: block;
    text-decoration: none;
    color: var(--gray-dark);
    height: 100%;
    border: 2px solid transparent;
}

.certificate-card-govt:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
    color: var(--primary-blue);
    text-decoration: none;
    border-color: var(--primary-blue);
}

.certificate-icon-govt {
    font-size: 2.2rem;
    color: var(--primary-blue);
    margin-bottom: 0.8rem;
}

.certificate-card-govt h6 {
    font-weight: 600;
    margin: 0;
    font-size: 0.85rem;
    line-height: 1.4;
}

/* Statistics */
.stat-card-govt {
    background: white;
    border-radius: 12px;
    padding: 2rem 1rem;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.stat-card-govt:hover {
    transform: translateY(-5px);
    border-color: var(--primary-blue);
    box-shadow: 0 10px 30px rgba(30, 58, 138, 0.15);
}

.stat-icon {
    font-size: 2.5rem;
    color: var(--primary-blue);
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--primary-blue);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--gray-dark);
    font-weight: 500;
    margin: 0;
}

/* Sidebar Cards */
.sidebar-card-govt {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.sidebar-card-govt:hover {
    border-color: var(--primary-blue);
    box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
}

.sidebar-card-govt .card-header-govt {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: white;
    padding: 1rem 1.5rem;
    border-bottom: none;
}

.sidebar-card-govt .card-header-govt h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-body-govt {
    padding: 1.5rem;
}

.leader-profile-card-govt .card-body-govt {
    padding: 1.5rem;
}

.leader-image-govt img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--light-blue);
}

.leader-name-govt {
    font-weight: 600;
    color: var(--gray-dark);
    margin-bottom: 0.2rem;
    font-size: 1.1rem;
}

.leader-position-govt {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.helpline-list-govt {
    list-style: none;
    padding: 0;
    margin: 0;
}

.helpline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #e9ecef;
}

.helpline-item:last-child {
    border-bottom: none;
}

.helpline-item span {
    color: var(--gray-dark);
    font-size: 0.9rem;
    text-align: left;
    flex: 1;
}

.helpline-number {
    color: var(--primary-blue);
    font-weight: 700;
    text-decoration: none;
    background: var(--light-blue);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.helpline-number:hover {
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
}

.quick-links-govt {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-link-item {
    color: var(--gray-dark);
    text-decoration: none;
    padding: 0.7rem 1rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    border: 1px solid #e9ecef;
}

.quick-link-item:hover {
    background: var(--light-blue);
    color: var(--primary-blue);
    text-decoration: none;
    border-color: var(--primary-blue);
    transform: translateX(5px);
}

/* Modal Styling */
.modal-header-govt {
    background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
    color: white;
    border-bottom: none;
    padding: 1.5rem;
}

.leader-modal-image-govt img {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid var(--light-blue);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.leader-modal-title {
    color: var(--primary-blue);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.leader-modal-name {
    color: var(--gray-dark);
    font-weight: 600;
    margin-bottom: 1rem;
}

.leader-message-govt {
    color: #6c757d;
    font-size: 1.1rem;
    line-height: 1.7;
    text-align: justify;
}

/* About Section */
.about-content-govt {
    position: relative;
}

.about-text-govt p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--gray-dark);
}

.item-title{
    text-align: center;
    font-size: 22px;
    padding-top: 22px;
}
.form-container{
    padding: 40px;
    background-color: #fff;
    border-radius: 16px;
}
/* Responsive Design */
@media (max-width: 768px) {
    .hero-main-section {
        padding: 60px 0;
        text-align: center;
    }

    .hero-main-title {
        font-size: 2.2rem;
    }

    .section-govt-padding {
        padding: 60px 0;
    }

    .section-govt-title {
        font-size: 1.8rem;
    }

    .service-card-govt, .service-category-card-govt {
        margin-bottom: 1.5rem;
    }

    .certificate-card-govt {
        margin-bottom: 1rem;
    }

    .stat-card-govt {
        margin-bottom: 1.5rem;
    }

    .hero-main-actions {
        text-align: center;
    }

    .hero-main-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }

    .hero-main-actions .btn:last-child {
        margin-bottom: 0;
    }
}

@media (max-width: 576px) {
    .hero-main-title {
        font-size: 1.8rem;
    }

    .hero-main-subtitle {
        font-size: 1.1rem;
    }

    .section-govt-title {
        font-size: 1.6rem;
    }

    .service-icon-govt {
        font-size: 2.5rem;
    }

    .certificate-card-govt {
        padding: 1rem 0.5rem;
    }

    .certificate-card-govt h6 {
        font-size: 0.8rem;
    }
}

    /* Mobile Styles */
    @media (max-width: 991.98px) {
      .hero-content h1 {
        font-size: 2rem;
      }
      
      .hero-content h2 {
        font-size: 1.2rem;
      }
      
      .quick-services {
        margin: -30px 15px 0 15px;
        padding: 20px;
      }
      
      .nav_list {
        flex-direction: column;
      }
      
      .sub_down {
        position: static;
        box-shadow: none;
        background: var(--gray-light);
      }
    }



  </style>
</head>

<body>
  <!-- Emergency Alert Bar -->
  <!-- <div class="emergency-alert text-center">
    <div class="container">
      <i class="fas fa-exclamation-triangle me-2"></i>
      জরুরি হেল্পলাইন: ৯৯৯ | জাতীয় সেবা: ৩৩৩ | দুদক: ১০৬
    </div>
  </div> -->

  <!-- Main Header -->
  <header class="govt-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- Notice Marquee -->
        <div class="col-md-10">
          <!-- <div class="marquee-container">
            <marquee scrollamount="4" direction="left" class="marquee-text">
              <span>
                @foreach($notices as $notice)
                <a href="{{ url('notices/' . $notice->slug) }}" class="text-decoration-none">
                  {{ $notice->title }} ({{ \Carbon\Carbon::parse($notice->published_at)->format('d/m/Y') }})
                </a>
                @if(!$loop->last) | @endif
                @endforeach
              </span>
            </marquee>
          </div> -->

           <!-- Main Navigation -->
            <nav class="main-navigation">
               <div class="header d-none d-lg-block">
                
                   <ul class="nav_list">
                    <li><a href="/"> <i class="fas fa-home me-1"></i> হোম </a></li>
                    <li class="#"><a> নাগরিক<i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">

                        <li><a href="{{url('/citizen-form')}}">নাগরিকত্ব সনদের আবেদন</a></li>
                        <!-- <li><a href="#">নাগরিকত্ব সনদের আবেদন যাচাই</a></li> -->
                        <li><a href="{{url('/citizen-verify')}}">নাগরিকত্ব সনদ পত্র </a></li>
                      </ul>
                    </li>
                    <li class="#"><a> ওয়ারিশ<i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('/heirship-apply')}}">ওয়ারিশ সনদের আবেদন</a></li>
                        <li><a href="{{url('/heirship-apply-verify')}}">ওয়ারিশ সনদের আবেদন যাচাই</a></li>
                        <!-- <li><a href="#"> ওয়ারিশ সনদ পত্র যাচাই </a></li> -->
                      </ul>
                    </li>
                    <li class="#"><a> লাইসেন্স<i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('/trade-license-form')}}">ট্রেড লাইসেন্স আবেদন</a></li>
                        <li><a href="{{url('/license-update-checking')}}">ট্রেড লাইসেন্স আবেদন যাচাই</a></li>
                        <!-- <li><a href="#">ট্রেড লাইসেন্স নবায়ন</a></li> -->
                        <li><a href="{{url('/trade-license-certificatee')}}">ট্রেড লাইসেন্স </a></li>
                      </ul>
                    </li>
                    <li class="#"><a> ভিজিএফ কার্ড <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('/dgf-card')}}">ভিজিএফ কার্ডের জন্য আবেদন </a></li>
                        <li><a href="{{url('/dgf-card/download')}}">ভিজিএফ কার্ড ডাউনলোড</a></li>
                      </ul>
                    </li>
                    <li class="#"><a> প্রকৌশল <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('road-excavation/apply')}}">রাস্তা খননের অনুমতি পত্র</a></li>
                        <li><a href="{{url('construction-apply')}}">ইমারত নির্মাণ/ পুকুর খনন/ভরাট আবেদন পত্র</a></li>
                        <li><a href="{{url('land-clearance/apply')}}">ভূমি ব্যবহার ছাড়পত্রের আবেদন</a></li>
                        <li><a href="{{url('holding/apply')}}">নতুন হোল্ডিং আবেদন</a></li>
                        <!-- <li><a href="#">হোল্ডিং নামজারি আবেদন</a></li> -->
                      </ul>
                    </li>

                    <li class="#"><a> প্রকল্প <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                       <li><a href="{{url('project/proposed')}}">প্রস্তাবিত</a></li>
                        <li><a href="{{url('project/ongoing')}}">চলমান</a></li>
                        <li><a href="{{url('project/completed')}}">সম্পন্ন</a></li>
                      </ul>
                    </li>

                     <li class="#"><a> বিবাহিত <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('married-apply')}}">বিবাহিত সনদ</a></li>
                         <li><a href="{{url('unmarried-apply')}}">অবিবাহিত সনদ</a></li>
                        <li><a href="{{url('second-married-apply')}}">দ্বিতীয় বিবাহের অনুমতি পত্র</a></li>
                      </ul>
                    </li>
                     <li class="#"><a> পারিবারিক <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('family-certificate-apply')}}">পারিবারিক সনদ</a></li>
                      <li><a href="{{url('monthly-salary-apply')}}">মাসিক আয়ের সনদ</a></li>
                          <li><a href="{{url('anual-income-apply')}}">বার্ষিক আয়ের প্রত্যয়ন</a></li>
                      </ul>
                    </li>



                    <li class="#"><a> অন্যান্য <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                        <li><a href="{{url('bibidh-apply')}}">বিবিধ প্রত্যয়নপত্র</a></li>
                        <li><a href="{{url('character-certificate-apply')}}">চারিত্রিক সনদ</a></li>
                        <li><a href="{{url('landless-apply')}}">ভূমিহীন সনদ</a></li>
                        <li><a href="{{url('disabled-apply')}}">প্রতিবন্ধী সনদপত্র</a></li>
                        <li><a href="{{url('no-objection-apply')}}">অনাপত্তি সনদপত্র</a></li>
                        <li><a href="{{url('financial-insolvency-apply')}}">আর্থিক অস্বচ্ছলতার সনদপত্র</a></li>
                        <li><a href="{{url('new-voter-apply')}}">নতুন ভোটারের প্রত্যয়ন পত্র</a></li>
                        <li><a href="{{url('voter-transfer-apply')}}">ভোটার স্থানান্তরের প্রত্যয়ন পত্র</a></li>
                        <li><a href="{{url('unemployment-apply')}}">বেকারত্বের সনদপত্র</a></li>
                        <li><a href="{{url('temporary-residence-apply')}}">অস্থায়ীভাবে বসবাসের প্রত্যয়ন পত্র</a></li>
                        <li><a href="{{url('nationality-apply')}}">জাতীয়তা সনদ</a></li>
                        <li><a href="{{url('permanent-resident-apply')}}">স্থায়ী বাসিন্দা সনদ</a></li>
                        <li><a href="{{url('orphan-apply')}}">এতিম সনদ</a></li>
                      </ul>
                    </li>


                    <li class="#"><a> তথ্য <i class="fas fa-caret-down ms-1"></i></a>
                      <ul class="sub_down">
                       @php
                      $menuPages = \App\Models\Page::published()->where('show_in_menu', true)->orderBy('menu_order')->get();
                      @endphp


                      @foreach($menuPages as $menuPage)
                          <li><a href="{{ route('frontend.page', $menuPage->slug) }}">{{ $menuPage->menu_title }}</a></li>
                      @endforeach

                      </ul>
                    </li>
                  </ul>
                </div>
              <!-- Mobile Navigation Toggle -->
              <button class="navbar-toggler d-lg-none w-100 text-start p-3 border-0 bg-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="fas fa-bars me-2"></i> মেনু
              </button>
             
            </nav>
        </div>

        <!-- Auth Buttons -->
        <div class="col-md-2 text-end">
          <div class="auth-buttons">
            @auth
              <a href="/admin/dashboard" class="btn btn-dashboard bengali">
                <i class="fas fa-tachometer-alt"></i> ড্যাশবোর্ড
              </a>
            @endauth
            @guest
              <a href="/login" class="btn btn-login bengali me-2">
                <i class="fas fa-sign-in-alt"></i> লগইন
              </a>
              <!-- <a href="/register" class="btn btn-register bengali">
                <i class="fas fa-user-plus"></i> রেজিস্টার
              </a> -->
            @endguest
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Banner -->
  <div class="hero-banner">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        @if(DB::table('sliders')->wherestatus(1)->get()->count() > 0)
          @foreach (DB::table('sliders')->wherestatus(1)->get() as $slider)
            <div class="swiper-slide">
              <div class="hero-slider" style="background-image: url('{{ url($slider->image) }}');"></div>
            </div>
          @endforeach
        @else
          <div class="swiper-slide">
            <div class="hero-slider" style="background-image: url('{{ url('images/srimty-shoudh.gif') }}');"></div>
          </div>
          <div class="swiper-slide">
            <div class="hero-slider" style="background-image: url('{{ url('images/sangsad.gif') }}');"></div>
          </div>
        @endif
      </div>
    </div>

    <div class="hero-content">
      <div class="container">
        <div class="hero-logo">
          <a href="/">
            <img src="{{ @$setting->site_logo ? url($setting->site_logo) : 'https://picsum.photos/115/115' }}" alt="{{@$setting->site_name}}">
          </a>
        </div>
        <div>
          <h1 class="text-white">{{$setting->site_name}}</h1>
          <h2 class="text-white">{{$setting->tagline}}</h2>
        </div>
      </div>
    </div>
  </div>
 

  <!-- Mobile Offcanvas Menu -->
  <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header bg-primary text-white">
      <h5 class="offcanvas-title">{{ @$setting->site_name }}</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <!-- Same mobile menu structure as Theme 1 -->
      <!-- ... (Include all the same mobile menu structure) ... -->
    </div>
  </div>

  <!-- Main Content -->
  <main class="container">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-main">
        <div class="row">
          <div class="col-lg-4 col-md-5 col-12 mb-4">
            <div class="hero-logo mb-3" style="display: inline-block;">
              <img src="{{ @$setting->site_logo ? url($setting->site_logo) : 'https://picsum.photos/80/80' }}" alt="{{@$setting->site_name}}" style="max-height: 80px;">
            </div>
            <p class="mb-3">{{$setting->footer_text}}</p>
          </div>
          <div class="col-lg-2 col-md-3 col-6 mb-4">
            <h4>গুরুত্বপূর্ণ লিঙ্ক</h4>
            <div class="footer-nav">
              <ul>
                <li><a href="{{ url('notices') }}">নোটিশ</a></li>
                <li><a href="{{ route('gallery.index') }}">গ্যালারি</a></li>
                <li><a href="{{ url('news') }}">নিউজ</a></li>
                <li><a href="{{ url('leases') }}">ইযারা</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-6 mb-4">
            <h4>গুরুত্বপূর্ণ তথ্য</h4>
            <div class="footer-nav">
              <ul>
                <li><a href="https://bangladesh.gov.bd/" target="_blank">জাতীয় তথ্য বাতায়ন</a></li>
                <li><a href="https://cabinet.gov.bd/" target="_blank">মন্ত্রিপরিষদ বিভাগ</a></li>
                <li><a href="https://bangladesh.gov.bd/eservices" target="_blank">অনলাইন আবেদনপত্র</a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 col-6 mb-4">
            <h4>কার্যকরী পরিষদ</h4>
            <div class="footer-nav">
              <ul>
                <li><a href="{{ url('representative') }}">নির্বাচিত প্রতিনিধি</a></li>
                <li><a href="{{ url('staff') }}">কর্মকর্তা ও কর্মচারীবৃন্দ</a></li>
                <li><a href="{{ url('security') }}">নিরাপত্তা সদ্যসবৃন্দ</a></li>
                <li><a href="{{ url('council') }}">পঞ্চায়েত</a></li>
                <li><a href="{{ url('enterpeniour') }}">উদ্যোক্তা</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="text-center">
          <strong>কপিরাইট &copy; {{date("Y")}} <a href="#" class="text-warning">{{@$setting->site_name}}</a>.</strong>
          তৈরি করেছে <a href="https://trustitbdltd.com/" target="_blank" class="text-warning">Trust-IT (BD) Ltd</a>
        </div>
        <div class="social-links pt-3 text-center">
          <a href="{{$setting->facebook_url}}" class="text-white"><i class="fab fa-facebook-f"></i></a>
          <a href="{{$setting->twitter_url}}" class="text-white"><i class="fab fa-twitter"></i></a>
          <a href="{{$setting->youtube_url}}" class="text-white"><i class="fab fa-youtube"></i></a>
          <a href="{{$setting->linkedin_url}}" class="text-white"><i class="fab fa-linkedin-in"></i></a>
          <a href="{{$setting->instagram_url}}" class="text-white"><i class="fab fa-instagram"></i></a>
          <a href="{{$setting->whatsapp_number}}" class="text-white"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts (Same as Theme 1) -->
  <script src="{{url('js/jquery-3.6.0.min.js')}}"></script>
  <script src="{{url('js/bootstrap.min.js')}}"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="{{url('js/lightbox.js')}}"></script>
  <script src="{{url('js/venobox.js')}}"></script>
  <script src="{{url('js/jquery.countup.min.js')}}"></script>
  <script src="{{url('js/jquery.waypoints.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="{{url('js/custom.js')}}"></script>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script>
    $(document).ready(function() {
      // Function to convert English numbers to Bengali
      function toBengaliNumber(number) {
        const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return number.toString().replace(/\d/g, function(digit) {
          return bengaliDigits[digit];
        });
      }

      // Function to add new heir row
      function addHeirRow() {
        const rowCount = $('#heirsTable tbody tr').length;
        const bengaliSerial = toBengaliNumber(rowCount + 1);
        const newRow = `
          <tr>
            <td class="serial-no">${bengaliSerial}</td>
            <td><input type="text" class="form-control" name="heirs[${rowCount}][name]" placeholder="" required></td>
            <td>
              <select class="form-select" name="heirs[${rowCount}][relation]" required>
                <option value="">পছন্দ করুন</option>
                <option value="son">পুত্র</option>
                <option value="daughter">কন্যা</option>
                <option value="wife">স্ত্রী</option>
                <option value="husband">স্বামী</option>
                <option value="father">পিতা</option>
                <option value="mother">মাতা</option>
                <option value="brother">ভাই</option>
                <option value="sister">বোন</option>
                <option value="other">অন্যান্য</option>
              </select>
            </td>
            <td><input type="number" class="form-control" name="heirs[${rowCount}][nid]" placeholder=""></td>
            <td><input type="tel" class="form-control" name="heirs[${rowCount}][mobile]"  maxlength="11" placeholder=""></td>
            <td><input type="text" class="form-control" name="heirs[${rowCount}][address]" placeholder=""></td>
            <td class="action-cell">
              <button type="button" class="remove-heir-btn" title="Remove">&times;</button>
            </td>
          </tr>
        `;
        $('#heirsTable tbody').append(newRow);
      }

      // Initialize with 2 heir rows
      addHeirRow();
      addHeirRow();

      // Add heir row when button is clicked
      $('#addHeirBtn').click(function() {
        addHeirRow();
      });

      // Remove heir row when delete button is clicked
      $(document).on('click', '.remove-heir-btn', function() {
        if($('#heirsTable tbody tr').length > 1) {
          $(this).closest('tr').remove();
          // Update serial numbers with Bengali numerals
          $('#heirsTable tbody tr').each(function(index) {
            $(this).find('.serial-no').text(toBengaliNumber(index + 1));
          });
        } else {
          alert('আপনি কমপক্ষে একজন ওয়ারিশ যোগ করতে বাধ্য।');
        }
      });
    });
</script>

  <script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'সফল!',
            text: '{{ session('success') }}',
            confirmButtonText: 'ঠিক আছে'
        });
    @endif
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'ত্রুটি!',
            text: '{{ session('error') }}',
            confirmButtonText: 'ঠিক আছে'
        });
    @endif
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'ত্রুটি!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'ঠিক আছে'
        });
    @endif

    // Mobile submenu toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
      const toggles = document.querySelectorAll('.mobile-submenu-toggle');

      toggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          this.classList.toggle('active');
          const submenu = this.nextElementSibling;

          if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
          } else {
            submenu.style.display = 'block';
          }
        });
      });
    });
  </script>

  <script>
    // Initialize AOS animation
    AOS.init();

    // Form submission handler
    $(document).ready(function() {
      $('#verificationForm').submit(function(e) {
        e.preventDefault();

        // Here you would typically make an AJAX call to your backend
        // For demo purposes, we'll just show the result section

        // Simulate different statuses for demo
        const statuses = ['pending', 'approved', 'rejected'];
        const randomStatus = statuses[Math.floor(Math.random() * statuses.length)];

        // Update status display
        $('#applicationStatus')
          .removeClass('status-pending status-approved status-rejected')
          .addClass('status-' + randomStatus)
          .text(
            randomStatus === 'pending' ? 'মূল্যায়নাধীন' :
            randomStatus === 'approved' ? 'অনুমোদিত' : 'প্রত্যাখ্যাত'
          );

        // Update tracking number display
        $('#trackingNumberDisplay').text($('#trackingNumber').val());

        // Show the result section
        $('#verificationResult').fadeIn();

        // Scroll to results
        $('html, body').animate({
          scrollTop: $('#verificationResult').offset().top - 100
        }, 500);
      });

      // Initialize Swiper
      var swiper = new Swiper(".mySwiper", {
    effect: "fade",
    fadeEffect: {
      crossFade: true
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    loop: true,
    speed: 1200, // Transition duration in ms
    parallax: true, // Optional: Adds depth effect
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
  });
    });
  </script>

  @yield('scripts')
</body>
</html>