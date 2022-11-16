@extends('layouts.web.app')

@section('page-scripts')
    <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('web/js/custom/utilities/modals/create-schedule.js') }}"></script>
    <script src="{{ asset('web/js/based/datatables/schedule-datatable.js') }}"></script>
@endsection

@section('page-custom-scripts')
  <script>
    let csrf_token = "{{ csrf_token() }}";
    let urlTable = "{{ route('admin.schedule.datatable') }}";
    let createUrl = "{{ route('admin.schedule.create') }}";
    let editUrl = "{{ route('admin.schedule.edit', ':id') }}";
  </script>
@endsection

@section('page-content')
    <div id="dynamic_modal"></div>

    <div class="card card-docs flex-row-fluid mb-2">
      <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <div class="p-0">
          <!--begin::Heading-->
          <h3 class="card-title">Daftar Jadwal Keberangkatan</h3>
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
                       class="form-control form-control-solid w-250px ps-15" placeholder="Search Jadwal"/>
              </div>
              <!--end::Search-->
              <!--begin::Toolbar-->
              <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                @can("create {$current_page}")
                  <button type="button" class="btn btn-primary" id="create-new" data-bs-toggle="tooltip"
                          title="Setup Jadwal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                    <span class="svg-icon svg-icon-2">
                  <i class="fa-solid fa-plus"></i>
                </span>
                    <!--end::Svg Icon-->Setup Jadwal
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
                <th class="min-w-150px">Tanggal Keberangkatan</th>
                <th class="min-w-150px">Status</th>
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
