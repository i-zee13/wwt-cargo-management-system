var employees = [];
var employeesTable = '';
var statusChangeRef = '';
var statusBtnRef = '';
var isRightGiven = false;
$(document).ready(function () {
    
    var segments = location.href.split('/');
    $(".datepicker").datepicker().datepicker("setDate", new Date());
    var notifications = [];
    if ($('#employee_updating_id').val() && $('#employee_updating_id').val().trim()) {
        $('.passwordLabel').text(Lang.get('fields.password'));
        $('#password').removeClass('required')
    } else {
        $('.passwordLabel').text(Lang.get('fields.password')+'*');
        $('#password').addClass('required')

    }
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        toggleActive: true,
        "orientation": 'right',
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
     
    if(segments[4] == 'register'){
        fetchEmployeesList();
        console.log(rightsGiven);
        if(rightsGiven){
            if(rightsGiven.includes('admin/create_employee')){
                $('.new_emp_btn').removeClass('d-none');
                isRightGiven = true;
            }else{
                $('.new_emp_btn').addClass('d-none');
            }

        }`
    }else{`
       

 
    }
    $('.profile-pic').dropify();
    var lastOp = "add";
    $(document).on('click', '#saveEmployee', function () {
        $('#customSearchInput').val('');
        $('.smallTag').remove();
        let dirty = false;
        let count_input = 0;
        let input_name = '';
        let current_input = '';
        $('.required').each(function () {
            if (!$(this).val() || $(this).val() == 0) {
                if (dirty == false) {
                    $(this).focus();
                }
                dirty = true;

                input_name = $(this).attr('name');
                count_input++;
            } else {

            }
        });
        if (dirty && count_input >= 2) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Fill All Required Fields (*)');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }
        if (count_input <= 1 && input_name != '') {
            $(current_input).focus();
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text(`Please fill all Required fields. ${input_name ?? ''}`);
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }
        if ($('#email').val() != "") {
            if (emailValidate($('#email').val()) == false) {
                $('#email').focus();
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text(`Please Enter Valid Email`);
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                return;
            }

        }
         
        $('#saveEmployee').attr('disabled', 'disabled');
        $('#cancelEmployee').attr('disabled', 'disabled');
        $('#saveEmployee').text(Lang.get('fields.processing'));
        var ajaxUrl = "/admin/register";

        if ($('#employee_updating_id').val() && $('#employee_updating_id').val().trim()) {
            ajaxUrl = "/admin/UpdateEmployee/" + $('input[name="employee_updating_id"]').val();
        }

        $('#saveEmployeeForm').ajaxSubmit({

            type: "POST",
            url: ajaxUrl,
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'));
            },
            success: function (response) {
                if (response) {

                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text(Lang.get('fields.save'));

                    if ($('#operation').val() !== "update") {
                        $('#saveEmployeeForm').find("input[type=text], textarea").not('#operation').val("");
                        $('#saveEmployeeForm').find("select").val("0").trigger('change');
                        $('.dropify-clear').click();
                    }

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Employee has been added successfully');
                    setTimeout(() => {
                        window.location = '/admin/register';
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#saveEmployee').removeAttr('disabled');
                    $('#cancelEmployee').removeAttr('disabled');
                    $('#saveEmployee').text(Lang.get('fields.save'));
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add Employee at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            },
            error: function (err) {
                $('.smallTag').remove();
                $('#saveEmployee').removeAttr('disabled');
                $('#cancelEmployee').removeAttr('disabled');
                $('#saveEmployee').text(Lang.get('fields.save'));
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Failed to add Employee at the moment');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small>', {
                            name: i,
                            class: 'smallTag',
                            style: 'color: red; position: absolute; width: 100%; text-align: right; margin-left: -30px;',
                            text: error[0]
                        }));

                    });
                }
            }
        });
    });



    //Change Password User Profile


    //Change Picture User Profile
    $(document).on('click', '#save_pic_user_profile', function () {
        var picture = $('#employeePicture').prop('files')[0];
        if (!picture && !$('#employeePictureHidden').val().trim()) {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please upload picture first');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return;
        }
        else {
            $(this).text(Lang.get('fields.processing'));
            $(this).attr("disabled", "disabled");
            $('#saveEditProfilePictureForm').ajaxSubmit({
                type: "POST",
                url: "/admin/update_user_profile_pic",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                cache: false,
                success: function (response) {
                    $("#save_pic_user_profile").removeAttr('disabled');
                    $("#save_pic_user_profile").text(Lang.get('fields.save'));
                    if (JSON.parse(response) == "success") {
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'green');
                        $('#notifDiv').text('Updated successfully');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                        location.reload();
                    } else if (JSON.parse(response) == "failed") {
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Unable to update');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                    } else if (JSON.parse(response) == "empty") {
                        $('#notifDiv').fadeIn();
                        $('#notifDiv').css('background', 'red');
                        $('#notifDiv').text('Please select picture to upload.');
                        setTimeout(() => {
                            $('#notifDiv').fadeOut();
                        }, 3000);
                    }
                }
            });
        }
    });

    //Get user Notifications
    $(document).on('click', '#tab4', function () {
        $('#table_notif').show();
        $('#update_emp_pref').attr('disabled', 'disabled');
        $('.consignment_box').attr('disabled', 'disabled');
        $('.complains_box').attr('disabled', 'disabled');
        $('.suggestions_box').attr('disabled', 'disabled');
        $('#update_emp_pref').text(Lang.get('fields.processing'));
        $('.check_box').prop('checked', false);
        var id = $('#employee_id').val();
        $.ajax({
            type: 'GET',
            url: '/admin/notif_pref_against_emp/' + id,
            success: function (response) {
                $('#update_emp_pref').removeAttr('disabled');
                $('.consignment_box').removeAttr('disabled');
                $('.complains_box').removeAttr('disabled');
                $('.suggestions_box').removeAttr('disabled');
                $('#update_emp_pref').text(Lang.get('fields.save'));
                var response = JSON.parse(response);
                notifications = [];
                response.forEach(element => {
                    $('input[id="' + element['notification_type_id'] + '"]').each(function () {
                        if ($(this).val() == "notifiable") {
                            $(this).prop('checked', (element['notifiable'] == "1" ? true : false));
                            if (element["notifiable"] == "1") {
                                var value = $(this).val();
                                if (notifications.find(x => x["code"] == element['notification_type_id'])) {
                                    notifications.find(x => {
                                        if (x["code"] == element['notification_type_id']) {
                                            if (x["properties"].includes(value)) {
                                                x["properties"].splice(x["properties"].indexOf(value), 1);
                                            } else {
                                                x["properties"].push(value);
                                            }
                                        }
                                    });
                                } else {
                                    notifications.push({
                                        code: element['notification_type_id'],
                                        properties: [$(this).val()]
                                    });
                                }
                            }
                        } else {
                            $(this).prop('checked', (element['email'] == "1" ? true : false));
                            if (element["email"] == "1") {
                                var value = $(this).val();
                                if (notifications.find(x => x["code"] == element['notification_type_id'])) {
                                    notifications.find(x => {
                                        if (x["code"] == element['notification_type_id']) {
                                            if (x["properties"].includes(value)) {
                                                x["properties"].splice(x["properties"].indexOf(value), 1);
                                            } else {
                                                x["properties"].push(value);
                                            }
                                        }
                                    });
                                } else {
                                    notifications.push({
                                        code: element['notification_type_id'],
                                        properties: [$(this).val()]
                                    });
                                }
                            }
                        }

                    });
                });
            }
        });
    });

    //Employee checkboxes
    $(document).on('click', '.check_box', function () {
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

    $(document).on('click', '.empStatusChange', function () {
        let status = $(this).attr('active') == "1" ? "0" : "1";
        let id = $(this).attr('id');
        var bodyText = '';
        var headerText = '' 
       statusBtnRef = $(this)
        if(id && status){
            if(status == 1){
                headerText =Lang.get('fields.activate_employee');;   
                bodyText =  Lang.get('fields.activate_employee_confirmation');
            }else{
                headerText = Lang.get('fields.deactivate_employee');
                bodyText = `${ Lang.get('fields.deactivate_employee_confirmation')}
                <div class="text-danger small fw-bold">${ Lang.get('fields.warning_deactivated_employee')}</div>`
            }
            $('.modal-title').text(headerText);
            $('.modal-custom-text').html(bodyText);
            $('.confirmStatusChangeEmp').attr('id', id);
            $('.confirmStatusChangeEmp').attr('status', status);  
            $('#hidden_btn_to_open_modal').click();
        }
    });
    $(document).on('click', '.confirmStatusChangeEmp',function(){
        statusChangeRef = $(this);
       let status = $(this).attr('status');
       let id = $(this).attr('id'); 
        changeStatus(status,id);
    });
    function changeStatus(status,id){
        statusChangeRef.attr('disabled',true);
        statusChangeRef.text(Lang.get('fields.processing'));
        ajaxer('/admin/ChangeEmpStatus', 'POST', {
            "_token": $('meta[name="csrf_token"]').attr('content'),
            'id': id,
            'status': status
        }).then(x => {
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'green');
            $('#notifDiv').text('Status Updated Successfully.');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            $('.cancel_delete_modal').click();
            statusChangeRef.attr('disabled',false);
            statusChangeRef.text('Yes'); 
            var employeeToUpdate = employees.find(employee => employee.id == id); 
            statusBtnRef.text(status == "1" ? Lang.get('fields.deactivate') : Lang.get('fields.activate'));
            statusBtnRef.attr("title", status == "1" ?  Lang.get('fields.deactivate') :  Lang.get('fields.activate'));
            if (employeeToUpdate) {
                employeeToUpdate.active = status; 
            }
            if (status == 1) { 
                statusBtnRef.addClass("btn-delete");
            } else {
                statusBtnRef.removeClass("btn-delete"); 
            } 
            statusBtnRef.attr("active", status);
        })
    }

    //Save Employee notifications
    $(document).on('click', '#update_emp_pref', function () {

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
        $('#update_emp_pref').text(Lang.get('fields.processing'));
        $.ajax({
            type: 'POST',
            url: '/admin/save_pref_against_emp',
            data: {
                _token: $('input[name="_token"]').val(),
                emp_id: emp_id,
                notifications: notifications
            },
            success: function (response) {
                $('#update_emp_pref').removeAttr('disabled');
                $('.consignment_box').removeAttr('disabled');
                $('.complains_box').removeAttr('disabled');
                $('.suggestions_box').removeAttr('disabled');
                $('#update_emp_pref').text(Lang.get('fields.save'));
                if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Saved Successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to save at the moment!');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }

            }
        });
    });

    $(document).on('click', '.view_device_logs', function () {
        $('.body_modal').empty();
        var thisRef = $(this);
        thisRef.text(Lang.get('fields.processing'));
        thisRef.attr('disabled', 'disabled');
        $.ajax({
            type: 'GET',
            url: '/admin/GetDeviceLogs/' + thisRef.attr('id'),
            success: function (response) {
                var response = JSON.parse(response);
                thisRef.text('View Device History');
                thisRef.removeAttr('disabled');
                $('.open_log_modal').click();
                $('.body_modal').append('<table class="table table-hover dt-responsive nowrap device_logs_table" style="width:100%"><thead><tr><th>Emp ID</th><th>Device Id</th><th>Model</th><th>App Version</th><th>Action</th></tr></thead><tbody></tbody></table>');
                $('.device_logs_table tbody').empty();
                response.forEach(element => {
                    $('.device_logs_table tbody').append(`<tr><td>${element['user_id']}</td><td>${element['device_id']}</td><td>${element['device_model']}</td><td>${element['app_version']}</td><td><button user="${element['user_id']}" id="${element['device_id']}" class="btn btn-default device_activation ${element['is_active'] == 1 ? "red-bg" : ""}" current_status="${element['is_active']}">${element['is_active'] == 1 ? "Deactivate" : "Activate"}</button></td></tr>`);
                });
                $('.device_logs_table').DataTable();
            }
        });
    });

    $(document).on('click', '.device_activation', function () {
        var thisRef = $(this);
        var current_status = thisRef.attr('current_status');
        var device_id = thisRef.attr('id');
        var user = thisRef.attr('user');
        thisRef.attr('disabled', 'disabled');
        $.ajax({
            type: 'POST',
            url: '/admin/update_device_activation',
            data: {
                _token: $('input[name="_token"]').val(),
                device_id: device_id,
                current_status: current_status,
                user: user
            },
            success: function (response) {
                thisRef.removeAttr('disabled');

                if (JSON.parse(response) == "success") {
                    $('.close_excel_modal').click();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Saved Successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to update at the moment!');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }

            }
        });
    })

});

function fetchEmployeesList() {
    $.ajax({
        type: 'GET',
        url: '/admin/EmployeesList',
        success: function (response) {
            $('.employee_table_body').empty();
             employees = JSON.parse(response); 
            loadEmployeesList()
        }
    });
}

function loadEmployeesList() {
    $('.employee_table_body').empty();
    $('.employee_table_body').append(`<table class="table dt-responsive nowrap payable-requests employeesListTable" style="width:100%"><thead>
        <tr>
        <th>${Lang.get('fields.emp_id')}</th>
        <th>${Lang.get('fields.employee_name')}</th>
        <th>${Lang.get('fields.email')}</th>
        <th>${Lang.get('fields.role')}</th>
        <th>${Lang.get('fields.actions')}</th>
        </tr>
        </thead><tbody></tbody></table>`);
    $('.employeesListTable tbody').empty();
    employees.forEach(element => {
        $('.employeesListTable tbody').append(`
                <tr>
                    <td>${element['id']}</td>
                    <td>${element['first_name'] + ' ' + element['last_name'] ?? 'NA'}</td> 
                    <td>${element['email'] ? element['email'] : '-'}</td>
                    <td>${element['designation_name'] ?? ''}</td>
                    <td>
                    ${isRightGiven == true?`<a href="/admin/create_employee/${element['id']}" id="${element['id']}" class="btn btn-outline-primary" title="Edit">
                                 <svg width="10" height="13" viewBox="0 0 10 13"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                                fill="" />
                                </svg> 
                        </a>`:''}
                        
                        <button title="${element.active == 1 ?  Lang.get('fields.deactivate') :  Lang.get('fields.activate')}" id="${element['id']}" class="btn btn-default btn btn-add-guest ${(element['active'] == "1" ? "btn-delete" : "")}  empStatusChange" active="${element.active}">${(element['active'] == "0" ?  Lang.get('fields.activate') :  Lang.get('fields.deactivate'))}</button> 
                        
                    </td>
                </tr>`);
    });
    $('#tblLoader').hide();
    $('.employee_table_body').fadeIn();
    employeesTable = $('.employeesListTable').DataTable({
        responsive: true,
        searching: false,
        lengthChange: false,
        info: false,
        pagingType: 'simple_numbers',
        pageLength: 10
    });

    $('#Tablelist_paginate').addClass('d-flex justify-content-center');
}
$('#customSearchInput').on('input', function () {
    var searchTerm = $(this).val().toLowerCase();
    if (searchTerm) {
        employeesTable.clear().draw();

        employees.forEach(element => {
            if (
                (element['id'] + '').toLowerCase().includes(searchTerm) ||
                element['first_name'].toLowerCase().includes(searchTerm) ||
                element['last_name'].toLowerCase().includes(searchTerm) ||
                (element['phone'] && element['phone'].toLowerCase().includes(searchTerm)) ||
                element['designation_name'].toLowerCase().includes(searchTerm)
            ) {
                employeesTable.row.add([
                    element['id'],
                    element['first_name'] + ' ' + element['last_name'],
                    element['phone'] ? element['phone'] : '-',
                    element['designation_name'],
                    `  <a href="/create_employee/${element['id']}" id="${element['id']}" class="btn btn-outline-primary  ">
                    <svg width="10" height="13" viewBox="0 0 10 13"
                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path
                                                   d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                   fill="" />
                   </svg> 
           </a>
                <button id="${element['id']}" class="btn btn-default btn btn-outline-primary ${element['active'] == "1" ? "btn-delete" : ""} empStatusChange" active="${element.active}">
                    ${element['active'] == "0" ? "Active" : "Deactive"}
                </button>`
                ]).draw();

            }
        });
    } else {
        employeesTable.clear().draw();
        loadEmployeesList();
    }
});

$(document).on('click', '#update_userpassword', function () {

    if (!$('#current_password').val().trim() || !$('#new_password').val().trim() || !$('#confirm_password').val().trim()) {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Please provide all required inforamtion (*)');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;

    } else if ($('#new_password').val() != $('#confirm_password').val()) {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('New Password and Confirm Password does not match!');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
    else if ($('#new_password').val().length < 6 || $('#confirm_password').val().length < 6) {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('New Password and Confirm Password should have atleast 6 characters');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
    else {
         var url = '/admin/update_user_password'
        if(!is_web.is_web){
            url = '/customer-password-update';
        }

        $(this).text(Lang.get('fields.processing'));
        $(this).attr("disabled", "disabled");
        $('#changePasswordForm').ajaxSubmit({
            type: "POST",
            url: url,
            cache: false,
            
            success: function (response) {
                $("#update_userpassword").removeAttr('disabled');
                $("#update_userpassword").text(Lang.get('fields.save_changes'));
                if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Updated successfully');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    location.reload();
                } else if (JSON.parse(response) == "failed") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to update');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if (JSON.parse(response) == "empty") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please fill all fields.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Current Password does not match.');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    }

});



const inputElements = document.querySelectorAll('input');


function handleChange(event) {
    const changedInput = event.target;
    $('small[name="' + changedInput.name + '"]').remove();
}
inputElements.forEach(input => {
    input.addEventListener('focus', handleChange);
    input.addEventListener('change', handleChange);
});