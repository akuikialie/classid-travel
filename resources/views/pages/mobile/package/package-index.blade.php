@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Paket</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div>

    <div class="splide single-slider boxed-slider slider-no-arrows slider-no-dots" id="single-slider-1"
        data-splide='{"interval":"5000"}'>
        <div class="splide__track">
            <div class="splide__list">
                @forelse ($packages as $package)
                    @php
                        $colors = ['red', 'blue', 'green', 'dark'];
                        $color = $colors[rand(0, 3)];
                    @endphp
                    <div class="splide__slide">
                        <div class="card card-style mb-3 gradient-{{ $color }}" data-card-height="140">
                            <div class="card-center ps-3 pb-3">
                                <h1 class="color-white mb-n1 font-25">{{ $package?->myPlan?->value }} 2024</h1>
                                <p class="opacity-50 color-white mb-0">{{ $package?->name }}</p>
                            </div>
                            <div class="card-center pe-3 pb-3">
                                <h1 class="text-end color-white mb-n1 font-25">{{ rupiahFormat($package->amount) }}</h1>
                                {{-- <p class="text-end opacity-50 color-white mb-0"><del>was $1999</del></p> --}}
                            </div>
                        </div>
                        <div class="card card-style mt-n5">
                            <div class="content">
                                {{-- <p>The master pack that includes everything from all packs in one to give you the best event
                                possible! </p>
                            <ul class="icon-list mb-4">
                                <li><i class="fa fa-check color-green-dark"></i>12 Hour Session</li>
                                <li><i class="fa fa-check color-green-dark"></i>500 Edited Pictures</li>
                                <li><i class="fa fa-check color-green-dark"></i>10 Locations, Free Travel</li>
                                <li><i class="fa fa-check color-green-dark"></i>5x Photo Album and Online </li>
                                <li><i class="fa fa-check color-green-dark"></i>Edited Photos In 5x7 Prints</li>
                            </ul> --}}
                                <a href="{{ route('package.add-to-jamaah', $package->id) }}"
                                    class="btn btn-full btn-m bg-{{ $color }}-dark text-uppercase font-700 rounded-sm">Pilih
                                    Paket</a>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endsection
