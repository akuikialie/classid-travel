<!DOCTYPE HTML>
<html lang="en">

@php
     $avatars = activeTenant()->getMedia('avatars');
     $avatar = null;

     dd($avatars);
     if ($avatars->count() > 0) {
         $avatar = collect($avatars)->last()->getUrl();
     }
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <link rel="shortcut icon" href="{{ $avatar ?? asset('logo/24w/logo-pict@24px.png') }}" />

    <title>{{ activeTenant()->name ?? 'ProHajj' }} - App</title>

    <style>
        body {
            touch-action: pan-x pan-y;
        }
    </style>

  {{-- @vite(['resources/sass/fonts.scss', 'resources/sass/app.scss']) --}}

    @include('customs.mobile.styles')
</head>

<body class="theme-light">

    <div id="preloader">
        <div class="spinner-border color-highlight" role="status"></div>
    </div>

    <div id="page">

        <!-- header and footer bar go here-->
        {{-- @include('components.mobile.header-bar') --}}

        @include('components.mobile.footer-bar')

        <div class="page-content mt-2">

            @yield('mobile-content')

        </div>
        <!-- end of page content-->

        <div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-load="menu-share.html"
            data-menu-height="420" data-menu-effect="menu-over">
        </div>

        <div id="menu-highlights" class="menu menu-box-bottom menu-box-detached rounded-m"
            data-menu-load="menu-colors.html" data-menu-height="510" data-menu-effect="menu-over">
        </div>

        <div id="menu-main" class="menu menu-box-right menu-box-detached rounded-m" data-menu-width="260"
            data-menu-load="menu-main.html" data-menu-active="nav-pages" data-menu-effect="menu-over">
        </div>

    </div>

    @yield('external-mobile-content')

    @include('customs.mobile.scripts')
    @include('notify.notify-loader')
</body>
