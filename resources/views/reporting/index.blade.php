@extends('layouts.master')

@section('content')
<style>
    .icon-input input {
        background-color: white !important;
    }

    div.dt-buttons {
        float: right !important;
        padding: 11px !important;
        color: red !important;
    }

    .dt-buttons {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .dt-buttons .btn {
        font-size: 14px;
        padding: 6px 12px;
        margin-right: 8px;
        display: inline-flex;
        align-items: center;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">

<div class="row">
    <div class="col-lg-3 col-md-12 form-group">
        <label for="date" class="form-label">{{__('fields.dates')}} *</label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="form-control   required select_class" id="filter_date" name="filter_date"
                    data-name="date" style="width: 100%;">
                    <option value="1" selected>{{__('fields.all_dates')}}</option>
                    <option value="2">{{__('fields.select_custom_date')}}</option>

                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-12 form-group dates_div" style="display:none">
        <label for="start_date" class="form-label">{{__('fields.start_date')}} *</label>
        <div class="icon-input">
            <input type="text" name="start_date" class="form-control required datepicker" autocomplete="off"
                id="start_date" value="">
        </div>
    </div>
    <div class="col-lg-2 col-md-12 form-group dates_div" style="display:none">
        <label for="end_date" class="form-label">{{__('fields.end_date')}} *</label>
        <div class="icon-input">
            <input type="text" name="end_date" class="form-control required datepicker" autocomplete="off" id="end_date"
                value="">
        </div>
    </div>
    <div class="col-lg-3 col-md-12 form-group  ">
        <label for="origins" class="form-label">{{__('fields.select_origin')}} </label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="reports-select multi all-select" name="origins" multiple="multiple" id="origins"
                    placeholder="Select Assosiate Personas" style="width: 100%">
                    @isset($origins)
                        <option value="all" selected>All Origins</option>
                        @foreach ($origins as $row)
                            <option value="{{ $row->id }}" disabled>
                                {{ $row->origin_name }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

        </div>

    </div>
    <div class="col-lg-3 col-md-12 form-group  ">
        <label for="branches" class="form-label">{{__('fields.select_branch')}} </label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="reports-select multi all-select" name="branches" multiple="multiple" id="branches"
                    placeholder="Select Assosiate Personas" style="width: 100%">
                    @isset($branches)
                        <option value="all" selected>All Branches</option>
                        @foreach ($branches as $row)
                            <option value="{{ $row->id }}" disabled>
                                {{ $row->branch }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

        </div>

    </div>
    <div class="col-lg-3 col-md-12 form-group  ">
        <label for="statuses" class="form-label">{{__('fields.package_status_label')}} </label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="reports-select multi all-select" name="statuses" multiple="multiple" id="statuses"
                    placeholder="Select Assosiate Personas" style="width: 100%">
                    <option value="all" selected>All Status</option>
                    <option value="received" disabled>{{ __('fields.package_status_received') }}</option>
                    <option value="embarked" disabled>{{ __('fields.package_status_embarked') }}</option>
                    <option value="in-progress" disabled>
                        {{ __('fields.package_status_in_progress') }}
                    </option>
                    <option value="arrived" disabled>{{ __('fields.package_status_arrived') }}</option>
                    <option value="retired" disabled>{{ __('fields.package_status_retired') }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-12 form-group  ">
        <label for="types" class="form-label">{{__('fields.type')}} </label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="reports-select multi all-select " name="types" multiple="multiple" id="types"
                    placeholder="Select Assosiate Personas" style="width: 100%">
                    <option value="all" selected>All Types</option>
                    <option value="air" disabled>{{__('fields.air')}}
                    </option>
                    <option value="land" disabled>
                        {{__('fields.land')}}
                    </option>
                    <option value="maritime" disabled>
                        {{__('fields.maritime')}}
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 form-group  ">
        <label for="clients" class="form-label">{{__('fields.select_client')}} </label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="reports-select multi all-select" name="clients" multiple="multiple" id="clients"
                    placeholder="Select Assosiate Personas" style="width: 100%">
                    @isset($clients)
                        <option value="all" selected>All Clients</option>
                        @foreach ($clients as $row)
                            <option value="{{ $row->id }}" disabled>
                                {{ $row->first_name }} {{ $row->last_name }} ({{$row->suite}})
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>

        </div>
    </div>
    <div class="col-lg-3 col-sm-12 mt-4">
        <button type="button" class="btn btn-primary btn-block"
            id="generate_report">{{__('fields.generate_report')}}</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-6 mt-auto mb-auto   pr-0">
                        <h2>{{__('fields.reporting')}}</h2>

                    </div>
                    <div class="col-6 mt-auto mb-auto text-end dt-buttons">
                        <button id="exportExcelBtn" class=" exportExcelBtn btn btn-primary" style="display:none">
                            <i class="bi bi-file-earmark-arrow-down"></i> Export to Excel
                        </button>

                    </div>

                </div>
            </div>

            <div class="card-body report_append_div p-0">
                <div class="placeholder-div text-center">{{__('fields.filter_records')}}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.reporting')}}')  
    </script>





    <script src="/js/custom/reporting.js"></script>

@endpush
@push('js')
    <script>
        $(function () {
            window.fs_test = $('.multi').fSelect();
        });
    </script>

    <!-- Buttons Extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <!-- PDFMake (for PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <!-- JSZip (for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        var organizationName = "{{ getOrganizationData()->name ?? config('brand.name') }}";
        var organizationLogo = "{{ getOrganizationData()->logo_base64 ?? '' }}";
        var brandPdfWatermarkLabels = @json([strtolower(config('brand.short_name')), 'wwc']);
        organizationLogo = organizationLogo.replace(/^data:image\/(png|jpg);base64,/, '')
    </script>

@endpush