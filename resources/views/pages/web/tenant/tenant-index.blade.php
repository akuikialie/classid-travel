@extends('layouts.web.app')

@section('page-styles')
  <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>

@endsection

@section('page-custom-scripts')
  <script>
    let csrf_token = "{{ csrf_token() }}";
    let urlTable = "{{ route('admin.tenant.datatable') }}";
    let createUrl = "{{ route('admin.tenant.create') }}";
    let editUrl = "{{ route('admin.tenant.edit', ':id') }}";
    let changeStatusUrl = "{{ route('admin.tenant.change-status', ':id') }}";
  </script>
@endsection

@section('page-scripts')
  <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="{{asset('web/js/based/datatables/tenant-datatable.js')}}"></script>
@endsection

@section('page-content')

  <div id="dynamic_modal"></div>

  <div class="card card-docs flex-row-fluid mb-2">
    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
      <div class="p-0">
        <!--begin::Heading-->
        <h3 class="card-title">List Travel</h3>
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
              <input type="text" data-kt-docs-table-filter="search"
                     class="form-control form-control-solid w-250px ps-15" placeholder="Search Travel"/>
            </div>
            <!--end::Search-->
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
              <!--begin::Filter-->
              <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                      data-kt-menu-placement="bottom-end">
                <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                <span class="svg-icon svg-icon-2">
                  <i class="fa-solid fa-filter"></i>
                </span>
                <!--end::Svg Icon-->Filter
              </button>
              <!--begin::Menu 1-->
              <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                   id="kt-toolbar-filter">
                <!--begin::Header-->
                <div class="px-7 py-5">
                  <div class="fs-4 text-dark fw-bold">Filter Options</div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Separator-->
                <!--begin::Content-->
                <form id="form-filter">
                  <div class="px-7 py-5">
                    <!--begin::Input group-->

                    <div class="mb-10">
                      <!--begin::Label-->
                      <label class="form-label fs-5 fw-semibold mb-3">Status:</label>
                      <!--end::Label-->
                      <!--begin::Options-->
                      <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="status">
                        <!--begin::Option-->
                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                          <input class="form-check-input" type="radio" name="status" value="all" checked="checked"/>
                          <span class="form-check-label text-gray-600">All</span>
                        </label>
                        <!--end::Option-->
                        <!--begin::Option-->
                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                          <input class="form-check-input" type="radio" name="status" value="active"/>
                          <span class="form-check-label text-gray-600">Active</span>
                        </label>
                        <!--end::Option-->
                        <!--begin::Option-->
                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                          <input class="form-check-input" type="radio" name="status" value="inactive"/>
                          <span class="form-check-label text-gray-600">Inactive</span>
                        </label>
                        <!--end::Option-->
                      </div>
                      <!--end::Options-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                      <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                              data-kt-menu-dismiss="true"
                              data-kt-docs-table-filter="reset">Reset
                      </button>
                      <button type="button" class="btn btn-primary" data-kt-menu-dismiss="true"
                              data-kt-docs-table-filter="filter" id="apply-filter">Apply
                      </button>
                    </div>
                    <!--end::Actions-->
                  </div>
                </form>

                <!--end::Content-->
              </div>
              <!--end::Menu 1-->
              <!--end::Filter-->

            @can(\App\Enums\Permissions\TravelPermission::TRAVEL_CREATE->value)
              <button type="button" class="btn btn-primary" id="create-new" data-bs-toggle="tooltip"
                      title="Buat Akun Travel Baru">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                <span class="svg-icon svg-icon-2">
                  <i class="fa-solid fa-plus"></i>
                </span>
                <!--end::Svg Icon-->Buat Travel
              </button>
            @endcan
            </div>
            <!--end::Toolbar-->
            <!--begin::Group actions-->
            <div class="d-flex justify-content-end align-items-center d-none" data-kt-docs-table-toolbar="selected">
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
              <th>Travel Name</th>
              <th>slug</th>
              <th>app_domain</th>
              <th>BCN</th>
              <th>status</th>
              <th>Created Date</th>
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

@endsection
