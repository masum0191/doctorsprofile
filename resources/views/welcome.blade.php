<?php
$setting = \App\Models\Setting::first();
?>

@php
   $template = $setting->template ?? 'one';
   
   $viewPath = "themeContent.$template";
   //dd($viewPath);
@endphp

@include($viewPath)

