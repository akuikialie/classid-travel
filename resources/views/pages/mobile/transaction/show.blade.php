@extends('layouts.mobile.app-mobile')

@section('mobile-content')

    @include('components.mobile.toolbar', [
      'title' => 'Detail Transaksi',
      'backDestination' => route('mutations.index', ['virtualAccount' => $mutation->mutable_id]),
  ])

    @switch($mutation->transaction->trx_type)
        @case(\App\Enums\TransactionType::DEPOSIT->value)
            <div class="card card-style mb-0">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <a href="#" class="icon icon-l bg-highlight color-white shadow-xl rounded-m me-3"><i
                                    class="fa fa-arrow-down"></i></a>
                        </div>
                        <div class="align-self-center">
                            <h4 class="font-600 mb-0">Pembayaran Diterima</h4>
                        </div>
                    </div>
                    <div class="divider mt-4 mb-2"></div>
                    <div class="row mb-0">
                        <div class="col-4"><p class="font-15 font-700 color-theme">Nomor Transaksi</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutation->transaction->trx_number }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Tabungan</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutation->mutable->name }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Metode Bayar</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \App\Enums\TransactionMethod::tryFrom($mutation->transaction->trx_method)->name() }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Tanggal</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->format('d F, Y') }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Waktu</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->format('H:i A') }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Nominal</p></div>
                        <div class="col-8"><p class="font-13 color-theme">
                                Rp. {{ moneyFormat($mutation->transaction->amount) }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Biaya Admin</p></div>
                        <div class="col-8"><p class="font-13 color-theme">
                                Rp. {{ moneyFormat($mutation->fee_admin) }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Catatan</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ $mutation->transaction->invocation->description }}</p>
                        </div>
                    </div>
                    <div class="divider divider-margins mt-2"></div>
                    {{--<a href="#" class="btn btn-m bg-highlight btn-full rounded-sm shadow-xl text-uppercase font-800">Download
                        Transaction as PDF</a>--}}
                </div>
            </div>
            @break
        @case(\App\Enums\TransactionType::MOVE->value)
            <div class="card card-style mb-0">
                <div class="content">
                    <div class="d-flex">
                        <div class="align-self-center">
                            <a href="#" class="icon icon-l bg-blue-dark color-white shadow-xl rounded-m me-3"><i
                                    class="fa fa-exchange-alt font-20"></i></a>
                        </div>
                        <div class="align-self-center">
                            <h4 class="font-600 mb-0">Pemindahan Dana Berhasil</h4>
                        </div>
                    </div>
                    <div class="divider mt-4 mb-2"></div>
                    <div class="row mb-0">
                        <div class="col-4"><p class="font-15 font-700 color-theme">Nomor Transaksi</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutation->transaction->trx_number }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Aktor</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \App\Enums\TransactionMethod::tryFrom($mutation->transaction->trx_method)->name() }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Tanggal</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->format('d F, Y') }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Waktu</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ \Carbon\Carbon::parse($mutation->transaction->trx_date)->format('H:i A') }}</p>
                        </div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                    </div>

                    <hr>

                    <div class="row mb-0">

                        <div class="col-4"><p class="font-15 font-700 color-theme">Dari Tabungan</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutationFrom->mutable->name }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Virtual Account</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutationFrom->mutable->va_number }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>


                        <div class="col-4"><p class="font-15 font-700 color-theme">Nominal Keluar</p></div>
                        <div class="col-8"><p class="font-13 color-theme">
                                Rp. {{ moneyFormat($mutationFrom->transaction->amount) }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                    </div>

                    <hr>

                    <div class="row mb-0">

                        <div class="col-4"><p class="font-15 font-700 color-theme">Ke Tabungan</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutationTo->mutable->name }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>

                        <div class="col-4"><p class="font-15 font-700 color-theme">Virtual Account</p></div>
                        <div class="col-8"><p class="font-13 color-theme">{{ $mutationTo->mutable->va_number }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>


                        <div class="col-4"><p class="font-15 font-700 color-theme">Nominal Masuk</p></div>
                        <div class="col-8"><p class="font-13 color-theme">
                                Rp. {{ moneyFormat($mutationTo->transaction->amount) }}</p></div>
                        <div class="divider divider-margins w-100 mt-2 mb-2"></div>
                    </div>

                    <hr>

                    <div class="row mb-0">

                        <div class="col-4"><p class="font-15 font-700 color-theme">Catatan</p></div>
                        <div class="col-8"><p
                                class="font-13 color-theme">{{ $mutation->transaction->invocation->description }}</p>
                        </div>
                    </div>

                    <div class="divider divider-margins mt-2"></div>
                    {{--<a href="#" class="btn btn-m bg-highlight btn-full rounded-sm shadow-xl text-uppercase font-800">Download
                        Transaction as PDF</a>--}}
                </div>
            </div>
            @break
    @endswitch
@endsection
