<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title fs-3 fw-bold">Project Settings</div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" action="{{ route('admin.user.update', ['user_hash' => $user->hash]) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-5">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Logo</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <!--begin::Image input-->
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                        style="background-image: url({{ $avatar?? asset('web/images/avatar.png') }})">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-125px h-125px bgi-position-center"
                            style="background-size: 75%; background-image: url({{ $avatar?? asset('web/images/avatar.png') }})">
                        </div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="avatar_remove" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar"
                            style="<?php if ($avatar == null) {
                                echo 'display: none';
                            } ?>">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Hint-->
                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                    <!--end::Hint-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Name</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-9 fv-row">
                    <input type="text" class="form-control form-control-solid" name="name"
                        value="{{ $user->name }}" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Username</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-9 fv-row">
                    <input type="text" class="form-control form-control-solid" name="username"
                        value="{{ $user->username }}" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Phone</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-9 fv-row">
                    <input type="text" name="phone" class="form-control form-control-solid"
                        value="{{ $user->phone }}" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Timezone</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-9 fv-row">
                    <input type="text" disabled class="form-control form-control-solid"
                        value="{{ $user->timezone }}" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            {{-- <div class="row mb-8">
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">password</div>
                </div>
                <div class="col-xl-9 fv-row">
                    <input type="text" name="password" class="form-control form-control-solid"
                        value="{{ $user->password }}" />
                </div>
            </div> --}}
            <!--end::Row-->

        </div>
        <!--end::Card body-->
        <!--begin::Card footer-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
            <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
        </div>
        <!--end::Card footer-->
    </form>
    <!--end:Form-->
</div>
<!--end::Card-->
<br>
<!--begin::Sign-in Method-->
<div class="card  mb-5 mb-xl-10">

    <!--begin::Content-->
    <div id="kt_account_settings_signin_method" class="collapse show">
        <!--begin::Card body-->
        <div class="card-body border-top p-9">

            <!--begin::Password-->
            <div class="d-flex flex-wrap align-items-center mb-10">
                <!--begin::Label-->
                <div id="kt_signin_password">
                    <div class="fs-6 fw-bold mb-1">Password</div>
                    <div class="fw-semibold text-gray-600">************</div>
                </div>
                <!--end::Label-->

                <!--begin::Edit-->
                <div id="kt_signin_password_edit" class="flex-row-fluid d-none">
                    <!--begin::Form-->
                    <form id="kt_signin_change_password" class="form" novalidate="novalidate">
                        <div class="row mb-1">
                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="currentpassword" class="form-label fs-6 fw-bold mb-3">Current
                                        Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="currentpassword" id="currentpassword" />
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="newpassword" class="form-label fs-6 fw-bold mb-3">New Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="newpassword" id="newpassword" />
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="fv-row mb-0">
                                    <label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">Confirm New
                                        Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="confirmpassword" id="confirmpassword" />
                                </div>
                            </div>
                        </div>

                        <div class="form-text mb-5">Password must be at least 8 character and contain symbols</div>

                        <div class="d-flex">
                            <button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6">Update
                                Password</button>
                            <button id="kt_password_cancel" type="button"
                                class="btn btn-color-gray-400 btn-active-light-primary px-6">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Edit-->

                <!--begin::Action-->
                <div id="kt_signin_password_button" class="ms-auto">
                    <button class="btn btn-light btn-active-light-primary">Reset Password</button>
                </div>
                <!--end::Action-->
            </div>
            <!--end::Password-->

        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->
</div>
<!--end::Sign-in Method-->
