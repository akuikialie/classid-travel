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


<!--begin::Input group-->
<div class="mb-5 fv-row">
    <!--begin::Label-->
    <label class="required fs-5 fw-semibold mb-2">Fee Admin</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input type="number" class="form-control form-control-solid" placeholder="Fee Admin" name="fee_admin"
           value="{{ old('fee_admin') }}"/>
    <!--end::Input-->
</div>
<!--end::Input group-->

<div class="separator separator-content fw-bold mt-10 mb-5">Kofigurasi</div>

<div class="mb-5 row row-cols-1 g-5">
  <div class="col">
    <label class="required fs-5 fw-semibold mb-2">Warna Utama</label>
    <input type="text" class="form-control color" name="options[style][bg_color]" value="{{ request('options.style.bg_color') }}" placeholder="Warna Utama" style="background-color:{{ request('options.style.bg_color') }}" />
  </div>
  <div class="col">
    <label class="required fs-5 fw-semibold mb-2">Kebalikan Warna Utama</label>
    <input type="text" class="form-control color" name="options[style][bg_inverse]" value="{{ request('options.style.bg_inverse') }}" placeholder="Kebalikan Warna Utama" style="background-color:{{ request('options.style.bg_inverse') }}" />
  </div>
  <div class="col">
    <label class="required fs-5 fw-semibold mb-2">Warna Teks</label>
    <input type="text" class="form-control color" name="options[style][color]" value="{{ request('options.style.color') }}" placeholder="Kebalikan Text" style="background-color:{{ request('options.style.color') }}" />
  </div>
</div>
