<!--begin::Fonts-->
<link rel="stylesheet" href="{{ asset('web/vendor/fonts/boxicons/css/boxicons.min.css') }}" />
{{-- <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}" /> --}}
<!--end::Fonts-->

<!--begin::Vendor Stylesheets(used for this page only)-->
@yield('page-styles')
<!--end::Vendor Stylesheets-->

<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
<link href="{{ asset('web/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('web/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Global Stylesheets Bundle-->

<!--Begin::Google Tag Manager -->
<script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-5FS8GGP');
</script>
<!--End::Google Tag Manager -->
