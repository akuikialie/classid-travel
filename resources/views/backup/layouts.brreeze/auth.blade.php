@extends('layouts.base')

@php
$sch = [
    "exist" => false,
    "logo" => '#', //loadImage(school('logo')),
    "banner" => '#', //school('img-cover-login', '#')
];
// try {
//     if (activeSchool()->id ?? false) {
//         $sch = [
//             "exist" => true,
//             "logo" => loadImage(school('logo')),
//             "banner" => school('img-cover-login', '#')
//         ];
//     }
// } catch (\Exception $e) {}
@endphp

@section('baseLayout')
  <div class="app" id="app">
    <div class="container-fluid p-0" style="height:100vh !important; min-height: 700px !important;">
      <div class="row m-0 h-100">
        <div class="col d-none d-md-grid d-flex align-content-end p-0" id="schoolBanner" style="background: #000 url('{{ $sch['banner'] ?? '' }}');">
          <div class="px-10 py-5" id="schoolTagline">
            <h2 class="font-weight-bold" style="font-size:1em;">Dashboard Yayasan</h2>
            <div class="font-italic mt-n3" style="font-size:.5em;">~ management support system ~</div>
          </div>
        </div>
        <div class="col-12 col-md-5 col-lg-4 col-xxl-3 min-w-400px bg-white d-flex flex-column justify-content-center shadow-sm">
          <div class="w-100 mb-auto">
            <div class="p-10 pb-0 d-flex flex-center">
              {{-- <img class="img-fluid shimmer" id="schoolLogo" src="{{ $sch['logo'] ?? asset('files/empty-logo-wide.png') }}" alt="Logo Sekolah" title="Logo Sekolah">
              <img class="img-fluid shimmer" src="#" alt=" " data-title="Logo Sekolah" style="min-width: 300px; min-height: 100px;"> --}}
              <div class="h-100px">&nbsp;</div>
            </div>
            @yield('content')
          </div>

          <div class="min-w-400px bg-white">
            @include('layouts.blocks.footer')
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    @keyframes shimmer {
      0% {
        background-position: -468px 0;
      }
      100% {
        background-position: 468px 0;
      }
    }
    .animate {
      background: #f6f7f8;
      background: linear-gradient(to right, #eff1f3 4%, #e2e2e2 25%, #eff1f3 36%);
      background-size: 800px 104px;
      display: inline-block;
      position: relative;

      -webkit-animation-duration: 1s;
      -webkit-animation-fill-mode: forwards;
      -webkit-animation-iteration-count: infinite;
      -webkit-animation-name: shimmer;
      -webkit-animation-timing-function: linear;
    }
    #schoolLogo {
      height: 100px !important;
      max-height: 100px !important;
      min-width: 100px !important;
    }
    #schoolBanner {
      background-size: cover !important;
      background-repeat: no-repeat !important;
      background-position: center !important;
    }
    #schoolTagline {
      padding-top: 150px !important;
      background: linear-gradient(0deg, rgba(255,255,255,.5) 0%, rgba(255,255,255,.4) 30%, rgba(255,255,255,0) 100%) !important;
      font-size: 2em;
    }
  </style>
@endpush

@push('js')
  <script>
    $(document).ready(function () {
      $(document).on('click', '.shimmer', function () {
        console.log($(this).attr('src'));
        $(this).addClass('animate');
      });
      $(document).on('load', '.shimmer', function () {
        $(this).addClass('animate');
      });
      // $(document).on('onloadeddata', '.shimmer', function () {
      //   $(this).removeClass('animate');
      // });
    });
  </script>
@endpush
