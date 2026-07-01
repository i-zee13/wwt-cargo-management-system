@extends('layouts.master')
@section('content')
<style>
    .UserProfile .u-info {
        border-top: solid 1px #e1e1eb;
        border-radius: 0;
        line-height: 1;
        font-size: 14px;
        letter-spacing: 1px;
        padding: 15px 0;
        margin: 0;
        display: block;
    }

    .UserProfile .control-label {
        opacity: 0.75;
        font-size: 13px;
    }

    ._head01 {
        font-size: 20px;
        margin: auto;
        line-height: 1;
    }

    .UserImage {
        border: solid 1px #00843d;
        width: 52px;
        height: 52px;
        background-color: #FFF;
        padding: 1px;
        border-radius: 50%;
        float: left;
    }

    .top-performer {
        background-color: #fff;
        border-radius: 12px;
        font-size: 14px;
        padding: 0;
        border: solid 0.0625rem rgba(0, 0, 0, 0.1);
        border-radius: var(--border-radius);
    }

    .top-performer .nav-tabs {
        background: linear-gradient(0deg, #f8f8f8 0%, #ffffff 50%);
        border: none;
        border-radius: 10px;
        padding: 10px;
    }

    .top-performer .nav-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        color: #979797;
        height: auto;
        line-height: 1;
        letter-spacing: 1px;
        font-size: 14px;
        margin-right: 10px;
    }

    .top-performer .nav-tabs .nav-link svg {
        width: 16px;
        height: 16px;
        margin-right: 5px;
        margin-top: -3px;
        fill: #4d815c;
    }

    .top-performer .nav-tabs .nav-item.show,
    .top-performer .nav-tabs .nav-link:HOVER,
    .top-performer .nav-tabs .nav-link.active {
        border: none;
        color: #fff;
        background-color: #4d815c;
    }

    .top-performer .nav-tabs .nav-link.active svg,
    .top-performer .nav-tabs .nav-link:HOVER svg {
        fill: #fff !important;
        opacity: 1;
    }

    .top-performer .tab-pane {
        padding: 25px
    }

    .mobNo-lab {
        font-size: 16px;
        letter-spacing: 2px;
        margin-top: auto;
        margin-bottom: auto;
    }

    .mobNo-lab svg {
        width: 18px;
        height: 18px;
        margin-top: -3px;
    }

    .top-performer #floating-label .form-group .form-control {
        border-radius: 6px;
    }

    .top-performer .dropify-wrapper {
        height: 130px;
        width: 130px;
        border-radius: 8px;
    }

    .top-performer .label-update {
        background: #4d815c;
        color: #fff;
        text-align: center;
        font-size: 11px;
        line-height: 1;
        padding: 3px;
        margin-top: -24px;
        margin-left: 7px;
        z-index: 5;
        position: relative;
        width: 50px
    }

    .top-performer .dropify-message p {
        letter-spacing: 0;
    }

    .top-performer ._cut-img {
        padding-bottom: 20px;
    }

    .AssNotification {
        padding: 0;
        margin-top: 0;
    }

    .AssNotification thead th {
        background: transparent !important;
        font-size: 14px;
    }

    .AssNotification .switch {
        width: 38px;
        height: 20px;
    }

    .AssNotification .switch .slider:before {
        height: 12px;
        width: 12px;
    }

    .top-performer .btn {
        border-radius: 6px !important;
        box-shadow: none !important;
    }

    .switch {
        position: relative !important;
        display: inline-block !important;
        width: 30px !important;
        height: 17px !important;
        margin-bottom: 0 !important;
        margin-right: 5px !important
    }

    .switch input {
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important
    }

    .switch .slider {
        position: absolute !important;
        cursor: pointer !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        background-color: #ccc !important;
        -webkit-transition: .4s !important;
        transition: .4s !important
    }

    .switch .slider:before {
        position: absolute;
        content: "" !important;
        height: 11px !important;
        width: 11px !important;
        left: 3px !important;
        bottom: 3px !important;
        background-color: #fff !important;
        -webkit-transition: .4s !important;
        transition: .4s !important
    }

    .switch input:checked+.slider {
        background-color: #00843d !important
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #00843d !important
    }

    .switch input:checked+.slider:before {
        -webkit-transform: translateX(14px) !important;
        -ms-transform: translateX(14px) !important;
        transform: translateX(14px) !important
    }

    .switch .slider.round {
        border-radius: 26px !important
    }

    .switch .slider.round:before {
        border-radius: 50% !important
    }

    .top-performer .dropify-wrapper {
        width: 100%;
    }
</style>


<div class="row mb-3  " style="margin-top:10px">
    <div class="col-auto pr-0">

        <img class="UserImage" src="{{ Auth::user()->picture ? asset(Auth::user()->picture) : '/images/avatar.svg' }}"
            alt="">

    </div>
    <div class="col mt-auto mb-auto">
        <h2 class="_head01">
            {{ Auth::user()->first_name != null ? Auth::user()->first_name . ' ' . Auth::user()->last_name : 'NA' }}
        </h2>

        <span class="font14">{{ $designation_name ?? '' }}</span>
    </div>
    <div class="col-auto mobNo-lab">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            x="0px" y="0px" viewBox="0 0 50 60.7" style="enable-background:new 0 0 50 60.7;" xml:space="preserve">
            <path class="st0"
                d="M37.3,0.6H12.3c-2.7,0-4.9,2.2-4.9,4.9V55c0,2.7,2.2,5,4.9,5c0,0,0,0,0,0h25.1c2.7,0,4.9-2.2,4.9-4.9V5.5
                                                                                C42.3,2.8,40.1,0.6,37.3,0.6C37.4,0.6,37.4,0.6,37.3,0.6z M9.9,10.9h29.7v37.5H9.9L9.9,10.9z M12.3,3.2h25.1c1.3,0,2.3,1,2.3,2.3v0
                                                                                v2.8H9.9V5.5C9.9,4.3,10.9,3.2,12.3,3.2C12.2,3.2,12.2,3.2,12.3,3.2z M37.3,57.3H12.3c-1.3,0-2.3-1-2.3-2.3l0,0v-4h29.7v4
                                                                                C39.7,56.3,38.6,57.3,37.3,57.3C37.4,57.3,37.4,57.3,37.3,57.3z M28.2,54.2c0,0.7-0.6,1.3-1.3,1.3h-4.2c-0.7,0-1.3-0.6-1.3-1.3
                                                                                s0.6-1.3,1.3-1.3h4.3C27.6,52.9,28.2,53.5,28.2,54.2L28.2,54.2z" />
        </svg>
        <strong>{{ Auth::user()->phone  ? Auth::user()->phone : 'NA' }}</strong>
    </div>
</div>
<div class="row m-0">
    <div class="tabs-header border-0 pr-0">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="nav-Employee-tab" data-bs-toggle="pill"
                    data-bs-target="#nav-Employee" type="button" role="tab" aria-controls="nav-Employee"
                    aria-selected="true">{{__('fields.information')}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-Password-tab" data-bs-toggle="pill" data-bs-target="#nav-Password"
                    type="button" role="tab" aria-controls="nav-Password" aria-selected="false">{{__('fields.password')}}</button>
            </li>
            @if (GetActiveGuardDetail()->is_web == 1)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="nav-picture-tab" data-bs-toggle="pill" data-bs-target="#nav-picture"
                    type="button" role="tab" aria-controls="nav-picture" aria-selected="false">{{__('fields.profile_picture')}}
                     </button>
            </li>
            @endif
           

        </ul>
    </div>
</div>
<div class="top-performer mt-3">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="nav-Employee" role="tabpanel" aria-labelledby="nav-Employee-tab">

            <div class="form-wrap p-0 UserProfile">
                <div class="card-header mb-3 p-0 pb-3">
                    <h2>{{__('fields.user_information')}}</h2>
                </div>
                <div class="row">
                    <div class="col-md-6 p-col-L">
                        <div class="form-group">
                            <b class="control-label mb-5"> {{__('fields.name')}}</b>
                            <b class="u-info">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'NA'}}</b>
                        </div>
                    </div>
                    @if (GetActiveGuardDetail()->is_web == 1)
                        <div class="col-md-6 p-col-R">
                            <div class="form-group">
                                <b class="control-label mb-5">{{__('fields.phone_number')}}</b>
                                <b class="u-info">{{ Auth::user()->phone != null ? Auth::user()->phone : 'NA' }}</b>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6 p-col-R">
                            <div class="form-group">
                                <b class="control-label mb-5">{{__('fields.suite')}}</b>
                                <b class="u-info">{{ Auth::user()->suite != null ? Auth::user()->suite : 'NA' }}</b>
                            </div>
                        </div>
                        <div class="col-md-6 p-col-R">
                            <div class="form-group">
                                <b class="control-label mb-5">{{__('fields.document_number')}}</b>
                                <b
                                    class="u-info">{{ Auth::user()->document_number != null ? Auth::user()->document_number : 'NA' }}</b>
                            </div>
                        </div>
                        <div class="col-md-6 p-col-L">
                        <div class="form-group">
                            <b class="control-label mb-5">{{__('fields.email_id')}}</b>
                            <b class="u-info">{{ Auth::user()->email != null ? Auth::user()->email : 'NA' }}</b>
                        </div>
                    </div>
                    <div class="col-md-6 p-col-R">
                        <div class="form-group">
                            <b class="control-label mb-5">{{__('fields.state')}}</b>
                            <b class="u-info">{{ @$state_name?? 'NA' }}</b>
                        </div>
                    </div>
                 
                    <div class="col-md-6 p-col-R">
                        <div class="form-group">
                            <b class="control-label mb-5">{{__('fields.country')}}</b>
                            <b class="u-info">{{ @$country_name?? 'NA' }}</b>
                        </div>
                    </div>
                    <div class="col-md-6 p-col-R">
                        <div class="form-group">
                            <b class="control-label mb-5">{{__('fields.address')}}</b>
                            <b class="u-info">{{ Auth::user()->address ? Auth::user()->address : 'NA' }}</b>
                        </div>
                    </div>
                    <div class="col-md-6 p-col-R">
                        <div class="form-group">
                            <b class="control-label mb-5">{{__('fields.postal_code')}}</b>
                            <b class="u-info">{{ Auth::user()->postal_code ? Auth::user()->postal_code : 'NA' }}</b>
                        </div>
                    </div>
                    @endif

                </div> 
                @if (GetActiveGuardDetail()->is_web == 1)
                    <div class="row">
                        <div class="col-md-6 p-col-L">
                            <div class="form-group">
                                <b class="control-label mb-5">{{__('fields.designation')}}</b>
                                <b class="u-info">{{ $designation_name }} </b>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <div class="tab-pane fade" id="nav-Password" role="tabpanel" aria-labelledby="nav-Password-tab">
            <div class="card-header mb-3 p-0 pb-3">
                <h2 class="heading02">{{__('fields.change_password')}}</h2>
            </div>
 
            <form style="display: flex; width:100%" id="changePasswordForm" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-12 form-group">
                        <label for="current_password" class="form-label">{{__('fields.current_password')}}</label>
                        <div class="icon-input">
                            <input type="password" name="current_password" class="form-control" id="current_password"
                                placeholder="{{__('fields.enter_current_password')}}" value="">
                        </div>

                    </div>
                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="col-lg-6 col-md-12 form-group">
                        <small class="float-end">{{__('fields.minimum_6_characters')}}</small>
                        <label for="new_password" class="form-label">{{__('fields.new_password')}}*</label>
                        <div class="icon-input">
                            <input type="password" name="password" name="salary" class="form-control" id="new_password"
                                placeholder="{{__('fields.enter_new_password')}}" value="">
                        </div>

                    </div>
                    <div class="col-lg-6 col-md-12">
                    </div>

                    <div class="col-lg-6 col-md-12 form-group">
                        <label for="confirm_password" class="form-label">{{__('fields.confirm_password')}}*</label>
                        <div class="icon-input">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                                placeholder="{{__('fields.confirm_password')}}" value="{{ @$employee->salary }}">
                        </div>

                    </div>

                    <div class="col-lg-6 col-md-12">
                    </div>
                    <div class="col-lg-6 col-md-12 form-group">
                        <button type="button" class="btn btn-primary" id="update_userpassword">{{__('fields.save_changes')}} </button>
                    </div>
            </form>

        </div>

    </div>
    <div class="tab-pane fade" id="nav-picture" role="tabpanel" aria-labelledby="nav-picture-tab">
        <div class="col-12">

            <div class="card-header mb-3 p-0 pb-3">
                <h2 class="heading02">{{__('fields.user_picture')}}</h2>
            </div>


            <form style="width:50%" id="saveEditProfilePictureForm">
                @csrf
                <input type="text" hidden name="user_id" value="{{ Auth::user()->id }}" />
                <label for="employeePictureLabel" class="form-label">{{__('fields.employee_picture')}}*</label>
                <input type="file" class="dropify w-100" id="employeePicture" name="employeePicture" accept="image/*"
                    data-default-file="{{ isset(Auth::user()->picture) && Auth::user()->picture != '' ? asset(Auth::user()->picture) : '' }}"
                    data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                <input type="hidden" id="employeePictureHidden" name="employeePictureHidden" accept="image/*"
                    value="{{ isset(Auth::user()->picture) && Auth::user()->picture != '' ? Auth::user()->picture : '' }}">

                <button type="button" class="btn btn-primary mt-3" id="save_pic_user_profile">{{__('fields.save')}}</button>
            </form>
        </div>

    </div>
</div>

</div>
@endsection
@push('js')
    <script>
        $('.content_head').text('{{__('fields.profile')}}') 
    </script>
    <script src="/js/custom/employee.js"></script>
@endpush