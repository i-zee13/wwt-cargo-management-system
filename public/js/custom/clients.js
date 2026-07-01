var clients = [];
let date = '';
$(document).ready(function () {
    date = sessionStorage.getItem('selectedDate'); 
    sessionStorage.removeItem('selectedDate');
    fetchClients(); 
});
function fetchClients(){
   
    $('#tblLoader').show();
    $('.clients_list').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/getClients',
        data: { date: date },
        success: function (response) { 
            date = '';
            clients = response.clients;
            console.log(clients);
            $('.clients_list').append(`
            <table class="table table-hover dt-responsive nowrap" id="clientsTable" style="width:100%;">
                <thead>
                    <tr>
                        <th>${Lang.get('fields.id')}</th>
                        <th>${Lang.get('fields.suite')}</th>
                        <th>${Lang.get('fields.name')}</th>    
                        <th>${Lang.get('fields.email')}</th>    
                        <th>${Lang.get('fields.phone_number')}</th>    
                        <th>${Lang.get('fields.branch')}</th>    
                        <th>${Lang.get('fields.document')}</th>    
                        <th>${Lang.get('fields.actions')}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>`);
            $('#clientsTable tbody').empty();
            clients.forEach(element => {

                $('#clientsTable tbody').append(`
                    <tr>
                    <td>${element['id']}</td> 
                    <td>${element['suite']??''}</td> 
                        <td style="width: 200px !important; word-break: break-all !important; overflow-wrap: anywhere !important; white-space: normal !important;">${element['first_name']??''+ element.last_name??''}</td>
                        <td>${element['email']??''}</td> 
                        <td>${element['phone']??''}</td> 
                        <td>${element['branch_name']??''}</td> 
                        <td>${element['document_number']??''}</td> 
                        <td>
                        <button id="${element['id']}" class="btn btn-outline-primary openDataSidebarForAddingClient">
                        <svg width="10" height="13" viewBox="0 0 10 13"
                                                   fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path
                                                       d="M4.68745 11.18L4.5055 10.7143L4.68745 11.18ZM1.98319 11.6993L1.73319 12.1323H1.73319L1.98319 11.6993ZM1.08078 9.09767L0.586477 9.17295L1.08078 9.09767ZM1.40764 7.36282L1.84065 7.61282L1.40764 7.36282ZM1.01821 8.18767L0.526854 8.09509H0.526854L1.01821 8.18767ZM6.02644 10.0295L6.45946 10.2795L6.02644 10.0295ZM5.50683 10.7792L5.83268 11.1584L5.50683 10.7792ZM3.93993 2.97677L3.50692 2.72677L3.93993 2.97677ZM8.12572 5.39343L5.59343 9.77949L6.45946 10.2795L8.99175 5.89343L8.12572 5.39343ZM1.84065 7.61282L4.37295 3.22677L3.50692 2.72677L0.974629 7.11282L1.84065 7.61282ZM4.5055 10.7143C3.77452 10.9999 3.27668 11.193 2.89236 11.2828C2.52111 11.3696 2.35265 11.3352 2.23319 11.2663L1.73319 12.1323C2.1687 12.3837 2.63595 12.3697 3.11987 12.2566C3.59073 12.1466 4.16753 11.9199 4.86941 11.6457L4.5055 10.7143ZM0.586477 9.17295C0.699936 9.91791 0.792032 10.5308 0.93219 10.9935C1.07624 11.4692 1.29768 11.8808 1.73319 12.1323L2.23319 11.2663C2.11373 11.1973 1.99977 11.0686 1.88926 10.7037C1.77486 10.326 1.69324 9.79823 1.57508 9.02239L0.586477 9.17295ZM0.974629 7.11282C0.760081 7.48443 0.588305 7.76894 0.526854 8.09509L1.50956 8.28024C1.5322 8.16011 1.59202 8.04348 1.84065 7.61282L0.974629 7.11282ZM1.57508 9.02239C1.5002 8.53078 1.48693 8.40038 1.50956 8.28024L0.526854 8.09509C0.465403 8.42124 0.521869 8.74875 0.586477 9.17295L1.57508 9.02239ZM5.59343 9.77949C5.34479 10.2101 5.2737 10.3203 5.18097 10.3999L5.83268 11.1584C6.08441 10.9421 6.24491 10.6511 6.45946 10.2795L5.59343 9.77949ZM4.86941 11.6457C5.26908 11.4896 5.58095 11.3747 5.83268 11.1584L5.18097 10.3999C5.08825 10.4796 4.96869 10.5333 4.5055 10.7143L4.86941 11.6457ZM7.33267 2.43371C7.88924 2.75505 8.26258 2.97182 8.51981 3.17033C8.76556 3.35997 8.84384 3.48771 8.87564 3.60638L9.84157 3.34756C9.73042 2.93278 9.46507 2.63665 9.13075 2.37865C8.80792 2.12952 8.36476 1.87489 7.83267 1.56769L7.33267 2.43371ZM8.99175 5.89343C9.29895 5.36134 9.55542 4.91925 9.71044 4.54208C9.87097 4.15149 9.95271 3.76235 9.84157 3.34756L8.87564 3.60638C8.90744 3.72506 8.90351 3.87483 8.78551 4.16193C8.66199 4.46246 8.44706 4.83686 8.12572 5.39343L8.99175 5.89343ZM7.83267 1.56769C7.30058 1.26048 6.85848 1.00401 6.48131 0.848997C6.09072 0.688463 5.70159 0.606727 5.2868 0.717869L5.54562 1.68379C5.66429 1.652 5.81406 1.65592 6.10117 1.77392C6.4017 1.89744 6.77609 2.11237 7.33267 2.43371L7.83267 1.56769ZM4.37295 3.22677C4.69428 2.67019 4.91106 2.29686 5.10956 2.03962C5.2992 1.79388 5.42694 1.71559 5.54562 1.68379L5.2868 0.717869C4.87201 0.829011 4.57588 1.09436 4.31788 1.42869C4.06875 1.75152 3.81412 2.19468 3.50692 2.72677L4.37295 3.22677ZM8.80873 5.21042L4.18993 2.54375L3.68993 3.40978L8.30873 6.07645L8.80873 5.21042Z"
                                                       fill="" />
                       </svg> 
                        </button>
                         <button title="Delete"  type="button" id="${element['id']}" class="btn btn-outline-primary   delete_client_record">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                        d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                        fill="" /> </svg>
                    </button>
                    ${element.email_verified_at == null ?
                        `<button type="button" id="${element.id}" class="btn btn-primary verifyClient">Verify</button> `:``
                    }
                            
                        </td>
                    </tr>`);
            });
            $('.clients_list').fadeIn();
           var clientsTable = $('#clientsTable').DataTable({
            responsive: true,
            searching: true,
            lengthChange: false,
            info: false,
            pagingType: 'simple_numbers',
            pageLength: 10, dom: 'B<"dt-buttons">rtip',   
            buttons: {
                buttons: [
                  {
                    extend: 'excelHtml5',  
                    text: ' <i class="bi bi-file-earmark-arrow-down" width="16" height="16"></i> Export to Excel',
                    className: 'btn btn-primary',
                    exportOptions: { 
                        columns: ':not(:last-child)'
                    }
                  }
                ]
              }
            }); 
            $("#customSearchInput").on("keyup", function () {
                clientsTable.search(this.value).draw();
              });
        
            $('#Tablelist_paginate').addClass('d-flex justify-content-center');
            $('#tblLoader').hide();
        }
    });


}
$(document).on('click','.verifyClient',function(){
    var id = $(this).attr('id');
    var thisRef = $(this);
    thisRef.attr("disabled", "disabled");
    thisRef.text(Lang.get('fields.processing'));
    if(id){
        $.ajax({
            type: "POST",
            url: "/admin/verify-client",
            data: {
              _token: $('meta[name="csrf_token"]').attr("content"),
              id: id,
            },
            cache: false,
            success: function (response) {
              thisRef.removeAttr("disabled"); 
              thisRef.text(Lang.get('fields.verify'));
              $(".cancel_delete_modal").removeAttr("disabled");
              if (response.status == "success") { 
                $("#customSearchInput").val("");
                fetchClients();
                $("#notifDiv").fadeIn();
                $("#notifDiv").css("background", "green");
                $("#notifDiv").text(" Client Verified successfully");
                setTimeout(() => {
                  $("#notifDiv").fadeOut();
                }, 3000);
              } else {
                $("#notifDiv").fadeIn();
                $("#notifDiv").css("background", "red");
                $("#notifDiv").text("Failed to verify client at the moment");
                setTimeout(() => {
                  $("#notifDiv").fadeOut();
                }, 3000);
              }
            },
            error: function (err) {
                thisRef.removeAttr("disabled"); 
              thisRef.text(Lang.get('fields.verify'));
              $("#notifDiv").fadeIn();
              $("#notifDiv").css("background", "red");
              $("#notifDiv").text("Failed to verify client at the moment");
              setTimeout(() => {
                $("#notifDiv").fadeOut();
              }, 3000);
            },
          });
    }else{
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Invalid Client Record');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
})
$(document).on('click','.delete_client_record',function(){
    const id = $(this).attr('id');
    $(".confirm_delete").attr("id", id);
    console.log('clicked');
    $("#hidden_btn_to_open_modal").click();
})
$(document).on('change','#branch',function(){
   var text = $(this).find("option:selected").text();
   $('#branch_name').val(text);
})
$(document).on("click", ".confirm_delete", function () { 
    var id = $(this).attr('id');
    var thisRef = $(this);
    thisRef.attr("disabled", "disabled");
    thisRef.text(Lang.get('fields.processing'));
    if(id){
        $.ajax({
            type: "POST",
            url: "/admin/delete-client",
            data: {
              _token: $('meta[name="csrf_token"]').attr("content"),
              id: id,
            },
            cache: false,
            success: function (response) {
              thisRef.removeAttr("disabled"); 
              thisRef.text("Yes");
              $(".cancel_delete_modal").removeAttr("disabled");
              if (response.status == "success") {
                $(".cancel_delete_modal").click();
                $("#customSearchInput").val("");
                fetchClients();
                $("#notifDiv").fadeIn();
                $("#notifDiv").css("background", "green");
                $("#notifDiv").text(" Client Deleted successfully");
                setTimeout(() => {
                  $("#notifDiv").fadeOut();
                }, 3000);
              } else {
                $("#notifDiv").fadeIn();
                $("#notifDiv").css("background", "red");
                $("#notifDiv").text("Failed to delete client at the moment");
                setTimeout(() => {
                  $("#notifDiv").fadeOut();
                }, 3000);
              }
            },
            error: function (err) {
              thisRef.removeAttr("disabled"); 
              thisRef.text("Yes");
              $("#notifDiv").fadeIn();
              $("#notifDiv").css("background", "red");
              $("#notifDiv").text("Failed to delete client at the moment");
              setTimeout(() => {
                $("#notifDiv").fadeOut();
              }, 3000);
            },
          });
    }else{
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Invalid Client Record');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
   
  });

$(document).on('click', '.openDataSidebarForAddingClient', function () {
    $('#dataSidebarLoader').hide();
    $('#password').addClass('required_content');
    $('.password_label').html(Lang.get('fields.password')+'*');
    $('.suite_div').hide();
    $('#saveClientForm')[0].reset();  
    $('#country').val('').trigger('change');
    $('#document_type').val('').trigger('change');
    $('#state').val('').trigger('change');
    $('#branch').val('').trigger('change');
    $('#client_type').val('').trigger('change');
    var id = $(this).attr('id');
    if (id && id.trim() != '') {
        $('#opp_id').val(id);
        client = clients.find(x => x.id == id);
        if (client) { 
            $('#saveBtn').attr('disabled',true);
            $('#country').val(client.country_id||'').trigger('change');
            $('#password').removeClass('required_content');
            $('.password_label').html(Lang.get('fields.password'));
            $('.suite_div').show();
            $('#suite').val(client.suite)
            $('#first_name').val(client.first_name||'')
            $('#last_name').val(client.last_name||'')
            $('#address').val(client.address||'')
            $('#company_name').val(client.company_name||'')
            $('#email').val(client.email||'')
            $('#branch').val(client.branch_id||'').trigger('change');
            $('#phone').val(client.phone||'')
            
            $('#document_type').val(client.document_type_id||'').trigger('change');
            $('#document_number').val(client.document_number||'') 
            let state_id = client.state_id??'';
            console.log(state_id);
            setTimeout(() => {
                console.log(state_id);
                $('#state').val(state_id).trigger('change');
              }, 1000);
            $('#postal_code').val(client.postal_code||'').trigger('change');
            $('#client_type').val(client.client_type||'').trigger('change');
        }
    } else {
        $('#opp_id').val(''); 
    }
    setTimeout(() => {
        $('#saveBtn').attr('disabled',false);
      }, 1000);
    openSidebar();
});
$(document).on('change', '#client_type', function() {
    if($(this).val() && $(this).val() == 'company'){
        $('.company_name_div').fadeIn();
    }else{
        $('.company_name_div').fadeOut();
        $('#company_name').val('');
    } 
});
$(document).on('click', '#saveBtn', function () {
    var dirty = false;
    var ref = $(this);
    var count = 1;
    $('.required_content').each(function () {
        if (!$(this).val() || $(this).val().trim() == '') {
            if(count == 1){
                $(this).focus();
            } 
                count = count+1; 
            dirty= true; 
        } 
    });
    if(dirty){
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Please provide all required information (*)');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }else if($('#password').val() && $('#password').val().length < 6){
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Password must be at least 6 characters');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }else if ($('#email').val() && !validateEmail($('#email').val())) {
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text('Please enter a valid email address');
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
     
    $(ref).attr('disabled', true); 
    $(ref).text(Lang.get('fields.processing'));

    $('#saveClientForm').ajaxSubmit({
        type: "POST",
        url: '/admin/save-client',
        cache: false,
        success: function (response) {
            $(ref).attr('disabled',false);
            
            $(ref).text(Lang.get('fields.save'));
            if (response.status == "success") {
                closeSidebar();
            fetchClients();
                $('#notifDiv').text(response.msg); 
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'green');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
            } else if (response.status == "error" || response.status == "duplicate") {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text(response.msg);
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
            } else {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Failed to save client at the moment');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                }, 3000);
            }
        },
        error: function (err) {
            $(ref).attr('disabled',false);
            
            $(ref).text(Lang.get('fields.save'));
            $('#notifDiv').fadeIn();
            $('#notifDiv').css('background', 'red');
            $('#notifDiv').text('Failed to save client at the moment');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
        }
    });

});
function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailPattern.test(email);
}