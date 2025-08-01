@extends('pages.web.jamaah-profile.show')

@section('fragment-content')
    <!--begin::details View-->
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <div class="card-header cursor-pointer">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profil Jamaah</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                    Nama Lengkap
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->name ?? '-' }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                   Email
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->email ?? '-' }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                    Terakhir Aktif
                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ !empty($user->last_login_at) ? carbon($user->last_login_at)->diffForHumans() : null }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::details View-->
    <!--begin::Reset Password-->
    <div class="card mb-5" id="kt_reset_password">
        <!--begin::Card header-->
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold">Reset Password Jamaah</h3>
            </div>
        </div>
        <!--end::Card header-->

        <!--begin::Form-->
        <form method="POST" action="{{ route('admin.jamaah.updatePassword', $user->hash) }}">
            @csrf
            @method('PUT')

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!--begin::Input group-->

                <div class="mb-7">
                    <label class="required form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-7">
                    <label class="required form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <!--end::Input group-->

                <!--begin::Actions-->
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Reset Password-->

@endsection
