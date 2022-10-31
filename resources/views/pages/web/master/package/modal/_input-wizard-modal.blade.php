-<!--begin::Step 1-->
<div class="current" data-kt-stepper-element="content">
  <div class="w-100" style="overflow:hidden; overflow-y:scroll; height:320px;">
    @csrf
    <!--begin::Input group-->
    <div class="fv-row mb-10">
      <div class="row">
        @forelse ($plans as $plan)
          <div class="col">
            <!--begin:Input-->
            <span class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="radio" name="type" id="{{ $plan->key }}"
                                   value="{{ $plan->id }}" checked/>

              <!--begin::Label-->
                            <label for="{{ $plan->key }}" class="d-flex align-items-center fs-5 fw-semibold ms-2">
                                <span class="required">Paket {{ $plan->value }}</span>
                            </label>
              <!--end::Label-->
                        </span>
          </div>
        @empty
        @endforelse


      </div>

      <!--end:Input-->
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    {{-- <div class="fv-row ">
    <div class="row">
        <div class="col">
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label required" for="year_started">Tahun
                    keberangkatan</label>
                <!--end::Label-->

                <!--begin::Flatpickr-->
                <div class="input-group w-250px">
                    <input name="departure_year"
                        class="form-control form-control-solid rounded rounded-end-0 datepicker-year"
                        placeholder="Pilih tahun keberangkatan" />
                </div>
                <!--end::Flatpickr-->
            </div>

        </div>

        <div class="col">
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label required">Kuartal</label>
                <!--end::Label-->
                <!--begin::Input-->
                <select name="kuartal"
                    class="form-select form-select-lg form-select-solid"
                    data-control="select2" data-placeholder="Select..."
                    data-allow-clear="true" data-hide-search="true">
                    @forelse ($kuartals as $kuartal)
                        <option value="{{ $kuartal->value }}">
                            {{ $kuartal->label() }}</option>
                    @empty
                    @endforelse
                </select>
                <!--end::Input-->
            </div>
        </div>
    </div>

    <!--end:Input-->
</div> --}}
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="fv-row mb-10">
      <!--begin::Label-->
      <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
        <span class="required">Lama Perjalanan (Hari) </span>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
           title="Lama perjalanan (Hari)"></i>
      </label>
      <!--end::Label-->
      <!--begin::Input-->
      <input type="number" class="form-control form-control-lg form-control-solid" name="long_days"
             placeholder="Lama Perjalanan"
             value="{{ old('long_days', isset($package) ? $package->long_days : null) }}"/>
      <!--end::Input-->
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="fv-row mb-10">
      <!--begin::Label-->
      <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
        <span class="required">Nama Paket</span>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Nama paket"></i>
      </label>
      <!--end::Label-->
      <!--begin::Input-->
      <input type="text" class="form-control form-control-lg form-control-solid" name="name"
             placeholder="Nama Paket" value="{{ old('name', isset($package) ? $package->name : null) }}"/>
      <!--end::Input-->
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="fv-row mb-10">
      <!--begin::Label-->
      <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
        <span>Deskripsi</span>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
           title="Tambahkan deskripsi paket"></i>
      </label>
      <!--end::Label-->
      <!--begin::Input-->
      <textarea name="description" class="form-control form-control-lg form-control-solid"
                rows="3">{{ old('descriptio', isset($package) ? $package->description : null) }}</textarea>
      <!--end::Input-->
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="fv-row mb-10">
      <!--begin::Label-->
      <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
        <span class="required">Biaya</span>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
           title="Tambahkan biaya paket"></i>
      </label>
      <!--end::Label-->
      <!--begin::Input-->
      <input type="number" class="form-control form-control-lg form-control-solid" name="amount"
             placeholder="Biaya Paket" value="{{ old('amount', isset($package) ? $package->amount : null) }}"/>
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
      <label class="d-flex align-items-center fs-5 fw-semibold mb-4" for="thumbnail">
        <span class="required">Upload Thumbnail</span>
        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
           title="Specify your apps framework"></i>
      </label>
      <!--end::Label-->

      <div class="mb-3">
        <input class="form-control" type="file" id="thumbnail" name="thumbnail">
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
    <!--begin::List Widget 3-->
    <div class="card">
      <!--begin::Header-->
      <div class="card-header">
        <h3 class="card-title fw-bold text-dark">Pilih Fasilitas</h3>
      </div>
      <!--end::Header-->
      <!--begin::Body-->
      <div class="card-body">
        <div style="overflow:hidden; overflow-y:scroll; height:320px;">
          @forelse ($facilities as $facility)
            @php
              switch ($facility->type) {
                  case 'Perjalanan':
                      $color = 'primary';
                      break;

                  case 'Penginapan':
                      $color = 'success';
                      break;

                  case 'Makan':
                      $color = 'warning';
                      break;

                  default:
                      $color = 'primary';
                      break;
              }
            @endphp
              <!--begin::Item-->
            <div class="d-flex align-items-center mb-2">
              <!--begin::Bullet-->
              <span class="bullet bullet-vertical h-40px bg-success"></span>
              <!--end::Bullet-->
              <!--begin::Checkbox-->
              <div class="form-check form-check-custom form-check-solid mx-5">
                <input class="form-check-input" type="checkbox" id="facilities-{{ $facility->id }}"
                       name="facilities[{{ $facility->id }}]"
                  {{ in_array($facility->id, (isset($package->myFacilities) ? collect($package->myFacilities)->pluck('id')->toArray() : []) )? 'checked': '' }} />
              </div>
              <!--end::Checkbox-->
              <!--begin::Description-->
              <div class="flex-grow-1" for="facilities">
                <label for="facilities-{{ $facility->id }}"
                       class="text-gray-800 text-hover-primary fw-bold fs-6">{{ $facility->name }}</label>
                <span class="text-muted fw-semibold d-block">{{-- --}}</span>
              </div>
              <!--end::Description-->
              <span
                class="badge badge-light-{{ $color }} fs-8 fw-bold">{{ $facility->type }}</span>
            </div>
            <!--end:Item-->
          @empty
          @endforelse
        </div>
      </div>
      <!--end::Body-->
    </div>
    <!--end:List Widget 3-->
  </div>
</div>
<!--end::Step 3-->

<!--begin::Step 4-->
<div data-kt-stepper-element="content">
  <div class="w-100 text-center">
    <!--begin::List Widget 3-->
    <div class="card">
      <!--begin::Header-->
      <div class="card-header">
        <h3 class="card-title fw-bold text-dark">Pilih Destinasi</h3>
      </div>
      <!--end::Header-->
      <!--begin::Body-->
      <div class="card-body">
        <div style="overflow:hidden; overflow-y:scroll; height:320px;">
          @forelse ($destinations as $destination)
            @php
              $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info'];
              $color = $colors[rand(0, 5)];
            @endphp
              <!--begin::Item-->
            <div class="d-flex align-items-center mb-2">
              <!--begin::Bullet-->
              <span class="bullet bullet-vertical h-40px bg-success"></span>
              <!--end::Bullet-->
              <!--begin::Checkbox-->
              <div class="form-check form-check-custom form-check-solid mx-5">
                <input class="form-check-input" type="checkbox"
                       id="destination-{{ $destination->id }}"
                       name="destinations[{{ $destination->id }}]"
                  {{ in_array($destination->id,(isset($package->destinations) ? collect($package->destinations)->pluck('id')->toArray() : []))? 'checked': '' }} />
              </div>
              <!--end::Checkbox-->
              <!--begin::Description-->
              <div class="flex-grow-1">
                <label for="destination-{{ $destination->id }}"
                       class="text-gray-800 text-hover-primary fw-bold fs-6">{{ $destination->name }}</label>
                <span
                  class="text-muted fw-semibold d-block">{{ $destination?->myAddress?->address }}</span>
              </div>
              <!--end::Description-->
              <span
                class="badge badge-light-{{ $color }} fs-8 fw-bold">{{ $destination->roaming_in_destination }}
                                Menit</span>
            </div>
            <!--end:Item-->
          @empty
          @endforelse
        </div>
      </div>
      <!--end::Body-->
    </div>
    <!--end:List Widget 3-->
  </div>
</div>
<!--end::Step 4-->

<!--begin::Step 5-->
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
           class="mw-100 mh-300px"/>
    </div>
    <!--end::Illustration-->
  </div>
</div>
<!--end::Step 5-->
