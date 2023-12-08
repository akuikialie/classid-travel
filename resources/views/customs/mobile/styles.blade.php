@php
  $partnerName = partner()->getName();
@endphp

<link rel="stylesheet" type="text/css" href="{{ asset('mobile/styles/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('mobile/styles/style.css') }}">
<link href="{{ cachedAsset("partner/{$partnerName}/css/mobile.css") }}" rel="stylesheet" type="text/css" />

<link
    href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
    rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('mobile/fonts/css/fontawesome-all.min.css') }}">
<link rel="manifest" href="{{ asset('mobile/_manifest.json') }}" data-pwa-version="set_in_manifest_and_pwa_js">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mobile/app/icons/icon-192x192.png') }}">

@yield('vendor-styles')
