<!--begin::Modal - Create App-->
<div class="modal fade" id="modal-create-travel-account" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-700px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2>Buat Akun Travel</h2>
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
        <form class="form" action="{{ route('admin.tenant.store') }}" method="post">
          @csrf
          <!--begin::Modal body-->
          <div class="modal-body py-1 px-lg-17">
            <!--begin::Scroll-->
            <div class="scroll-y me-n7 pe-7" id="modal_create_activity" data-kt-scroll="true"
                 data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                 data-kt-scroll-offset="300px">

              @include('pages.web.tenant.modals._input-tenant')

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
