@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    @include('components.mobile.toolbar', [
        'title' => 'Rincian Tabungan',
        'backDestination' => url()->previous(),
    ])

    @include('pages.mobile.tabungan.menu.menu-tabungan', $moneybox)

    <div class="card card-style">
        <div class="content">
            <h3 class="float-start font-16">Riwayat Tabungan</h3>
            <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
            <div class="clearfix mb-1"></div>

             @foreach($invocations as $invocation)
                <div class="d-flex">
                    <div>
                        <a href="#" class="icon icon-m bg-green-dark rounded-m shadow-xl"><i
                                class="fa fa-arrow-down"></i></a>
                    </div>
                    <div class="align-self-center ps-3">
                        <h5 class="font-600 font-14 mb-n2">{{ $invocation->invoice_number }}</h5>
                        <span class="color-theme font-11">Recieved via Bank Transfer</span>
                    </div>
                    <div class="align-self-center ms-auto">
                        <h5 class="color-green-dark mb-n1 text-end">{{ $invocation->transaction->amount }}</h5>
                        <span class="color-theme d-block font-11 text-end">{{ \Carbon\Carbon::parse($invocation->transaction->trx_date)->format('M d, Y H:i') }}</span>
                    </div>
                </div>
                <div class="divider mt-3 mb-3"></div>
             @endforeach
        </div>
    </div>
@endsection
