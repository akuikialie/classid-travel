 <!--begin::Step 1-->
 <div class="current" data-kt-stepper-element="content">
     <div class="w-100">
         @csrf
         <!--begin::Input group-->
         <div class="fv-row mb-10">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                 <span class="required">Tanggal Keberangkatan</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Tanggal keberangkatan"></i>
             </label>
             <!--end::Label-->
             <!--begin::Input-->
             <input type="text" class="form-control form-control-lg form-control-solid datepicker"
                 name="departure_date" placeholder="Tanggal Keberangkatan" value="{{ old('departure_date', isset($schedule) ? $schedule->departure_date : null ) }}" />
             <!--end::Input-->
         </div>
         <!--end::Input group-->
     </div>
 </div>
 <!--end::Step 1-->
 <!--begin::Step 2-->
 <div data-kt-stepper-element="content">
     <div class="w-100 text-center">
         <!--begin::Heading-->
         <h1 class="fw-bold text-dark mb-3">Semuanya telah selesai!</h1>
         <!--end::Heading-->
         <!--begin::Description-->
         <div class="text-muted fw-semibold fs-3">Klik submit untuk menyimpan data.
         </div>
         <!--end::Description-->
         <!--begin::Illustration-->
         <div class="text-center px-4 py-15">
             <img src="/metronic8/demo1/assets/media/illustrations/sketchy-1/9.png" alt=""
                 class="mw-100 mh-300px" />
         </div>
         <!--end::Illustration-->
     </div>
 </div>
 <!--end::Step 2-->
