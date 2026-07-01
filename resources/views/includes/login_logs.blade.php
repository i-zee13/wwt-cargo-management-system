@extends('layouts.master')
@section('content')
<style>
    .set-width-filter {
        width: 250px;
        float: left; 
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

<div class="row  " style="margin-top: 20px">
<div class="col-auto col-xsm-w pr-0">
    <div class="search set-width-filter " style="width: 280px;">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z" stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <input class="form-control" type="text" id="customSearchInput" placeholder="Search Logs Here...">
    </div>

</div>
    <div class="set-width-filter form-group">
        <input type="text" autocomplete="off" name="start_date" class="date-picker form-control" id="start_date" placeholder="Start Date *" value="">

    </div>
    <div class=" set-width-filter form-group pl-0">
        <input type="text" autocomplete="off" name="end_date" class="date-picker form-control" id="end_date" placeholder="End Date *" value="">

    </div>
    <div class="col-auto pl-0 col-xsm-w  ">
        <button type="button" class="btn btn-primary searchlogs">
            Search </button>
            <button type="button" class="btn btn-danger   reset-btn">
            Reset </button>
    </div>
    <div class="col pl-0 col-xsm-w  ">
        
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
                        <h2>List</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto" style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body p-0 logsTable" style="display: block">

                <table class="table table-hover nowrap" id="loginLogTable" style="width:100%;display:none">
                    <thead>
                        <tr>
                            <th hidden>ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>IP Address</th>
                            <th>Mac Address</th>
                            <th>Login Date</th>
                        </tr>
                    </thead>
                    <tbody class="log_table_body">
                        @foreach ($records as $data)
                        <tr>
                            <td hidden>{{ $data->id}}</td>
                            <td>{{ $data->user_id ? $data->user_id : 'NA' }}</td>
                            <td>{{ $data->username ? $data->username : 'NA' }}</td>
                            <td>{{ $data->ip_address ? $data->ip_address : 'NA' }}</td>
                            <td>{{ $data->mac_address ? $data->mac_address : 'NA' }}</td>
                            <td>{{ $data->created_at ? date('d-m-Y h:i:s A', strtotime($data->created_at)) : 'NA' }}
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
@push('js')
<script>
    $('.content_head').text('Login Logs')

    $(document).ready(function() {
        $(".date-picker").datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true
        }).on('changeDate', function(e) {
            $(this).datepicker('hide');
        });
        var Table = $('#loginLogTable').DataTable({
            responsive: true,
            searching: true,
            lengthChange: false,
            info: false,
            pagingType: 'simple_numbers',
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            dom: 'lrtip'
        });
        $('#customSearchInput').on('keyup', function() {
            Table.search(this.value).draw();
        });
        $('#tblLoader').hide();
        $('#loginLogTable').fadeIn();

        $('.reset-btn').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('.logsTable').html('<div class="placeholder-div text-center">Search Your Logs!</div>');
            $('.logsTable').show();
        });

        $(document).on('click', '.searchlogs', function() {

            const currentRef    = $(this);
            const from_date     = $('#start_date').val();
            const to_date       = $('#end_date').val();
            if (from_date && to_date) {
                if (new Date(from_date) > new Date(to_date)) {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').text(' Start Date must be less than or equal to End Date');
                    setTimeout(function() {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    return;
                }
                $('.loader').show();
                $('.logsTable').empty();
                $('.logsTable').hide();
                currentRef.attr('disabled', 'disabled');
                currentRef.text('Searching...');
                $.ajax({
                    type: 'GET',
                    url: '/search-logs',
                    data: {
                        'from_date': from_date,
                        'to_date': to_date 
                    },
                    success: function(response) {
                       $('.logsTable').empty();
                        $('.log_table_body').empty();
                        if (response.logs.length > 0) {
                            loadlogsTable(response.logs);
                        } else {
                            $('.logsTable').html('<div class="placeholder-div text-center">No Inquiries Found!</div>');
                             $('.logsTable').show();
                        }
                    },
                    complete: function() {
                        currentRef.removeAttr('disabled');
                        currentRef.text('Search');
                        $('.loader').hide();
                    },
                    error: function(err) {
                        currentRef.removeAttr('disabled');
                        currentRef.text('Search');
                        $('.loader').hide();
                        $('.logsTable').hide();
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('An error occured while searching for logs, Please try again later');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 1500);
                    }
                });

            } else {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Please Choose dates (*)');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 1500);
                return;
            }
        })

        function loadlogsTable(logs) { 
         
            $('.logsTable').append(`
                    <table class="table table-hover dt-responsive nowrap" id="loginLogTable" style="width:100%;">
                    <thead>
                    <tr>
                    <th hidden>ID</th> 
                    <th>User ID</th> 
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Mac Address</th>
                    <th>Login Date</th>
                
                    </tr>
                </thead>
                <tbody class=""></tbody>
                    </table>`
                    );
            logs.forEach(log => {
                $('#loginLogTable tbody').append(`  
                    <tr id="table-row-${log.id}">
                        <td hidden>${log.id}</td>
                        <td>${log.user_id}</td>
                        <td>${log.username}</td>
                        <td>${log.ip_address}</td>
                        <td>${log.mac_address}</td> 
                        <td>${log.created_at}</td>   
                    </tr>`);
            });   
            $('#loginLogTable').fadeIn();
            logsTable = $('#loginLogTable').DataTable({
                responsive: true,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10,
                dom: 'lrtip'
            });
            $('#customSearchInput').on('keyup', function() {
                logsTable.search(this.value).draw();
            });
            $('.search-div').fadeIn();
            $('.logsTable').show();
        }
    });
</script>
@endpush