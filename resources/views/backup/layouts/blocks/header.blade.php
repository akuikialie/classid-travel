<div id="kt_header" style="" class="header align-items-stretch h-80px">
  <!--begin::Container-->
  <div class="container-fluid d-flex align-items-stretch justify-content-between">
    <!--begin::Aside mobile toggle-->
    <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
      <div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
        <!--begin::Svg Icon | path: icons/duotone/Text/Menu.svg-->
        <span class="svg-icon svg-icon-2x mt-1">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <rect x="0" y="0" width="24" height="24" />
              <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5" />
              <path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L18.5,10 C19.3284271,10 20,10.6715729 20,11.5 C20,12.3284271 19.3284271,13 18.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3" />
            </g>
          </svg>
        </span>
        <!--end::Svg Icon-->
      </div>
    </div>
    <!--end::Aside mobile toggle-->

    <!--begin::Wrapper-->
    <div class="d-flex align-items-stretch">
      <!--begin::Topbar-->
      <div class="d-flex align-items-stretch flex-shrink-0">

        <!--begin::Toolbar wrapper-->
        <div class="d-flex align-items-stretch flex-shrink-0">
          <span class="py-7 mb-2">
            <!--begin::Menu wrapper-->
            <div class="text-end">
              <span class="badge badge-light-primary my-1" id="clockSrv">{{ carbon()->isoFormat('ll LT \WIB') }}</span>
            </div>
            <!--end::Menu wrapper-->
          </span>

          <!--begin::User-->
          <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
            <!--begin::Menu wrapper-->
            <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
              {{-- <img src="{{ is_null($student->user->avatar) ? asset('images/avatar.png') : $student->user->avatar_url }}" alt="metronic" /> --}}
              <img src="{{ asset('images/avatar.png') }}" alt="metronic" />
            </div>
            <!--begin::Menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-300px" data-kt-menu="true">

              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-50px me-5">
                    {{-- <img alt="Logo" src="{{ is_null($student->user->avatar) ? asset('images/avatar.png') : $student->user->avatar_url }}" /> --}}
                    <img alt="Logo" src="{{ asset('images/avatar.png') }}" />
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Username-->
                  <div class="d-flex flex-column">
                    {{--<span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">{{ CID\Platform\Models\Accounts\User::TYPES[$activeUser->type] }}</span>--}}
                    <div class="fw-bolder d-flex align-items-center fs-5">{{ $activeUser->name }}</div>
                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{ $activeUser->username }}</a>
                  </div>
                  <!--end::Username-->
                </div>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start" data-kt-menu-flip="bottom">
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5 my-1">
                <a href="/app/setting" class="menu-link px-5">Account Settings</a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5">
                <a href="/auth/logout" class="menu-link px-5">Sign Out</a>
              </div>
              <!--end::Menu item-->
            </div>
            <!--end::Menu-->
            <!--end::Menu wrapper-->
            {{-- <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px position-relative ms-3" id="kt_drawer_chat_toggle">
              <!--begin::Svg Icon | path: icons/duotune/communication/com012.svg-->
              <i class="fa-solid fa-message-lines fs-1 text-primary"></i>
              <!--end::Svg Icon-->
              {{ <span class=" {{ $activeUser->unread_notifications <= 0 ? '' : 'bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink' }} ">
              </span> }}
            </div> --}}
          </div>
          <!--end::User -->
        </div>
        <!--end::Toolbar wrapper-->

      </div>
      <!--end::Topbar-->
    </div>
    <!--end::Wrapper-->
  </div>
  <!--end::Container-->
</div>
