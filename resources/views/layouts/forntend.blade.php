<?php
$setting = \App\Models\Setting::first();
?>
@php
   $template =$setting->template ?? 'one';
  // $setting->template ?? 'one';
   //dd($template);
   $viewPath = "theme.$template";
  //dd($viewPath);
@endphp

@include('layouts.' . $viewPath)

