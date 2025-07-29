@extends('layouts.mobile.guest-mobile')

@section('title', 'Ubah Pass')
@section('mobile-content')

    @include('components.mobile.toolbar', ['title' => 'Daftar', 'backDestination' => url('/')])

    <div class="card card-style">
        <form action="{{ route('reset-password-post') }}" method="post">
            @csrf

            <div class="content mb-0 mt-1">
                <div class="input-style no-borders has-icon validate-field mt-2">
                    <i class="fas fa-phone"></i>
                    <input type="phone" class="form-control validate-email" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Telepon / NIK">
                    <label for="phone" class="color-blue-dark font-10 mt-1">Telepon / NIK</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                @if ($errors->has('phone'))
                    <span class="text-danger"><small>{{ $errors->first('phone') }}</small></span>
                @endif

                <div class="input-style no-borders has-icon validate-field mt-2">
                    <i class="fa fa-lock"></i>
                    <input type="password" class="form-control validate-password" id="password" name="password"
                        placeholder="Password">
                    <label for="password" class="color-blue-dark font-10 mt-1">Password</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger"><small>{{ $errors->first('password') }}</small></span>
                @endif
                <div class="input-style no-borders has-icon validate-field mt-2">
                    <i class="fa fa-lock"></i>
                    <input type="password" class="form-control validate-password" id="password_confirmation"
                        name="password_confirmation" placeholder="Konfirmasi Password">
                    <label for="password_confirmation" class="color-blue-dark font-10 mt-1">Konfirmasi Password</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-sm btn-full rounded-sm shadow-l bg-highlight text-uppercase font-700 ">
                        Simpan
                    </button>
                </div>


                <div class="divider mt-4"></div>

                <div class="d-flex">
                    <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a href="{{ route('login') }}"
                            class="color-theme">Sudah punya akun?</a></div>
                </div>


            </div>
        </form>
    </div>
@endsection
