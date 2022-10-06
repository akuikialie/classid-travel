@extends('layouts.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Profile</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="images/pictures/20s.jpg"></div>
    </div>

    <div class="card card-style">
        <div class="d-flex content mb-1">
            <!-- left side of profile -->
            <div class="flex-grow-1 ">
                <h1 class="font-20 ">Winata Bayu Nur<i
                        class="fa fa-check-circle color-blue-dark float-end font-13 mt-2 me-3"></i>
                </h1>
                <p class="mb-2">
                    6281331307327
                </p>
                <p class="font-14 ">
                    <strong class="color-theme pe-1">3</strong>Tabungan
                    <strong class="color-theme ps-3 pe-1">0</strong>Jamaah
                </p>
            </div>
            <!-- right side of profile. increase image width to increase column size-->
            <img src="images/empty.png" data-src="images/avatars/4s.png" width="115"
                class="bg-highlight rounded-circle mt-3 shadow-xl preload-img">
        </div>
        <!-- follow buttons-->
        <div class="content">
            <div class="row mb-0">
                <div class="col-6">
                    <a href="{{ route('profile.edit', Auth::user()->id) }}"
                        class="btn btn-full btn-sm rounded-s text-uppercase font-900 bg-blue-dark">Edit
                        Profil</a>
                </div>
                <div class="col-6">
                    <a href="#"
                        class="btn btn-full btn-sm btn-border rounded-s text-uppercase font-900 color-highlight border-blue-dark">Invite
                        Jamaah</a>
                </div>
            </div>
        </div>
        <div class="divider mb-3 mt-1"></div>

    </div>

    <div class="card card-style">
        <div class="content">
            <h5 class="float-start font-16 font-600">Jamaah Terdaftar</h5>
            <a class="float-end font-12 color-highlight mt-n1" href="#">View All</a>
            <div class="clearfix"></div>
            <p class="pt-2">
                Daftar jamaah yang sudah berhasil anda daftarkan!.
            </p>
        </div>
        <div class="splide user-slider slider-no-arrows slider-no-dots" id="user-slider-1">
            <div class="splide__track">
                <div class="splide__list">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="splide__slide">
                            <div class="text-center">
                                <img src="images/avatars/{{ rand(1, 5) }}s.png" width="55" height="55"
                                    class="rounded-xl shadow-l gradient-blue">
                                <p>{{ fake()->name() }}</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content">
            <div class="list-group list-custom-small">
                <a href="#">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang Kami</span>
                    <i class="fa fa-arrow-right"></i>
                </a>
                <a href="#">
                    <i class="fas fa-question"></i>
                    <span>Bantuan</span>
                    <i class="fa fa-arrow-right"></i>
                </a>

                <a href="#" class="text-danger" data-menu="menu-confirm">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                    <i class="fa fa-arrow-right"></i>
                </a>

            </div>
        </div>
    </div>
@endsection

@section('external-mobile-content')
    <!---------------->
    <!---------------->
    <!--Menu Confirm-->
    <!---------------->
    <!---------------->
    <div id="menu-confirm" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="200"
        data-menu-effect="menu-over">
        <h2 class="text-center font-700 mt-3 pt-1">Keluar Akun?</h2>
        <p class="boxed-text-l">
            Apa anda yakin ingin keluar dari akun anda?
        </p>
        <div class="me-3 ms-3 ">
            <form action="{{ route('logout') }}" method="post" class="d-flex justify-content-end">
                @csrf
                <button
                    class="close-menu btn btn-sm btn-full button-s shadow-l rounded-s text-uppercase font-900 bg-red-dark ms-2">Keluar</button>
                <a href="#"
                    class="close-menu btn btn-full btn-sm btn-border rounded-s text-uppercase font-900 color-dark-light border-dark-dark ms-2">Batal</a>


            </form>
        </div>
    </div>
@endsection
