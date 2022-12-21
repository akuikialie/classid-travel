@extends('layouts.web.app')

@section('page-styles')
  <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-custom-scripts')
  <script>
    let csrf_token = "{{ csrf_token() }}";
    let urlTable = "{{ route('admin.user.datatable') }}";
    let createUrl = "{{ route('admin.user.create') }}";
    let editUrl = "{{ route('admin.role.edit', ':id') }}";
    let searchUsers = @json($search_users);
    let deleteUrl = "{{ route('admin.role.destroy', ':id') }}";
  </script>

  <script>
    initDatatable();
  </script>
@endsection

@section('page-scripts')
  <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="{{ asset('web/js/based/datatables/role-datatable.js') }}"></script>
@endsection

@section('page-content')

  <div id="dynamic_modal"></div>

  <!--begin::Content wrapper-->
  <div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <!--begin::Toolbar container-->
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <!--begin::Title-->
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">View Role
            Details</h1>
          <!--end::Title-->
          <!--begin::Breadcrumb-->
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
              <a href="/metronic8/demo1/../demo1/index.html" class="text-muted text-hover-primary">Home</a>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">User Management</li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <!--end::Item-->
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">Roles</li>
            <!--end::Item-->
          </ul>
          <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          <!--begin::Primary button-->
          <a href="{{ url()->previous() }}"
             class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold">Back</a>
          <!--end::Primary button-->
        </div>
        <!--end::Actions-->
      </div>
      <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <!--begin::Content container-->
      <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">
          <!--begin::Sidebar-->
          <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
            <!--begin::Card-->
            <div class="card card-flush h-md-100">
              <!--begin::Card header-->
              <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                  <h2>{{ $role->name }}</h2>
                </div>
                <!--end::Card title-->
              </div>
              <!--end::Card header-->
              <!--begin::Card body-->
              <div class="card-body pt-1">
                <!--begin::Users-->
                <div class="fw-bold text-gray-600 mb-5">Total users with this role: {{ $role->users_count }}</div>
                <!--end::Users-->
                <!--begin::Permissions-->
                <div class="d-flex flex-column text-gray-600">
                  @php
                    $permissionGroups = collect($role->permissions)->groupBy('group');
                  @endphp

                  @foreach($permissionGroups as $group => $permission)
                      <div class="d-flex align-items-center py-2">
                        <span class="bullet bg-primary me-3"></span>Manage {{ $group }} ({{ $permission->count() }}) </div>
                  @endforeach


                </div>
                <!--end::Permissions-->
              </div>
              <!--end::Card body-->
              <!--begin::Card footer-->
              <form class="form" action="{{ route('admin.role.destroy', $role->hash) }}" method="post">
                @method('DELETE')
                @csrf
                <div class="card-footer flex-wrap pt-0">
                  <button type="button" class="btn btn-light btn-active-light-primary my-1 update-role"
                          data-id="{{ $role->hash }}">Edit Role
                  </button>

                  <button type="submit" class="btn btn-light btn-light-danger my-1">Delete Role
                  </button>
                </div>
              </form>
              <!--end::Card footer-->
            </div>
            <!--end::Card-->
            <!--begin::Modal-->
            <!--begin::Modal - Update role-->
            <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
              <!--begin::Modal dialog-->
              <div class="modal-dialog modal-dialog-centered mw-750px">
                <!--begin::Modal content-->
                <div class="modal-content">
                  <!--begin::Modal header-->
                  <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Update Role</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
																	<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                       xmlns="http://www.w3.org/2000/svg">
																		<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                          transform="rotate(-45 6 17.3137)" fill="currentColor"/>
																		<rect x="7.41422" y="6" width="16" height="2" rx="1"
                                          transform="rotate(45 7.41422 6)" fill="currentColor"/>
																	</svg>
																</span>
                      <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                  </div>
                  <!--end::Modal header-->
                  <!--begin::Modal body-->
                  <div class="modal-body scroll-y mx-5 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_update_role_form" class="form" action="#">
                      <!--begin::Scroll-->
                      <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                           data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                           data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header"
                           data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                          <!--begin::Label-->
                          <label class="fs-5 fw-bold form-label mb-2">
                            <span class="required">Role name</span>
                          </label>
                          <!--end::Label-->
                          <!--begin::Input-->
                          <input class="form-control form-control-solid" placeholder="Enter a role name"
                                 name="role_name" value="Developer"/>
                          <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Permissions-->
                        <div class="fv-row">
                          <!--begin::Label-->
                          <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                          <!--end::Label-->
                          <!--begin::Table wrapper-->
                          <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                              <!--begin::Table body-->
                              <tbody class="text-gray-600 fw-semibold">
                              <!--begin::Table row-->
                              <tr>
                                <td class="text-gray-800">Administrator Access
                                  <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                     title="Allows a full access to the system"></i></td>
                                <td>
                                  <!--begin::Checkbox-->
                                  <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                    <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all"/>
                                    <span class="form-check-label" for="kt_roles_select_all">Select all</span>
                                  </label>
                                  <!--end::Checkbox-->
                                </td>
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">User Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="user_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="user_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="user_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Content Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="content_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="content_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="content_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Financial Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="financial_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="financial_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="financial_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Reporting</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value="" name="reporting_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value="" name="reporting_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value="" name="reporting_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Payroll</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value="" name="payroll_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value="" name="payroll_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value="" name="payroll_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Disputes Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="disputes_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="disputes_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="disputes_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">API Controls</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="api_controls_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="api_controls_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="api_controls_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Database Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="database_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="database_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="database_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              <!--begin::Table row-->
                              <tr>
                                <!--begin::Label-->
                                <td class="text-gray-800">Repository Management</td>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <td>
                                  <!--begin::Wrapper-->
                                  <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                      class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="repository_management_read"/>
                                      <span class="form-check-label">Read</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="repository_management_write"/>
                                      <span class="form-check-label">Write</span>
                                    </label>
                                    <!--end::Checkbox-->
                                    <!--begin::Checkbox-->
                                    <label class="form-check form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" value=""
                                             name="repository_management_create"/>
                                      <span class="form-check-label">Create</span>
                                    </label>
                                    <!--end::Checkbox-->
                                  </div>
                                  <!--end::Wrapper-->
                                </td>
                                <!--end::Input group-->
                              </tr>
                              <!--end::Table row-->
                              </tbody>
                              <!--end::Table body-->
                            </table>
                            <!--end::Table-->
                          </div>
                          <!--end::Table wrapper-->
                        </div>
                        <!--end::Permissions-->
                      </div>
                      <!--end::Scroll-->
                      <!--begin::Actions-->
                      <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">Discard
                        </button>
                        <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                          <span class="indicator-label">Submit</span>
                          <span class="indicator-progress">Please wait...
																		<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                      </div>
                      <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                  </div>
                  <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
              </div>
              <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Update role-->
            <!--end::Modal-->
          </div>
          <!--end::Sidebar-->
          <!--begin::Content-->
          <div class="flex-lg-row-fluid ms-lg-10">
            <!--begin::Card-->
            <div class="card card-docs flex-row-fluid mb-2">
              <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="p-0">
                  <!--begin::Heading-->
                  <h3 class="card-title">User Management</h3>
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
                               class="form-control form-control-solid w-250px ps-15" placeholder="Search Pengguna"/>
                      </div>
                      <!--end::Search-->
                      <!--begin::Toolbar-->
                      <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
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
                        <!--end::Filter-->
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
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
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
            <!--end::Card-->
          </div>
          <!--end::Content-->
        </div>
        <!--end::Layout-->
      </div>
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Content wrapper-->

@endsection
