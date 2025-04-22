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

    @can(\App\Enums\Permissions\JamaahBalancePermission::JAMAAH_BALANCE_VIEW->value)
        @php
            if ($virtual_account->vaable instanceof \App\Models\User){
                $hashid = $virtual_account->vaable->hash;
            }else{
                $hashid = $virtual_account->vaable->user->hash;
            }
            @endphp
        <div class="menu-item px-3">
            <a href="{{ route('admin.jamaah.show', ['user' => $hashid ]) }}" class="menu-link px-3">
                detail
            </a>
        </div>
    @endcan

    @can(\App\Enums\Permissions\JamaahBalancePermission::JAMAAH_BALANCE_UPDATE->value)
        <div class="menu-item px-3">
            <a data-id="{{ $virtual_account->id }}" class="menu-link px-3 btn-balance-exchange">
                Konversi Saldo
            </a>
        </div>
    @endcan

    <!--end::Menu item-->
</div>
<!--end::Menu-->
