@extends('layouts.web.app')

@section('page-content')
    @php
        $avatars = $tenant->getMedia('avatars');
        $avatar = null;
        if ($avatars->count() > 0) {
            $avatar = collect($avatars)
                ->last()
                ->getUrl();
        }
    @endphp

    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Navbar-->
        <div class="card mb-6 mb-xl-9">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <!--begin::Image-->
                    <div
                        class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                        <img class="mw-50px mw-lg-75px" src="{{ $avatar ?? asset('logo/96w/logo-pict@96px.png') }}"
                            alt="image" />
                    </div>
                    <!--end::Image-->
                    <!--begin::Wrapper-->
                    <div class="flex-grow-1">
                        <!--begin::Head-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::Details-->
                            <div class="d-flex flex-column">
                                <!--begin::Status-->
                                <div class="d-flex align-items-center mb-1">
                                    <a href="#"
                                        class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">{{ $tenant->name }}</a>
                                    <span class="badge badge-light-success me-auto">{{ $tenant->slug }}</span>
                                </div>
                                <!--end::Status-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <a href="#"
                                        class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                        <span class="svg-icon svg-icon-4 me-1"><svg width="18" height="18"
                                                viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                    d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                                    fill="currentColor" />
                                                <rect x="7" y="6" width="4" height="4"
                                                    rx="2" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--> {{ $tenant->app_domain }}
                                    </a>
                                    <a href="#"
                                        class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                                        <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                    d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--> {{ $tenant->location }}
                                    </a>
                                    <a href="#"
                                        class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                        <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3"
                                                    d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--> {{ $tenant->email }}
                                    </a>
                                </div>
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <!--begin::Stats-->
                                        <div class="d-flex flex-wrap">
                                            <!--begin::Stat-->
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                    <div class="fs-2 fw-bold" data-kt-countup="true">
                                                        {{ $tenant->jamaah_count }}</div>
                                                </div>
                                                <!--end::Number-->

                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">Total Jamaah</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->

                                            <!--begin::Stat-->
                                            <div
                                                class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                    <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                    <div class="fs-2 fw-bold" data-kt-countup="true">
                                                        {{ $tenant->packages_count }}</div>
                                                </div>
                                                <!--end::Number-->

                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">Total paket</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--begin::Description-->
                                <div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-400">
                                    {{-- --}}
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Details-->
                            {{-- <!--begin::Actions-->
              <div class="d-flex mb-4">
                <a href="#" class="btn btn-sm btn-bg-light btn-active-color-primary me-3" data-bs-toggle="modal"
                   data-bs-target="#kt_modal_users_search">Add User</a>
                <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal"
                   data-bs-target="#kt_modal_new_target">Add Target</a>
                <!--begin::Menu-->
                <div class="me-0">
                  <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click"
                          data-kt-menu-placement="bottom-end">
                    <i class="bi bi-three-dots fs-3"></i>
                  </button>
                  <!--begin::Menu 3-->
                  <div
                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                    data-kt-menu="true">
                    <!--begin::Heading-->
                    <div class="menu-item px-3">
                      <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
                    </div>
                    <!--end::Heading-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                      <a href="#" class="menu-link px-3">Create Invoice</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                      <a href="#" class="menu-link flex-stack px-3">Create Payment
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                           title="Specify a target name for future usage and reference"></i></a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                      <a href="#" class="menu-link px-3">Generate Bill</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
                      <a href="#" class="menu-link px-3">
                        <span class="menu-title">Subscription</span>
                        <span class="menu-arrow"></span>
                      </a>
                      <!--begin::Menu sub-->
                      <div class="menu-sub menu-sub-dropdown w-175px py-4">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="#" class="menu-link px-3">Plans</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="#" class="menu-link px-3">Billing</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="#" class="menu-link px-3">Statements</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <div class="menu-content px-3">
                            <!--begin::Switch-->
                            <label class="form-check form-switch form-check-custom form-check-solid">
                              <!--begin::Input-->
                              <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked"
                                     name="notifications"/>
                              <!--end::Input-->
                              <!--end::Label-->
                              <span class="form-check-label text-muted fs-6">Recuring</span>
                              <!--end::Label-->
                            </label>
                            <!--end::Switch-->
                          </div>
                        </div>
                        <!--end::Menu item-->
                      </div>
                      <!--end::Menu sub-->
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 my-1">
                      <a href="#" class="menu-link px-3">Settings</a>
                    </div>
                    <!--end::Menu item-->
                  </div>
                  <!--end::Menu 3-->
                </div>
                <!--end::Menu-->
              </div>
              <!--end::Actions--> --}}
                        </div>
                        <!--end::Head-->
                        {{-- <!--begin::Info-->
            <div class="d-flex flex-wrap justify-content-start">
              <!--begin::Stats-->
              <div class="d-flex flex-wrap">
                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                  <!--begin::Number-->
                  <div class="d-flex align-items-center">
                    <div class="fs-4 fw-bold">29 Jan, 2022</div>
                  </div>
                  <!--end::Number-->
                  <!--begin::Label-->
                  <div class="fw-semibold fs-6 text-gray-400">Due Date</div>
                  <!--end::Label-->
                </div>
                <!--end::Stat-->
                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                  <!--begin::Number-->
                  <div class="d-flex align-items-center">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                    <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1"
                                            transform="rotate(-90 11 18)" fill="currentColor"/>
																			<path
                                        d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z"
                                        fill="currentColor"/>
																		</svg>
																	</span>
                    <!--end::Svg Icon-->
                    <div class="fs-4 fw-bold" data-kt-countup="true" data-kt-countup-value="75">0</div>
                  </div>
                  <!--end::Number-->
                  <!--begin::Label-->
                  <div class="fw-semibold fs-6 text-gray-400">Open Tasks</div>
                  <!--end::Label-->
                </div>
                <!--end::Stat-->
                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                  <!--begin::Number-->
                  <div class="d-flex align-items-center">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                    <span class="svg-icon svg-icon-3 svg-icon-success me-2">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
																			<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                                            transform="rotate(90 13 6)" fill="currentColor"/>
																			<path
                                        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                        fill="currentColor"/>
																		</svg>
																	</span>
                    <!--end::Svg Icon-->
                    <div class="fs-4 fw-bold" data-kt-countup="true" data-kt-countup-value="15000"
                         data-kt-countup-prefix="$">0
                    </div>
                  </div>
                  <!--end::Number-->
                  <!--begin::Label-->
                  <div class="fw-semibold fs-6 text-gray-400">Budget Spent</div>
                  <!--end::Label-->
                </div>
                <!--end::Stat-->
              </div>
              <!--end::Stats-->
              <!--begin::Users-->
              <div class="symbol-group symbol-hover mb-3">
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                  <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
                  <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-11.jpg"/>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michelle Swanston">
                  <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-7.jpg"/>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Francis Mitcham">
                  <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-20.jpg"/>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
                  <span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
                  <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-2.jpg"/>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
                  <span class="symbol-label bg-info text-inverse-info fw-bold">P</span>
                </div>
                <!--end::User-->
                <!--begin::User-->
                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
                  <img alt="Pic" src="/metronic8/demo1/assets/media/avatars/300-12.jpg"/>
                </div>
                <!--end::User-->
                <!--begin::All users-->
                <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal"
                   data-bs-target="#kt_modal_view_users">
                  <span class="symbol-label bg-dark text-inverse-dark fs-8 fw-bold" data-bs-toggle="tooltip"
                        data-bs-trigger="hover" title="View more users">+42</span>
                </a>
                <!--end::All users-->
              </div>
              <!--end::Users-->
            </div>
            <!--end::Info--> --}}
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Details-->
                <div class="separator"></div>
                <!--begin:: fragment menu-->
                <form action="" method="get" id="form-renderer">
                    <input hidden class="input-fragment" name="fragment">
                    <!--begin::Nav-->
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a href="{{ route('admin.tenant.showProfile', 'overview') }}"
                                class="nav-link text-active-primary py-5 me-6 {{ ($fragment_active ?? 'overview') == 'overview' ? 'active' : '' }}"
                                type="button" data-fragment="overview">Overview</a>
                        </li>
                        <!--end::Nav item-->
                        {{-- <!--begin::Nav item-->
            <li class="nav-item">
              <a class="nav-link text-active-primary py-5 me-6 {{ ($fragment_active ?? 'overview') == 'metadata' ? 'active' : '' }}"
                 type="button" data-fragment="metadata">Metadata</a>
            </li>
            <!--end::Nav item--> --}}
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a href="{{ route('admin.tenant.showProfile', 'media') }}"
                                class="nav-link text-active-primary py-5 me-6 {{ ($fragment_active ?? 'overview') == 'media' ? 'active' : '' }}"
                                type="button" data-fragment="media">Media</a>
                        </li>
                        <!--end::Nav item-->

                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a href="{{ route('admin.tenant.showProfile', 'setting') }}"
                                class="nav-link text-active-primary py-5 me-6 fragment {{ ($fragment_active ?? 'overview') == 'setting' ? 'active' : '' }}"
                                type="button" data-fragment="setting">Setting</a>
                        </li>
                        <!--end::Nav item-->

                        <!--begin::Nav item-->
                        <li class="nav-item">
                          <a href="{{ route('admin.tenant.showProfile', 'misc') }}"
                              class="nav-link text-active-primary py-5 me-6 fragment {{ ($fragment_active ?? 'overview') == 'misc' ? 'active' : '' }}"
                              type="button" data-fragment="misc">Misc</a>
                      </li>
                      <!--end::Nav item-->
                    </ul>
                    <!--end::Nav-->
                </form>
                <!--end:: fragment menu-->
            </div>
        </div>
        <!--end::Navbar-->

        @if (isset($fragment_view))
            @include($fragment_view)
        @endif
    </div>

@endsection
