@extends('layouts.app-mobile')

@section('mobile-content')
    <div class="card card-style">
        <div class="content">
            <div class="d-flex justify-content-between">
                <h3 class="font-600">Bayu Nur Winata</h3>
                <p class="text-nowrap"><strong>Total Tabungan</strong></p>

            </div>
            <p class="font-11 mt-n2 color-hi ghlight">6281331307327</p>

            <div class="float-start">
                <p class="font-12 opacity-80 mb-n1"><i class="far fa-calendar"></i> August 28 <i class="ms-4 far fa-clock"></i>
                    09:00 PM</p>
                <p class="font-12 opacity-100"> <strong><i class="fa-solid fa-wallet"></i> Rp.14.290.000
                    </strong> <small>dari
                        Rp34.000.000</small></p>

            </div>
            <a href="#" data-menu="mulai-menabung"
                class="float-end btn btn-s bg-highlight rounded-s shadow-xl text-uppercase font-900 font-11 mt-2">Tabung</a>
        </div>
    </div>
@endsection
