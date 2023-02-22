<!--begin::Card-->
<div class="card">
    <!--begin::Card header-->
    <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title fs-3 fw-bold">Change Theme</div>
        <!--end::Card title-->
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" action="{{ route('admin.tenant.change_theme') }}" method="post">
        @csrf
        <!--begin::Card body-->
        <div class="card-body p-9">
            <input type="hidden" name="tenant_id" value="{{ auth()->user()->tenant_id }}">

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Color sidebar logo</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-6 fv-row">
                    <input type="color" value="{{ $logoColor }}" class="form-control form-control-solid colorpicker" name="sidebar_color" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Color sidebar</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-6 fv-row">
                    <input type="color" value="{{ $sidebarColor }}" class="form-control form-control-solid colorpicker" name="logo_color" />
                </div>
            </div>
            <!--end::Row-->

            <!--begin::Row-->
            <div class="row mb-8">
                <!--begin::Col-->
                <div class="col-xl-3">
                    <div class="fs-6 fw-semibold mt-2 mb-3">Color Font</div>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-6 fv-row">
                    <input type="color" value="{{ $fontColor }}" class="form-control form-control-solid colorpicker" name="font_color" />
                </div>
            </div>
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
