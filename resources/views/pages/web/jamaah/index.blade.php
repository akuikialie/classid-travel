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
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <form id="form-filter" action="{{ routed('admin.transaction.download') }}" method="post">
                                @csrf

                                @if(!empty(request()->input('package_id')))
                                    <input name="package_id" value="{{ request()->input('package_id') }}" hidden="hidden">
                                @endif
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

                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_jamaah">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-2">
                                  <i class="fa-solid fa-plus"></i>
                                </span>
                                <!--end::Svg Icon-->Tambahkan Jamaah
                            </button>
                        </div>


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
                            <th>Total Paket Dipilih</th>
                            <th>Jumlah Tabungan</th>
                            <th>Dibuat Pada</th>
                            <th>Actions</th>
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


    <div class="modal fade" tabindex="-1" id="modal_add_jamaah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambahkan Jamaah Kedalam Paket Ini</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <!--begin::Modal body-->
                    <div class="modal-body py-lg-10 px-lg-10">
                        <!--begin::Form-->
                        <form class="form" action="{{ route('admin.jamaah.add-to-package') }}" method="post">
                            @csrf
                            <!--begin::Modal body-->
                            <div class="modal-body py-1 px-lg-17">
                                <!--begin::Scroll-->
                                <div class="scroll-y me-n7 pe-7" id="modal_create_activity" data-kt-scroll="true"
                                     data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                                     data-kt-scroll-offset="300px">

                                    <!--begin::Input group-->
                                    <div class="mb-5 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-5 fw-semibold mb-2">Jamaah</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="jamaah_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Select an option" data-allow-clear="true" data-allow-search="true" data-dropdown-parent="#modal_add_jamaah">
                                            <option></option>
                                            @foreach($jamaahs as $jamaah)
                                                <option value="{{ $jamaah->hash }}" @selected(old('user_id') == $jamaah->hash)>{{ $jamaah->user->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="mb-5 fv-row">
                                        <!--begin::Label-->
                                        <label class="required fs-5 fw-semibold mb-2">Paket</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select name="package_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Paket">
                                            <option></option>
                                            @foreach($packages as $package)
                                                <option value="{{ $package->hash }}" @selected(old('package_id', request()->input('package_id')) == $package->hash)>{{ $package->name }}</option>
                                            @endforeach
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->

                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3">Batal</button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Submit</span>
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
            </div>
        </div>
    </div>
@endsection
