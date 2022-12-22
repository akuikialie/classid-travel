@extends('layouts.web.app')

@section('page-styles')
  <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-custom-scripts')
  <script>
    let csrf_token = "{{ csrf_token() }}";
    let urlTable = "{{ route('admin.role.datatable') }}";
    let createUrl = "{{ route('admin.role.create') }}";
    {{--let columns = @json($columns);--}}
    let editUrl = "{{ route('admin.role.edit', ':id') }}";
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
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Roles List</h1>
          <!--end::Title-->
          <!--begin::Breadcrumb-->
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <!--begin::Item-->
            <li class="breadcrumb-item text-muted">
              <a href="#" class="text-muted text-hover-primary">Home</a>
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
          <!--begin::Filter menu-->
          <div class="m-0">
            <!--begin::Menu toggle-->
            <a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold"
               data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
              <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
              <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path
                            d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                            fill="currentColor"/>
												</svg>
											</span>
              <!--end::Svg Icon-->Filter</a>
            <!--end::Menu toggle-->
            <!--begin::Menu 1-->
            <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                 id="kt_menu_63a16ddb107dc">
              <!--begin::Header-->
              <div class="px-7 py-5">
                <div class="fs-5 text-dark fw-bold">Filter Options</div>
              </div>
              <!--end::Header-->
              <!--begin::Menu separator-->
              <div class="separator border-gray-200"></div>
              <!--end::Menu separator-->
              <!--begin::Form-->
              <form class="form" method="get" action="">
                <div class="px-7 py-5">
                  <!--begin::Input group-->
                  <div class="mb-10">
                    <!--begin::Label-->
                    <label class="form-label fw-semibold">Filter By Role:</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div>
                      <select class="form-select form-select-solid" data-kt-select2="true"
                              data-placeholder="Select option" data-dropdown-parent="#kt_menu_63a16ddb107dc"
                              data-allow-clear="true" name="role_name">
                        <option></option>
                        @foreach($role_filters AS $_role)
                          <option value="{{ $_role->name }}"
                            {{ request()->get('role_name') == $_role->name ? 'selected' : '' }}
                          >{{ $_role->name }}</option>

                        @endforeach
                      </select>
                    </div>
                    <!--end::Input-->
                  </div>
                  <!--end::Input group-->

                  <!--begin::Input group-->
                  <div class="mb-10">
                    <!--begin::Label-->
                    <label class="form-label fw-semibold">Filter Travel:</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <div>
                      <select class="form-select form-select-solid" data-kt-select2="true"
                              data-placeholder="Select option" data-dropdown-parent="#kt_menu_63a16ddb107dc"
                              data-allow-clear="true" name="travel_name">
                        <option></option>
                        @foreach($tenant_filters AS $tenant)
                          <option value="{{ $tenant->hash }}"
                            {{ request()->get('travel_name') == $tenant->hash ? 'selected' : '' }}
                          >{{ $tenant->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <!--end::Input-->
                  </div>
                  <!--end::Input group-->

                  <!--begin::Actions-->
                  <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.role.index') }}" class="btn btn-sm btn-light btn-active-light-primary me-2">
                      Reset
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                  </div>
                  <!--end::Actions-->
                </div>
              </form>
              <!--end::Form-->
            </div>
            <!--end::Menu 1-->
          </div>
          <!--end::Filter menu-->
          <!--begin::Secondary button-->
          <!--end::Secondary button-->
          <!--begin::Primary button-->
          <a href="#" class="btn btn-sm fw-bold btn-primary" id="create-new">Create</a>
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
        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">

          @foreach($roles ?? [] as $role)
            <!--begin::Col-->
            <div class="col-md-4">
              <!--begin::Card-->
              <div class="card card-flush h-md-100">
                <!--begin::Card header-->
                <div class="card-header">
                  <!--begin::Card title-->
                  <div class="card-title">
                    <h2>{{ $role->name }} <sup class="text-muted fs-7">({{ $role->tenant->name ?? 'master' }})</sup></h2>
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

                      $total = 5;
                      $permissionGroups = collect($role->permissions)->groupBy('group');
                      $permissionsTotal = $permissionGroups->count();

                      if ($permissionsTotal < 5){
                          $total = $permissionsTotal;
                      }
                    @endphp

                    @foreach($permissionGroups as $group => $permission)
                      @if($loop->index < 5)
                        <div class="d-flex align-items-center py-2">
                          <span class="bullet bg-primary me-3"></span>Manage {{ $group }} ({{ $permission->count() }})
                        </div>
                      @endif

                    @endforeach

                    @if($permissionsTotal > 5)
                      <div class='d-flex align-items-center py-2'>
                        <span class='bullet bg-primary me-3'></span>
                        <em>and {{ $permissionsTotal - $total }} more...</em>
                      </div>
                    @endif

                  </div>
                  <!--end::Permissions-->
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer flex-wrap pt-0">
                  <a href="{{ route('admin.role.show', $role->hash) }}"
                     class="btn btn-light btn-active-primary my-1 me-2">View Role</a>
                  <button type="button" class="btn btn-light btn-active-light-primary my-1 update-role"
                          data-id="{{ $role->hash }}">Edit Role
                  </button>
                </div>
                <!--end::Card footer-->
              </div>
              <!--end::Card-->
            </div>
            <!--end::Col-->
          @endforeach


        </div>
        <!--end::Row-->
        <!--begin::Modals-->

        <!--end::Modals-->
      </div>
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Content wrapper-->

@endsection
