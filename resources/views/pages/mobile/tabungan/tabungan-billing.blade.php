@extends('layouts.mobile.app-mobile')

@section('mobile-content')
  @include('components.mobile.toolbar', [
      'title' => 'Pembayaran',
      'backDestination' => url()->previous(),
  ])

  <div class="card card-style text-center">
    <div class="content pb-2">
      <h1>
        <i data-feather="gift"
           data-feather-line="1"
           data-feather-size="55"
           data-feather-color="red-dark"
           data-feather-bg="red-fade-dark">
        </i>
      </h1>
      <p class="font-16 mt-n1 color-highlight mb-1">Nomor virtual account anda:</p>
      <h4 class="font-700 mt-2">{{ $va->va_number }}</h4>

      <p class="boxed-text-xl h5 text-muted">
        {{ $va->name }}
      </p>

      <a href="#" class="btn btn-center-xl btn-m text-uppercase font-900 bg-highlight rounded-sm shadow-l mt-5">Silahkan transfer ke nomor rekening diatas
      </a>
    </div>
  </div>

@endsection
