@extends('layouts.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Tabungan</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div>
    @foreach ($list_moneyboxs as $moneybox)
        @include('pages.mobile.tabungan.menu.menu-tabungan', $moneybox)
    @endforeach
@endsection
