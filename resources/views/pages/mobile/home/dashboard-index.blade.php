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
        <p class="text-nowrap"><strong>Total Tabungan</strong></p>
      </div>
      <p class="font-11 mt-n2 color-hi ghlight">{{ isset($data['phone']) ? $data['phone'] : '-' }}</p>

      <div class="float-start">
        <p class="font-12 opacity-80 mb-n1"><i class="far fa-calendar"></i>
          {{ Carbon::now()->format('F m') }} <i class="ms-4 fas fa-money-check"></i>
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
@endsection
