@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    @include('components.mobile.toolbar', [
        'title' => 'Rincian Tabungan',
        'backDestination' => route('tabungan.index'),
    ])

    @include('pages.mobile.tabungan.menu.menu-tabungan', $moneybox)

    <div class="card card-style">
        <div class="content">
            <h3 class="float-start font-16">Riwayat Tabungan</h3>
            <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
            <div class="clearfix mb-1"></div>

            {{-- <div class="d-flex">
                <div>
                    <a href="#" class="icon icon-m bg-green-dark rounded-m shadow-xl"><i
                            class="fa fa-arrow-down"></i></a>
                </div>
                <div class="align-self-center ps-3">
                    <h5 class="font-600 font-14 mb-n2">Envato Income</h5>
                    <span class="color-theme font-11">Recieved via PayPal</span>
                </div>
                <div class="align-self-center ms-auto">
                    <h5 class="color-green-dark mb-n1 text-end">$12.350</h5>
                    <span class="color-theme d-block font-11 text-end">Jul 15th</span>
                </div>
            </div>
            <div class="divider mt-3 mb-3"></div>
            <div class="d-flex">
                <div>
                    <a href="#" class="icon icon-m bg-dark-dark rounded-m shadow-xl"><i
                            class="fab fa-apple font-20"></i></a>
                </div>
                <div class="align-self-center ps-3">
                    <h5 class="font-600 font-14 mb-n2">Apple Music</h5>
                    <span class="color-theme font-11">Monthly Subscription</span>
                </div>
                <div class="align-self-center ms-auto">
                    <h5 class="color-yellow-dark mb-n1 text-end">$12.350</h5>
                    <span class="color-theme d-block font-11 text-end">Jul 15th</span>
                </div>
            </div>
            <div class="divider mt-3 mb-3"></div>
            <div class="d-flex">
                <div>
                    <a href="#" class="icon icon-m bg-red-dark rounded-m shadow-xl"><i
                            class="fa fa-arrow-up font-20"></i></a>
                </div>
                <div class="align-self-center ps-3">
                    <h5 class="font-600 font-14 mb-n2">Work Partner</h5>
                    <span class="color-theme font-11">via VISA Card ****1234</span>
                </div>
                <div class="align-self-center ms-auto">
                    <h5 class="color-red-dark mb-n1 text-end">$1.350</h5>
                    <span class="color-theme d-block font-11 text-end">Jul 15th</span>
                </div>
            </div>
            <div class="divider mt-3 mb-3"></div>
            <div class="d-flex">
                <div>
                    <a href="#" class="icon icon-m bg-blue-dark rounded-m shadow-xl"><i
                            class="fa fa-exchange-alt font-20"></i></a>
                </div>
                <div class="align-self-center ps-3">
                    <h5 class="font-600 font-14 mb-n2">Savings Account</h5>
                    <span class="color-theme font-11">via VISA Card ****1234</span>
                </div>
                <div class="align-self-center ms-auto">
                    <h5 class="color-blue-dark mb-n1 text-end">$10.000</h5>
                    <span class="color-theme d-block font-11 text-end">Jul 15th</span>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
