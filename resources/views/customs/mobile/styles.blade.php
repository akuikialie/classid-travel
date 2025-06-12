@php
  $tenantOption = tenantOptions();
  $partnerName = partner()->getName();

  $styles = collect([
    'https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap',
    'mobile/styles/bootstrap.css',
    'mobile/styles/style.css',
    'partner/'.$partnerName.'/css/mobile.css',
    'mobile/fonts/css/fontawesome-all.min.css',
  ])->map(function ($st) {
    $style = str($st)->startsWith(['http://', 'https://', '//'])
        ? $st
        : cachedAsset($st);
    return "<link rel='stylesheet' type='text/css' href='{$style}'/>";
  })->implode('');

  echo $styles;
@endphp
<link rel="manifest" href="{{ asset('mobile/_manifest.json') }}" data-pwa-version="set_in_manifest_and_pwa_js">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('mobile/app/icons/icon-192x192.png') }}">
<style>
  .bg-highlight {
    background-color: {{ $tenantOption->get('style.bg_color', '#611e91') }} !important;
    color: {{ $tenantOption->get('style.bg_inverse', '#fff') }} !important;
  }
  .border-highlight {
    border-color: {{ $tenantOption->get('style.bg_color', '#611e91') }} !important;
  }
  .color-highlight {
    color: {{ $tenantOption->get('style.color', '#611e91') }} !important;
  }
</style>

@yield('vendor-styles')
