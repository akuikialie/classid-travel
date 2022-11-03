<!--begin::Input group-->
<div class="mb-5 fv-row">
  <!--begin::Label-->
  <label class="required fs-5 fw-semibold mb-2">Aktifitas</label>
  <!--end::Label-->
  <!--begin::Input-->
  <input type="text" class="form-control form-control-solid" placeholder="Aktifitas" name="activity"
  value="{{ old('activity', $activity->activity ?? null) }}"/>
  <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="d-flex flex-column mb-5 fv-row">
  <!--begin::Label-->
  <label class="fs-5 fw-semibold mb-2">Detail Aktifitas</label>
  <!--end::Label-->
  <!--begin::Input-->
  <textarea class="form-control form-control-solid" rows="3" name="detail"
            placeholder="Jelaskan tentang aktifitas ini.">{{ old('detail', $activity->detail ?? null) }}</textarea>
  <!--end::Input-->
</div>
<!--end::Input group-->
