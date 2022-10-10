@extends('layouts.guest-mobile')

@section('mobile-content')
    <div class="card preload-img" data-src="images/pictures/18.jpg" data-card-height="cover">

        <div class="card-top mt-5 text-center">
            <img class="preload-img rounded-circle mt-5 mb-5" data-src="images/pictures/18t.jpg" width="200">
            <h1 class="fa-3x color-theme font-900">Pro Hajj APP</h1>
            <h6 class="font-300 color-highlight mt-3">Selamat datang di Pro Hajj APP.</h6>

            {{-- <p class="boxed-text-xl pt-4 font-14">
                Welcome to Azures. A beautifully crafted Mobile PWA & Site Template made to run incredibly fast and be
                extremely easy to edit and customize.
            </p> --}}
        </div>

        <div class="card-bottom mb-3">
            <div class="row mb-4">
                <div class="col-6 pe-0"><a href="{{ route('login') }}"
                        class="back-button btn btn-center-s bg-highlight rounded-sm font-700 text-uppercase scale-box">Masuk</a>
                </div>
                <div class="col-6 ps-0"><a href="{{ route('register') }}"
                        class="back-button btn btn-border border-highlight btn-center-s color-highlight rounded-sm font-700 text-uppercase scale-box">Daftar</a>
                </div>
            </div>
        </div>

        <div class="card-overlay bg-theme opacity-95"></div>
    </div>
@endsection
