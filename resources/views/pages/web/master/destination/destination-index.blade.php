@extends('layouts.web.app')

@section('page-scripts')
  <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="{{ asset('web/js/custom/utilities/modals/create-destination.js') }}"></script>
  <script src="{{ asset('web/js/based/datatables/destination-datatable.js') }}"></script>
@endsection

@section('page-custom-scripts')
  <script>
    let csrf_token = "{{ csrf_token() }}";
    let urlTable = "{{ route('admin.destination.datatable') }}";
    let createUrl = "{{ route('admin.destination.create') }}";
    let editUrl = "{{ route('admin.destination.edit', ':id') }}";
  </script>
@endsection

@section('page-content')
  <div id="dynamic_modal"></div>

  <div class="card card-docs flex-row-fluid mb-2">
    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
      <div class="p-0">
        <!--begin::Heading-->
        <h3 class="card-title">Daftar Tujuan</h3>
        <!--end::Heading-->
        <!--begin::CRUD-->
        <div class="py-5">
          <!--begin::Wrapper-->
          <div class="d-flex flex-stack flex-wrap mb-5">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1 mb-2 mb-md-0">
              <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
              <span class="svg-icon svg-icon-1 position-absolute ms-6">
                <i class="fa-brands fa-searchengin"></i>
              </span>
              <!--end::Svg Icon-->
              <input type="text" data-kt-user-table-filter="search"
                     class="form-control form-control-solid w-250px ps-15" placeholder="Search Tujuan"/>
            </div>
            <!--end::Search-->
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
              {{-- <!--begin::Filter-->
               <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                       data-kt-menu-placement="bottom-end">
                 <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                 <span class="svg-icon svg-icon-2">
                   <i class="fa-solid fa-filter"></i>
                 </span>
                 <!--end::Svg Icon-->Filter
               </button>
               <!--begin::Menu 1-->
               <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                 <!--begin::Header-->
                 <div class="px-7 py-5">
                   <div class="fs-5 text-dark fw-bold">Filter Options</div>
                 </div>
                 <!--end::Header-->
                 <!--begin::Separator-->
                 <div class="separator border-gray-200"></div>
                 <!--end::Separator-->
                 <!--begin::Content-->
                 <div class="px-7 py-5" data-kt-user-table-filter="form">
                   <!--begin::Input group-->
                   <div class="mb-10">
                     <label class="form-label fs-6 fw-semibold">Role:</label>
                     <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                             data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="role"
                             data-hide-search="true">
                       <option></option>
                       @foreach($roles as $role)
                         <option value="{{ $role->name }}">{{ \Illuminate\Support\Str::ucfirst($role->name) }}</option>
                       @endforeach
                     </select>
                   </div>
                   <!--end::Input group-->
                   <!--begin::Input group-->
                   <div class="mb-10">
                     <label class="form-label fs-6 fw-semibold">Two Step Verification:</label>
                     <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                             data-placeholder="Select option" data-allow-clear="true"
                             data-kt-user-table-filter="two-step" data-hide-search="true">
                       <option></option>
                       <option value="Enabled">Enabled</option>
                     </select>
                   </div>
                   <!--end::Input group-->
                   <!--begin::Actions-->
                   <div class="d-flex justify-content-end">
                     <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                             data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset
                     </button>
                     <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true"
                             data-kt-user-table-filter="filter">Apply
                     </button>
                   </div>
                   <!--end::Actions-->
                 </div>
                 <!--end::Content-->
               </div>
               <!--end::Menu 1-->
               <!--end::Filter-->--}}

              @can("create {$current_page}")
                <button type="button" class="btn btn-primary" id="create-new" data-bs-toggle="tooltip"
                        title="Setup Tujuan">
                  <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                  <span class="svg-icon svg-icon-2">
                  <i class="fa-solid fa-plus"></i>
                </span>
                  <!--end::Svg Icon-->Setup Tujuan
                </button>

              @endcan
            </div>
            <!--end::Toolbar-->
            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
              <div class="fw-bold me-5">
                <span class="me-2" data-kt-docs-table-select="selected_count"></span>Selected
              </div>
              <button type="button" class="btn btn-danger" data-kt-docs-table-select="delete_selected">Selection
                Action
              </button>
            </div>
            <!--end::Group actions-->
          </div>
          <!--end::Wrapper-->
          <!--begin::Datatable-->
          <table id="kt_datatable_example_1" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
              <th class="w-10px pe-2">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                  <input class="form-check-input" type="checkbox" data-kt-check="true"
                         data-kt-check-target="#kt_datatable_example_1 .form-check-input" value="1"/>
                </div>
              </th>
              <th class="min-w-150px">Name</th>
              <th class="min-w-140px">Lokasi</th>
              <th class="min-w-140px">status</th>
              <th class="text-end min-w-100px">Actions</th>
            </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
          </table>
          <!--end::Datatable-->

        </div>
        <!--end::CRUD-->
      </div>
    </div>
  </div>

  {{--<!--begin::Tables Widget 13-->
  <div class="card mb-5 mb-xl-8">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold fs-3 mb-1">Data Destinasi</span>
      </h3>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-3">
      <!--begin::Table container-->
      <div class="table-responsive">
        <!--begin::Table-->
        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
          <!--begin::Table head-->
          <thead>
          <tr class="fw-bold text-muted">
            <th class="w-25px">
              <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="1" data-kt-check="true"
                       data-kt-check-target=".widget-13-check"/>
              </div>
            </th>
            <th class="min-w-150px">Name</th>
            <th class="min-w-150px">Lokasi</th>
            <th class="min-w-140px">Waktu Jelajah</th>
            <th class="min-w-120px">Koleksi Photo</th>
            <th class="min-w-100px text-end">Actions</th>
          </tr>
          </thead>
          <!--end::Table head-->
          <!--begin::Table body-->
          <tbody>
          @forelse ($destinations as $destination)
            <tr>
              <td>
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                  <input class="form-check-input widget-13-check" type="checkbox" value="1"/>
                </div>
              </td>
              <td class="text-dark fw-bold text-hover-primary fs-6">{{ $destination->name }}</td>
              <td class="text-dark fw-bold text-hover-primary fs-6">
                @if (isset($destination->myAddress?->address))
                  {{ mb_strimwidth($destination->myAddress?->address, 0, 20, '...') }}
                  @if (strlen($destination->myAddress?->address) > 20)
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                       title="{{ $destination->myAddress?->address }}"></i>
                  @endif
                @else
                  -
                @endif
              </td>
              <td class="text-dark fw-bold text-hover-primary fs-6">
                {{ isset($destination->roaming_in_destination) ? "{$destination->roaming_in_destination} Menit" : '-' }}
              </td>
              <td class="text-dark fw-bold text-hover-primary fs-6">{{ $destination->media_count }} Photo
              </td>


              <!--begin::Action=-->
              <td class="text-end">
                <a href="#" data-id="{{ $destination->hash }}" data-bs-toggle="tooltip"
                   title="Edit jadwal"
                   class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit-modal">
                  <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                  <span class="svg-icon svg-icon-3">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                      d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                      fill="currentColor"/>
                                                <path
                                                  d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                  fill="currentColor"/>
                                            </svg>
                                        </span>
                  <!--end::Svg Icon-->
                </a>
                @if ($destination->packages_count < 1)
                  <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                     data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                    <span class="svg-icon svg-icon-5 m-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                  d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                  fill="currentColor"/>
                                            </svg>
                                        </span>
                    <!--end::Svg Icon-->
                  </a>
                @endif
                <!--begin::Menu-->
                <div
                  class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                  data-kt-menu="true">
                  --}}{{--<!--begin::Menu item-->
                  <div class="menu-item px-3" data-kt-menu-trigger="hover"
                      data-kt-menu-placement="right-start">
                      <!--begin::Menu item-->
                      <a href="#" class="menu-link px-3">
                          <span class="menu-title">Menu Cepat </span>
                          <span class="menu-arrow"></span>
                      </a>
                      <!--end::Menu item-->
                      <!--begin::Menu sub-->
                      <div class="menu-sub menu-sub-dropdown w-175px py-4">
                          @if (!$destination->is_active)
                              <!--begin::Menu item-->
                              <div class="menu-item px-3">
                                  <a href="#" class="menu-link px-3">Aktifkan Tujuan</a>
                              </div>
                              <!--end::Menu item-->
                          @else
                              <!--begin::Menu item-->
                              <div class="menu-item px-3">
                                  <a href="#" class="menu-link px-3">Nonaktifkan Tujuan</a>
                              </div>
                              <!--end::Menu item-->
                          @endif
                      </div>
                      <!--end::Menu sub-->
                  </div>
                  <!--end::Menu item-->--}}{{--
                  <!--begin::Menu item-->
                  @if ($destination->packages_count < 1)
                    <form action="{{ route('admin.destination.destroy', $destination->id) }}"
                          method="post" id="delete">
                      @csrf
                      @method('delete')
                      <div class="menu-item px-3">
                        <a onclick="$('#delete').submit()"
                           data-kt-subscriptions-table-filter="delete_row"
                           class="menu-link px-3">Delete</a>
                      </div>
                    </form>
                  @endif
                  <!--end::Menu item-->
                </div>
                <!--end::Menu-->
              </td>
              <!--end::Action=-->
            </tr>

          @empty
          @endforelse
          </tbody>
          <!--end::Table body-->
        </table>
        <!--end::Table-->
      </div>
      <!--end::Table container-->
    </div>
    <!--begin::Body-->
  </div>
  <!--end::Tables Widget 13-->--}}
@endsection
