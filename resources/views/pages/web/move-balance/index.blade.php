@extends('layouts.web.app')
@section('page-content')
    <div class="card">
        <div class="card-header">
            <div class="card-title fs-3 fw-bold">{{ $pageTitle }}</div>
        </div>
        <form class="form" action="{{ route('admin.move-balance.move') }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="card-body p-9">
                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Jamaah</div>
                    </div>
                    <div class="col-xl-9 fv-row">
                        <select id="select-user" class="form-select form-select-solid" name="user_id"
                                data-placeholder="Pilih Jamaah" data-allow-clear="true">
                            <option></option>
                        </select>
                    </div>
                </div>

                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Dari Tabungan</div>
                    </div>
                    <div class="col-xl-9 fv-row">
                        <select id="saving-source" class="form-select form-select-solid" disabled name="source_balance"
                                data-placeholder="Pilih Tabungan" data-allow-clear="true">
                            <option></option>
                        </select>
                    </div>
                </div>

                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Ke Tabungan</div>
                    </div>
                    <div class="col-xl-9 fv-row">
                        <select id="saving-destination" class="form-select form-select-solid" disabled name="destination_balance"
                                data-placeholder="Pilih Tabungan" data-allow-clear="true">
                            <option></option>
                        </select>
                    </div>
                </div>


                <div class="row mb-8">
                    <div class="col-xl-3">
                        <div class="fs-6 fw-semibold mt-2 mb-3">Jumlah</div>
                    </div>
                    <div class="col-xl-9 fv-row">
                        <input type="number" class="form-control form-control-solid" name="amount"
                               value="">
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
            </div>
        </form>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            let baseUrl = "{{ env('APP_URL') }}";

            $('#select-user').select2({
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: baseUrl + '/api-option/users',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            tenant_id: "{{ activeTenant()->hash }}",
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.payload.data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: false
                }
            });

            $('#select-user').on('select2:select', function () {
                if ($('#saving-source').prop('disabled')) {
                    $('#saving-source').prop('disabled', false);
                }
                $('#saving-source').empty().trigger('change');

                if ($('#saving-destination').prop('disabled')) {
                    $('#saving-destination').prop('disabled', false);
                }
                $('#saving-destination').empty().trigger('change');
            });

            $('#saving-source').select2({
                allowClear: true,
                ajax: {
                    url: baseUrl + '/api-option/get-saving',
                    dataType: 'json',
                    data: function () {
                        return {
                            user_id: $('#select-user').val(),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' - ' + item.balance
                                };
                            })
                        };
                    },
                    cache: false
                }
            });

            $('#saving-destination').select2({
                allowClear: true,
                ajax: {
                    url: baseUrl + '/api-option/get-saving',
                    dataType: 'json',
                    data: function () {
                        return {
                            user_id: $('#select-user').val(),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.data.map(function (item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' - ' + item.balance
                                };
                            })
                        };
                    },
                    cache: false
                }
            });
        });
    </script>
@endpush
