<!--begin::Modal - Create Api Key-->
<div class="modal fade" id="modal_itinerary_activity" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2>Edit Aktifitas</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Modal header-->
      <!--begin::Form-->
      <form class="form" action="{{ route('admin.itinerary.update', $activity->hash) }}" method="post">
        @csrf
        @method('PUT')
        <!--begin::Modal body-->
        <div class="modal-body py-10 px-lg-17">
          <!--begin::Scroll-->
          <div class="scroll-y me-n7 pe-7" id="modal_create_activity" data-kt-scroll="true"
               data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
               data-kt-scroll-offset="300px">

            {{--<!--begin::Notice-->
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6">
              <!--begin::Icon-->
              <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
              <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
											<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)"
                            fill="currentColor"/>
											<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)"
                            fill="currentColor"/>
										</svg>
									</span>
              <!--end::Svg Icon-->
              <!--end::Icon-->
              <!--begin::Wrapper-->
              <div class="d-flex flex-stack flex-grow-1">
                <!--begin::Content-->
                <div class="fw-semibold">
                  <h4 class="text-gray-900 fw-bold">Catatan!</h4>
                  <div class="fs-6 text-gray-700">Aktifitas ini akan digunakan seb
                    <a href="#">Affiliate Income</a></div>
                </div>
                <!--end::Content-->
              </div>
              <!--end::Wrapper-->
            </div>
            <!--end::Notice-->--}}

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
          <button type="submit" class="btn btn-primary">
            <span class="indicator-label">Submit</span>
          </button>
          <!--end::Button-->
        </div>
        <!--end::Modal footer-->
      </form>
      <!--end::Form-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
<!--end::Modal - Create Api Key-->
