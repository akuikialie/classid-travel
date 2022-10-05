<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="author" content="Class Indonesia">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Yayasan</title>

  {{--<link rel="stylesheet" type="text/css" href="{$assetPath}plugins/global/plugins.bundle.css">
  <link rel="stylesheet" type="text/css" href="{$assetPath}css/treeview.css">
  <link rel="stylesheet" type="text/css" href="". mix('css/app.bundle.css') ."">--}}
  {{--@vite(['resources/sass/fonts.scss', 'resources/sass/app.scss']){!! $pluginCss ?? '' !!}--}}
  <link rel="stylesheet" type="text/css" href="{{ asset('css/fonts.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/global/plugins.bundle.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/treeview.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.bundle.css') }}">
  {!! $pluginCss ?? '' !!}
  <style>
    #scrool_top {
      position: fixed;
      right: 3%;
      bottom: 5%;
    }
    .swal2-content {font-weight: normal !important;}
    .font-monospace {font-family: monospace}
    table > tbody {
      counter-reset: _rownum;
    }
    table > tbody > tr:not(.row-num-skip) {
      counter-increment: _rownum;
    }
    table > tbody .row-num::before {
      display: block;
      text-align: right;
      content: counter(_rownum) ". ";
    }
    .form-control-feedback {
      display: none;
    }
    input.number::-webkit-outer-spin-button, input.number::-webkit-inner-spin-button {-webkit-appearance: none; margin: 0;}
    input.number[type=number] {-moz-appearance: textfield;}
    .has-success .form-control-feedback,
    .has-warning .form-control-feedback,
    .has-danger .form-control-feedback {
      display: block;
    }
    label.required:after {
      content: " *";
      white-space: pre;
      color: #f00;
    }
  </style>
  @stack('css')

  @if(app()->environment() != 'local')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-2771016-41"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() {dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-2771016-41');
    </script>
  @endif
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px" >
  @yield('baseLayout', '')

  {{--<script type="text/javascript" src="{$assetPath}plugins/global/plugins.bundle.js"></script>
  <script type="text/javascript" src="{$assetPath}js/app.bundle.js"></script>
  <script type="text/javascript" src="{$assetPath}js/app.js"></script>--}}
  {{--@vite(['resources/js/app.js']){!! $pluginJs ?? '' !!}--}}
  <script type="text/javascript" src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/app.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
  {!! $pluginJs ?? '' !!}
  <script>
    $(function () {
      {{--@if(isset($tsUnix))
      if ($('#clockSrv').length > 0) {
        var _uts = {!! $tsUnix !!};

        setInterval(function () {
          _uts += 1;
          $('#clockSrv').html(moment.unix(_uts).format('DD MMM YYYY HH:mm:ss'));
        }, 1000);

        $('#clockSrv').html(moment.unix(_uts).format('DD MMM YYYY HH:mm:ss'));
      }
      @endif--}}

      $('#scrool_top').hide()
      $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 300) {
          $('#scrool_top').fadeIn();
        } else {
          $('#scrool_top').fadeOut();
        }
      });

      $('#scrool_top').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 500);
        return false;
      });
    });
  </script>
  @stack('js')
</body>
</html>
