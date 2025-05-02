@can(\App\Enums\Permissions\PackagePermission::PACKAGE_UPDATE->value)
    <a href="#" data-id="{{ $package->hash }}" data-bs-toggle="tooltip"
       title="Edit paket"
       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit-modal">
        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
        <span class="svg-icon svg-icon-3">
    <i class="fa-solid fa-pen-to-square"></i>
  </span>
        <!--end::Svg Icon-->
    </a>
@endcan
<a href="#" class="btn btn-light btn-active-light-primary btn-sm"
   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
    <span class="svg-icon svg-icon-5 m-0">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path
            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
            fill="currentColor"/></svg>
  </span>
    <!--end::Svg Icon-->
</a>
<!--begin::Menu-->
<div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">
    <!--begin::Menu item-->

    @can(\App\Enums\Permissions\PackagePermission::PACKAGE_UPDATE->value)
        <div class="menu-item px-3 text-nowrap">
            <a href="{{ route('admin.jamaah.index', ['package_id' => $package->hash]) }}" class="menu-link px-3"
               data-bs-toggle="tooltip"
               title="Setup kegiatan">Jamaah</a>
        </div>
    @endcan

    @can(\App\Enums\Permissions\PackagePermission::PACKAGE_CREATE->value)
        <div class="menu-item px-3 text-nowrap">
            <a class="menu-link px-3 btn-itinerary-setup" data-id="{{ $package->hash }}"
               data-bs-toggle="tooltip"
               title="Setup kegiatan">Setup Kegiatan</a>
        </div>
    @endcan
    <!--end::Menu item-->

    {{--<!--begin::Menu item-->
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
        @if (!$package->is_publish)
          <!--begin::Menu item-->
          <div class="menu-item px-3">
            <a href="#" class="menu-link px-3">Publish Paket</a>
          </div>
          <!--end::Menu item-->
        @else
          <!--begin::Menu item-->
          <div class="menu-item px-3">
            <a href="#" class="menu-link px-3">Unpublish Paket</a>
          </div>
          <!--end::Menu item-->
        @endif
      </div>
      <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->--}}

    @can(\App\Enums\Permissions\PackagePermission::PACKAGE_DELETE->value)
        <!--begin::Menu item-->
        <form action="{{ route('admin.package.destroy', $package->hash) }}"
              method="post" id="delete">
            @csrf
            @method('delete')
            <div class="menu-item px-3">
                <a onclick="$('#delete').submit()"
                   data-kt-subscriptions-table-filter="delete_row"
                   class="menu-link px-3 text-danger">Delete</a>
            </div>
        </form>
        <!--end::Menu item-->
    @endcan
</div>
<!--end::Menu-->
