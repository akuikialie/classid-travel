<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <!--begin::Toolbar container-->
  <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex align-items-center mt-n5 mt-lg-0 me-lg-2 pb-2 pb-lg-0" data-kt-swapper="true"
         data-kt-swapper-mode="prepend"
         data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
      @if(!empty($backLink))
        <div class="d-flex me-5">
          <a href="{{ $backLink }}" class="btn btn-icon btn-sm btn-light-dark hover-scale" title="Kembali">
            <i class="fas fa-arrow-left"></i>
          </a>
        </div>
      @endif
      <div class="d-flex flex-column align-items-start justify-content-center flex-wrap">
        <!--begin::Heading-->
        <h1 class="text-dark fw-bold my-0 fs-2">{{ $pageTitle ?? '' }}</h1>
        <!--end::Heading-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb fw-semibold fs-base my-1">
          <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Home</a>
          </li>
          @foreach ($breadCrumbs as $bc)
            @if($bc->url == '#' || empty($bc->url))
              <li class="breadcrumb-item text-dark active"><span>{{ $bc->title }}</span></li>
            @else
              <li class="breadcrumb-item text-muted">
                <a href="{{ $bc->url }}" class="text-muted"><span>{{ $bc->title }}</span></a>
              </li>
            @endif
          @endforeach
        </ul>
        <!--end::Breadcrumb-->
      </div>
    </div>
    <!--end::Page title=-->
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3">
      <!--begin::Filter menu-->
      {{-- <div class="m-0">
          <!--begin::Menu toggle-->
          <a href="#"
              class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold"
              data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
              <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
              <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                          fill="currentColor" />
                  </svg>
              </span>
              <!--end::Svg Icon-->Filter
          </a>
          <!--end::Menu toggle-->
          <!--begin::Menu 1-->
          <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
              id="kt_menu_6347b1af5c0ed">
              <!--begin::Header-->
              <div class="px-7 py-5">
                  <div class="fs-5 text-dark fw-bold">Filter Options</div>
              </div>
              <!--end::Header-->
              <!--begin::Menu separator-->
              <div class="separator border-gray-200"></div>
              <!--end::Menu separator-->
              <!--begin::Form-->
              <div class="px-7 py-5">
                  <!--begin::Input group-->
                  <div class="mb-10">
                      <!--begin::Label-->
                      <label class="form-label fw-semibold">Status:</label>
                      <!--end::Label-->
                      <!--begin::Input-->
                      <div>
                          <select class="form-select form-select-solid" data-kt-select2="true"
                              data-placeholder="Select option" data-dropdown-parent="#kt_menu_6347b1af5c0ed"
                              data-allow-clear="true">
                              <option></option>
                              <option value="1">Approved</option>
                              <option value="2">Pending</option>
                              <option value="2">In Process</option>
                              <option value="2">Rejected</option>
                          </select>
                      </div>
                      <!--end::Input-->
                  </div>
                  <!--end::Input group-->
                  <!--begin::Input group-->
                  <div class="mb-10">
                      <!--begin::Label-->
                      <label class="form-label fw-semibold">Member Type:</label>
                      <!--end::Label-->
                      <!--begin::Options-->
                      <div class="d-flex">
                          <!--begin::Options-->
                          <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                              <input class="form-check-input" type="checkbox" value="1" />
                              <span class="form-check-label">Author</span>
                          </label>
                          <!--end::Options-->
                          <!--begin::Options-->
                          <label class="form-check form-check-sm form-check-custom form-check-solid">
                              <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                              <span class="form-check-label">Customer</span>
                          </label>
                          <!--end::Options-->
                      </div>
                      <!--end::Options-->
                  </div>
                  <!--end::Input group-->
                  <!--begin::Input group-->
                  <div class="mb-10">
                      <!--begin::Label-->
                      <label class="form-label fw-semibold">Notifications:</label>
                      <!--end::Label-->
                      <!--begin::Switch-->
                      <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                          <input class="form-check-input" type="checkbox" value="" name="notifications"
                              checked="checked" />
                          <label class="form-check-label">Enabled</label>
                      </div>
                      <!--end::Switch-->
                  </div>
                  <!--end::Input group-->
                  <!--begin::Actions-->
                  <div class="d-flex justify-content-end">
                      <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                          data-kt-menu-dismiss="true">Reset</button>
                      <button type="submit" class="btn btn-sm btn-primary"
                          data-kt-menu-dismiss="true">Apply</button>
                  </div>
                  <!--end::Actions-->
              </div>
              <!--end::Form-->
          </div>
          <!--end::Menu 1-->
      </div> --}}
      <!--end::Filter menu-->
      <!--begin::Secondary button-->
      <!--end::Secondary button-->
      <!--begin::Primary button-->

      @yield('toolbar')
      <!--end::Primary button-->
    </div>
    <!--end::Actions-->
  </div>
  <!--end::Toolbar container-->
</div>
