@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col col-xsm-w pr-0">
        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z" stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text" placeholder="{{__('fields.search_employee')}}">
        </div>
    </div>
    <div class="col-auto col-xsm-w">
        <a href="/admin/create_employee" class="btn btn-primary new_emp_btn mt-0  " >+ {{__('fields.new_employee')}}</a>
        <!-- <button type="button" class="btn btn-primary mt-0">CREATE NEW REQUEST +</button> -->
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
                        <h2>{{__('fields.employees_list')}}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body employee_table_body p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $('.content_head').text('{{__('fields.employee_management')}}') 
</script>
@endpush