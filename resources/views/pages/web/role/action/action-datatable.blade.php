@can(\App\Enums\Permissions\RolePermission::ROLE_VIEW->value)
  <a href="{{ route('admin.role.show', $role->hash) }}" class="btn btn-light btn-active-light-primary btn-sm" >
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
    <span class="svg-icon svg-icon-5 m-0">
      <i class="fa-solid fa-eye"></i>
    </span>
    <!--end::Svg Icon-->
  </a>
@endcan

@can(\App\Enums\Permissions\RolePermission::ROLE_UPDATE->value)
  <a type="button" data-id="{{ $role->hash }}" class="btn btn-light btn-active-light-primary btn-sm update-role">
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
    <span class="svg-icon svg-icon-5 m-0">
      <i class="fa-solid fa-pen-to-square"></i>
    </span>
    <!--end::Svg Icon-->
  </a>
@endcan

@can(\App\Enums\Permissions\RolePermission::ROLE_DELETE->value)
  <a class="btn btn-light btn-active-light-primary btn-sm"
     data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
    <span class="svg-icon svg-icon-5 m-0">
      <i class="fa-solid fa-caret-down"></i>
    </span>
    <!--end::Svg Icon-->
  </a>

  <div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">

    <!--begin::Menu item-->
    <div class="menu-item px-3 text-nowrap">
      <form action="{{ route('admin.role.destroy', $role->hash) }}" method="post"
            data-kt-form-id="delete-{{ $role->hash }}">
        @csrf
        @method('DELETE')

        <a class="menu-link px-3 btn-delete" data-id="{{ $role->hash }}"
           data-bs-toggle="tooltip" title="Hapus Role ">
          <span class="badge badge-light-danger"> Hapus Role </span>
        </a>
      </form>
    </div>
    <!--end::Menu item-->
  </div>
@endcan


