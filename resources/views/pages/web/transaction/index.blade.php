@extends('layouts.web.app')

@section('page-styles')
    <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-custom-scripts')
    <script>
        let csrf_token = "{{ csrf_token() }}";
        let urlTable = "{{ route('admin.transaction.datatable') }}";
    </script>
@endsection

@section('page-scripts')
    <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('web/js/based/datatables/transaction-datatable.js') }}"></script>
@endsection

@section('page-content')
    <div id="dynamic_modal"></div>

    <div class="card card-docs flex-row-fluid mb-2">
        <div class="card-body fs-6 py-15 py-lg-15 px-lg-15 px-10 text-gray-700">
            <div class="p-0">
                <!--begin::Heading-->
                <h3 class="card-title">List Transaksi</h3>
                <!--end::Heading-->
                <!--begin::CRUD-->
                <div class="py-5">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack mb-5 flex-wrap">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative mb-md-0 my-1 mb-2">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-brands fa-searchengin"></i>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-docs-table-filter="search"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="Search Transaksi"/>
                        </div>
                        <!--end::Search-->
                        <!--begin::Toolbar-->
                        <form id="form-filter" action="{{ routed('admin.transaction.download') }}" method="post">
                            @csrf
                            <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                        data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    Filter
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-sm-300px w-md-500px" data-kt-menu="true"
                                     id="kt-toolbar-filter">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-4 text-gray-900 fw-bold">Filter Options</div>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->

                                    <!--begin::Content-->
                                    <div class="px-7 py-5">

                                        <!--begin::Input group-->
                                        <div class="row mb-6">
                                            <!--begin::Label-->
                                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                                {{ __('Date') }}
                                            </label>
                                            <!--end::Label-->

                                            <!--begin::Col-->
                                            <div class="col-lg-8">
                                                @include('components.range.date-range')
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->

                                        <div class="fv-row mb-8">
                                            <!--begin::Email-->
                                            <label class="required" for="date_to">Metode Pembayaran</label>
                                            <select class="form-select" data-control="select2" name="trx_method"
                                                    data-placeholder="Select an option" data-allow-clear="true">
                                                <option></option>
                                                @foreach($transactionMethods as $transactionMethod)
                                                    <option
                                                        value="{{ $transactionMethod->value }}" @selected(request()->input('trx_method') == $transactionMethod->value)>{{ $transactionMethod->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('trx_method')
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="fv-row mb-8">
                                            <!--begin::Email-->
                                            <label class="required" for="date_to">Tipe Transaksi</label>
                                            <select class="form-select" data-control="select2" name="trx_type"
                                                    data-placeholder="Select an option">
                                                <option></option>
                                                @foreach($transactionTypes as $transactionType)
                                                    <option
                                                        value="{{ $transactionType->value }}" @selected(old('trx_type') == $transactionType->value)>{{ $transactionType->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('trx_type')
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                                    data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">
                                                Reset
                                            </button>

                                            <button type="button" class="btn btn-primary" data-kt-menu-dismiss="true"
                                                    id="apply-filter"
                                                    data-kt-customer-table-filter="filter">Apply
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->

                                <!--begin::Export-->
                                <button type="submit" class="btn btn-light-primary me-3">
                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr078.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24"
                                             fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.3" x="12.75" y="4.25" width="12" height="2"
                                                  rx="1" transform="rotate(90 12.75 4.25)" fill="currentColor"/>
                                            <path
                                                d="M12.0573 6.11875L13.5203 7.87435C13.9121 8.34457 14.6232 8.37683 15.056 7.94401C15.4457 7.5543 15.4641 6.92836 15.0979 6.51643L12.4974 3.59084C12.0996 3.14332 11.4004 3.14332 11.0026 3.59084L8.40206 6.51643C8.0359 6.92836 8.0543 7.5543 8.44401 7.94401C8.87683 8.37683 9.58785 8.34458 9.9797 7.87435L11.4427 6.11875C11.6026 5.92684 11.8974 5.92684 12.0573 6.11875Z"
                                                fill="currentColor"/>
                                            <path opacity="0.3"
                                                  d="M18.75 8.25H17.75C17.1977 8.25 16.75 8.69772 16.75 9.25C16.75 9.80228 17.1977 10.25 17.75 10.25C18.3023 10.25 18.75 10.6977 18.75 11.25V18.25C18.75 18.8023 18.3023 19.25 17.75 19.25H5.75C5.19772 19.25 4.75 18.8023 4.75 18.25V11.25C4.75 10.6977 5.19771 10.25 5.75 10.25C6.30229 10.25 6.75 9.80228 6.75 9.25C6.75 8.69772 6.30229 8.25 5.75 8.25H4.75C3.64543 8.25 2.75 9.14543 2.75 10.25V19.25C2.75 20.3546 3.64543 21.25 4.75 21.25H18.75C19.8546 21.25 20.75 20.3546 20.75 19.25V10.25C20.75 9.14543 19.8546 8.25 18.75 8.25Z"
                                                  fill="currentColor"/>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon--> Export
                                </button>
                                <!--end::Export-->

                            </div>
                        </form>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                             data-kt-docs-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-docs-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger" data-kt-docs-table-select="delete_selected">
                                Selection
                                Action
                            </button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <table id="kt_datatable_example_1" class="table-row-dashed fs-6 gy-5 table align-middle">
                        <thead>
                        <tr class="fw-bold fs-7 text-uppercase gs-0 text-start text-gray-400">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                           data-kt-check-target="#kt_datatable_example_1 .form-check-input"
                                           value="1"/>
                                </div>
                            </th>
                            <th>Jamaah</th>
                            <th>Virtual Account</th>
                            <th>Invoice Number</th>
                            <th>Nominal</th>
                            <th>Tipe Transaksi</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Tanggal Transaksi</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                    <!--end::Datatable-->
                </div>
                <!--end::CRUD-->
            </div>
        </div>
    </div>
@endsection
