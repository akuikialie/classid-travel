@extends('layouts.web.app')

@section('page-styles')
    <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
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
                                class="form-control form-control-solid w-250px ps-15" placeholder="Search Transaksi" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                <i class="ki-duotone ki-filter fs-2"><span class="path1"></span><span class="path2"></span></i>
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
                                    <form id="form-filter">

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
                                            <label class="required" for="date_to">Category</label>
                                            <select class="form-select" data-control="select2" name="trx_method" data-placeholder="Select an option" data-allow-clear="true">
                                                <option></option>
                                                @foreach($transactionMethods as $transactionMethod)
                                                    <option value="{{ $transactionMethod->value }}" @selected(request()->input('trx_method') == $transactionMethod->value)>{{ $transactionMethod->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('trx_method')
                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="fv-row mb-8">
                                            <!--begin::Email-->
                                            <label class="required" for="date_to">Transaction Type</label>
                                            <select class="form-select" data-control="select2" name="trx_type" data-placeholder="Select an option">
                                                <option></option>
                                                @foreach($transactionTypes as $transactionType)
                                                    <option value="{{ $transactionType->value }}" @selected(old('trx_type') == $transactionType->value)>{{ $transactionType->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('trx_type')
                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                                    data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset
                                            </button>

                                            <button type="button" class="btn btn-primary" data-kt-menu-dismiss="true" id="apply-filter"
                                                    data-kt-customer-table-filter="filter">Apply
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                        </div>
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
                                            value="1" />
                                    </div>
                                </th>
                                <th>Owner</th>
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
