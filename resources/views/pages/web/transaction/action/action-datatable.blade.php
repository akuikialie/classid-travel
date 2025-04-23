<a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary show menu-dropdown"
   data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-outline ki-down fs-5 ms-1"></i>
</a>
<!--begin::Menu-->
<div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 py-4 w-150px"
    data-kt-menu="true" data-popper-placement="bottom-end">
    <!--begin::Menu item-->

    <div class="menu-item px-3">
        <a href="{{ route('admin.jamaah.mutations', ['user' => $transaction->user->hash, 'transaction_id' => $transaction->id ]) }}" class="menu-link px-3">
            Mutasi
        </a>
    </div>

    <!--end::Menu item-->
</div>
<!--end::Menu-->
