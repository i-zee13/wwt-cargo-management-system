$(document).ready(function() {

    fetchDesignationAccessRights(); 
    var lastOp = "add";
    var currentEmpId = 0; 

    $(document).on('click', '.openDataSidebarForAddingAccessRights', function() {
        $('.dataSidebarLoader').hide();
        $('[name="rights[]"]').prop('checked', false);
        $('input[id="operation"]').val('add');
        openSidebar();
        $('#employeesRow').show();
    });

    $(document).on('click', '.openDataSidebarForUpdateAccessRights', function() {

        $('[name="rights[]"]').prop('checked', false);
       
        $('.dataSidebarLoader').show();
        $('._cl-bottom').hide();
        $('.pc-cartlist').hide();

        $('input[id="operation"]').val('update');
        openSidebar();

        $('#employeesRow').hide();

        currentEmpId = $(this).attr('id');
        $.ajax({
            type: "GET",
            url: '/admin/DesignationAccessRights/' + currentEmpId,
            success: function(response) {
                console.log(response);
                var response = JSON.parse(response); 
                response.forEach(element => {
                    $('input[value="' + element["controller_name"] + '"]').prop('checked', true);
                });

                $('.dataSidebarLoader').hide();
                $('._cl-bottom').show();
                $('.pc-cartlist').show();
            }
        });

    }); 
    $(document).on('click', '.all_rights', function(){
        if($(this).prop('checked') == true){
            $('.access_rights_headings').each(function () {
                $(this).prop('checked', true);
            })
            $('.access_rights_emp').each(function () {
                $(this).prop('checked', true);
            })
        }else{
            $('.access_rights_headings').each(function () {
                $(this).prop('checked', false);
            })
            $('.access_rights_emp').each(function () {
                $(this).prop('checked', false);
            })
        }
        
    })

    $(document).on('click', '.access_rights_headings', function(){
        var this_heading = $(this).attr('heading');
        if($(this).prop('checked') == true){
            $('.access_rights_emp').each(function () {
                if($(this).attr('heading') == this_heading){
                    $(this).prop('checked', true);
                } 
            })
        }else{
            $('.access_rights_emp').each(function () {
                if($(this).attr('heading') == this_heading){
                    $(this).prop('checked', false);
                } 
            }) 
        }
    })


    $(document).on('click', '#saveRights', function() {
        var rights = [];
        $('.access_rights_emp').each(function () {
            if($(this).prop('checked') == true){
                rights.push($(this).val());
            }
        })

        if(rights.length == 0){
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Please Select Access Rights');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
            return
        }

        $('#saveRights').attr('disabled', 'disabled');
        $('#cancelRights').attr('disabled', 'disabled');
        $('#saveRights').text(Lang.get('fields.processing'));

        var ajaxUrl = "/admin/DesignationAccessRights";
        if ($('#operation').val() !== "add") {
            ajaxUrl = "/admin/DesignationAccessRights/" + currentEmpId;
        }

        $('#saveRightsForm').ajaxSubmit({
            type: $('#operation').val() !== "add" ? "PUT" : "POST",
            url: ajaxUrl,
            data: {
                rights: rights,
                designation_id : currentEmpId
            },
            cache: false,
            success: function(response) { 
                if (JSON.parse(response) == "success") {
                    fetchDesignationAccessRights();
                    $('#saveRights').removeAttr('disabled');
                    $('#cancelRights').removeAttr('disabled');
                    $('#saveRights').text(Lang.get('fields.save'));

                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Access Rights have been assigned successfully');
                    currentEmpId = '';
                      closeSidebar();
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else if (JSON.parse(response) == "exist") {
                    $('#saveRights').removeAttr('disabled');
                    $('#cancelRights').removeAttr('disabled');
                    $('#saveRights').text(Lang.get('fields.save'));
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Rights already given. Please update this Designation\'s existing rights');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                } else {
                    $('#saveRights').removeAttr('disabled');
                    $('#cancelRights').removeAttr('disabled');
                    $('#saveRights').text(Lang.get('fields.save'));
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Failed to add rights at the moment');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            },
            error: function(err) {
                if (err.status == 422) {
                    $.each(err.responseJSON.errors, function(i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        el.after($('<small style="color: red; position: absolute; width:100%; text-align: right; margin-left: -30px">' + error[0] + '</small>'));
                    });
                }
            }
        });

    });

    $(document).on('click', '.deleteAccessRight', function() {
        var typeId = $(this).attr('id');
        var thisRef = $(this);
        thisRef.attr('disabled', 'disabled');
        $.ajax({
            type: "GET",
            url: '/admin/revoke-designation-acc-right/' + typeId,
            data: thisRef.parent().serialize(),
            cache: false,
            success: function(response) {
                if (JSON.parse(response) == "success") {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'green');
                    $('#notifDiv').text('Access Rights have been revoked');
                    setTimeout(() => {
                        $('#notifDiv').fadeOut();
                    }, 3000);
                    fetchDesignationAccessRights();  
                } else {
                    $('#notifDiv').fadeIn();
                    $('#notifDiv').css('background', 'red');
                    $('#notifDiv').text('Unable to revoke rights at this moment');
                    setTimeout(() => {
                        thisRef.removeAttr('disabled');
                        $('#notifDiv').fadeOut();
                    }, 3000);
                }
            }
        });
    });

});

function fetchDesignationAccessRights() {
    $.ajax({
        type: 'GET',
        url: '/admin/list-designation-rights',
        success: function(response) {
            $('.body_accessrights').empty();
            $('.body_accessrights').append(`
                <table class="table table-hover dt-responsive nowrap" id="rightsTable" style="width:100%;">
                    <thead>
                    <tr>
                    <th>${translations.sno}</th>
                    <th>${translations.role}</th>
                    <th>${translations.total_rights}</th>
                    <th>${translations.actions}</th>
                    </tr>
                    </thead>
                <tbody></tbody></table>`);
            $('#rightsTable tbody').empty();
            var response = JSON.parse(response);
            var sNo = 1; 
            response.forEach(element => {
                console.log(element);
                $('#rightsTable tbody').append(`
                    <tr>
                        <td>${sNo++ }</td>
                     <td>${element['designation'] }</td>
                     <td>${element['total_rights'] }</td>
                     <td> 
                      <button title="Edit"  id="${element['id']}" class="btn btn-outline-primary openDataSidebarForUpdateAccessRights">
                        <svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                        d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                        fill="" />
                        </svg>
                    </button> 
                    <button title="Delete"  type="button" id="${element['id']}" class="btn btn-outline-primary btn-delete deleteAccessRight">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                        d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                        fill="" /> </svg>
                    </button>     
                     </td></tr>`);
            });
            $('#tblLoader').hide();
            $('.body_accessrights').fadeIn(); 
            
          var Rightstable =  $('#rightsTable').DataTable({
                responsive: true,
                searching: true,
                lengthChange: false,
                info: false,
                pagingType: 'simple_numbers',
                pageLength: 10,
                dom: "lrtip",
            });
            $("#customSearchInput").on("keyup", function () {
                Rightstable.search(this.value).draw();
              });
        
            $('#Tablelist_paginate').addClass('d-flex justify-content-center');
        }
    });
}
