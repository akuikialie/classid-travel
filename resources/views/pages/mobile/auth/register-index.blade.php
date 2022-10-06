@extends('layouts.guest-mobile')

@section('title', 'Daftar')
@section('mobile-content')

    @include('components.mobile.toolbar', ['title' => 'Daftar', 'backDestination' => url('/')])

    <div class="card card-style">
        <div class="content mb-0 mt-1">
            <div class="input-style no-borders has-icon validate-field">
                <i class="fa fa-user"></i>
                <input type="name" class="form-control validate-name" id="form1a" placeholder="Name">
                <label for="form1a" class="color-blue-dark font-10 mt-1">Nama</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(required)</em>
            </div>
            <div class="input-style no-borders has-icon validate-field mt-2">
                <i class="fa fa-at"></i>
                <input type="text" class="form-control validate-email" id="form1aa"
                    placeholder="Username / Email / Telepon">
                <label for="form1aa" class="color-blue-dark font-10 mt-1">Username / Email / Telepon</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(required)</em>
            </div>
            <div class="input-style no-borders has-icon validate-field mt-2">
                <i class="fa fa-lock"></i>
                <input type="password" class="form-control validate-password" id="form3a" placeholder="Password">
                <label for="form3a" class="color-blue-dark font-10 mt-1">Password</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(required)</em>
            </div>
            <div class="input-style no-borders has-icon validate-field mt-2">
                <i class="fa fa-lock"></i>
                <input type="password" class="form-control validate-password" id="form3a1"
                    placeholder="Konfirmasi Password">
                <label for="form3a1" class="color-blue-dark font-10 mt-1">Konfirmasi Password</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(required)</em>
            </div>

            <a href="#" class="btn btn-sm btn-full rounded-sm shadow-l bg-highlight text-uppercase font-700 mt-4">Buat
                Akun</a>

            <div class="divider mt-4"></div>

            <div class="d-flex">
                <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a href="{{ route('login') }}"
                        class="color-theme">Sudah punya akun?</a></div>
            </div>


        </div>
    </div>
@endsection
