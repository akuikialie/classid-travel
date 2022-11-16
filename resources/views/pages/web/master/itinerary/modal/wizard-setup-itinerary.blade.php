<!--begin::Modal - Create App-->
<div class="modal fade" id="modal_wizard_setup_itinerary" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <!--begin::Modal content-->
    <div class="modal-content ">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2>Setup Kegiatan</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Modal header-->
      <!--begin::Modal body-->
      <div class="modal-body py-lg-10 px-lg-10 scroll-y h-500px">
        <!--begin::Stepper-->
        <div class="stepper stepper-pills stepper-column d-flex flex-column flex-lg-row " id="wizard_setup_itinerary">
          <!--begin::Aside-->
          <div class="d-flex flex-row-auto w-100 w-lg-300px">
            <div class="hover-scroll h-400px px-5" style="width: 100%">
              <!--begin::Nav-->
              <div class="stepper-nav flex-center">

                @for($i = 1; $i <= $package->long_days ?? 0; $i++)

                  @php
                    $itinerary = collect($myItineraries)->firstWhere('day', $i);
                  @endphp
                    <!--begin::Step title-->
                  <div class="stepper-item me-5 {{ $i == 1 ? 'current' : null }} " data-kt-stepper-element="nav"
                       data-kt-stepper-action="step">
                    <!--begin::Wrapper-->
                    <div class="stepper-wrapper d-flex align-items-center">
                      <!--begin::Icon-->
                      <div class="stepper-icon w-40px h-40px">
                        <i class="stepper-check fas fa-check"></i>
                        <span class="stepper-number">{{ $i }}</span>
                      </div>
                      <!--end::Icon-->

                      <!--begin::Label-->
                      <div class="stepper-label">
                        <h3 class="stepper-title">
                          Hari ke-{{$i}}
                        </h3>

                        <div class="stepper-desc">
                          {{ $itinerary?->activities->count() ?? 0 }} Kegiatan
                        </div>
                      </div>
                      <!--end::Label-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Line-->
                    <div class="stepper-line h-40px"></div>
                    <!--end::Line-->
                  </div>
                  <!--end::Step title-->

                @endfor
              </div>
              <!--end::Nav-->
            </div>

          </div>

          <!--begin::Content-->
          <div class="flex-row-fluid">
            <!--begin::Form-->
            <form class="form w-lg-500px mx-auto"
                  action="{{ route('admin.package.itinerary-setup.store', $package->hash) }}" method="post">
              <!--begin::Group-->
              <div class="mb-5 scroll-y h-375px">
                @for($i = 1; $i <= $package->long_days ?? 0; $i++)

                  <!--begin::Step content-->
                  <div class="flex-column {{ $i == 1 ? 'current' : null }}" data-kt-stepper-element="content">

                    @if($i == 1)
                      @csrf
                    @endif

                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                      <!--begin::Title-->
                      <h1 class="mb-3">Kegiatan hari ke-{{ $i }}</h1>
                      <!--end::Title-->
                      <!--begin::Description-->
                      <div class="text-muted fw-semibold fs-5">Tambahkan kegiatan pada hari ke-{{ $i }}.
                        <a href="#" class="fw-bold link-primary add-itinerary-activity"
                           data-itinerary="data-itinerary-{{ $i }}"
                           data-add-itinerary="new-itinerary-activity-{{$i}}">Tambah Kegiatan</a>.
                      </div>
                      <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    @php
                      $itinerary = collect($myItineraries)->firstWhere('day', $i);
                      $lastData = [];
                      foreach ($itinerary->activities ?? [] as $_activity) {
                          $lastData[] = [
                              'time' => $_activity->pivot->time,
                              'activity' => $_activity->id,
                          ];
                      }

                    @endphp

                      <!--begin::Input group-->
                    <div class="fv-row mb-10">
                      <!--begin::Label-->
                      <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                        <span class="required">Keterangan</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Summary"></i>
                      </label>
                      <!--end::Label-->
                      <!--begin::Input-->
                      <input type="text" class="form-control form-control-lg form-control-solid"
                             name="name[day-{{$i}}][]"
                             placeholder="Summary"
                             value="{{ old('name', $itinerary->name ?? "Keterangan hari ke-{$i}") }}"/>
                      <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    @if(count($lastData) > 0)
                      @foreach($lastData as $item)
                        <div class="row ">
                          <div class="col-lg-3 col-12">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                              <!--begin::Label-->
                              <label class="form-label">Waktu</label>
                              <!--end::Label-->

                              <!--begin::Input-->
                              <input type="text" class="form-control form-control-solid time-picker"
                                     name="time[day-{{$i}}][]" value="{{ $item['time'] }}"/>
                              <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                          </div>
                          <div class="col-lg-7 col-10">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                              <!--begin::Label-->
                              <label class="form-label">Pilih Aktifitas</label>
                              <!--end::Label-->

                              <select class="form-select form-select-solid" data-control="select2"
                                      data-dropdown-parent="#modal_wizard_setup_itinerary"
                                      data-placeholder="Select an option" data-allow-clear="true"
                                      name="itinerary[day-{{$i}}][]">
                                <option></option>
                                @forelse($itineraries as $_itinerary)
                                  <option value="{{ $_itinerary->id }}"
                                    {{ $item['activity'] == $_itinerary->id ? 'selected' : ''}}>
                                    {{ $_itinerary->activity }}
                                  </option>
                                @empty
                                  <option value="" selected disabled>Data kegiatan belum tersedia</option>
                                @endforelse
                              </select>
                            </div>
                            <!--end::Input group-->

                          </div>
                          <div class="col-lg-2 col-2">
                            <!--begin::Input group-->
                            <div class="fv-row mt-10">
                              <a
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-remove-itinerary">
                                <i class="fa-solid fa-trash text-danger"></i>
                              </a>
                            </div>
                            <!--end::Input group-->


                          </div>
                        </div>
                      @endforeach
                    @endif

                    <div hidden id="data-itinerary-{{$i}}" class="row ">
                      <div class="col-lg-3 col-12">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                          <!--begin::Label-->
                          <label class="form-label">Time</label>
                          <!--end::Label-->

                          <!--begin::Input-->
                          <input type="text" class="form-control form-control-solid time-picker"
                                 name="time[day-{{$i}}][]"/>
                          <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                      </div>
                      <div class="col-lg-7 col-10">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                          <!--begin::Label-->
                          <label class="form-label">Pilih Aktifitas</label>
                          <!--end::Label-->

                          <select class="form-select form-select-solid" data-control="select2"
                                  data-dropdown-parent="#modal_wizard_setup_itinerary"
                                  data-placeholder="Select an option" data-allow-clear="true"
                                  name="itinerary[day-{{$i}}][]">
                            <option></option>
                            @forelse($itineraries as $itinerary)
                              <option value="{{ $itinerary->id }}">{{ $itinerary->activity }}</option>
                            @empty
                              <option value="" selected disabled>Data kegiatan belum tersedia</option>
                            @endforelse
                          </select>
                        </div>
                        <!--end::Input group-->

                      </div>
                      <div class="col-lg-2 col-2">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                          <a
                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-remove-itinerary">
                            <i class="fa-solid fa-trash text-danger"></i>
                          </a>
                        </div>
                        <!--end::Input group-->


                      </div>
                    </div>

                    <div id="new-itinerary-activity-{{$i}}"></div>

                  </div>
                  <!--begin::Step content-->

                @endfor
              </div>
              <!--end::Group-->

              <!--begin::Actions-->
              <div class="d-flex flex-stack">
                <!--begin::Wrapper-->
                <div class="me-2">
                  <button type="button" class="btn btn-light btn-active-light-primary"
                          data-kt-stepper-action="previous">
                    Back
                  </button>
                </div>
                <!--end::Wrapper-->

                <!--begin::Wrapper-->
                <div>

                  <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                    Continue
                  </button>

                  <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">
                            Submit
                        </span>
                    <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                  </button>
                </div>
                <!--end::Wrapper-->
              </div>
              <!--end::Actions-->
            </form>
            <!--end::Form-->
          </div>
        </div>
        <!--end::Stepper-->
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
<!--end::Modal - Create App-->

