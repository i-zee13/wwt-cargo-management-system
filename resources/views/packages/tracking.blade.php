@extends('layouts.master')

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
<div class="modal fade" id="requestStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content top-border p-0">
            <div class="modal-header statusMH">
                <h5 class="modal-title package-title" id="exampleModalLabel">Package Status</h5>
                <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body   pb-0">
                <div class="row">
                    <div class="col-12">
                        <h2 class="heading-text">Status</h2>
                    </div>
                </div>
                <div class="row" style="margin-top:10px">
                    <div class="col-4 status-sh StActive  ">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="received"
                                value="received" name="radio_status">
                            <label class="form-check-label head-sta" for="received">Received</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="embarked"
                                value="embarked" name="radio_status">
                            <label class="form-check-label head-sta" for="embarked">Embarked</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="Inprogress"
                                value="in-progress" name="radio_status">
                            <label class="form-check-label head-sta" for="Inprogress">InProgress</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="arrived"
                                value="arrived" name="radio_status">
                            <label class="form-check-label head-sta" for="arrived">Arrived</label>
                        </div>
                    </div>
                    <div class="col-4 status-sh StInActive">
                        <div class="form-check">
                            <input class="form-check-input radio_status pr-status-radio" type="radio" id="retired"
                                value="retired" name="radio_status">
                            <label class="form-check-label head-sta" for="retired">Retired</label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary update_status">Update</button>
                <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>-->
            </div>
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
                        <h2>{{__('fields.update_package_status')}}</h2>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form style="width: 100%" id="saveClientForm" autocomplete="off">
                    @csrf
                    <input type="text" id="operation" name="operation" hidden>
                    <input type="text" id="opp_id" name="opp_id" hidden>
                    <input type="text" id="opp_name_input" name="opp_name_input" hidden>

                    <div class="row">
                           <div class="col-lg-4 col-md-12 ">
                            <label for="tracking_status" class="form-label">{{__('fields.update_button')}}*</label>
                            <div class="icon-input">
                                <div class="form-s2">
                                    <select class="form-control required_content select_class tracking_status"
                                        id="tracking_status" name="tracking_status" data-name="tracking_status"
                                        style="width: 100%;">
                                        <option value="">{{ __('fields.package_status_select') }}</option>
                                        <option value="received">{{ __('fields.package_status_received') }}</option>
                                        <option value="embarked">{{ __('fields.package_status_embarked') }}</option>
                                        <option value="in-progress">{{ __('fields.package_status_in_progress') }}
                                        </option>
                                        <option value="arrived">{{ __('fields.package_status_arrived') }}</option>
                                        <option value="retired">{{ __('fields.package_status_retired') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 form-group suite_div">
                            <label for="tracking_waybill" class="form-label">{{__('fields.waybill')}}</label>
                            <div class="icon-input">
                                <input type="text" name="tracking_waybill" class="form-control  " id="tracking_waybill"
                                    value="" placeholder="{{__('fields.enter_waybill_package')}} ">
                            </div>
                        </div>
                     
                        <div class="col-4">
                            <button type="button" class="btn btn-primary me-2 mt-4 update_status isTracking"
                                isTracking="true" data-type="tracking-status" id="update_status">{{__('fields.update_button')}}</button>
                        </div>
                    </div>



                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.packages_tracking')}}') 
    </script>
    <script src="/js/custom/packages.js"></script>
@endpush