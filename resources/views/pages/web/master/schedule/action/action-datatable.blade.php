@can("update {$current_page}")
  <a type="button" data-id="{{ $schedule->hash }}" data-bs-toggle="tooltip"
     title="Edit Jadwal"
     class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit-modal">
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
    <i class="fa-solid fa-caret-down"></i>
  </span>
  <!--end::Svg Icon-->
</a>

<!--begin::Menu-->
<div
  class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
  data-kt-menu="true">

  @can("update {$current_page}")
    <!--begin::Menu item-->
    <div class="menu-item px-3" data-kt-menu-trigger="hover"
         data-kt-menu-placement="right-start">
      <!--begin::Menu item-->
      <a href="#" class="menu-link px-3">
        <span class="me-2"><i class="fa-solid fa-caret-left"></i></span>
        <span class="menu-title">Ubah Status</span>
      </a>
      <!--end::Menu item-->
      <!--begin::Menu sub-->
      <div class="menu-sub menu-sub-dropdown w-175px py-4 px-4 ">
        <form method="post" data-kt-form-id="change-status-{{ $schedule->hash }}" action="{{ route('admin.schedule.change-status', $schedule->hash) }}">
          @csrf
          <a href="#" class="menu-link px-3 change-status {{ $schedule->is_active ? 'active' : '' }}"
             data-id="{{ $schedule->hash }}" data-status="1">
        <span class="menu-bullet">
          <span class="bullet bullet-dot"></span>
        </span>
            <span class="menu-title">Aktif</span>
          </a>
          <!--end::Menu item-->
          <!--begin::Menu item-->
          <a href="#" class="menu-link px-3 change-status {{ $schedule->is_active ? '' : 'active' }}"
             data-id="{{ $schedule->hash }}" data-status="0">
        <span class="menu-bullet">
          <span class="bullet bullet-dot"></span>
        </span>
            <span class="menu-title">Tidak Aktif</span>
          </a>
          <!--end::Menu item-->
        </form>
      </div>
      <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
  @endcan

  <!--begin::Menu item-->
  @can("delete {$current_page}")
    <div class="menu-item px-3 text-nowrap">
      <form action="{{ route('admin.schedule.destroy', $schedule->hash) }}" method="post"
            data-kt-form-id="delete-{{ $schedule->hash }}">
        @csrf
        @method('DELETE')
        <a class="menu-link px-3 btn-delete" data-id="{{ $schedule->hash }}"
           data-bs-toggle="tooltip" title="Hapus jadwal">
          <span class="badge badge-light-danger"> Hapus jadwal</span>
        </a>
      </form>
    </div>
  @endcan
  <!--end::Menu item-->
</div>


