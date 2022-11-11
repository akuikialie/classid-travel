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
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true"
                            data-kt-docs-table-filter="reset">Reset
                    </button>
                    <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true"
                            data-kt-docs-table-filter="filter">Apply
                    </button>
                  </div>
                  <!--end::Actions-->
                </div>
                <!--end::Content-->
              </div>
              <!--end::Menu 1-->
              <!--end::Filter-->

              <button type="button" class="btn btn-primary" id="create-new" data-bs-toggle="tooltip" title="Buat Akun Travel Baru">
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                <span class="svg-icon svg-icon-2">
                  <i class="fa-solid fa-plus"></i>
                </span>
                <!--end::Svg Icon-->Buat Travel
              </button>
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

  {{-- <div class="card shadow-sm">
     <div class="card-header">
       <h3 class="card-title">List Travel</h3>
       <div class="card-toolbar">
         <button type="button" class="btn btn-sm btn-light">
           Action
         </button>
       </div>
     </div>
     <div class="card-body">
       <!--begin::Wrapper-->
       <div class="d-flex flex-stack mb-5">
         <!--begin::Search-->
         <div class="d-flex align-items-center position-relative my-1">
           <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
           <span class="svg-icon svg-icon-1 position-absolute ms-6">
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg">
                               <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                     transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                               <path
                                 d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                 fill="currentColor"/>
                             </svg>
                           </span>
           <!--end::Svg Icon-->
           <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-15"
                  placeholder="Search Travel"/>
         </div>
         <!--end::Search-->

         <!--begin::Toolbar-->
         <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
           <!--begin::Filter-->
           <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                   data-kt-menu-placement="bottom-end">
             <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
             <span class="svg-icon svg-icon-2">
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg">
                               <path
                                 d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                 fill="currentColor"/>
                             </svg>
                           </span>
             <!--end::Svg Icon-->Filter
           </button>
           <!--begin::Menu 1-->
           <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
             <!--begin::Header-->
             <div class="px-7 py-5">
               <div class="fs-4 text-dark fw-bold">Filter Options</div>
             </div>
             <!--end::Header-->
             <!--begin::Separator-->
             <div class="separator border-gray-200"></div>
             <!--end::Separator-->
             <!--begin::Content-->
             <div class="px-7 py-5">
               <!--begin::Input group-->
               <div class="mb-10">
                 <!--begin::Label-->
                 <label class="form-label fs-5 fw-semibold mb-3">Payment Type:</label>
                 <!--end::Label-->
                 <!--begin::Options-->
                 <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="payment_type">
                   <!--begin::Option-->
                   <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                     <input class="form-check-input" type="radio" name="payment_type" value="all" checked="checked"/>
                     <span class="form-check-label text-gray-600">All</span>
                   </label>
                   <!--end::Option-->
                   <!--begin::Option-->
                   <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                     <input class="form-check-input" type="radio" name="payment_type" value="visa"/>
                     <span class="form-check-label text-gray-600">Visa</span>
                   </label>
                   <!--end::Option-->
                   <!--begin::Option-->
                   <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                     <input class="form-check-input" type="radio" name="payment_type" value="mastercard"/>
                     <span class="form-check-label text-gray-600">Mastercard</span>
                   </label>
                   <!--end::Option-->
                   <!--begin::Option-->
                   <label class="form-check form-check-sm form-check-custom form-check-solid">
                     <input class="form-check-input" type="radio" name="payment_type" value="americanexpress"/>
                     <span class="form-check-label text-gray-600">American Express</span>
                   </label>
                   <!--end::Option-->
                 </div>
                 <!--end::Options-->
               </div>
               <!--end::Input group-->
               <!--begin::Actions-->
               <div class="d-flex justify-content-end">
                 <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true"
                         data-kt-docs-table-filter="reset">Reset
                 </button>
                 <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true"
                         data-kt-docs-table-filter="filter">Apply
                 </button>
               </div>
               <!--end::Actions-->
             </div>
             <!--end::Content-->
           </div>
           <!--end::Menu 1-->
           <!--end::Filter-->
           <!--begin::Add customer-->
           <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" title="Coming Soon">
             <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
             <span class="svg-icon svg-icon-2">
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  xmlns="http://www.w3.org/2000/svg">
                               <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                     transform="rotate(-90 11.364 20.364)" fill="currentColor"/>
                               <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor"/>
                             </svg>
                           </span>
             <!--end::Svg Icon-->Add Customer
           </button>
           <!--end::Add customer-->
         </div>
         <!--end::Toolbar-->

         <!--begin::Group actions-->
         <div class="d-flex justify-content-end align-items-center d-none" data-kt-docs-table-toolbar="selected">
           <div class="fw-bold me-5">
             <span class="me-2" data-kt-docs-table-select="selected_count"></span> Selected
           </div>

           <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" title="Coming Soon">
             Selection Action
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
           <th>Created Date</th>
           <th class="text-end min-w-100px">Actions</th>
         </tr>
         </thead>
         <tbody class="text-gray-600 fw-semibold">
         </tbody>
       </table>
       <!--end::Datatable-->
     </div>
   </div>--}}

@endsection
