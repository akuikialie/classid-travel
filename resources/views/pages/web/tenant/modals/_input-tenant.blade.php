<!--begin::Input group-->
<div class="mb-5 fv-row">
  <!--begin::Label-->
  <label class="required fs-5 fw-semibold mb-2">BCN</label>
  <!--end::Label-->
  <!--begin::Input-->
  <input type="number" class="form-control form-control-solid" placeholder="BCN" name="bcn"
         value="{{ old('bcn') }}"/>
  <!--end::Input-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="mb-5 fv-row">
  <!--begin::Label-->
  <label class="required fs-5 fw-semibold mb-2">Nama</label>
  <!--end::Label-->
  <!--begin::Input-->
  <input type="text" class="form-control form-control-solid" placeholder="Nama Travel" name="name" {{ ($edit_mode ?? false) === true ? 'disabled' : '' }}
         value="{{ old('name') }}"/>
  <!--end::Input-->
</div>
<!--end::Input group-->

<!--begin::Input group-->
<div class="mb-5 fv-row">
  <!--begin::Label-->
  <label class="required fs-5 fw-semibold mb-2">App Domain</label>
  <!--end::Label-->
  <!--begin::Input-->
  <input type="text" class="form-control form-control-solid" placeholder="App Domain" name="app_domain"
         value="{{ old('app_domain') }}"/>
  <!--end::Input-->
</div>
<!--end::Input group-->

@if(($edit_mode ?? false) === false)
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
@endif

