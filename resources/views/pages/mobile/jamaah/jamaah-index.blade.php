@extends('layouts.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Jamaah</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div>
    {{-- <div class="card card-style">
        <div class="content">
            <div class="d-flex justify-content-between">
                <h3 class="font-600">Bayu Nur Winata</h3>
                <p class="text-nowrap"><strong>Total Tabungan</strong></p>

            </div>
            <p class="font-11 mt-n2 color-hi ghlight">6281331307327</p>

            <div class="float-start">
                <p class="font-12 opacity-80 mb-n1"><i class="far fa-calendar"></i> August 28 <i
                        class="ms-4 far fa-clock"></i>
                    09:00 PM</p>
                <p class="font-12 opacity-100"> <strong><i class="fa-solid fa-wallet"></i> Rp.14.290.000
                    </strong> <small>dari
                        Rp34.000.000</small></p>

            </div>
            <a href="#" data-menu="mulai-menabung"
                class="float-end btn btn-s bg-highlight rounded-s shadow-xl text-uppercase font-900 font-11 mt-2">Tabung</a>
        </div>
    </div> --}}

    <div class="card card-style">
        <div class="content">
            <h5 class="float-start font-16 font-600">Jamaah Terdaftar</h5>
            <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
            <div class="clearfix"></div>
            <p class="pt-2">
                Daftar jamaah yang sudah berhasil anda daftarkan!.
            </p>
        </div>
        <div class="splide user-slider slider-no-arrows slider-no-dots" id="user-slider-1">
            <div class="splide__track">
                <div class="splide__list">
                    @forelse ($people_invited as $invited)
                        <div class="{{ $people_invited->count() > 2 ? 'splide__slide' : '' }}">
                            <div class="text-center">
                                <img src="images/avatars/4s.png" width="55" height="55"
                                    class="rounded-xl shadow-l gradient-blue">
                                <p>{{ $invited?->user?->name }}</p>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection

@section('external-mobile-content')
    {{-- @include('pages.mobile.home.menu.mulai-menabung') --}}
@endsection
