@extends('layouts.auth')

@section('content')
  <div class="px-20 px-md-10 pt-10">
    <div class="mb-10 text-center">
      <h3 class="text-dark">Masuk Menggunakan Akun Anda</h3>
    </div>
    {{-- @include('flash::message') --}}
    <form name="form" method="post" class="form w-100" id="kt_sign_in_form">
      @csrf
      {{-- @if(in_array(app()->environment(), ['local', 'demo', 'staging']))
        <div class="fv-row mb-10  {{ ! $errors->has('school_key') ?:'has-danger' }}">
          <label class="form-label fs-6 fw-bolder text-dark">Sekolah</label>
          <select id="search-school" name="school_key" class="form-select md-input p-l-1"></select>
          {!! $errors->first('school_key','<div class="small text-danger">:message</div>') !!}

        </div>
      @else
        <div class="fv-row mb-10  {{ ! $errors->has('school_key') ?:'has-danger' }}">
          <label class="form-label fs-6 fw-bolder text-dark">Sekolah</label>
          <input placeholder="Kode Sekolah (BCN)" type="text" class="md-input p-l-1" name="school_key" value="{{ old('school_key') }}" autocomplete="new-password">
          {!! $errors->first('school_key','<div class="small text-danger">:message</div>') !!}
        </div>
      @endif --}}

      <div class="fv-row mb-10 {{ !$errors->has('username') ?:' has-danger' }}">
        <label class="form-label fs-6 fw-bolder text-dark">User-ID</label>
        <input class="form-control form-control-lg form-control-solid" type="text" name="username" tabindex="1" autocomplete="new-password" value="{{ old('username') }}" placeholder="Username/Email" required autofocus>
      </div>

      <div class="fv-row mb-10 {{ !$errors->has('password') ?:' has-danger' }}">
        <div class="d-flex flex-stack mb-2">
          <label class="form-label fs-6 fw-bolder text-dark">Password</label>
          @if (Route::has('auth.password.forgot'))
            <a href="{{ route('auth.password.forgot') }}" class="link-primary fs-6 fw-bolder">
              {{ __('Forgot Password ?') }}
            </a>
          @endif
        </div>
        <input class="form-control form-control-lg form-control-solid" type="password" name="password" tabindex="2" autocomplete="new-password" placeholder="Password" required>
      </div>

      {{--<div class="fv-row mb-10">
        <label class="form-check form-check-custom form-check-solid">
          <input class="form-check-input" type="checkbox" name="remember" value="1" {{ old('remember', 0) ? 'checked' : '' }}>
          <span class="form-check-label fw-bold text-gray-700 fs-6">{{ __('Remember me') }}</span>
        </label>
      </div>--}}

      <div class="text-center">
        <!--begin::Submit button-->
        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" tabindex="3">
          <x-label-indicator>{{ __('Sign In') }}</x-label-indicator>
        </button>
        @if(config('classid.authentication.public_registration'))
          <div>Belum Punya Akun? <a href="{{ route('auth.register') }}" class="text-primary _600">@lang('Register')</a></div>
        @endif
      </div>
    </form>
  </div>

  @if(config('classid.authentication.enable'))
    <div class="p-v-lg text-center w-full">
      <a href="{{ route('auth.oauth.authorize') }}" class="btn btn-lg white">
        <span class="pull-left m-r-sm">
          <img src="{{ asset('images/logo.png') }}" alt="." class="w-48">
        </span>
        <span class="clear text-left l-h-1x">
          <span class="text-muted text-xs">Login with</span>
          <b class="text-md block m-b-xs">class.id</b>
        </span>
      </a>
    </div>
  @endif
@endsection
