@extends('layouts.app-mobile')

@section('mobile-content')
    @include('components.mobile.toolbar', [
        'title' => 'Perbarui Data',
        'backDestination' => route('profile.index'),
    ])

    <div class="card card-style">
        <div class="content">
            <div class="d-flex">
                <div>
                    <img src="{{ asset('images/avatars/5s.png') }}" width="50" class="me-3 bg-highlight rounded-xl">
                </div>
                <div>
                    <h1 class="mb-0 pt-1">Winata Bayu</h1>
                    <p class="color-highlight font-11 mt-n2 ">6281331307327</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-style">
        <form action="" method="post">
            @csrf
            @method('put')
            <div class="content mb-0">
                <h3 class="font-600 mb-4">Data Pribadi</h3>

                <div class="input-style has-borders hnoas-icon input-style-always-active validate-field mb-4">
                    <input type="name" class="form-control validate-name" id="name" placeholder="nama"
                        value="{{ isset($user) ? $user?->name : null }}">
                    <label for="name" class="color-highlight font-400 font-13">Name</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>

                <div class="input-style has-borders no-icon input-style-always-active validate-field mb-4">
                    <input type="text" class="form-control validate-text" id="username" placeholder="username"
                        value="{{ isset($user) ? $user?->username : null }}">
                    <label for="username" class="color-highlight font-400 font-13">Username</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em></em>
                </div>

                <div class="input-style has-borders no-icon input-style-always-active validate-field mb-4">
                    <input type="email" class="form-control validate-email" id="email" placeholder="email"
                        value="{{ isset($user) ? $user?->email : null }}">
                    <label for="email" class="color-highlight font-400 font-13">Email</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em></em>
                </div>

                <div class="input-style has-borders no-icon input-style-always-active validate-field mb-4">
                    <input type="text" class="form-control validate-text" id="form44"
                        placeholder="Melbourne, Victoria" value="{{ isset($user) ? $user?->address : null }}">
                    <label for="form44" class="color-highlight font-400 font-13">Location</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>

                <div class="d-flex justify-content-end">
                    <button
                        class="btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 mt-2 mb-2">Simpan
                        Perubahan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-style">
        <div class="content mb-2">
            <h3 class="font-600">Lain Lain</h3>

            <div class="list-group list-custom-small">
                <a href="#">
                    <i class="fas fa-phone-alt"></i>
                    <span>Ubah Nomor Pengguna</span>
                    <i class="fa fa-arrow-right"></i>
                </a>
                <a href="#" data-menu="change-password">
                    <i class="fas fa-unlock-alt"></i>
                    <span>Ubah Password</span>
                    <i class="fa fa-arrow-right"></i>
                </a>

            </div>
        </div>
    </div>
@endsection

@section('external-mobile-content')
    <!--------------->
    <!--------------->
    <!--Menu Forgot-->
    <!--------------->
    <!--------------->
    <div id="change-password" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="360"
        data-menu-effect="menu-over">
        <form action="" method="post">
            @csrf

            <div class="me-3 ms-3 mt-3 pt-1">
                <h2 class="font-700 mb-0">Ganti Sandi?</h2>
                <p class="font-11 mb-3">
                    Pastikan kata sandi baru mudah untuk diingat!.
                </p>
                <div class="input-style no-borders has-icon validate-field mb-4 mt-3">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control validate-password" id="old-password"
                        placeholder="Password Lama">
                    <label for="old-password" class="color-highlight font-11 font-500 mt-1">Password Lama</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style no-borders has-icon validate-field mb-4 mt-3">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control validate-password" id="new-password"
                        placeholder="Password Baru">
                    <label for="new-password" class="color-highlight font-11 font-500 mt-1">Password Baru</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style no-borders has-icon validate-field mb-4 mt-3">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control validate-password" id="confirm-password"
                        placeholder="Konfirmasi Password Baru">
                    <label for="confirm-password" class="color-highlight font-11 font-500 mt-1">Konfirmasi Password
                        Baru</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="#"
                        class="close-menu btn btn-full btn-m btn-border rounded-s text-uppercase font-900 color-dark-light border-dark-dark me-2 ">Batal</a>
                    <button
                        class="close-menu btn btn-full btn-m shadow-l rounded-s bg-highlight text-uppercase font-900 ">Perbarui
                        Password</button>
                </div>
            </div>
        </form>
    </div>
@endsection
