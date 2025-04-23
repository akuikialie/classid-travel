@extends('pages.web.jamaah-profile.show')

@section('fragment-content')
    <div class="row g-6 g-xl-9">
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('admin.jamaah.mutations', ['user' => $user->hash, 'virtual_account' => $saving->hash ]) }}" class="card border-hover-primary ">
                <div class="card-header border-0 pt-9">
                    <div class="card-title m-0">
                        <div class="fs-3 fw-bold text-gray-900">
                            Tabungan Pribadi
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <span class="badge badge-light-primary fw-bold me-auto px-4 py-3">
                            {{ $saving->va_number }}
                        </span>

                    </div>
                </div>

                <div class="card-body p-9">
                    <div class="d-flex flex-wrap mb-5">
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">Rp. {{ moneyFormat($saving->balance) }}</div>
                            <div class="fw-semibold text-gray-500">Saldo (Rp)</div>
                        </div>

                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
                            <div class="fs-6 text-gray-800 fw-bold">$ {{ moneyFormat($saving->usd_balance) }}</div>
                            <div class="fw-semibold text-gray-500">Saldo (USD)</div>
                        </div>
                    </div>

                </div>
            </a>
        </div>

        @foreach($packageSavings ?? [] as $packageSaving)
            <div class="col-md-6 col-xl-4">
                <a class="card border-hover-primary ">
                    <div class="card-header border-0 pt-9">
                        <div class="card-title m-0">
                            <div class="fs-3 fw-bold text-gray-900">
                                {{ $packageSaving->name }}
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <span class="badge badge-light-primary fw-bold me-auto px-4 py-3">{{ $packageSaving->va_number }}</span>
                        </div>
                    </div>

                    <div class="card-body p-9">

                        @if($packageSaving->myPackage instanceof \App\Models\Plan\PlanPackage)
                            <div class="fs-3 fw-bold text-gray-900">
                                Paket:
                            </div>

                            <p class="text-gray-500 fw-semibold fs-5 mt-1 mb-7">
                                {{ $packageSaving->myPackage->name }}
                            </p>
                        @endif


                        <div class="d-flex flex-wrap mb-5">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">Rp. {{ moneyFormat($packageSaving->balance) }}</div>
                                <div class="fw-semibold text-gray-500">Saldo (Rp)</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
                                <div class="fs-6 text-gray-800 fw-bold">$ {{ moneyFormat($packageSaving->usd_balance) }}</div>
                                <div class="fw-semibold text-gray-500">Saldo (USD)</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>


@endsection
