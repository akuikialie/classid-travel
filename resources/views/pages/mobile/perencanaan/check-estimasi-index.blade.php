@php use Carbon\Carbon; @endphp
@extends('layouts.mobile.app-mobile')

@section('mobile-content')
  @if ($errors->any())
    @dd($errors)
  @endif
  @include('components.mobile.toolbar', [
      'title' => 'Cek Estimasi',
      'backDestination' => url()->previous(),
  ])

  <div class="card card-style">
    <div class="content">
      Proses simulasi perhitungan biaya keberangkatan
    </div>
  </div>

  <div id="tab-group-1" class="card bg-transparent mb-0">
    <div class="rounded-m overflow-hidden mx-3">
      <div class="tab-controls tabs-large tabs-rounded" data-highlight="bg-dark-dark">
        <a href="#" data-active data-bs-toggle="collapse" data-bs-target="#simulasi-menabung">Menabung</a>
        <a href="#" data-bs-toggle="collapse" data-bs-target="#simulasi-keberangkatan">Berangkat</a>
        {{--        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-3">Overdue</a>--}}
      </div>
    </div>

    <div data-bs-parent="#tab-group-1" class="collapse {{ request()->get('type') == 'perencanaan-ibadah' ? 'show' : '' }}" id="simulasi-menabung">
      <div class="mt-3"></div>
      <div class="card card-style mb-3">
        <div class="content">
          <h3>Simulasi Menabung</h3>
          <p class="mb-0">
            Simulasi menabung akan membantu anda untuk memperkirakan keberangkatan anda berdasarkan besaran tabungan
            anda setiap bulannya.
          </p>

          <div class="divider mb-3 mt-3"></div>

          <form action="#" method="get">
            <input hidden name="type" value="{{ isset($type) ? $type : 'perencanaan-ibadah' }}">

            @include('pages.mobile.perencanaan._input-check-estimasi')

            <div class="input-style has-borders no-icon input-style-always-active validate-field mb-4">
              <input type="number" class="form-control validate-text" id="besaran_menabung" name="besaran_menabung"
                     placeholder="Besaran menabung">
              <label for="besaran_menabung" class="color-highlight font-400 font-13">Besaran menabung</label>
              <i class="fa fa-times disabled invalid color-red-dark"></i>
              <i class="fa fa-check disabled valid color-green-dark"></i>
              <em></em>
            </div>

            <div class="d-flex justify-content-end">
              <button
                class="btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 mt-2 mb-2">Cek
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>


    <div data-bs-parent="#tab-group-1" class="collapse {{ request()->get('type') == 'berangkat-langsung' ? 'show' : '' }}" id="simulasi-keberangkatan">
      <div class="mt-3"></div>
      <div class="card card-style mb-3">
        <div class="content">
          <h3>Simulasi Berangkat</h3>
          <p class="mb-0">
            Simulasi berangkat akan membantu anda untuk memperkirakan besaran tabungan anda tiap bulannya berdasarkan
            bulan / tahun keberangkatan!.
          </p>

          <div class="divider mb-3 mt-3"></div>

          <form action="#" method="get">
            <input hidden name="type" value="{{ isset($type) ? $type : 'berangkat-langsung' }}">

            @include('pages.mobile.perencanaan._input-check-estimasi')

            <div class="input-style has-borders no-icon mb-4">
              <input type="date" value="{{ Carbon::now()->format('Y-m-d') }}" name="tanggal_keberangkatan"
                     min="{{ Carbon::now()->format('Y-m-d') }}" class="form-control validate-text" id="tanggal_keberangkatan"
                     placeholder="Tanggal Keberangkatan">
              <label for="tanggal_keberangkatan" class="color-highlight">Tanggal Keberangkatan</label>
              <i class="fa fa-check disabled valid me-4 pe-3 font-12 color-green-dark"></i>
              <i class="fa fa-check disabled invalid me-4 pe-3 font-12 color-red-dark"></i>
            </div>

            <div class="d-flex justify-content-end">
              <button
                class="btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 mt-2 mb-2">Cek
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>


  </div>

  @if(isset($hasil_simulasi))
    <div class="card mt-4 preload-img" data-src="images/pictures/20s.jpg">
      <div class="card-body py-4">
        <h3 class="color-white mb-n1">Hasil simulasi</h3>
        <p class="color-white opacity-60">
          Berikut adalah hasil simulasinya.
        </p>
        <div class="card rounded-m mt-n3 mb-0">
          <div class="content mb-2 mt-3">
            <div class="d-flex">
              <div class="w-100 align-self-center ps-3">
                <h6 class="font-14 font-500">Paket
                  <span class="float-end text-muted">{{$hasil_simulasi->package}}</span>
                </h6>
                <div class="divider mb-2 mt-1"></div>
                <h6 class="font-14 font-500">Biaya
                  <span class="float-end text-muted">Rp. {{$hasil_simulasi->price}}</span>
                </h6>
              </div>
            </div>
            <div class="divider mt-2 mb-3"></div>
            <div class="row mb-0">
              <div class="col-6 pe-1">
                <div class="mx-0 mb-3">
                  <h6 class="font-13 font-400 mb-0">Target Menabung</h6>
                  <h3 class="text-muted font-15 mb-0">Rp {{$hasil_simulasi->target_savings}}</h3>
                </div>
              </div>
              <div class="col-6 ps-1">
                <div class="mx-0 mb-3">
                  <h6 class="font-13 font-400 mb-0">Estimasi Keberangkatan</h6>
                  <h3 class="text-muted font-15 mb-0">{{$hasil_simulasi->estimated_departure}}</h3>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="card-overlay bg-highlight opacity-95"></div>
      <div class="card-overlay dark-mode-tint"></div>
    </div>

  @endif


@endsection
