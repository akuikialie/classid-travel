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
  <form class="form" action="{{ route('tenant.update', $tenant->hash) }}" method="post" enctype="multipart/form-data">
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
          <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('/metronic8/demo1/assets/media/svg/avatars/blank.svg')">
            <!--begin::Preview existing avatar-->
            <div class="image-input-wrapper w-125px h-125px bgi-position-center" style="background-size: 75%; background-image: url('/metronic8/demo1/assets/media/svg/brand-logos/volicity-9.svg')"></div>
            <!--end::Preview existing avatar-->
            <!--begin::Label-->
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
              <i class="bi bi-pencil-fill fs-7"></i>
              <!--begin::Inputs-->
              <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
              <input type="hidden" name="avatar_remove" />
              <!--end::Inputs-->
            </label>
            <!--end::Label-->
            <!--begin::Cancel-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
																<i class="bi bi-x fs-2"></i>
															</span>
            <!--end::Cancel-->
            <!--begin::Remove-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
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
          <input type="text" class="form-control form-control-solid" name="name" value="{{ $tenant->name }}" />
        </div>
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row mb-8">
        <!--begin::Col-->
        <div class="col-xl-3">
          <div class="fs-6 fw-semibold mt-2 mb-3">Slug</div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-9 fv-row">
          <input type="text" class="form-control form-control-solid" name="slug" value="{{ $tenant->slug }}" />
        </div>
      </div>
      <!--end::Row-->

      <!--begin::Row-->
      <div class="row mb-8">
        <!--begin::Col-->
        <div class="col-xl-3">
          <div class="fs-6 fw-semibold mt-2 mb-3">App Domain</div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-9 fv-row">
          <input type="text" disabled class="form-control form-control-solid" value="{{ $tenant->app_domain }}" />
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
