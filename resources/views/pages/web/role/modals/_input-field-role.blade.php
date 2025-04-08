<!--begin::Input group-->
<div class="fv-row mb-10">
  <!--begin::Label-->
  <label class="fs-5 fw-bold form-label mb-2">
    <span class="required">Role name</span>
  </label>
  <!--end::Label-->
  <!--begin::Input-->
  <input class="form-control form-control-solid" {{ $is_on_edit ?? false ? 'readonly' : '' }}
  value="{{ $role->name ?? null }}" placeholder="Enter a role name"
         name="name"/>
  <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Permissions-->
<div class="fv-row">
  <!--begin::Label-->
  <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
  <!--end::Label-->

  <div id="kt_example_js_content">
    <!--begin::Table wrapper-->
    <div class="table-responsive">
      <!--begin::Table-->
      <table class="table align-middle table-row-dashed fs-6 gy-5">

        <!--begin::Table body-->
        <tbody class="text-gray-600 fw-semibold">
        <!--begin::Table row-->
        <tr>
          <td class="text-gray-800">Administrator Access
            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
               title="Allows a full access to the system"></i></td>
          <td>
            <!--begin::Checkbox-->
            <label class="form-check form-check-custom form-check-solid me-9">
              <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all"/>
              <span class="form-check-label" for="kt_roles_select_all">Select all</span>
            </label>
            <!--end::Checkbox-->
          </td>
        </tr>
        <!--end::Table row-->
        @foreach($permission_grouped as $permissionGroup => $permissions)
          <!--begin::Table row-->
          <tr>
            <!--begin::Label-->
            <td class="text-gray-800">{{ $permissionGroup }} Management</td>
            <!--end::Label-->
            <!--begin::Options-->
              @foreach($permissions as $permission)
                  <td>
                      <!--begin::Wrapper-->
                      <div class="d-flex">

                          <!--begin::Checkbox-->
                          <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                              <input class="form-check-input" type="checkbox"
                                     value="{{ strtolower("{$permission['id']}") }}"
                                     {{ $permission['is_active'] ? 'checked' : '' }} name="permissions[]"/>
                              <span class="form-check-label text-nowrap">{{ ucfirst($permission['name']) }}</span>
                          </label>
                          <!--end::Checkbox-->

                      </div>
                      <!--end::Wrapper-->
                  </td>

              @endforeach

            <!--end::Options-->
          </tr>
          <!--end::Table row-->
        @endforeach

        </tbody>
        <!--end::Table body-->

      </table>
      <!--end::Table-->
    </div>
    <!--end::Table wrapper-->
  </div>

</div>
<!--end::Permissions-->

