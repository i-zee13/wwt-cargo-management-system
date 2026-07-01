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
@if ($errors->has('waybill'))
    <div class="alert alert-danger">
        {{ $errors->first('waybill') }}
    </div>
@endif
  
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    <div class="col mt-auto mb-auto pl-0 pr-0">
                        <h2>{{__('fields.print_package_label')}}</h2>
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
                        <div class="col-lg-4 col-md-12 form-group suite_div">
                            <label for="tracking_waybill" class="form-label">{{__('fields.waybill')}}</label>
                            <div class="icon-input">
                                <input type="text" name="tracking_waybill"   class="form-control  "
                                    id="tracking_waybill" value="" placeholder="{{__('fields.enter_waybill_package')}} ">
                            </div> 
                        </div>  
                        <div class="col-4">
                        <button type="button" class="btn btn-primary me-2 mt-4 print_package_label" isTracking="true"  id="print_package_label">{{__('fields.print_label')}}</button>
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
    $('.content_head').text('{{__('fields.print_package_label')}}') 
</script>
<script src="/js/custom/packages.js"></script>
@endpush