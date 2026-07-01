@extends('layouts.master')
@section('content')
<div class="row mt-2 mb-3">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h2 class="_head01">Notification <span> Preferences</span></h2>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <ol class="breadcrumb">
            <li><a href="#"><span>Notification</span></a></li>
            <li><span>Preferences</span></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>Notification <span> Preferences</span></h2>
            </div>
            <div class="body">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-s2">
                            <select class="form-control formselect" id="employee_id" placeholder="Select Employee">
                                <option value="0" selected disabled>Select Employee</option>
                                @foreach($emp as $employe)
                                <option value="{{ $employe->id }}">{{ $employe->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary sm-mt15" id="update_emp_pref">Save</button>
                    </div>
                </div>
                <table class="table table-bordered dt-responsive AssNotification" style="width:100%">
                    <thead>
                        <tr>
                            <th>General Notification</th>
                            <th>Email</th>
                            <th>Web</th>
                        </tr>
                    </thead>
                    <tbody id="table_notif" style="display:none;">
                        @foreach($codes as $notif)
                        <tr>
                            <td>{{ $notif->name }}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" name="notification_permissions" value="email" id="{{ $notif->code }}"
                                        class="check_box {{ $notif->code }}">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" name="notification_permissions" value="web" id="{{ $notif->code }}"
                                        class="check_box {{ $notif->code }}">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
