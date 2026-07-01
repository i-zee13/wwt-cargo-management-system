var translations = [];
$(document).ready(function () {
    fetchTranslations(); 
});
function fetchTranslations(){
   
    $('#tblLoader').show();
    $('.clients_list').empty();
    $.ajax({
        type: 'GET',
        url: '/admin/getTranslations',
        success: function (response) { 
            translations = response.translations;
            console.log(translations);
            $('.clients_list').append(`
            <table class="table table-hover dt-responsive nowrap" id="clientsTable" style="width:100%;">
                <thead>
                    <tr>
                        <th>${Lang.get('fields.english_text')}</th>
                        <th>${Lang.get('fields.spanish_text')}</th> 
                        <th>${Lang.get('fields.actions')}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>`);
            $('#clientsTable tbody').empty();
            translations.forEach(element => {

                $('#clientsTable tbody').append(`
                    <tr>
                 
                        <td>${element['english']??''}</td> 
                        <td>${element['spanish']??''}</td>  
                        <td>
                        <button id="${element['key']}" class="btn btn-outline-primary openDataSidebarForAddingClient">
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
            $('.clients_list').fadeIn();
           var clientsTable = $('#clientsTable').DataTable({
            responsive: true,
            searching: true,
            lengthChange: false,
            info: false,
            pagingType: 'simple_numbers',
            pageLength: 10,
            dom: "lrtip",
            }); 
            $("#customSearchInput").on("keyup", function () {
                clientsTable.search(this.value).draw();
              });
        
            $('#Tablelist_paginate').addClass('d-flex justify-content-center');
            $('#tblLoader').hide();
        }
    });


}
 
 

$(document).on('click', '.openDataSidebarForAddingClient', function () {
    $('#dataSidebarLoader').hide(); 
    $ 
    var id = $(this).attr('id');
    if (id && id.trim() != '') {
        $('#opp_id').val(id);
        translation = translations.find(x => x.key == id);
        if (translation) {  
            $('#english').val(translation.english||'')
            $('#spanish').val(translation.spanish||'') 
            $('#key').val(translation.key||'')
            openSidebar();
        }
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
    }
     
    $(ref).attr('disabled', true); 
    $(ref).text(Lang.get('fields.processing')); 
    $('#saveClientForm').ajaxSubmit({
        type: "POST",
        url: '/admin/save-translation',
        cache: false,
        success: function (response) {
            $(ref).attr('disabled',false);
            
            $(ref).text(Lang.get('fields.save'));
            if (response.status == "success") {
                closeSidebar();
      
                $('#notifDiv').text(response.msg); 
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'green');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                  window.location.reload();
                }, 2000);
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
                $('#notifDiv').text('Failed to update translation at the moment');
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
            $('#notifDiv').text('Failed to update translation at the moment');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
        }
    });

});