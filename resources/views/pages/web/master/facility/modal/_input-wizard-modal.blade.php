 <!--begin::Step 1-->
 <div class="current" data-kt-stepper-element="content">
     <div class="w-100">
         @csrf
         <!--begin::Input group-->
         <div class="fv-row mb-10">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                 <span class="required">Nama Fasilitas</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Nama fasilitas"></i>
             </label>
             <!--end::Label-->
             <!--begin::Input-->
             <input type="text" class="form-control form-control-lg form-control-solid" name="name"
                 placeholder="Nama Fasilitas" value="{{ old('name', isset($facility) ? $facility->name : null) }}" />
             <!--end::Input-->
         </div>
         <!--end::Input group-->
         <!--begin::Input group-->
         <div class="fv-row">
             <!--begin::Label-->
             <label class="d-flex align-items-center fs-5 fw-semibold mb-4">
                 <span class="required">Jenis Fasilitas</span>
                 <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Jenis fasilitas"></i>
             </label>
             <!--end::Label-->
             <!--begin:Options-->
             <div class="fv-row">
                 <!--begin:Option-->

                 @forelse ($category_facilities as $categoryFacility)
                     <label class="d-flex flex-stack mb-5 cursor-pointer">
                         <!--begin:Label-->
                         <span class="d-flex align-items-center me-2">
                             <!--begin:Icon-->
                             <span class="symbol symbol-50px me-6">
                                 <span class="symbol-label bg-light-primary">
                                     <!--begin::Svg Icon | path: icons/duotune/maps/map004.svg-->
                                     <span class="svg-icon svg-icon-1 svg-icon-primary">
                                         <i class='{{ $categoryFacility['icon'] }}'></i>
                                     </span>
                                     <!--end::Svg Icon-->
                                 </span>
                             </span>
                             <!--end:Icon-->
                             <!--begin:Info-->
                             <span class="d-flex flex-column">
                                 <span class="fw-bold fs-6">{{ $categoryFacility['name'] }}</span>
                             </span>
                             <!--end:Info-->
                         </span>
                         <!--end:Label-->
                         <!--begin:Input-->
                         <span class="form-check form-check-custom form-check-solid">
                             <input class="form-check-input" type="radio" name="type"
                                 value="{{ $categoryFacility['name'] }}"
                                 {{isset($facility) &&  ($categoryFacility['name'] == $facility->type) ? 'checked' : '' }} />
                         </span>
                         <!--end:Input-->
                     </label>
                 @empty
                 @endforelse

                 <!--end::Option-->
             </div>
             <!--end:Options-->
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
