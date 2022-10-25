@php use Carbon\Carbon; @endphp
@extends('layouts.web.app')

@section('toolbar')
@endsection

@section('page-scripts')
  <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endsection

@section('page-custom-scripts')

@endsection

@section('page-content')
  <!--begin::Row-->
  <div class="row g-5 g-xl-10 mb-5 mb-xl-10">

    <!--begin::Col-->
    <div class="col-12 p-2">
      <!--begin::Table Widget 3-->
      <div class="card card-flush h-xl-100">
        <!--begin::Card header-->
        <div class="card-header py-7">
          <!--begin::Tabs-->
          <div class="card-title pt-3 mb-0 gap-4 gap-lg-10 gap-xl-15 nav nav-tabs border-bottom-0"
               data-kt-table-widget-3="tabs_nav">
            <!--begin::Tab item-->
            <div class="fs-4 fw-bold pb-3 border-bottom border-3 border-primary cursor-pointer"
                 data-kt-table-widget-3="tab" data-kt-table-widget-3-value="Show All">Data Keberangkatan
              ({{ collect($data_keberangkatan)->count() }})
            </div>
            <!--end::Tab item-->
            <!--begin::Tab item-->
            <div class="fs-4 fw-bold text-muted pb-3 cursor-pointer" data-kt-table-widget-3="tab"
                 data-kt-table-widget-3-value="SEDANG_BERANGKAT">Sedang Berangkat
              ({{ collect($data_keberangkatan)->where('departure_status', 'SEDANG_BERANGKAT')->count() }})
            </div>
            <!--end::Tab item-->
            <!--begin::Tab item-->
            <div class="fs-4 fw-bold text-muted pb-3 cursor-pointer" data-kt-table-widget-3="tab"
                 data-kt-table-widget-3-value="BELUM_BERANGKAT">Belum Berangkat
              ({{ collect($data_keberangkatan)->where('departure_status', 'BELUM_BERANGKAT')->count() }})
            </div>
            <!--end::Tab item-->
            <!--begin::Tab item-->
            <div class="fs-4 fw-bold text-muted pb-3 cursor-pointer" data-kt-table-widget-3="tab"
                 data-kt-table-widget-3-value="BATAL_BERANGKAT">Batal Berangkat
              ({{ collect($data_keberangkatan)->where('departure_status', 'BATAL_BERANGKAT')->count() }})
            </div>
            <!--end::Tab item-->
          </div>
          <!--end::Tabs-->
          <!--begin::Create campaign button-->
          {{--<div class="card-toolbar">
            <a href="#" type="button" class="btn btn-primary" data-bs-toggle="modal"
               data-bs-target="#kt_modal_create_campaign">Create Campaign</a>
          </div>--}}
          <!--end::Create campaign button-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-1">
          <!--begin::Seprator-->
          <div class="separator separator-dashed my-1"></div>
          <!--end::Seprator-->
          <!--begin::Table-->
          <table id="kt_widget_table_3" class="table table-row-dashed align-middle fs-6 gy-4 my-0 pb-3"
                 data-kt-table-widget-3="all">
            <thead class="d-none">
            <tr>
              <th>Campaign</th>
              <th>Platforms</th>
              <th>Status</th>
              <th>Date</th>
              <th>Progress</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_keberangkatan as $item)
              @php
                $bg = ['primary', 'secondary', 'info', 'danger', 'warning', 'success'];
                $boarded_at = null;
                if (isset($item->departure_date)){
                    $boarded_at = Carbon::parse($item->departure_date);
                }
              @endphp
              <tr>
                <td class="min-w-175px">
                  <div class="position-relative ps-6 pe-3 py-2">
                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-{{ $bg[rand(0,5)] }}"></div>
                    <a href="#" class="mb-1 text-dark text-hover-primary fw-bold">
                      {{ $item->name }}
                    </a>
                    <div class="fs-7 text-muted fw-bold"> {{ isset($boarded_at) ? 'Berangkat
                      pada'. $boarded_at->format('d M Y') : 'Keberangkatan belum Disetting' }}</div>
                  </div>
                </td>
                <td>
                  <div
                    class="fs-7 text-muted fw-bold">{{ isset($item->plan) ? $item->plan : 'Belum memiliki rencana' }}</div>
                </td>
                <td>
                  @php
                    switch ($item->departure_status){
                        case 'SEDANG_BERANGKAT':
                          $badgeColor = 'primary';
                          break;

                          case 'SUDAH_BERANGKAT':
                          $badgeColor = 'success';
                          break;

                          case 'BELUM_BERANGKAT':
                          $badgeColor = 'warning';
                          break;

                          case 'BATAL_BERANGKAT':
                          $badgeColor = 'seccondary';
                          break;

                          default :
                              break;
                    }
                  @endphp

                  <span class="badge badge-light-{{ $badgeColor }}">{{ $item->departure_status }}</span>

                </td>

                <td class="min-w-150px">
                  <div class="mb-2 fw-bold">
                    @if(isset($boarded_at) && isset($item->plan_long_days))
                      {{ $boarded_at->format('d M Y') }} - {{ $boarded_at->addDay($item->plan_long_days)->format('d M Y') }}</div>
                    @else
                      belum ada perencanaan
                    @endif
                  <div class="fs-7 fw-bold text-muted">Estimasi</div>
                </td>
                <td class="d-none">
                {{$item->departure_status}}
                <td class="text-end">
                  <button type="button" class="btn btn-icon btn-sm btn-light btn-active-primary w-25px h-25px">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr001.svg-->
                    <span class="svg-icon">
																			<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                           xmlns="http://www.w3.org/2000/svg">
																				<path d="M14.4 11H3C2.4 11 2 11.4 2 12C2 12.6 2.4 13 3 13H14.4V11Z"
                                              fill="currentColor"/>
																				<path opacity="0.3"
                                              d="M14.4 20V4L21.7 11.3C22.1 11.7 22.1 12.3 21.7 12.7L14.4 20Z"
                                              fill="currentColor"/>
																			</svg>
																		</span>
                    <!--end::Svg Icon-->
                  </button>
                </td>
              </tr>
            @endforeach
            </tbody>
            <!--end::Table-->
          </table>
          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Table Widget 3-->
    </div>
    <!--end::Col-->
  </div>
  <!--end::Row-->
@endsection

@section('page-modals')
  <!--begin::Modal - View Users-->
  <div class="modal fade" id="kt_modal_view_users" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog mw-650px">
      <!--begin::Modal content-->
      <div class="modal-content">
        <!--begin::Modal header-->
        <div class="modal-header pb-0 border-0 justify-content-end">
          <!--begin::Close-->
          <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
            <span class="svg-icon svg-icon-1">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)"
                        fill="currentColor"/>
									<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                        fill="currentColor"/>
								</svg>
							</span>
            <!--end::Svg Icon-->
          </div>
          <!--end::Close-->
        </div>
        <!--begin::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
          <!--begin::Heading-->
          <div class="text-center mb-13">
            <!--begin::Title-->
            <h1 class="mb-3">Browse Users</h1>
            <!--end::Title-->
            <!--begin::Description-->
            <div class="text-muted fw-semibold fs-5">If you need more info, please check out our
              <a href="#" class="link-primary fw-bold">Users Directory</a>.
            </div>
            <!--end::Description-->
          </div>
          <!--end::Heading-->
          <!--begin::Users-->
          <div class="mb-15">
            <!--begin::List-->
            <div class="mh-375px scroll-y me-n7 pe-7">
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-6.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Emma Smith
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Art Director</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">smith@kpmg.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$23,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-danger text-danger fw-semibold">M</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Melody Macy
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Marketing Analytic</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">melody@altbox.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$50,500</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-1.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Max Smith
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Software Enginer</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">max@kt.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$75,900</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-5.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Sean Bean
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Web Developer</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">sean@dellito.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$10,500</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-25.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Brian Cox
                      <span class="badge badge-light fs-8 fw-semibold ms-2">UI/UX Designer</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">brian@exchange.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$20,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-warning text-warning fw-semibold">C</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Mikaela
                      Collins
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Head Of Marketing</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">mik@pex.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$9,300</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-9.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Francis
                      Mitcham
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Software Arcitect</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">f.mit@kpmg.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$15,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-danger text-danger fw-semibold">O</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Olivia Wild
                      <span class="badge badge-light fs-8 fw-semibold ms-2">System Admin</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">olivia@corpmail.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$23,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-primary text-primary fw-semibold">N</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Neil Owen
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Account Manager</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">owen.neil@gmail.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$45,800</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-23.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Dan Wilson
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Web Desinger</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">dam@consilting.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$90,500</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-danger text-danger fw-semibold">E</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Emma Bold
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Corporate Finance</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">emma@intenso.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$5,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-12.jpg"/>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Ana Crown
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Customer Relationship</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">ana.cf@limtel.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$70,000</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
              <!--begin::User-->
              <div class="d-flex flex-stack py-5">
                <!--begin::Details-->
                <div class="d-flex align-items-center">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-35px symbol-circle">
                    <span class="symbol-label bg-light-info text-info fw-semibold">A</span>
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Details-->
                  <div class="ms-6">
                    <!--begin::Name-->
                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-dark text-hover-primary">Robert Doe
                      <span class="badge badge-light fs-8 fw-semibold ms-2">Marketing Executive</span></a>
                    <!--end::Name-->
                    <!--begin::Email-->
                    <div class="fw-semibold text-muted">robert@benko.com</div>
                    <!--end::Email-->
                  </div>
                  <!--end::Details-->
                </div>
                <!--end::Details-->
                <!--begin::Stats-->
                <div class="d-flex">
                  <!--begin::Sales-->
                  <div class="text-end">
                    <div class="fs-5 fw-bold text-dark">$45,500</div>
                    <div class="fs-7 text-muted">Sales</div>
                  </div>
                  <!--end::Sales-->
                </div>
                <!--end::Stats-->
              </div>
              <!--end::User-->
            </div>
            <!--end::List-->
          </div>
          <!--end::Users-->
          <!--begin::Notice-->
          <div class="d-flex justify-content-between">
            <!--begin::Label-->
            <div class="fw-semibold">
              <label class="fs-6">Adding Users by Team Members</label>
              <div class="fs-7 text-muted">If you need more info, please check budget planning</div>
            </div>
            <!--end::Label-->
            <!--begin::Switch-->
            <label class="form-check form-switch form-check-custom form-check-solid">
              <input class="form-check-input" type="checkbox" value="" checked="checked"/>
              <span class="form-check-label fw-semibold text-muted">Allowed</span>
            </label>
            <!--end::Switch-->
          </div>
          <!--end::Notice-->
        </div>
        <!--end::Modal body-->
      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
  <!--end::Modal - View Users-->
@endsection
