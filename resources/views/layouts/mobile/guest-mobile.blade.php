<!DOCTYPE HTML>
<html lang="en">

@php
    $avatars = activeTenant()->getMedia('avatars');
    $avatar = null;
    if ($avatars->count() > 0) {
        $avatar = collect($avatars)->last()->getUrl();
    }
@endphp

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover"/>
  <title>{{ activeTenant()->name ?? 'ProHajj' }} - App</title>

  <link rel="shortcut icon" href="{{ $avatar ?? asset('logo/24w/logo-pict@24px.png') }}"/>


  @include('customs.mobile.styles')

</head>

<body class="theme-light">

<div id="preloader">
  <div class="spinner-border color-highlight" role="status"></div>
</div>

<div id="page">

  <div class="page-content pb-0">

    @yield('mobile-content')

  </div>
  <!-- end of page content-->

  <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached rounded-m"
       data-menu-load="menu-colors.html" data-menu-height="510" data-menu-effect="menu-over">
  </div>

</div>


@include('customs.mobile.scripts')
@include('notify.notify-loader')

</body>
