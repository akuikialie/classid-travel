@extends('layouts.mobile.app-mobile')

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

{{--    @section('external-mobile-content')--}}
{{--      @for($i = 0; $i < count($list_moneyboxs); $i++)--}}
{{--        @include('pages.mobile.tabungan.menu.va-billing', ['id' => $list_moneyboxs[$i]['id'], 'data' => $list_moneyboxs[$i]])--}}
{{--      @endfor--}}
{{--    @endsection--}}
@endsection
