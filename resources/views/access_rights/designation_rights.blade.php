@extends('layouts.master')
@section('data-sidebar')

<form style="width: 100%" id="saveRightsForm">
    @csrf
    <input type="text" id="operation" hidden>
    <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
        aria-labelledby="access-rightLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading">{{ __('fields.rights_management') }}</h2>
            <div class="form-check d-inline-block selectall">
                <input type="checkbox" name="rights[]" class="form-check-input   all_rights" value="" id="br-001">
                <label class="form-check-label" for="br-001">{{ __('fields.select_all') }}</label>
            </div>

            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row employee_row">
                            <input type="hidden" name="employee_id" id="employee_id_hidden" value="">
                            <input type="hidden" id="get-all-designations" value="{{ $designations }}">
                            <div class="col-lg-6 col-md-12 form-group" id="employeesRow">
                                <label for="employee_id" class="form-label">{{ __('fields.rights_management') }} *</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select class="form-control custom_my_class required" id="employee_id"
                                            name="employee_id" data-name="Employee" style="width: 100%;">

                                            @foreach ($designations as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->designation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @foreach ($controllers as $count => $heading)
                            <div class="row">
                                <div class="col-12 position-relative">
                                    <h3>
                                        <div class="form-check d-inline-block mb-0">
                                            <input
                                                class="form-check-input supplementary_services_client access_rights_headings"
                                                type="checkbox" heading="{{ $heading['heading'] }}" name="rights[]" value=""
                                                id="select-all-{{ $count }}">
                                            <label class="form-check-label"
                                                for="select-all-{{ $count }}">{{ $heading['heading'] }}</label>
                                        </div>
                                    </h3>
                                </div>

                                <div class="col-12">
                                    <div class="div-rights">
                                        <div class="row">
                                            @foreach ($heading['sub_mod'] as $key => $rights)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input   supplementary_services_client access_rights_emp"
                                                            type="checkbox" heading="{{ $heading['heading'] }}" name="rights[]"
                                                            value="{{ $rights['controller'] }}"
                                                            id="{{ $rights['made_up_name'] . $key }}">
                                                        <label class="form-check-label"
                                                            for="{{ $rights['made_up_name'] . $key }}">{{ $rights['made_up_name'] }}</label>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary me-2" id="saveRights">{{ __('fields.save') }}</button>
            <button type="button" class="btn btn btn-light close_sidebar" data-bs-dismiss="offcanvas">{{ __('fields.close') }}</button>
        </div>
    </div>
</form>
@endsection
@section('content')

<div class="row">
    <div class="col col-xsm-w pr-0">

        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z"
                    stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text" placeholder="{{ __('fields.search_access_rights') }}">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    <div class="col mt-auto mb-auto pl-0 pr-0">
                        <h2>{{ __('fields.access_rights_list') }}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto"
                    style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body body_accessrights p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.content_head').text("{{ __('fields.manage_rights_management') }}");
    });
</script>
<script> 
var translations = {
    'sno': '{{ __('fields.sno') }}',
    'role': '{{ __('fields.role') }}',
    'actions': '{{ __('fields.actions') }}',
    'total_rights': '{{ __('fields.total_rights') }}'
};
    console.log(translations);

</script>
    <script src="/js/custom/designation_access_rights.js"></script>
@endpush