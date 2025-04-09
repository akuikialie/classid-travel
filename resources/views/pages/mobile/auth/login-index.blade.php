@extends('layouts.mobile.guest-mobile')

@section('title', 'Masuk')

@section('mobile-content')

    @include('components.mobile.toolbar', ['title' => 'Masuk', 'backDestination' => url('/')])

    <div class="card card-style">
        <div class="content mt-2 mb-0">
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-style no-borders has-icon validate-field mb-4">
                    <i class="fa fa-user"></i>
                    <input type="text" class="form-control" name="login" id="login"
                        placeholder="Username / Email / Phone / NIK" value="{{ old('login') }}">
                    <label for="login" class="color-blue-dark font-10 mt-1">Username / Email / Phone / NIK</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    {{-- <i class="fa fa-check disabled valid color-green-dark"></i> --}}
                    <em>(required)</em>
                </div>
                @if ($errors->has('login'))
                    <span class="text-danger"><small>{{ $errors->first('login') }}</small></span>
                @endif

                <div class="input-style no-borders has-icon validate-field mb-4">
                    <i class="fa fa-lock"></i>
                    <input type="password" class="form-control validate-password" name="password" placeholder="Password">
                    <label for="form3a" class="color-blue-dark font-10 mt-1">Password</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger"><small>{{ $errors->first('password') }}</small></span>
                @endif


                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn mt-2 mb-2 btn-full bg-highlight rounded-sm text-uppercase font-900"  style="width: 80%">
                    Masuk
                  </button>

                </div>

            </form>

            <div class="divider"></div>

            <div class="d-flex">
{{--                <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-start"><a href="{{ route('register') }}"--}}
{{--                        class="color-theme">Buat Akun</a></div>--}}
                <div class="w-50 font-11 pb-2 color-theme opacity-60 pb-3 text-end"><a href="system-forgot-1.html"
                        class="color-theme">Lupa Password</a></div>
            </div>
        </div>

    </div>
@endsection
