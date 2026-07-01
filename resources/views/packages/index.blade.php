@extends('layouts.master')
@section('data-sidebar')
<form style="width: 100%" id="savePackageSidebar" autocomplete="off">
    @csrf
    <input type="text" id="operation" name="operation" hidden>
    <input type="hidden" id="opp_id" name="opp_id" hidden>
    <input type="text" id="opp_name_input" name="opp_name_input" hidden>
    <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
        aria-labelledby="access-rightLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading">{{__('fields.package_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row"> 
                    <input type="hidden" name="package_id"id="package_id" value="">
                    <div class="col-lg-12 col-md-12">
                        <label for="origin" class="form-label">{{__('fields.origin')}} </label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required select_class" id="origin" name="origin"
                                    data-name="origin" style="width: 100%;">
                                    <option value="">{{__('fields.select_origin')}}</option>
                                    @if(@$origins)
                                    @foreach (@$origins as $origin)
                                        <option value="{{ $origin->id }}" {{@$package->origin_id == $origin->id ? 'selected' : ''}} rate="{{$origin->rate}}">
                                            {{ $origin->origin_name }}
                                        </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-3 form-group">
                        <label for="kg" class="form-label">{{__('fields.kg')}} </label>
                        <div class="icon-input">
                            <input type="text" name="kg" class="form-control only_decimals_no" id="kg"
                                placeholder="{{__('fields.enter_kg')}}"  >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-3 form-group">
                        <label for="cbm" class="form-label">{{__('fields.cbm')}} </label>
                        <div class="icon-input">
                            <input type="text" name="cbm" class="form-control only_decimals_no" id="cbm"
                                placeholder="{{__('fields.enter_cbm')}}" >
                        </div>

                    </div>
                    <div class="col-lg-12 col-md-12 mt-3 form-group">
                        <label for="grand_total" class="form-label">{{__('fields.grand_total')}} </label>
                        <div class="icon-input">
                            <input type="text" name="grand_total" class="form-control   only_decimals_no"
                                id="grand_total" readonly placeholder="" >
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary me-2" id="saveSideBarBtn">{{__('fields.save')}}</button>
            <button type="button" class="btn btn btn-light close_sidebar"
                data-bs-dismiss="offcanvas">{{__('fields.close')}}</button>
        </div>
    </div>
</form>
@endsection
@section('content')
<style>
    .StActive .custom-radio .custom-control-input:checked~.custom-control-label::before,
    .StActive .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #00C20D;
    }

    .StInActive .custom-radio .custom-control-input:checked~.custom-control-label::before,
    .StInActive .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #E00000;
    }

    .StInDraft .custom-radio .custom-control-input:checked~.custom-control-label::before,
    .StInDraft .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #b7b1b1;
    }

    .package-title span {

        font-weight: lighter !important;

    }

    .ST-Active .fa-circle {
        font-size: 10px;
        color: #00C20D;
        margin-right: 8px
    }

    .ST-InActive .fa-circle {
        font-size: 10px;
        color: #E00000;
        margin-right: 10px
    }

    .ST-draft .fa-circle {
        font-size: 10px;
        color: #b7b1b1;
        margin-right: 10px
    }


    .search .form-control {
        border: 0.0625rem solid #e5e5e5;
    }

    .set-width-filter {
        width: 300px;
        float: left;
        margin-right: 0.9375rem;
    }

    .col-xsm-w .btn-primary {
        padding: 0.75rem 1rem;
    }

    .PB-10 {
        padding-bottom: 10px;
    }

    .heading-text {
        color: #79808E;
        font-size: 1rem;
    }
</style>
<input type="hidden" value="{{@$status}}" id="homePageStatus" hidden>
<div class="modal fade" id="requestStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-border p-0">
            <div class="modal-header statusMH">
                <h5 class="modal-title package-title" id="exampleModalLabel">
                    {{ __('fields.package_status_modal_title') }}
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body pb-0">
                <div class="row">
                    <div class="col-12">
                        <h2 class="heading-text">{{ __('fields.status_heading') }}</h2>
                    </div>
                </div>
                <div class="row" style="margin-top:10px">
                    <div class="col-4 status-sh StActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="received"
                                value="received" name="radio_status">
                            <label class="form-check-label head-sta"
                                for="received">{{ __('fields.package_status_received') }}</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="embarked"
                                value="embarked" name="radio_status">
                            <label class="form-check-label head-sta"
                                for="embarked">{{ __('fields.package_status_embarked') }}</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="Inprogress"
                                value="in-progress" name="radio_status">
                            <label class="form-check-label head-sta"
                                for="Inprogress">{{ __('fields.package_status_in_progress') }}</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="arrived"
                                value="arrived" name="radio_status">
                            <label class="form-check-label head-sta"
                                for="arrived">{{ __('fields.package_status_arrived') }}</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="retired"
                                value="retired" name="radio_status">
                            <label class="form-check-label head-sta"
                                for="retired">{{ __('fields.package_status_retired') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary update_status">{{ __('fields.update_button') }}</button>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col col-xsm-w pr-0">
        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z"
                    stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text"
                placeholder="{{ __('fields.search_packages') }}">
        </div>
    </div>

    <div class="col-auto col-xsm-w">
        <a href="{{GetActiveGuardDetail()->is_web ? '/admin/create-package' : '/create-customer-package'}}"
            class="btn btn-primary   mt-0  ">+{{ __('fields.new_package') }}</a>
        <!-- <button type="button" class="btn btn-primary mt-0">CREATE NEW REQUEST +</button> -->
    </div>

</div>

<div class="row mt-3">
    <div class="col-lg-4 col-md-6">
        <label for="origin" class="form-label">{{ __('fields.package_status_label') }}</label>
        <div class="icon-input">
            <div class="form-s2">
                <select class="form-control select_class" id="package_status" name="package_status"
                    data-name="package_status" style="width: 100%;">
                    <option value="">{{ __('fields.package_status_select') }}</option>
                    <option value="received">{{ __('fields.package_status_received') }}</option>
                    <option value="embarked">{{ __('fields.package_status_embarked') }}</option>
                    <option value="in-progress" {{@$status == 'in-progress' ? 'selected' : ''}}>
                        {{ __('fields.package_status_in_progress') }}
                    </option>
                    <option value="arrived" {{@$status == 'arrived' ? 'selected' : ''}}>
                        {{ __('fields.package_status_arrived') }}
                    </option>
                    <option value="retired" {{@$status == 'retired' ? 'selected' : ''}}>
                        {{ __('fields.package_status_retired') }}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 mt-4">
        <button class="btn btn-primary" id="search_package" title="Search">{{__('fields.search')}}</button>
          <button class="btn btn-primary" id="get_all_package" title="Search">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
  <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
</svg>
          </button>
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
                        <h2>{{__('fields.packages_list')}}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto"
                    style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body package_body p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.package_management')}}') 
    </script>
    <script src="/js/custom/packages.js"></script>
@endpush