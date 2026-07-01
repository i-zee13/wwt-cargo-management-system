@extends('layouts.master')
@section('data-sidebar')
<div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
    aria-labelledby="access-rightLabel">
    <form id="saveCountryForm" style="display:none">
        @csrf
        <input type="text" name="hidden_country_id" hidden />
        <input type="text" name="operation" id="operation" hidden>
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading"> {{__('fields.country_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 form-group  ">
                        <label for="name" class="form-label">{{__('fields.country_name')}}*</label>
                        <div class="icon-input">
                            <input type="text" name="country_name"
                                class="form-control required_designation avoidSpecialChars" id="country_name"
                                data-name=""  >
                        </div>

                    </div>
                    <div class="col-lg-12 col-md-12 form-group  ">
                        <label for="iso" class="form-label">{{__('fields.country_iso')}}*</label>
                        <div class="icon-input">
                            <input type="text" name="iso" class="form-control required_designation avoidSpecialChars"
                                id="iso" data-name=""  >
                        </div>

                    </div>

                </div>

            </div>


        </div>
    </form>
    <form id="saveStateForm" style="display:none">
        @csrf
        <input type="text" name="hidden_state_id" hidden />
        <input type="text" name="operation_state" id="operation_state" hidden>

        <div class="offcanvas-header">
            <h2 class="offcanvas-heading"> {{__('fields.state_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-lg-6 col-md-12 form-group" id=" ">
                        <label for="countries" class="form-label">{{__('fields.country')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control all_countries_form_state select_class" id="countries"
                                    name="country_id" data-name="Country" style="width: 100%;">

                                </select>
                            </div>
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                    </div>
                    <div class="col-lg-6 col-md-12 form-group  ">
                        <label for="name" class="form-label">{{__('fields.state')}}*</label>
                        <div class="icon-input">
                            <input type="text" name="state_name" class="form-control avoidSpecialChars" id="state_name"
                                data-name=""  >
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </form>
    <form id="saveCityForm" style="display:none">
        @csrf
        <input type="text" name="hidden_city_id" hidden />
        <input type="text" name="operation_city" id="operation_city" hidden>
        <input type="text" name="hidden_city_state_id" id="hidden_city_state_id" hidden />
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading"> {{__('fields.city_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-lg-6 col-md-12 form-group" id=" ">
                        <label for="country_id" class="form-label">{{__('fields.country')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control all_countries_form select_class" id="country_id"
                                    name="country_id" data-name="Country" style="width: 100%;">

                                </select>
                            </div>
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                    </div>
                    <div class="col-lg-6 col-md-12 form-group" id="">
                        <label for="state_id" class="form-label">{{__('fields.state')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control all_states_form select_class" id="state_id" name="state_id"
                                    data-name="Country" style="width: 100%;">

                                </select>
                            </div>
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                    </div>
                    <div class="col-lg-6 col-md-12 form-group">
                        <label for="name" class="form-label">{{__('fields.city')}}*</label>
                        <div class="icon-input">
                            <input type="text" name="city_name" class="form-control avoidSpecialChars" id="city_name"
                                data-name="" placeholder="">
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </form>
    <div class="offcanvas-footer" style="position: absolute; bottom:0; width:100%">
        <button type="button" class="btn btn-primary me-2" id="saveBtn">{{__('fields.save')}}</button>
        <button type="button" class="btn btn btn-light close_sidebar" data-bs-dismiss="offcanvas">{{__('fields.close')}}</button>
    </div>
</div>
@endsection
@section('content')
<style>
    .search {
        width: 360px !important;
    }

    .search .form-control {
        border: 0.0625rem solid #e5e5e5;
    }

    .set-width-filter {
        width: 280px;
        float: left;
        margin-right: 0.9375rem;
    }

    .col-xsm-w .btn-primary {
        padding: 0.75rem 1rem;
    }

    .PB-10 {
        padding-bottom: 10px;
    }

    @media (max-width:480px) {

        .set-width-filter {
            width: 100% !important;
            float: left;
            margin-right: 0;
            margin-bottom: 0.625rem;
        }

        .col-xsm-w .btn-primary {
            width: 100% !important;
            padding: 0.625rem 0.625rem;
        }
    }
</style>
{{-- <div class="row ">
    <div class="col col-xsm-w pr-0 ">
        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z"
                    stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text" placeholder="Search Countries Here...">
        </div>
    </div>
</div> --}}
<div class="row  m-0" style="margin-top: 20px!important;">
    <div class="tabs-header border-0 pr-0">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active geographical-tabs" id="pills-001-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-001" type="button" role="tab" aria-controls="pills-001"
                    aria-selected="true">{{__('fields.countries')}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link geographical-tabs" id="pills-002-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-002" type="button" role="tab" aria-controls="pills-002"
                    aria-selected="false">{{__('fields.states')}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link geographical-tabs" id="pills-003-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-003" type="button" role="tab" aria-controls="pills-003"
                    aria-selected="false">{{__('fields.cities')}}</button>
            </li>

        </ul>
    </div>
</div>
<div class="row  " style="margin-top: 20px">
    <div class="col-auto col-xsm-w pr-0">
        <div class="search set-width-filter">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z"
                    stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" type="text" id="customSearchInput" placeholder="{{__('fields.search_countries_here')}}">
        </div>

        <div class="icon-input set-width-filter filters-citites" style="display:none;">
            <div class="form-s2">
                <select class="form-control select_class" id="searchCountry" name="searchCountry" style="width: 100%;">
                </select>
            </div>
            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="icon-input set-width-filter filters-citites" style="display:none;">
            <div class="form-s2">
                <select class="form-control select_class" id="searchState" name="searchState" style="width: 100%;">

                </select>
            </div>
            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

    </div>
    <div class="col pl-0 col-xsm-w filters-citites" style="display:none;">
        <button class="btn btn-primary searchCity">
        {{__('fields.search')}} </button>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade show active" id="pills-001" role="tabpanel" aria-labelledby="pills-001-tab"
                    tabindex="0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col mt-auto mb-auto">
                                <h2 class="heading02">{{__('fields.countries_list')}}</h2>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-add-guest openDataSidebarForAddingCountries">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg> {{__('fields.add_new')}}</button>
                            </div>
                        </div>


                    </div>
                    <div style="min-height: 400px" class="loader">
                        <img src="/images/loader.gif" width="30px" height="auto"
                            style="position: absolute; left: 47%; top: 45%;">
                    </div>
                    <div class="card-body CountriesTbl">

                    </div>
                </div>

                <div class="tab-pane fade" id="pills-002" role="tabpanel" aria-labelledby="pills-002-tab" tabindex="0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col mt-auto mb-auto">
                                <h2 class="heading02">{{__('fields.states_list')}}</h2>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-add-guest openDataSidebarForAddingStates">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg> {{__('fields.add_new')}}</button>
                            </div>
                        </div>
                    </div>
                    <div style="min-height: 400px" class="loader">
                        <img src="/images/loader.gif" width="30px" height="auto"
                            style="position: absolute; left: 47%; top: 45%;">
                    </div>
                    <div class="card-body StatesTbl">


                    </div>
                </div>
                <div class="tab-pane fade" id="pills-003" role="tabpanel" aria-labelledby="pills-003-tab" tabindex="0">

                    <div class="card-header">

                        <div class="row " style="margin-top: 20px;">
                            <div class="col mt-auto mb-auto">
                                <h2 class="heading02">{{__('fields.cities_list')}}</h2>
                            </div>

                            <div class="col-auto">
                                <button class="btn btn-add-guest openDataSidebarForAddingCities">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg> {{__('fields.add_new')}}</button>
                            </div>
                        </div>

                    </div>

                    <div class="card-body CitiesTbl">


                    </div>
                    <div style="min-height: 400px" class="Cititesloader">
                        <img src="/images/loader.gif" width="30px" height="auto"
                            style="position: absolute; left: 47%; top: 45%;">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
    $('.content_head').text('{{__('fields.manage_locations')}}')
</script>
<script src="/js/custom/geographical_setting.js"></script>
@endpush