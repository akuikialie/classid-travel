@php use Carbon\Carbon; @endphp
@extends('layouts.mobile.app-mobile')

@section('mobile-content')
  <div class="page-title page-title-small">
    <h2><a href="#"></a>Beranda</h2>
  </div>
  <div class="card header-card shape-rounded" data-card-height="150">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
  </div>
  <div class="card card-style">
    <div class="content">
      <div class="d-flex justify-content-between">

        <h3 class="font-600">{{ isset($data['name']) ? $data['name'] : 'unknown' }}</h3>
        <p class="text-nowrap"><strong>Semua Tabungan</strong></p>
      </div>
      <p class="font-11 mt-n2 color-hi ghlight">{{ isset($data['phone']) ? $data['phone'] : '-' }}</p>

      <div class="float-start">
        <p class="font-12 opacity-80 mb-n1"><i class="far fa-calendar"></i>
          {{ \carbon()->format('F d') }} <i class="ms-4 fas fa-money-check"></i>
          <strong>{{ isset($total_tabungan) ? $total_tabungan : 0 }}</strong> Tabungan
        </p>
        <p class="font-12 opacity-100"><strong><i class="fa-solid fa-wallet"></i>
            {{ isset($data['totalSavings']) ? $data['totalSavings'] : 0 }}
          </strong> <small>dari
            {{ isset($data['targetSavings']) ? $data['targetSavings'] : '~' }}</small></p>

      </div>
      <a href="#" data-menu="mulai-menabung"
         class="float-end btn btn-s bg-highlight rounded-s shadow-xl text-uppercase font-900 font-11 mt-2">Tabung</a>
    </div>
  </div>

  @if(isset($banners))

    <!-- Homepage Slider-->
    <div class="splide single-slider slider-no-arrows slider-no-dots homepage-slider" id="single-slider-1">
      <div class="splide__track">
        <div class="splide__list">

          @for($i = 0; $i < config('media-collections.collection_settings.banners.max'); $i++)
            @php
              $a = $i;

              $currentBanner = collect($banners)->where('order', $a+1)->last();
              $currentBanner = $currentBanner['image_url'] ?? null;
              if (!isset($currentBanner)){
                  continue;
              }
            @endphp

            <div class="splide__slide">
              <div class="card rounded-l mx-2 text-center shadow-l" style="background-image: url({{$currentBanner}})" data-card-height="320">
                <div class="card-bottom">
                  <p class="boxed-text-xl">
                    {{--Azures brings beauty and colors to your Mobile device with a stunning user interface to match.--}}
                  </p>
                </div>
                <div class="card-overlay bg-gradient-fade"></div>
              </div>
            </div>
          @endfor

        </div>
      </div>
    </div>
  @endif

  <div class="content mb-2">
    <h5 class="float-start font-16 font-500">Program Kami</h5>
    {{--    <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>--}}
    <div class="clearfix"></div>
  </div>

  <div class="row text-center mb-0">
    <form action="{{ route('perencanaan.check-estimasi') }}" method="get" id="berangkat-langsung" class="col-6 pe-2">
      <input hidden name="type" value="berangkat-langsung">
      <button>
        <div class="card card-style me-0 mb-3">
          <h1 class="center-text pt-4 mt-2">
            <i class="fa-solid fa-passport font-40 color-facebook"></i>
          </h1>
          <h4 class="color-theme font-600">Berangkat Langsung</h4>
          <p class="font-10 opacity-30 mb-1">Klik untuk detail</p>
        </div>
      </button>
    </form>

    <form action="{{ route('perencanaan.check-estimasi') }}" method="get" id="berangkat-langsung" class="col-6 pe-2">
      <input hidden name="type" value="perencanaan-ibadah">
      <button>
        <div class="card card-style ms-0 mb-3">
          <h1 class="center-text pt-4 mt-2">
            <i class="fa-solid fa-book-atlas font-40 color-instagram"></i>
          </h1>
          <h4 class="color-theme font-600">Perencanaan Ibadah</h4>
          <p class="font-10 opacity-30 mb-1">Klik untuk detail</p>
        </div>
      </button>
    </form>
  </div>

@endsection

@section('external-mobile-content')
  @include('pages.mobile.home.menu.mulai-menabung')
{{--  @yield('external-mobile-content-sub')--}}
@endsection
