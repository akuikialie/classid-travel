@extends('layouts.mobile.app-mobile')

@section('mobile-content')
    <div class="page-title page-title-small">
        <h2><a href="#"></a>Profile</h2>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="{{ asset('mobile/images/pictures/20s.jpg') }}"></div>
    </div>

    <div class="card card-style">
        <div class="d-flex content mb-1">
            <!-- left side of profile -->
            <div class="flex-grow-1 ">
                <h1 class="font-20 ">{{ isset($user) ? $user?->name : 'unknown' }}<i
                        class="fa fa-check-circle color-blue-dark float-end font-13 mt-2 me-3"></i>
                </h1>
                <p class="mb-2">
                    {{ isset($user) ? $user->phone : '-' }}
                </p>
                <p class="font-14 ">
                    <strong class="color-theme pe-1">{{ isset($total_tabungan) ? $total_tabungan : 1 }}</strong>Tabungan
                    <strong
                        class="color-theme ps-3 pe-1">{{ isset($user) ? $user->people_inviteds_count : 0 }}</strong>Jamaah
                </p>
            </div>
            <!-- right side of profile. increase image width to increase column size-->
            <img src="{{ asset('mobile/images/empty.png') }}" data-src="{{ asset('mobile/images/avatars/4s.png') }}"
                width="115" class="bg-highlight rounded-circle mt-3 shadow-xl preload-img">
        </div>
        <!-- follow buttons-->
        <div class="content">
            <div class="row mb-0">
                <div class="col-6">
                    <a href="{{ route('profile.edit', Auth::user()->id) }}"
                        class="btn btn-full btn-sm rounded-s text-uppercase font-900 bg-highlight">Edit
                        Profil</a>
                </div>
                <div class="col-6">
                    <a href="#" data-menu="menu-share-invitation"
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
                    @forelse ($people_invited as $invited)
                        <div class="{{ $people_invited->count() > 2 ? 'splide__slide' : '' }}">
                            <div class="text-center">
                                <img src="{{ asset('mobile/images/avatars/4s.png') }}" width="55" height="55"
                                    class="rounded-xl shadow-l gradient-blue">
                                <p>{{ $invited?->user?->name }}</p>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content">
            <div class="list-group list-custom-small">
                {{-- <a href="#">
                    <i class="fas fa-info-circle"></i>
                    <span>Tentang Kami</span>
                    <i class="fa fa-arrow-right"></i>
                </a>
                <a href="#">
                    <i class="fas fa-question"></i>
                    <span>Bantuan</span>
                    <i class="fa fa-arrow-right"></i>
                </a> --}}

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
    <!--Menu Share Inviation-->
    <!---------------->
    <!---------------->
    <div id="menu-share-invitation" class="menu menu-box-modal rounded-m" data-menu-height="330" data-menu-width="310">
        <form {{-- action="{{ route('invite.store') }}" --}} method="post" id="form-invite-jamaah">
            @csrf
            <div class="me-3 ms-3 mt-3">
                <h1 class="font-700 mb-0">Invite Jamaah</h1>
                <p class="font-11  mt-n1 mb-0">
                    Undang jamaah lain menggunakan kode referal kamu!.
                </p>

                <h6 class="font-13 ps-1 font-500 mb-1 mt-3">Pilih Paket</h6>
                <div class="input-style input-style-always-active has-borders no-icon my-4">
                    <select id="pilih-package" name="package_id">
                        @forelse ($planPackages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @empty
                            <option value="" selected disabled>Belum Punya Paket</option>
                        @endforelse
                    </select>
                    <span><i class="fa fa-chevron-down"></i></span>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <i class="fa fa-check disabled invalid color-red-dark"></i>
                    <em></em>
                </div>

                <div class="input-style no-borders has-icon validate-field mb-4 mt-3">
                    <input type="text" class="form-control validate-password" id="link"
                        placeholder="Referal Link">
                    <label for="link" class="color-highlight font-11 font-500 mt-1">Referal Link</label>
                    <em>(required)</em>
                </div>

                @if ($errors->has('package_id'))
                    <span class="text-danger"><small>{{ $errors->first('package_id') }}</small></span>
                @endif
                <div class="d-flex justify-content-center">
                    <button id="btnShareLink" type="button"
                        class="btn btn-full btn-sm shadow-l rounded-s text-uppercase font-900 bg-highlight mt-4">Bagikan</button>
                </div>

            </div>
        </form>
    </div>

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

@section('page-scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $("#btnShareLink").click(function(e) {
                e.preventDefault();
                $.ajax({
                    data: $("#form-invite-jamaah").serialize(),
                    url: "{{ route('invite.store') }}",
                    type: "POST",
                    dataType: "json",
                    success: function(data) {

                        $('#link').val(data.link);

                        copyToClipboard(data.link);

                    },
                    error: function(error) {
                        console.log(error);
                        // Swal.fire({
                        //     icon: error.responseJSON.icon,
                        //     title: error.responseJSON.title,
                        //     text: error.responseJSON.message,
                        //     footer:
                        //         '<a href="">Error Code: ' +
                        //         error.status +
                        //         ", " +
                        //         error.statusText +
                        //         "...</a>",
                        // });
                    },
                });
            });

            function copyToClipboard(text) {
                const elem = document.createElement('textarea');
                elem.value = text;
                document.body.appendChild(elem);
                elem.select();
                document.execCommand('copy');
                document.body.removeChild(elem);
            }


        });
    </script>
@endsection
