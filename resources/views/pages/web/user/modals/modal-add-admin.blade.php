<!--begin::Modal - Create App-->
<div class="modal fade" id="modal-add-admin" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-700px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2>Tambahkan Admin Baru</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Modal header-->
      <!--begin::Modal body-->
      <div class="modal-body py-lg-10 px-lg-10">
        <!--begin::Form-->
        <form class="form" action="{{ route('admin.user.store') }}" method="post">
          @csrf
          <!--begin::Modal body-->
          <div class="modal-body py-1 px-lg-17">
            <!--begin::Scroll-->
            <div class="scroll-y me-n7 pe-7" id="modal_create_activity" data-kt-scroll="true"
                 data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                 data-kt-scroll-offset="300px">

              <!--begin::Input group-->
              <div class="mb-5 fv-row">
                <!--begin::Label-->
                <label class="required fs-5 fw-semibold mb-2">Set Login via Phone</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="number" class="form-control form-control-solid" placeholder="Phone" name="phone"
                       value="{{ old('login') }}"/>
                <!--end::Input-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="mb-10">
                <label class="form-label fs-6 fw-semibold">Role:</label>
                <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                        data-placeholder="Select option" data-allow-clear="true" data-kt-user-modal-select="role"
                        data-hide-search="true" data-dropdown-parent="#modal-add-admin" name="role">
                  <option></option>
                  @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                  @endforeach
                </select>
              </div>
              <!--end::Input group-->

            </div>
            <!--end::Scroll-->
          </div>
          <!--end::Modal body-->
          <!--begin::Modal footer-->
          <div class="modal-footer flex-center">
            <!--begin::Button-->
            <button type="reset" class="btn btn-light me-3">Batal</button>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="submit" class="btn btn-primary">
              <span class="indicator-label">Submit</span>
            </button>
            <!--end::Button-->
          </div>
          <!--end::Modal footer-->
        </form>
        <!--end::Form-->
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
<!--end::Modal - Create App-->
