 <!--begin::Step 1-->
 <div class="current" data-kt-stepper-element="content">
     <div class="w-100">
         @csrf
         <!--begin::Input group-->
         <div class="fv-row mb-10">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                 <span class="required">Nama Destinasi</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Nama destinasi"></i>
             </label>
             <!--end::Label-->
             <!--begin::Input-->
             <input type="text" class="form-control form-control-lg form-control-solid" name="name"
                 placeholder="Nama Destinasi" value="{{ old('name', isset($destination) ? $destination->name : null) }}" />
             <!--end::Input-->
         </div>
         <!--end::Input group-->

         <!--begin::Input group-->
         <div class="fv-row mb-10">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                 <span class="required">Waktu Jelajah (Menit)</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                     title="Tambahkan durasi waktu jelajah di tempat tujuan"></i>
             </label>
             <!--end::Label-->
             <!--begin::Input-->
             <input type="number" class="form-control form-control-lg form-control-solid" name="roaming_in_destination"
                 placeholder="Waktu Jelajah (Menit)" value="{{ old('roaming_in_destination', isset($destination) ? $destination->roaming_in_destination : 30) }}" />
             <!--end::Input-->
         </div>
         <!--end::Input group-->

         <!--begin::Input group-->
         <div class="fv-row mb-10">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                 <span class="required">Lokasi</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Tambahkan lokasi"></i>
             </label>
             <!--end::Label-->
             <!--begin::Input-->
             <textarea name="address" class="form-control form-control-lg form-control-solid" rows="3">{{ old('name', isset($destination) ? $destination->myAddress?->address : '') }}</textarea>
             <!--end::Input-->
         </div>
         <!--end::Input group-->
     </div>
 </div>
 <!--end::Step 1-->
 <!--begin::Step 2-->
 <div data-kt-stepper-element="content">
     <div class="w-100">
         <!--begin::Input group-->
         <div class="fv-row">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-4" for="photo_collection">
                 <span class="required">Upload Koleksi Photo</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                     title="Specify your apps framework"></i>
             </label>
             <!--end::Label-->

             <div class="mb-3">
                 <input class="form-control" type="file" id="photo_collection" name="photo_collection[]" multiple>
             </div>

             <!--begin::Hint-->
             <span class="form-text fs-6 text-muted">Max image size is 3MB per image,
                 .PNG/.JPG !</span>
             <!--end::Hint-->
         </div>
         <!--end::Input group-->
     </div>
 </div>
 <!--end::Step 2-->
 <!--begin::Step 3-->
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
 <!--end::Step 3-->
