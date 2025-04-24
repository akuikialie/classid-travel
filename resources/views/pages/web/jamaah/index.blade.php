@extends('layouts.web.app')

@section('page-styles')
    <link href="{{ asset('web/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('page-custom-scripts')
    <script>
        let csrf_token = "{{ csrf_token() }}";
        let urlTable = "{{ route('admin.jamaah.datatable') }}";
    </script>
@endsection

@section('page-scripts')
    <script src="{{ asset('web/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('web/js/based/datatables/jamaah-datatable.js') }}"></script>
@endsection

@section('page-content')
    <div id="dynamic_modal"></div>

    <div class="card card-docs flex-row-fluid mb-2">
        <div class="card-body fs-6 py-15 py-lg-15 px-lg-15 px-10 text-gray-700">
            <div class="p-0">
                <!--begin::Heading-->
                <h3 class="card-title">Data Jamaah</h3>
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
                                   placeholder="Cari"/>
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
                                Selection Action
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
                            <th>Paket Dipilih</th>
                            <th>Jumlah Tabungan</th>s
                            <th>Dibuat Pada</th>
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
