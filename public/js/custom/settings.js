var segments = location.href.split('/');
var last_operation = 'add';
var opp_name = '';
var glob_type = '';
var deleteRef = '';
var settingData = [];
var all_departments = [];
var all_languages = [];
var this_Ref = '';
var branches = [];
var all_docs = [];
$(document).ready(function () {
    fetchDesignationData();
    $(document).on('click', '.openDataSidebarForAddingDesignation', function () {
        $('#dataSidebarLoader').hide();
        $('input[name="designation_name"]').val('');
        $('input[name="designation_name"]').blur();
        $('.offcanvas-heading').text(Lang.get('fields.role_details'));
        $('#designation_name').val('');
        $('.designation_form_div').show();
        $('.department_form_div').hide();
        $('.languages_form_div').hide();
        $('.branch_form_div').hide();
        $('.doc_form_div').hide();
        openSidebar();
        last_operation = 'add';
        opp_name = 'designation';
        $('#operation').val('add');
        $('#opp_name_input').val('designation');
    });

    $(document).on('click', '.openDataSidebarForAddingDepartment', function () {
        $('#dataSidebarLoader').hide();

        $('input[name="department_name"]').val('');
        $('input[name="department_name"]').blur();

       $('.offcanvas-heading').text(Lang.get('fields.department_details'));
        $('.designation_form_div').hide();
        $('.languages_form_div').hide();
        $('.department_form_div').show(); 
        $('.branch_form_div').hide();
        $('.doc_form_div').hide();
        openSidebar();
        last_operation = 'add';
        opp_name = 'department';
        $('#operation').val('add');
        $('#opp_name_input').val('department');
    });
    $(document).on('click', '.openDataSidebarForAddingBranch', function () {
        $('#dataSidebarLoader').hide(); 
        $('input[name="code"]').val('').focus().blur();
        $('input[name="branch"]').val('').focus().blur(); 
        $('.offcanvas-heading').text(Lang.get('fields.branch_details'));
        $('.designation_form_div').hide();
        $('.languages_form_div').hide();
        $('.department_form_div').hide();
        $('.branch_form_div').show(); 
        $('.doc_form_div').hide();
        openSidebar();
        last_operation = 'add';
        opp_name = 'branches';
        $('#operation').val('add');
        $('#opp_name_input').val('branches');
    });
    $(document).on('click', '.openDataSidebarForAddingDoc', function () {
        $('#dataSidebarLoader').hide(); 
        $('input[name="document_name"]').val('').focus().blur(); 
        $('.offcanvas-heading').text(Lang.get('fields.document_type_details'));
        $('.designation_form_div').hide();
        $('.languages_form_div').hide();
        $('.department_form_div').hide(); 
        $('.branch_form_div').hide();  
        $('.doc_form_div').show();
        openSidebar();
        last_operation = 'add';
        opp_name = 'documents';
        $('#operation').val('add');
        $('#opp_name_input').val('documents');
    });
    $(document).on('click', '.openDataSidebarForAddingLanguage', function () {
        $('#dataSidebarLoader').hide();

        $('input[name="language_title"]').val('');
        $('input[name="iso_code"]').val('');
        $('select[name="rtl"]').val('0').trigger('change');
        $('input[name="language_title"]').blur();
        $('input[name="iso_code"]').blur();

        $('.offcanvas-heading').text(Lang.get('fields.language_details'));
        $('.designation_form_div').hide();
        $('.languages_form_div').show();
        $('.department_form_div').hide(); 
        $('.branch_form_div').hide();
        $('.doc_form_div').hide();
        openSidebar();
        last_operation = 'add';
        opp_name = 'language';
        $('#operation').val('add');
        $('#opp_name_input').val('language');
    });
    $(document).on('click', '.openDataSidebarForUpdateBranch', function () {
        var id = $(this).attr('id');
        $('#dataSidebarLoader').show();
        $('.designation_form_div').hide();
        $('.department_form_div').hide();
        $('.languages_form_div').hide();
        $('#branch').val('');
        $('#code').val('');
        $('.offcanvas-heading').text(Lang.get('fields.branch_details'));
        $('#opp_id').val(id);
        $('.custom_checkbox').prop('checked', false);
        openSidebar();
        last_operation = 'update';
        opp_name = 'branches';
        $('#operation').val('update');
        $('#opp_name_input').val('branches');
        $.ajax({
            type: 'GET',
            url: '/admin/GetBranchData/' + id,
            success: function (response) {
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show(); 
                $('input[name="branch"]').val(response.branch).focus().blur(); 
                $('input[name="code"]').val(response.code).focus().blur(); 
                $('.branch_form_div').show(); 
            }
        });
    });
    $(document).on('click', '.openDataSidebarForUpdateDoc', function () {
        var id = $(this).attr('id');
        $('#dataSidebarLoader').show();
        $('.designation_form_div').hide();
        $('.department_form_div').hide();
        $('.languages_form_div').hide();
        $('.branch_form_div').hide();
        $('#document_name').val(''); 
        $('.offcanvas-heading').text(Lang.get('fields.document_type_details'));
        $('#opp_id').val(id);
        $('.custom_checkbox').prop('checked', false);
        openSidebar();
        last_operation = 'update';
        opp_name = 'documents';
        $('#operation').val('update');
        $('#opp_name_input').val('documents');
        $.ajax({
            type: 'GET',
            url: '/admin/GetDocumentTypes/' + id,
            success: function (response) {
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show(); 
                $('input[name="document_name"]').val(response.document_name).focus().blur();   
                $('.doc_form_div').show(); 
            }
        });
    });
    $(document).on('click','.fetchBranches',function(){
        $(this).attr('disabled',true);
        fetchBranches();
        $(this).attr('disabled',false); 
    })
    $(document).on('click', '.openDataSidebarForUpdateDesignation', function () {
        var id = $(this).attr('id');
        $('#dataSidebarLoader').show();
        $('.designation_form_div').hide();
        $('.department_form_div').hide();
        $('.languages_form_div').hide();
        $('#designation_name').val('');
        $('.offcanvas-heading').text(Lang.get('fields.role_details'));
        $('#opp_id').val(id);
        $('.custom_checkbox').prop('checked', false);
        openSidebar();
        last_operation = 'update';
        opp_name = 'designation';
        $('#operation').val('update');
        $('#opp_name_input').val('designation');
        $.ajax({
            type: 'GET',
            url: '/admin/GetDesignation/' + id,
            success: function (response) {
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('input[name="designation_name"]').focus();
                $('input[name="designation_name"]').val(response.designation);
                $('input[name="designation_name"]').blur();
                $('.designation_form_div').show();
                $('.department_form_div').hide();
            }
        });
    });
    $(document).on('click', '.openDataSidebarForUpdateLangugae', function () {
        var id = $(this).attr('id');
        $('#dataSidebarLoader').show();
        $('.designation_form_div').hide();
        $('.department_form_div').hide();
        $('.languages_form_div').hide();
        $('#language_title').val('');
        $('#iso_code').val('');
        $('.offcanvas-heading').text(Lang.get('fields.language_details'));
        $('#opp_id').val(id);
        $('.custom_checkbox').prop('checked', false);
        openSidebar();
        last_operation = 'update';
        opp_name = 'language';
        $('#operation').val('update');
        $('#opp_name_input').val('language');
        $.ajax({
            type: 'GET',
            url: '/admin/getLanguage/' + id,
            success: function (response) {
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('input[name="language_title"]').focus();
                $('input[name="language_title"]').focus();
                $('input[name="language_title"]').val(response.language_title);
                $('input[name="iso_code"]').val(response.iso_code);
                $('select[name="rtl"]').val(response.rtl).trigger('change');
                $('input[name="iso_code"]').blur();
                $('input[name="iso_code"]').blur();
                $('.designation_form_div').hide();
                $('.languages_form_div').show();
                $('.department_form_div').hide();
            }
        });
    });

    $(document).on('click', '.openDataSidebarForUpdatedepartment', function () {
        var id = $(this).attr('id');
        $('#dataSidebarLoader').show();
        $('.languages_form_div').hide();
        $('.designation_form_div').hide();
        $('.department_form_div').hide();
       $('.offcanvas-heading').text(Lang.get('fields.department_details'));
        $('#opp_id').val(id);
        openSidebar();
        last_operation = 'update';
        opp_name = 'department';
        $('#operation').val('update');
        $('#opp_name_input').val('department');
        $.ajax({
            type: 'GET',
            url: '/admin/GetDepartment/' + id,
            success: function (response) {
                var response = JSON.parse(response);
                $('#dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
                $('input[name="department_name"]').focus();
                $('input[name="department_name"]').val(response.department);
                $('input[name="department_name"]').blur();
                $('.designation_form_div').hide();
                $('.department_form_div').show();
            }
        });
    });

    $(document).on('click', '#saveBtn', function () {
        var invalidSave = [];
        var designation_rights = [];
        var pnl_access = 0
        if (opp_name == 'designation') {
            $('.required_designation').each(function () {
                if (!$(this).val() || $(this).val() == '') {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please provide all the role information (*)');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    invalidSave.push(true);
                } else {
                    invalidSave.push(false);
                }
            });

        } else if (opp_name == 'department') {
            $('.required_department').each(function () {
                if (!$(this).val() || !$(this).val().trim()) {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please provide all the department information (*)');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    invalidSave.push(true);
                } else {
                    invalidSave.push(false);
                }
            });
        }else if (opp_name == 'documents') {
            $('.required_doc').each(function () {
                if (!$(this).val() || !$(this).val().trim()) {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please provide all the documents information (*)');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    invalidSave.push(true);
                } else {
                    invalidSave.push(false);
                }
            });
        }  
        else if (opp_name == 'branches') {
            $('.required_branch').each(function () {
                if (!$(this).val() || !$(this).val().trim()) {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please provide all the Branch information (*)');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    invalidSave.push(true);
                } else {
                    invalidSave.push(false);
                }
            });
        } 
        else if (opp_name == 'language') {
            $('.required_languages').each(function () {
                if (!$(this).val() || !$(this).val().trim()) {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Please provide all the Langauge information (*)');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    invalidSave.push(true);
                } else {
                    invalidSave.push(false);
                }
            });
        }

        if (invalidSave.includes(true))
            return;

        $('#saveBtn').attr('disabled', 'disabled');
        $('#btn-cancel').attr('disabled', 'disabled');
        $('#saveBtn').text(Lang.get('fields.processing'));

        $('#saveSettingsForm').ajaxSubmit({
            type: "POST",
            url: '/admin/save_settings',
            data: {
                designation_rights: designation_rights,
                pnl_access: pnl_access
            },
            cache: false,
            success: function (response) {

                $('#saveBtn').removeAttr('disabled');
                $('#btn-cancel').removeAttr('disabled');
                $('#saveBtn').text(Lang.get('fields.save'));
                if (JSON.parse(response) == "success") {
                    closeSidebar();
                    if (opp_name == 'department') {
                        fetchDepartmentsData();
                    } else if (opp_name == 'designation') {
                        fetchDesignationData();

                    } else if (opp_name == 'language') {
                        fetchLanguagesData();
                    }
                    else if (opp_name == 'branches') {
                        fetchBranches();
                    }
                    else if (opp_name == 'documents') {
                        fetchDocuments();
                    }
                    $('#notifDiv').text('Saved Successfully!');
                    if ($('#operation').val() == "add") {
                        $('input[name="designation_name"]').val('');
                        $('input[name="department_name"]').val('');

                    }
                    //  debugger;


                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if (JSON.parse(response) == "already_exist") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Already Exist!');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to save at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            },
            error: function (err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    // $(document).on('click', '.delete_settings', function () {
    //     var id = $(this).attr('id');
    //     glob_type = $(this).attr('name');
    //     $('.confirm_delete').attr('id', id);
    //     deleteRef = $(this);
    //     $('#hidden_btn_to_open_modal').click();
    // })

    // $(document).on('click', '.confirm_delete', function () {
    //     var thisRef = $(this);
    //     thisRef.attr('disabled', 'disabled');
    //     thisRef.text('Processing...');
    //     var id = $(this).attr('id');
    //     $('.cancel_delete_modal').attr('disabled', 'disabled');
    //     deleteRef.attr('disabled', 'disabled');
    //     deleteRef.text('Processing...');
    //     $.ajax({
    //         type: "POST",
    //         url: '/admin/delete_from_settings',
    //         data: {
    //             _token: $('meta[name="csrf_token"]').attr('content'),
    //             type: glob_type,
    //             id: id
    //         },
    //         cache: false,
    //         success: function (response) {
    //             thisRef.removeAttr('disabled');
    //             deleteRef.removeAttr('disabled');
    //             thisRef.text('Yes');
    //             $('.cancel_delete_modal').removeAttr('disabled');
    //             if (JSON.parse(response) == "success") {

    //                 $('#notifDiv').fadeIn();
    //                 $('#notifDiv').css('background', 'green');
    //                 $('#notifDiv').text('Deleted successfully');
    //                 setTimeout(() => {
    //                     $('#notifDiv').fadeOut();
    //                 }, 3000);
    //                 $('.cancel_delete_modal').click();

    //             } else {
    //                 $('#notifDiv').fadeIn();
    //                 $('#notifDiv').css('background', 'red');
    //                 $('#notifDiv').text('Failed to add delete at the moment');
    //                 setTimeout(() => {
    //                     $('#notifDiv').fadeOut();
    //                 }, 3000);
    //             }
    //         },
    //         error: function (err) {
    //             if (err.status == 422) {
    //                 $.each(err.responseJSON.errors, function (i, error) {
    //                     var el = $(document).find('[name="' + i + '"]');
    //                     el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
    //                 });
    //             }
    //         }
    //     });
    // });

});
$(document).on('click', '.change_status', function() {
    const $this = $(this), type = $this.attr('data-entity'), status = $this.attr('active');
    const currentRef =$(this);
        currentRef.attr('disabled',true);
    ajaxer('/upate-setting-status', 'POST', {
        "_token": $('meta[name="csrf_token"]').attr('content'),
        'id': $this.attr('id'),
        'type': type
    }).then(() => {
        currentRef.attr('disabled',true);
        $this.removeAttr('disabled')
             .text(status == "1" ? "In Active" : "Active")
             .attr("active", status == "1" ? "0" : "1");
             $('#notifDiv').fadeIn();
             $('#notifDiv').css('background', 'green');
             $('#notifDiv').text(`Status Updates Succesfully`);
             setTimeout(() => {
                 $('#notifDiv').fadeOut();
             }, 3000);
      
        switch (type) {
            case 'department': fetchDepartmentsData(); break;
            case 'designation': fetchDesignationData(); break; 
            case 'branches': fetchBranches(); break;
            case 'documents': fetchDocuments(); break;
        }
        return;
    }).catch( currentRef.attr('disabled',false));
});

$(document).on('click', '.fetchDepartments', function () {
    if (all_departments && !all_departments.length) {
        $('.loader').show();
        fetchDepartmentsData();
    } else {
        $('.loader').hide();
    }

});
$(document).on('click', '.fetchLanguages', function () {
    if (all_languages && !all_languages.length) {
        $('.loader').show();
        fetchLanguagesData();
    } else {
        $('.loader').hide();
    }

});
$(document).on('click', '.fetchDocuments', function () {
    if (all_docs && !all_docs.length) {
        $('.loader').show();
        fetchDocuments();
    } else {
        $('.loader').hide();
    }

});
$(document).on('click', '.fetchDesignation', function () {
    $('.loader').hide();
});

function fetchDesignationData() {
    $('.loader').show();
    $('.body_designations').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/GetDesignationsData',
        success: function (response) { 
            var response = JSON.parse(response); 
            $('.body_designations').append(`
            <table class="table table-hover dt-responsive nowrap" id="designationsTable" style="width:100%;">
                <thead>
                    <tr>
                        <th>${Lang.get('fields.id')}</th>
                        <th>${Lang.get('fields.role')}</th>    
                        <th>${Lang.get('fields.actions')}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>`);
            $('#designationsTable tbody').empty();
            response.forEach(element => {

                $('#designationsTable tbody').append(`
                    <tr>
                        <td>${element['id']}</td>
                        <td>${element['designation']}</td> 
                        <td>
                        <button title="Edit" id="${element['id']}" class="btn btn-outline-primary openDataSidebarForUpdateDesignation ">
                        <svg width="10" height="13" viewBox="0 0 10 13"
                                                   fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path
                                                       d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                       fill="" />
                       </svg> 
                        </button>
                            
                        </td>
                    </tr>`);
            });
            $('.body_designations').fadeIn();
            $('#designationsTable').DataTable({
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10
            });
            $('.loader').hide();
        }
    });
}

function fetchDepartmentsData() {
    $('.loader').show();
    $('.body_departments').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/GetDepartmentData',
        success: function (response) {
            all_departments = JSON.parse(response); 
            $('.body_departments').append(`
                <table class="table table-hover dt-responsive nowrap  " id="departmentsTable" style="width:100%;"><thead><tr>
                <th>${Lang.get('fields.id')}</th><th>${Lang.get('fields.department')}</th><th>${Lang.get('fields.actions')}</th></tr></thead><tbody></tbody></table>`);
            $('#departmentsTable tbody').empty();
            all_departments.forEach(element => {
                $('#departmentsTable tbody').append(`
                <tr>
                    <td>${element['id']}</td>
                    <td>${element['department']}</td>
                    <td>
                    <button  title="Edit" id="${element['id']}" class="btn btn-outline-primary openDataSidebarForUpdatedepartment ">
                    <svg width="10" height="13" viewBox="0 0 10 13"
                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path
                                                   d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                   fill="" />
                   </svg> 
                    </button>
                    </td>
                </tr>`);
            });
            $('.body_departments').fadeIn();
            $('#departmentsTable').DataTable({
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10
            });

            $('.loader').hide();
        }
    });
}

function fetchLanguagesData() {
    $('.loader').show();
    $('.body_languages').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/GetLanguageData',
        success: function (response) {
            all_languages = JSON.parse(response); 
            $('.body_languages').append(`<table class="table table-hover dt-responsive nowrap" id="languagesTable" style="width:100%;"><thead><tr>
                <th>${Lang.get('fields.id')}</th><th>${Lang.get('fields.language_title')}</th><th>${Lang.get('fields.language_iso_code')}</th><th>${Lang.get('fields.rtl')}</th> 
                <th>${Lang.get('fields.status_heading')}</th><th>${Lang.get('fields.actions')}</th></tr></thead><tbody></tbody></table>`);
            $('#languagesTable tbody').empty();
            all_languages.forEach(element => {
                $('#languagesTable tbody').append(`
                <tr>
                    <td>${element['language_id']}</td>
                    <td>${element['language_title']}</td>
                    <td>${element['iso_code']}</td>
                    <td>${element['rtl'] == 1 ? 'Yes' : 'No'}</td>
                    <td class="status-${element['language_id']}">${element['status'] == 1 ? 'Active' : 'In Active'}</td>
                    <td>
                    <button title="Edit" id="${element['language_id']}" class="btn btn-outline-primary openDataSidebarForUpdateLangugae">
                    <svg width="10" height="13" viewBox="0 0 10 13"
                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path
                                                   d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                   fill="" />
                   </svg> 
                    </button>
                    ${element.is_default == 0 ?
                        `   <button type="button" class="btn btn-primary language_status" is-default = "${element.is_default}" id="${element['language_id']}"  active="${element.status}">${element['status'] == 1 ? 'In Active' : 'Active'}</button>
                        `: ''}
                     </td >
                </tr > `);
            });
            $('.body_languages').fadeIn();
            $('#languagesTable').DataTable({
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10
            });

            $('.loader').hide();
        }
    });
}
function fetchBranches() {
    $('.loader').show();
    $('.body_branch').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/GetBranchData',
        success: function (response) {
            branches = JSON.parse(response); 
            $('.body_branch').append(`
                <table class="table table-hover dt-responsive nowrap" id="branchTable" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>${Lang.get('fields.branch')}</th>
                        <th>${Lang.get('fields.branch_code')}</th> 
                        <th>${Lang.get('fields.status_heading')}</th>
                        <th>${Lang.get('fields.actions')}</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>`
            );
            $('#branchTable tbody').empty();
            branches.forEach(element => {
                $('#branchTable tbody').append(`
                <tr>
                    <td>${element['id']}</td>
                    <td>${element['branch']}</td>
                    <td>${element['code']}</td> 
                    <td class="status-${element['id']}">${element['status'] == 1 ? 'Active' : 'In Active'}</td>
                    <td>
                    <button title="Edit" id="${element['id']}" class="btn btn-outline-primary openDataSidebarForUpdateBranch">
                    <svg width="10" height="13" viewBox="0 0 10 13"
                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path
                                                   d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                   fill="" />
                   </svg> 
                    </button>  <button type="button" data-entity="branches" class="btn btn-primary change_status"  " id="${element['id']}"  active="${element.status}">${element['status'] == 1 ? 'In Active' : 'Active'}</button>
                     
                     </td >
                </tr > `);
            });
            $('.body_branch').fadeIn();
            $('#branchTable').DataTable({
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10
            }); 
            $('.loader').hide();
        }
    });
}
function fetchDocuments() {
    $('.loader').show();
    $('.body_doc').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/GetDocumentTypes',
        success: function (response) {
            all_docs = JSON.parse(response); 
            $('.body_doc').append(`
                <table class="table table-hover dt-responsive nowrap" id="documentTable" style="width:100%;">
                <thead>
                    <tr>
                        <th>${Lang.get('fields.id')}</th>
                        <th>${Lang.get('fields.document_type')}</th> 
                        <th>${Lang.get('fields.status_heading')}</th>
                        <th>${Lang.get('fields.actions')}</th>
                    </tr>
                </thead>
                <tbody></tbody>
                </table>`
            );
            $('#documentTable tbody').empty();
            all_docs.forEach(element => {
                $('#documentTable tbody').append(`
                <tr>
                    <td>${element['id']}</td>
                    <td>${element['document_name']}</td> 
                    <td class="status-${element['id']}">${element['status'] == 1 ? 'Active' : 'In Active'}</td>
                    <td>
                    <button title="Edit" id="${element['id']}" class="btn btn-outline-primary openDataSidebarForUpdateDoc">
                    <svg width="10" height="13" viewBox="0 0 10 13"
                                               fill="none" xmlns="http://www.w3.org/2000/svg">
                                               <path
                                                   d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                   fill="" />
                   </svg> 
                    </button>  <button type="button" data-entity="documents" class="btn btn-primary change_status"  " id="${element['id']}"  active="${element.status}">${element['status'] == 1 ? 'In Active' : 'Active'}</button>
                     
                     </td >
                </tr > `);
            });
            $('.body_doc').fadeIn();
            $('#documentTable').DataTable({
                responsive: true,
                searching: false,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10
            }); 
            $('.loader').hide();
        }
    });
}
$(document).on('click', '.language_status', function () {
    if ($(this).attr('is-default') == 1) {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text(`Default language status can't be change`);
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
    this_Ref = $(this);
    var status = $(this).attr('active') == "1" ? "0" : "1";
    var id = $(this).attr('id'); 
    if (status == 0) {
        $('.confirm_delete').attr('id', id);
        $('.confirm_delete').attr('status', status);
        $('.modal-title').html('Confirm Deactivation')
        deleteRef = $(this);
        $('.modal-custom-text').empty();
        $('.modal-custom-text').html('Do you really want  to In-Active this language?');

        $('#hidden_btn_to_open_modal').click();
    } else {
        ajaxer('/upate-language-status', 'POST', {
            "_token": $('meta[name="csrf_token"]').attr('content'),
            'id': $(this).attr('id'),
            'status': status
        }).then(x => {
            this_Ref.removeAttr('disabled')
            this_Ref.text(status == "1" ? "In Active" : "Active");
            this_Ref.attr("active", status);
            fetchLanguagesData();

        })
    }

});
$(document).on('click', '.confirm_delete', function () {
    var thisRef = $(this);
    thisRef.attr('disabled', 'disabled');
    var status = this_Ref.attr('status');
    var id = this_Ref.attr('id');
    $('.cancel_delete_modal').attr('disabled', 'disabled');
    deleteRef.attr('disabled', 'disabled');

    ajaxer('/upate-language-status', 'POST', {
        "_token": $('meta[name="csrf_token"]').attr('content'),
        'id': id,
        'status': status
    }).then(x => {
        thisRef.attr('disabled', false);
        $('.cancel_delete_modal').attr('disabled', false);
        this_Ref.removeAttr('disabled')
        this_Ref.text(status == "1" ? "In Active" : "Active");
        this_Ref.attr("active", status);
        $('.cancel_delete_modal').click();
        fetchLanguagesData();


    });
});
$(document).on('click', '.default_language', function () {
    var id = $(this).attr('id');
    $(this).attr('disabled', true);
    var title = $(this).attr('language-title');
    if (id) {
        $.ajax({
            type: "POST",
            url: '/admin/change-defualt-language/' + id,
            data: {
                _token: $('meta[name="csrf_token"]').attr('content'),
                id: id
            },
            cache: false,
            success: function (response) {
                $(this).attr('disabled', false);

                if (response.status == 'success') {
                    fetchLanguagesData();
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text(`Default language set to ${title}`);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
                else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text(`Unable to set language to default at this moment`);
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);

                }
            }, error: function (err) {
                $(this).attr('disabled', false);
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text(`Unable to set language to default at this moment`);
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
            }
        });
    } else {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Invalid Language');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
    }
});