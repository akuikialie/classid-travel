@php use App\Enums\UserStatus; @endphp
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

  @can(\App\Enums\Permissions\UserPermission::USER_UPDATE->value)
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
        <form method="post" data-kt-form-id="change-status-{{ $user->hash }}"
              action="{{ route('admin.user.change-status', ['type' => $type,'user' => $user->hash]) }}">
          @csrf
          @foreach(UserStatus::cases() as $status)
            <a href="#" class="menu-link px-3 change-status {{ $status->value == $user->status ? 'active' : '' }}"
               data-id="{{ $user->hash }}" data-status="{{ $status->value }}">
            <span class="menu-bullet">
              <span class="bullet bullet-dot"></span>
            </span>
              <span class="menu-title">{{ $status->label() }}</span>
            </a>
          @endforeach
        </form>
      </div>
      <!--end::Menu sub-->
    </div>
    <!--end::Menu item-->
  @endcan

  @can(\App\Enums\Permissions\UserPermission::USER_DELETE->value)

    <!--begin::Menu item-->
    <div class="menu-item px-3 text-nowrap">
      <form action="{{ route('admin.user.destroy', ['type' => $type,'user' => $user->hash]) }}" method="post"
            data-kt-form-id="delete-{{ $user->hash }}">
        @csrf
        @method('DELETE')
        <a class="menu-link px-3 btn-delete" data-id="{{ $user->hash }}"
           data-bs-toggle="tooltip" title="Hapus Akun">
          <span class="badge badge-light-danger"> Hapus Akun</span>
        </a>
      </form>
    </div>
    <!--end::Menu item-->
  @endcan


</div>


