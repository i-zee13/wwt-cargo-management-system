@extends('layouts.master')
@section('data-sidebar')
<style>
    .iti--inline-dropdown {
        width: 100%;
    }
</style>
<form style="width: 100%" id="saveRateForm" autocomplete="off">
    @csrf
    <input type="text" id="operation" name="operation" hidden>
    <input type="text" id="opp_id" name="opp_id" hidden>
    <input type="text" id="opp_name_input" name="opp_name_input" hidden>

    <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
        aria-labelledby="access-rightLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading">{{ __('fields.freight_rate_detail') }}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="{{ __('fields.close') }}"></button>
        </div>

        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">

                    <div class="col-lg-12 col-md-12">
                        <label for="branch" class="form-label">{{ __('fields.branch') }}*</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required_content select_class" id="branch" name="branch"
                                    data-name="branch" style="width: 100%;">
                                    <option value="">{{ __('fields.select_branch') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->branch }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 mt-3">
                        <label for="type" class="form-label">{{ __('fields.type') }}*</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required_main select_class" id="rate_type" name="rate_type"
                                    data-name="rate_type" style="width: 100%;">
                                    <option value="">{{ __('fields.select_type') }}</option>
                                    <option value="air">{{ __('fields.type_air') }}</option>
                                    <option value="land">{{ __('fields.type_land') }}</option>
                                    <option value="maritime">{{ __('fields.type_maritime') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 mt-3">
                        <label for="origin" class="form-label">{{ __('fields.origin') }}*</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required_content select_class" id="origin" name="origin"
                                    data-name="origin" style="width: 100%;">
                                    <option value="">{{ __('fields.select_origin') }}</option>
                                    @foreach ($origins as $origin)
                                        <option value="{{ $origin->id }}">
                                            {{ $origin->origin_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 mt-3">
                        <label for="client_id" class="form-label">{{ __('fields.customer') }}</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control   select_class" id="client_id" name="client_id"
                                    data-name="client_id" style="width: 100%;">
                                    <option value="">{{ __('fields.select_client') }}</option>
                                    @foreach (@$customers as $customer)
                                        <option value="{{ $customer->id }}">
                                        {{ $customer->first_name . ' ' . $customer->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 form-group mt-3">
                        <label for="rate" class="form-label">{{ __('fields.rate') }} *</label>
                        <div class="icon-input">
                            <input type="text" name="rate" class="form-control required_content only_decimals_no" id="rate"
                                value="" placeholder="{{ __('fields.enter_rate') }}">
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 form-group mt-1">
                        <label for="additional" class="form-label">{{ __('fields.additional') }}</label>
                        <div class="icon-input">
                            <input type="text" name="additional" class="form-control only_decimals_no" id="additional"
                                value="" placeholder="{{ __('fields.enter_additional') }}">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary me-2" id="saveBtn">{{ __('fields.save') }}</button>
            <button type="button" class="btn btn-light close_sidebar" data-bs-dismiss="offcanvas">{{ __('fields.close') }}</button>
        </div>
    </div>
</form>

@endsection
@section('content')
  
<div class="row">
    <div class="col col-xsm-w pr-0">
        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z" stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text" placeholder="{{ __('fields.search_freight_rates_here') }}">
        </div>
    </div>
    <div class="col-auto col-xsm-w">
        <button  class="btn btn-primary openDataSidebarForAddingRate mt-0 " >+ {{ __('fields.new_freight_rate') }}</button> 
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
                        <h2>{{ __('fields.freight_rate_list') }}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body rate_list p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
     $('.content_head').text('{{__('fields.freight_rate_management')}}')  
    </script>
  
 
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

 
<script src="/js/custom/freight_rates.js?v=1.5.0.1"></script>  
@endpush
