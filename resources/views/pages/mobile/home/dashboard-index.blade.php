@extends('layouts.app-mobile')

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
                    {{ \Carbon\Carbon::now()->format('F m') }} <i class="ms-4 fas fa-money-check"></i>
                    <strong>{{ isset($total_tabungan) ? $total_tabungan : 0 }}</strong> Tabungan
                </p>
                <p class="font-12 opacity-100"> <strong><i class="fa-solid fa-wallet"></i>
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
        <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
        <div class="clearfix"></div>
    </div>

    <div class="row text-center mb-0">
        <a href="#" class="col-6 pe-2">
            <div class="card card-style me-0 mb-3">
                <h1 class="center-text pt-4 mt-2">
                    <i data-feather="file" data-feather-line="1" data-feather-size="50" data-feather-color="blue-dark"
                        data-feather-bg="blue-fade-light">
                    </i>
                </h1>
                <h4 class="color-theme font-600">Berangkat Langsung</h4>
                {{-- <p class="line-height-s font-11 pb-1">
                    Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
                </p> --}}
                <p class="font-10 opacity-30 mb-1">Coming Soon</p>
            </div>
        </a>
        <a href="#" class="col-6 ps-2">
            <div class="card card-style ms-0 mb-3">
                <h1 class="center-text pt-4 mt-2">
                    <i data-feather="smartphone" data-feather-line="1" data-feather-size="50"
                        data-feather-color="green-dark" data-feather-bg="green-fade-light">
                    </i>
                </h1>
                <h4 class="color-theme font-600">Perencanaan Ibadah</h4>
                {{-- <p class="line-height-s font-11 pb-1">
                    Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...
                </p> --}}
                <p class="font-10 opacity-30 mb-1">Coming Soon</p>
            </div>
        </a>
    </div>

    {{-- <div class="content mb-2">
        <h5 class="float-start font-16 font-500">Produk Kami</h5>
        <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
        <div class="clearfix"></div>
    </div>

    <div class="splide double-slider visible-slider slider-no-arrows slider-no-dots" id="double-slider-1">
        <div class="splide__track">
            <div class="splide__list">
                <div class="splide__slide ps-3">
                    <div class="bg-theme rounded-m shadow-m text-center">
                        <i class="mt-4 mb-4" data-feather="shield" data-feather-line="1" data-feather-size="45"
                            data-feather-color="blue-dark" data-feather-bg="blue-fade-light"></i>
                        <h5 class="font-16">Elite Quality</h5>
                        <p class="line-height-s font-11 pb-4">
                            Built with care and <br>every detail in mind
                        </p>
                    </div>
                </div>
                <div class="splide__slide ps-3">
                    <div class="bg-theme rounded-m shadow-m text-center">
                        <i class="mt-4 mb-4" data-feather="smartphone" data-feather-line="1" data-feather-size="45"
                            data-feather-color="brown-dark" data-feather-bg="brown-fade-light"></i>
                        <h5 class="font-16">PWA Ready</h5>
                        <p class="line-height-s font-11 pb-4">
                            Just add it to your <br>Home Screen
                        </p>
                    </div>
                </div>
                <div class="splide__slide ps-3">
                    <div class="bg-theme rounded-m shadow-m text-center">
                        <i class="mt-4 mb-4" data-feather="sun" data-feather-line="1" data-feather-size="45"
                            data-feather-color="yellow-dark" data-feather-bg="yellow-fade-light"></i>
                        <h5 class="font-16">Eye Friendly</h5>
                        <p class="line-height-s font-11 pb-4">
                            Light & Dark and <br> Auto Dark Detection
                        </p>
                    </div>
                </div>
                <div class="splide__slide ps-3">
                    <div class="bg-theme rounded-m shadow-m text-center">
                        <i class="mt-4 mb-4" data-feather="smile" data-feather-line="1" data-feather-size="45"
                            data-feather-color="green-dark" data-feather-bg="green-fade-light"></i>
                        <h5 class="font-16">Easy Code</h5>
                        <p class="line-height-s font-11 pb-4">
                            Built for you and me <br> copy and paste code.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('external-mobile-content')
    @include('pages.mobile.home.menu.mulai-menabung')
@endsection
