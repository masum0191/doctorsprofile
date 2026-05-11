<?php
$setting = \App\Models\Setting::first();
?>

@php
   $template = $setting->template ?? 'one';
   $packageFeatures = $packageFeatures ?? config('package_features.presets.free', []);
   $canBookAppointments = (bool) ($packageFeatures['appointment_booking'] ?? false);
   $showServices = (bool) ($packageFeatures['services'] ?? false);
   $showProfessionalProfile = (bool) ($packageFeatures['profile_professional'] ?? false);
   $showContent = (bool) ($packageFeatures['content'] ?? false);
   
   $viewPath = "themeContent.$template";
   //dd($viewPath);
@endphp

@include($viewPath)
