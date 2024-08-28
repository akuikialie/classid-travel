<!--begin::details View-->
<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <!--begin::Card header-->
    <div class="card-header cursor-pointer">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details</h3>
        </div>
        <!--end::Card title-->

        <!--begin::Action-->
        <a href="{{ route('admin.tenant.showProfile', 'setting') }}" class="btn btn-sm btn-primary align-self-center">Edit
            Profile</a>
        <!--end::Action-->
    </div>
    <!--begin::Card header-->

    <!--begin::Card body-->
    <div class="card-body p-9">
        <!--begin::Row-->
        <div class="row mb-7">
            <!--begin::Label-->
            <label class="col-lg-4 fw-semibold text-muted">Name</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">{{ $tenant->name }}</span>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Input group-->
        <div class="row mb-7">
            <!--begin::Label-->
            <label class="col-lg-4 fw-semibold text-muted">Slug</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8 fv-row">
                <span class="fw-semibold text-gray-800 fs-6">{{ $tenant->slug }}</span>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="row mb-7">
            <!--begin::Label-->
            <label class="col-lg-4 fw-semibold text-muted">App Domain</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <a href="#"
                    class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $tenant->app_domain }}</a>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="row mb-7">
            <!--begin::Label-->
            <label class="col-lg-4 fw-semibold text-muted">BCN</label>
            <!--end::Label-->

            <!--begin::Col-->
            <div class="col-lg-8">
                <a href="#" class="fw-semibold fs-6 text-gray-800 text-hover-primary">{{ $tenant->bcn }}</a>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
    </div>
    <!--end::Card body-->
</div>
<!--end::details View-->
