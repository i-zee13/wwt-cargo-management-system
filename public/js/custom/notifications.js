$(document).ready(function() {
    var segments = location.href.split('/');
    var action = segments[3];
    var notifications = [];
    var notif_ids = [];
    var filterDate = 0;
    var filterStartDate = '';
    var filterEndDate = '';

    if (action !== "" && action !== "home") {
        // $('.notifications_list_all').each(function() {
        //     notif_ids.push($(this).attr('id'));
        // });

        // $.ajax({
        //     type: 'POST',
        //     url: '/read_notif_four',
        //     data: {
        //         _token: $('input[name="_token"]').val(),
        //         notif_ids: notif_ids
        //     },
        //     success: function(response) {
        //         var response = JSON.parse(response);
        //         //console.log(response);
        //     }
        // });
    }
    if(action == 'ViewAllNotifications'){
        fetchALlNotifications();
    }

    $(document).on('change', '#employee_id', function() {
        $('#table_notif').show();
        $('#update_emp_pref').attr('disabled', 'disabled');
        $('.consignment_box').attr('disabled', 'disabled');
        $('.complains_box').attr('disabled', 'disabled');
        $('.suggestions_box').attr('disabled', 'disabled');
        $('#update_emp_pref').text('Processing..');
        $('.check_box').prop('checked', false);
        var id = $('#employee_id').val();
        $.ajax({
            type: 'GET',
            url: '/Notifications/' + id,
            success: function(response) {
                $('#update_emp_pref').removeAttr('disabled');
                $('.consignment_box').removeAttr('disabled');
                $('.complains_box').removeAttr('disabled');
                $('.suggestions_box').removeAttr('disabled');
                $('#update_emp_pref').text('Save');
                var response = JSON.parse(response);
                notifications = [];
                response.forEach(element => {
                    $('input[id="' + element['notification_code_id'] + '"]').each(function() {
                        if ($(this).val() == "email") {
                            $(this).prop('checked', (element['email'] == "1" ? true : false));
                            if (element["email"] == "1") {
                                notifications.push({
                                    code: element['notification_code_id'],
                                    properties: ["email"]
                                });
                            }
                        } else {
                            $(this).prop('checked', (element['web'] == "1" ? true : false));
                            if (element["web"] == "1") {
                                notifications.push({
                                    code: element['notification_code_id'],
                                    properties: ["web"]
                                });
                            }
                        }
                    });
                });
            }
        });
    });

    $(document).on('click', '.check_box', function() {
        var id = $(this).attr('id');
        var value = $(this).val();
        if (notifications.find(x => x["code"] == id)) {
            notifications.find(x => {
                if (x["code"] == id) {
                    if (x["properties"].includes(value)) {
                        x["properties"].splice(x["properties"].indexOf(value), 1);
                    } else {
                        x["properties"].push(value);
                    }
                }
            });
        } else {
            notifications.push({
                code: id,
                properties: [$(this).val()]
            });
        }
    });

    $(document).on('click', '#update_emp_pref', function() {

        if ($('#employee_id').val() == 0 || $('#employee_id').val() == null) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please select Employee');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        if (notifications == "") {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Check Notification First');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }

        var emp_id = $('#employee_id').val();

        $('#update_emp_pref').attr('disabled', 'disabled');
        $('.consignment_box').attr('disabled', 'disabled');
        $('.complains_box').attr('disabled', 'disabled');
        $('.suggestions_box').attr('disabled', 'disabled');
        $('#update_emp_pref').text('Processing..');
        $.ajax({
            type: 'POST',
            url: '/Notifications',
            data: {
                _token: $('input[name="_token"]').val(),
                emp_id: emp_id,
                notifications: notifications
            },
            success: function(response) {
                if (JSON.parse(response) == "success") {
                    $('#update_emp_pref').removeAttr('disabled');
                    $('.consignment_box').removeAttr('disabled');
                    $('.complains_box').removeAttr('disabled');
                    $('.suggestions_box').removeAttr('disabled');
                    $('#update_emp_pref').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Saved Successfully');
                    $('#employee_id').val(0).trigger('change');
                    $('.check_box').prop('checked', false);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#update_emp_pref').removeAttr('disabled');
                    $('.consignment_box').removeAttr('disabled');
                    $('.complains_box').removeAttr('disabled');
                    $('.suggestions_box').removeAttr('disabled');
                    $('#update_emp_pref').text('Save');
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to save at the moment!');
                    $('#employee_id').val(0).trigger('change');
                    $('.check_box').prop('checked', false);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }

            }
        });
    });

    $(document).on('change', '.notif_date_filter', function(){
        filterDate = $(this).val();
        if($(this).val() == 5){
            $('.notif_custom_div').show();
        }else{
            $('.notif_custom_div').hide();
            fetchALlNotifications(filterDate, filterStartDate, filterEndDate);
        }
    });

    $(document).on('change', '.notif_start_date', function(){
        filterStartDate = $(this).val();
        if(filterEndDate){
            fetchALlNotifications(filterDate, filterStartDate, filterEndDate);
        } 
    });

    $(document).on('change', '.notif_end_date', function(){
        filterEndDate = $(this).val()
        if(filterStartDate){
            fetchALlNotifications(filterDate, filterStartDate, filterEndDate);
        }
    });

    var searchQuery = '';
    $(document).on('input', '.searchNotif', function(){
        if($(this).val() == ''){
            searchQuery = null;
            $('.task-name').unmark(searchQuery);
            $('.emp-name').unmark(searchQuery);
            $('.time_interval').unmark(searchQuery);
            $('.heading-up').unmark(searchQuery);
            $('._description').unmark(searchQuery);
            $('._action').unmark(searchQuery);
            $('.heading_dw').unmark(searchQuery);
        }else{
            $('.task-name').unmark(searchQuery);
            $('.emp-name').unmark(searchQuery);
            $('.time_interval').unmark(searchQuery);
            $('.heading-up').unmark(searchQuery);
            $('._description').unmark(searchQuery);
            $('._action').unmark(searchQuery);
            $('.heading_dw').unmark(searchQuery);
            searchQuery = $(this).val();
        }
        if(searchQuery){
            searchWords(searchQuery);
        }
    });

});

function fetchALlNotifications(date = null, start_date = null, end_date = null){
    //$('#tblLoader').fadeIn();
    //$('.body').hide();
    $('.notificationList').empty();
    $.ajax({
        type: 'GET',
        url: '/fetchAllNotifications',
        data: {
            date        : date,
            start_date  : start_date,
            end_date    : end_date
        },
        success: function(response) {
            var response    = JSON.parse(response);
            var today       = response.current_date_time;
            var yesterday   = response.yesterday_date_time;
            var previous    = response.before_date_time;
            if(response.notifications.length > 0){
                $('.notificationList').append(`
                    <div class="notification-date today-div" style="display:none">Today</div>
                        <div class="today-list" style="display:none"></div>
                    <div class="notification-date yesterday-div" style="display:none">Yesterday</div>
                        <div class="yesterday-list" style="display:none"></div> 
                    <div class="notification-date previous-div" style="display:none">Previous</div>
                        <div class="previous-list" style="display:none"></div> 
                `);
            }
            $('.today-list').empty();
            $('.yesterday-list').empty();
            $('.previous-list').empty();
            response.notifications.forEach(element => {
                var duration        =   element.daysAgo != '0' ? element.daysAgo+' days ago' : element.hoursAgo != '0' ? element.hoursAgo+' hours ago' : element.minutesAgo != '0' ? element.minutesAgo+' minutes ago' : element.secondsAgo != '0' ? element.secondsAgo+' seconds ago' : 'Just now';
                if(moment(element.created_at).format('Y-M-D') == (moment(today).format('Y-M-D'))){
                    if(element){
                        $('.today-list').append(NotificationListHtml(duration,element));
                        $('.today-div,.today-list').show();
                    }else{
                        $('.today-div,.today-list').hide();
                    }
                }
                if(moment(element.created_at).format('Y-M-D') == (moment(yesterday).format('Y-M-D'))){
                    if(element){
                        $('.yesterday-list').append(NotificationListHtml(duration,element));
                        $('.yesterday-div,.yesterday-list').show();
                    }else{
                        $('.yesterday-div,.yesterday-list').hide();
                    }
                }
                if(moment(element.created_at).format('Y-M-D') <= (moment(previous).format('Y-M-D'))){
                    if(element){
                        $('.previous-list').append(NotificationListHtml(duration,element));
                        $('.previous-div,.previous-list').show();
                    }else{
                        $('.previous-div,.previous-list').hide();
                    }
                }
            });
        }
    });
}
function NotificationListHtml(duration,element){
    var details         =   element.message.split('-');
    var image           =   element.notification_action_id == '1' ? '/images/form-inquiry-icon.svg' :
                            element.notification_action_id == '2' ? '/images/lead-assignment-icon.svg' : 
                            element.notification_action_id == '3' ? '/images/Intakeformsubmitted-icon.svg' : 
                            element.notification_action_id == '4' ? '/images/formreminder-icon.svg' : 
                            element.notification_action_id == '5' ? '/images/videocall-schedule-icon.svg' : 
                            element.notification_action_id == '6' ? '/images/Followup-up-icon.svg' : 
                            element.notification_action_id == '7' ? '/images/reminder-icon.svg' : 
                            element.notification_action_id == '8' ? '/images/casefile-icon.svg' : 
                            '/images/avatar.svg';
    
    return `<li class="${element.read_at ? '' : 'New'}">
        <div class="row">
            <div class="col-3"><img class="NotiImg" src="${image}" alt="">
                <h4 class="heading-up">${element.notification_action_id == '1' ? 'Web Inquiry' : element.notification_action_id == '2' ? 'Lead Assignment' : element.notification_action_id == '3' ? 'Intake Form Submitted' : element.notification_action_id == '4' ? 'Intake form Reminder' : element.notification_action_id == '5' ? 'Video Call Verification Scheduled' : element.notification_action_id == '6' ? 'Follow Up' : element.notification_action_id == '7' ? 'Reminder' : `<button type="button" href="/casefile-details/${element.lead_id}" class="btn-view btn-activity" data-id="${element.id}">Case File# ${element.lead_id}</button>`}
                </h4><small class="time_interval">${ duration ? duration : '0' }</small>
            </div>
            <div class="col border-left">
                <h4><span class="task-name">${details[1]+' '+details[2]}</span></h4> 
                <span class="_description">${details[3]}</span>
            </div>
            <div class="col-auto mt-auto mb-auto"><button type="button" href="${element.notification_action_id == '1' ? `/inquiries` : element.notification_action_id == '8' ? `/casefile-details/${element.lead_id}` : element.notification_action_id == '5' ? `/Correspondence/create/lead/${element.lead_id}` : element.notification_action_id == '3' ? `/intake-view/${element.intake_id}` : `/Correspondence/create/lead/${element.lead_id}`}" class="btn btn-primary btn-view" data-id="${element.id}">View</button></div>
        </div>
    </li>`;
}
$(document).on('click','.btn-view',function(){
    var current = $(this);
    var id      = $(this).attr('data-id');
    $.ajax({
        url     :   '/notification-read-status/'+id,
        success :   function(response){
            if(response.status == "success"){
                var page_load = current.attr('href');
                window.location = `${page_load}`;
            }else{
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Not marked read at this moment');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
            }
        }
    });
});
function searchWords(searchQuery){
    $('.task-name').mark(searchQuery);
    $('.emp-name').mark(searchQuery);
    $('.time_interval').mark(searchQuery);
    $('.heading-up').mark(searchQuery);
    $('._description').mark(searchQuery);
    $('._action').mark(searchQuery);
    $('.heading_dw').mark(searchQuery);
}