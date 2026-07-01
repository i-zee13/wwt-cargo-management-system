@extends('layouts.master')
@section('data-sidebar')
<style>
    .iti--inline-dropdown {
        width: 100%;
    }
</style>
<form style="width: 100%" id="saveClientForm" autocomplete="off">
    @csrf
    <input type="text" id="operation" name="operation" hidden>
    <input type="text" id="key" name="key" hidden>
    <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
        aria-labelledby="access-rightLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading">{{__('fields.translations_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 form-group  ">
                        <label for="english" class="form-label">{{__('fields.english_text')}}</label>
                        <div class="icon-input">
                            <input type="text" name="english" class="form-control  " id="english" value=""
                                placeholder="{{__('fields.enter_english_text')}} ">
                        </div>

                    </div>
                    <div class="col-lg-12 col-md-12 form-group">
                        <label for="spanish" class="form-label">{{__('fields.spanish_text')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="spanish" class="form-control required_content" id="spanish"
                                value="" placeholder="{{__('fields.enter_spanish_text')}} ">
                        </div> 
                    </div>  
            </div>
        </div> 
    </div>
    <div class="offcanvas-footer">
        <button type="button" class="btn btn-primary me-2" id="saveBtn">{{__('fields.save')}}</button>
        <button type="button" class="btn btn btn-light close_sidebar"
            data-bs-dismiss="offcanvas">{{__('fields.close')}}</button>
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
            <input class="form-control" id="customSearchInput" type="text"
                placeholder="{{__('fields.search_translations_here')}}">
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
                        <h2>{{__('fields.translations_list')}}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto"
                    style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body clients_list p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.translations_management')}}')  
    </script> 
    <script src="/js/custom/translations.js"></script>
@endpush