@extends('layouts.master')
@section('content')
<style>
    ._activityEmp-Head {
      border: none;
    }
    ._activity-filter-EMP {
      margin-top: 0;
      padding-bottom: 0.0625rem;
      background-color: #F8F8F8;
      box-shadow: none;
      padding-left: 1.5625rem;
      padding-right: 1.5625rem;
    }
    .S__Activity input,
    .Datefilter-EMP div input {
      padding: 0.5rem 0.625rem;
    }
    .Datefilter-EMP div input {
      border: solid 0.0625rem #F0F0F0;
      background-color: #fff;
      height: 1.8125rem;
      border-radius: 0;
      width: 8.75rem;
    }
    .Datefilter-EMP div {
      width: auto;
    }
    .S__Activity .fa {
      opacity: 0.35;
    }
    .__filter {
      padding-top: 0.625rem;
    }
    ._activity-filter-EMP .select2-container--default .select2-selection--single .select2-selection__rendered {
      background-color: #fff !important;
      border: solid 0.0625rem #F0F0F0;
    }
    .__filter .date-List .select2-container--default .select2-selection--single .select2-selection__arrow {
      display: block;
      right: 0rem;
    }
    .__filter .form-s2 .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 1.8125rem !important;
      font-family: 'proximanova-light', sans-serif !important;
    }
    .__filter .form-s2 .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 1.8125rem !important;
    }
    .__filter .date-List .select2-container .select2-selection--single,
    .__filter .date-List .select2-container--default .select2-selection--single .select2-selection__rendered {
      height: 1.8125rem !important;
    }
    .__filter .EMP__List {
      margin-top: -0.0625rem;
    }
    .notificationList li h4 {
      font-size: 0.875rem;
    }
    .notificationList li {
      box-shadow: none !important;
      padding: 0.5rem 0.625rem;
      margin-bottom: 15px;
      margin-top: 0;
      margin-left: 0;
      margin-right: 0;
      background: #fff ;
      border: solid 0.0625rem #EBEBEB !important;
    }
    .notificationList li:hover {
      border: solid 0.0625rem #EBB30A !important;
    }
    .heading_dw ._description {
      opacity: 0.7;
    }
    .notificationList li h4 {
      font-weight: 500 !important;
    }
    .NotiImg {
      border-radius: 0;
      border: none; background-color: transparent;
    }
    .notificationList li.New{
      background-color: #F8F8F8 !important;
    }
    .notificationList li .btn-primary{
      background: #fff;
      border: solid 0.0625rem #040725;
      color: #040725;
      padding: 10px 15px;
      line-height: 1px;
    }
    .notificationList li .btn-primary:focus, .notificationList li .btn-primary:hover{
      background: #040725 !important;
      border: solid 0.0625rem #040725;
      color: #fff;
    }
    .notification-date{
      font-size: 11px;
     background-color: #F8F8F8;
      padding: 5px 10px;
      margin-bottom: 12px;
      display: inline-block; line-height: 1;
    }
    .btn-activity{
      background: transparent!important;
      border: none!important;
      padding: 0px!important;
      font-weight: 500 !important;
    }
    .btn-activity:hover{
      text-decoration: underline!important;
      color: #EBB30A!important;
    }
</style>

{{-- <div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">All <span> Notification</span></h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Notification</span></a></li>
            <li><span>List</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                @if(!empty($all_notifications))
                @foreach($all_notifications as $notifications)
                <div class="alert alert-warning alert-dismissible fade show alert-color _NF-se" role="alert">
                    <img src="{{ Storage::exists('public/company/'.$notifications->picture) ? URL::to('/storage/company').'/'.$notifications->picture : '/images/avatar.svg'}}"
class="NU-img float-none mb-0" alt="">
<strong class="notifications_list_all"
    id="{{$notifications->id}}">{{$notifications->order_id ? "Order " : $notifications->customer}}
</strong> {{ $notifications->message }}
</div>
@endforeach
@else
<label>No new notifications</label>
@endif
</div>
</div>
</div>
</div> --}}
<div class="_activityEmp">

    <div class="_activityEmp-Head">
        
        <div class="row mt-2">
            <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                <h2 class="_head01">Overall <span>Notification</span></h2>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="#"><span>Overall</span></a></li>
                    <li><span>Notification</span></li>
                </ol>
            </div>

            <div class="col-12 _activity-filter-EMP">
                <div class="_more-action __filter pr-0">
                    <div class="form-s2 date-List EMP__List" style="width: 150px!important">
                        <select class="form-control formselect notif_date_filter">
                            <option value="0" selected>All</option>
                            <option value="1">Yesterday</option>
                            <option value="2">Today</option>
                            <option value="3">Current Month</option>
                            <option value="4">Last Month</option>
                            <option value="5">Custom</option>
                        </select>
                    </div>
                    <div class="Datefilter-EMP notif_custom_div" style="display:none">
                        <div><input type="text" class="datepicker notif_start_date" class="form-control" placeholder="Start Date"
                                style="font-size: 13px"></div>
                        <div><input type="text" class="datepicker notif_end_date" class="form-control" placeholder="End Date"
                                style="font-size: 13px"></div>
                    </div>




                    <div class="S__Activity"> <a ><i class="fa fa-search"></i></a>
                        <input type="search" placeholder="Search Notification" class="searchNotif">
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="_activityEmp-timeline">
        <div class="row">
            <div class="col-md-12">
                <ul class="notificationList">
                    {{-- @if(!empty($all_notifications))
                    @foreach($all_notifications as $notifications)

                        <li>
                            <div class="row">
                                <div class="col-3"><img class="NotiImg" src="{{ Storage::exists('public/company/'.$notifications->picture) ? URL::to('/storage/company').'/'.$notifications->picture : '/images/avatar.svg'}}" alt="">
                                <h4>Created BY</h4><small>time</small>
                                </div>
                                <div class="col-9 border-left">
                                <h4><span>{{($notifications->order_id ? "Order " : ($notifications->customer_id ? 'Customer' : ($notifications->supplier_id ? 'Supplier' : ($notifications->prospect_customer_id ? 'Prospect Customer' : 'Item'))))}}</span></h4>
                                {{($notifications->order_id ? "Order " : ($notifications->customer_id ? 'Customer' : ($notifications->supplier_id ? 'Supplier' : ($notifications->prospect_customer_id ? 'Prospect Customer' : 'Item'))))}} <strong>{{$notifications->_name}}</strong> {{ $notifications->message }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @else
                    <label>No new notifications</label>
                    @endif --}}
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
