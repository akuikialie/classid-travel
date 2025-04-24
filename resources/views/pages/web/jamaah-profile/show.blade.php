@extends('layouts.web.app')

@section('page-content')
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
                                       class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">{{ $user->name }}</a>
                                </div>
                                <!--end::Status-->
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack mt-3">
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
                                                        Rp. {{ moneyFormat($saving->balance) }}
                                                    </div>
                                                </div>
                                                <!--end::Number-->

                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">Saldo Tabungan (Rp)</div>
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
                                                        $ {{ moneyFormat($saving->usd_balance) }}
                                                    </div>
                                                </div>
                                                <!--end::Number-->

                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">Saldo Tabungan (USD)</div>
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
                                                        {{ $user->transactions_count }}
                                                    </div>
                                                </div>
                                                <!--end::Number-->

                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">Jumlah Transaksi</div>
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
                           {{--  <!--begin::Actions-->
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
                            <a href="{{ routed('admin.jamaah.overview', ['user' => $user->hash]) }}"
                               class="nav-link text-active-primary py-5 me-6 {{ request()->routeIs('admin.jamaah.overview') ? 'active' : null }}"
                               type="button" data-fragment="overview">Overview</a>
                        </li>
                        <!--end::Nav item-->

                        <li class="nav-item">
                            <a href="{{ routed('admin.jamaah.savings', ['user' => $user->hash]) }}"
                               class="nav-link text-active-primary py-5 me-6 {{ request()->routeIs('admin.jamaah.savings') ? 'active' : null }}"
                               type="button" data-fragment="savings">Tabungan</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ routed('admin.jamaah.transactions', ['user' => $user->hash]) }}"
                               class="nav-link text-active-primary py-5 me-6 {{ request()->routeIs('admin.jamaah.transactions') ? 'active' : null }}"
                               type="button" data-fragment="transactions">Transaksi</a>
                        </li>
                    </ul>
                    <!--end::Nav-->
                </form>
                <!--end:: fragment menu-->
            </div>
        </div>
        <!--end::Navbar-->

        @yield('fragment-content')
    </div>

@endsection
