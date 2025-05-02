@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    @include('components.mobile.toolbar', [
       'title' => 'Transaksi',
       'backDestination' => route('tabungan.index'),
   ])


    <div class="card card-style">
        <div class="content">
            <h3 class="font-16">Transaksi Terbaru</h3>

            <div class="divider mt-4"></div>

            @foreach($mutations as $mutation)
                @switch($mutation->transaction->trx_type)
                    @case(\App\Enums\TransactionType::DEPOSIT->value)
                        <div class="d-flex">
                            <div>
                                <a href="{{ route('mutations.show', ['mutation' => $mutation->id]) }}" class="icon icon-m bg-green-dark rounded-m shadow-xl"><i
                                        class="fa fa-arrow-down"></i></a>
                            </div>
                            <div class="align-self-center ps-3">
                                <h5 class="font-600 font-14 mb-n2">{{ $mutation->transaction->trx_number }}</h5>
                                <span class="color-theme font-11">Pembayaran diterima melalui {{ \App\Enums\TransactionMethod::tryFrom($mutation->transaction->trx_method)->name() }}</span>
                            </div>
                            <div class="align-self-center ms-auto">
                                <h5 class="color-green-dark mb-n1 text-end">Rp. {{ moneyFormat($mutation->transaction->amount) }}</h5>
                                <span class="color-theme d-block font-11 text-end">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->toDateTimeString() }}</span>
                            </div>
                        </div>
                        @break
                    @case(\App\Enums\TransactionType::MOVE->value)
                        <div class="d-flex">
                            <div>
                                <a href="{{ route('mutations.show', ['mutation' => $mutation->id]) }}" class="icon icon-m bg-blue-dark rounded-m shadow-xl"><i
                                        class="fa fa-exchange-alt font-20"></i></a>
                            </div>
                            <div class="align-self-center ps-3">
                                <h5 class="font-600 font-14 mb-n2">{{ $mutation->transaction->trx_number }}</h5>
                                <span class="color-theme font-11">Pemindahan dana dilakukan oleh {{ \App\Enums\TransactionMethod::tryFrom($mutation->transaction->trx_method)->name() }}</span>
                            </div>
                            <div class="align-self-center ms-auto">
                                <h5 class="color-blue-dark mb-n1 text-end">Rp. {{ moneyFormat($mutation->transaction->amount) }}</h5>
                                <span class="color-theme d-block font-11 text-end">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->toDateTimeString() }}</span>
                            </div>
                        </div>
                        @break
                @endswitch

                <div class="divider mt-3 mb-3"></div>
            @endforeach

            {{ $mutations->links('vendor.pagination.azures-pagination') }}
        </div>
    </div>
@endsection
