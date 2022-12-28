<!--begin::Modal - Create Api Key-->
<div class="modal fade" id="modal_itinerary_activity" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2>Buat Aktifitas Baru</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Modal header-->
      <!--begin::Form-->
      <form class="form" id="form-create-itinerary" action="{{ route('admin.itinerary.store') }}" method="post">
        @csrf
        <!--begin::Modal body-->
        <div class="modal-body ">
          <!--begin::Scroll-->
          <div class="scroll-y me-n7 pe-7" id="modal_create_activity" data-kt-scroll="true"
               data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
               data-kt-scroll-offset="300px">

            @include('pages.web.master.itinerary.modal._input-tenant_activity')

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
          <button type="submit" class="btn btn-primary" id="create-itinerary" onclick="$('#form-create-itinerary').submit(); $('#create-itinerary').attr('disabled', 'disabled');">
            <span class="indicator-label">Submit</span>
          </button>
          <!--end::Button-->
        </div>r
        <!--end::Modal footer-->
      </form>
      <!--end::Form-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
<!--end::Modal - Create Api Key-->
