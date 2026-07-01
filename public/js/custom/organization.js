var lastOp = "";
var segments = location.href.split('/');
$(document).ready(function () {
    loadPhoneValidation()
    getOrganizationPhone();
    $('#organizationForm').css('display', 'block');
    $('#tblLoader').hide();

});
function loadPhoneValidation() {
    // Select all phone fields and apply validation
    document.querySelectorAll('.phone_field').forEach(inputField => {
        const iti = window.intlTelInput(inputField, {
            initialCountry: "us",
            nationalMode: false,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js"
        });

        inputField.addEventListener('keypress', function(e) {
            if (!/[\d\s+\-()]/.test(e.key)) {
                e.preventDefault();
            }
        });

        inputField.addEventListener('input', function() {
            if (inputField.value.length === 0) {
                iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
            }
        });

        if (inputField.value.length === 0) {
            iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
        }
    });
}
$(document).on('click', '#submit-button', function () {
    let dirty = false;
    let notValidUrl = false;
    var inputFile = $('.organization_logo')[0];

    $('.required').each(function () {
        if ($(this).val().trim().length < 2) {
            dirty = true;
        }

    });

    if (dirty) {
        showNotification('Please provide all the required information (*)', 'red');
        return;
    }
    var count = 1;
    $('.url-link-organization').each(function() {
        var url = $(this).val();
        if(url && url.trim()){
            if (!isValidUrl(url)) { 
                notValidUrl = true;
                if(count == 1){
                    $(this).focus();
                }
                count++;
            
            } 
        }
       
    }); 
    var emailInput = $('#email').val();
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (!emailPattern.test(emailInput)) {
        showNotification('Please enter a valid email address (*)', 'red');
        return false;
    }
    dirty = false;
    var count = 1;
    $(document).find('.phone_field').each(function(){
        if(!$(this).val().includes('+')){
            if(count == 1){
                $(this).focus();
            }
            dirty  = true;
            console.log($(this).attr('name'),$(this).attr('id'))
            count++; 
        }
    });
    if(dirty){
        $('#notifDiv').fadeIn();
        $('#notifDiv').css('background', 'red');
        $('#notifDiv').text(`Please Enter Valid Phone Number`);
        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
    
    if (inputFile.files.length > 0 && !inputFile.files[0].type.match(/image.*/)) {
        showNotification('Icon type must be an Image (*)', 'red');
        return;
    } else if (inputFile.files.length === 0 && !inputFile.getAttribute('data-default-file')) {
        showNotification('Please Choose Business Icon (*)', 'red');
        return;
    }
    if(notValidUrl){
        showNotification('Please Enter Valid Social Media Link', 'red');
        return;
    }

    handleFormSubmit();
});

function isValidUrl(url) {
         
    var pattern = new RegExp('^(https?:\\/\\/)?' +  
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' +  
        '((\\d{1,3}\\.){3}\\d{1,3}))' + 
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' +  
        '(\\?[;&a-z\\d%_.~+=-]*)?' +  
        '(\\#[-a-z\\d_]*)?$', 'i');  

    return !!pattern.test(url);
}

function handleFormSubmit() {
    var isNotEmpty = checkIfFieldsNotEmpty();
    if (!$('.phone_div').find('.col-md-6').length > 0) {
        showNotification('Please Add ateast one phone number', 'red');
        return;
    } else if (!isNotEmpty) {
        showNotification('Please ensure that all phone number input fields are filled before saving.', 'red');
        return;

    }
    var CurrentRef = $('#submit-button');
    CurrentRef.attr('disabled', 'disabled').text(Lang.get('fields.processing'));

    var url = "/admin/save-organization";
    $('#organizationForm').ajaxSubmit({
        type: "POST",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
        success: function (response) {
            if (response.status == 'success') {
                showNotification(' Business Details Added Successfully', 'green');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                    window.location.reload()
                }, 3000);
            } else {
                showNotification('Not Added at this moment', 'red');
            }
            CurrentRef.attr('disabled', false).text(Lang.get('fields.save'));
        },
        error: function (err) {
            CurrentRef.attr('disabled', false).text(Lang.get('fields.save'));
            showNotification('Not Added at this moment', 'red');

            if (err.status == 403) {
                showNotification(err.responseJSON['message'], 'red');
            }
            if (err.status == 422) {
                var count = 1;

                $.each(err.responseJSON.errors, function (i, error) {

                    var el = $('[name="' + i + '"]');
                    if (count == 1) {
                        el.focus();
                        count++;
                    }
                    el.after($('<small name="' + i + '" style="color: red; position: absolute; width:100%; text-align: LEFT;">' + error[0] + '</small>'));
                });
            }
        }
    });
}
$('.add_phone_no').on('click', function () {
    appendPhoneFields();
});

function appendPhoneFields() {
    var isNotEmpty = checkIfFieldsNotEmpty(); 
    if (isNotEmpty) {
        var uniqueKey = generateUniqueIntegerKey(8);
        $('.phone_div').append(`
    <div class="col-md-6 delete-${uniqueKey}" >
                                            <div class="row">
                                                <div class="col-auto pr-0 form-group">
                                                    <div class="icon-input">
                                                        <div class="form-s2"  style="width:120px!important">
                                                            <select class="form-select select_class"
                                                                name="type[${uniqueKey}][]"
                                                                style="width: 100%; border-top-right-radius: 0px!important;border-right:none;
                                                                border-bottom-right-radius: 0px!important;">
                                                                <option value="">${Lang.get('fields.type')}</option>
                                                                <option value="1">${Lang.get('fields.business')}</option>
                                                                <option value="2">${Lang.get('fields.home')}</option>
                                                                <option value="3">${Lang.get('fields.whatsapp')}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col pl-0 form-group">
                                                    <div class="icon-input">
                                                    <button type="button" id="${uniqueKey}"  class=" btn btn-outline-primary  remove-phone btn-delete" data-action="deleteFromPage" >
                                                    <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z" fill=""></path> </svg>
                                                </button>
                                                        <input type="text" name="phone_numbers[${uniqueKey}][]" class="phone_field form-control border-l-0"
                                                             data-name="" placeholder="00000000000"
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    `);
        $('.select_class').select2({
            minimumResultsForSearch: Infinity
        })
        loadPhoneValidation();
    } else {

        showNotification('Please complete the phone number field that has already been added before adding new.', 'red');
        return;
    }
}

$('.dropify-clear').on('click', function () {
    var name = $(this).prev('input').attr('name');
    $(`input[name='${name}']`).attr('data-default-file', '');
    if (name = "meta_og_image") {
        $(`input[name='image_og_hidden']`).attr('value', '');
    }
});

const inputElements = document.querySelectorAll('input');

function handleChange(event) {
    const changedInput = event.target;
    $('small[name="' + changedInput.name + '"]').remove();
}

inputElements.forEach(input => {
    input.addEventListener('input', handleChange);
    input.addEventListener('change', handleChange);
});

function generateUniqueIntegerKey(length) {
    var keySet = new Set();

    while (true) {
        var randomKey = Math.floor(Math.random() * Math.pow(10, length));
        if (!keySet.has(randomKey)) {
            keySet.add(randomKey);
            return randomKey;
        }
    }
}

function checkIfFieldsNotEmpty() {
    var emptyFields = false;

    $('.phone_div').find('select[name^="type"], input[name^="phone_numbers"]').each(function () {
        if ($(this).val() === '') {
            emptyFields = true;
            $(this).focus();
            return false;
        }
    });

    if (emptyFields) {
        return false;
    } else {
        return true;
    }
}

function getOrganizationPhone() {
    $.ajax({
        type: 'GET',
        url: '/admin/getOrganizationPhones',
        success: function (response) {
            if (response.phone_numbers && response.phone_numbers.length) {
                $('.phone_div').empty();
                response.phone_numbers.forEach(element => {
                    $('.phone_div').append(`
                    <div class="col-md-6 delete-${element.id}">
                    <div class="row">
                        <div class="col-auto pr-0 form-group">
                            <div class="icon-input">
                                <div class="form-s2" style="width:120px!important">
                                    <select class="form-select select_class border-r-0"
                                        name="type[${element.id}][]"
                                      >
                                        <option value="">${Lang.get('fields.type')}</option>
                                        <option value="1"
                                            ${element.type && element.type == 1 ? 'selected' : ''}>
                                            ${Lang.get('fields.business')}</option>
                                        <option value="2"
                                              ${element.type && element.type == 2 ? 'selected' : ''} >
                                            ${Lang.get('fields.home')}</option>
                                        <option value="3"
                                              ${element.type && element.type == 3 ? 'selected' : ''}  >
                                            ${Lang.get('fields.whatsapp')}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col pl-0 form-group ">
                          <div class="icon-input ">
                          <button type="button" id="${element.id}"  class="btn btn-outline-primary  remove-phone btn-delete" data-action="deleteFromDatabase" >
                                <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z" fill=""></path> </svg>
                            </button>
                                <input type="text"
                                    name="phone_numbers[${element.id}][]"
                                    class=" phone_field form-control border-l-0" data-name=""
                                    placeholder="00000000000"
                                    value="${element.phone_number}">
                            </div>
                        </div>
                    </div>
                </div>  
                    `);
                });
                $('.select_class').select2({
                    minimumResultsForSearch: Infinity
                })
            } else {
                $('.phone_div').empty();
                appendPhoneFields();
            }
            loadPhoneValidation();
        }
    });
}
$(document).on('click', '.remove-phone', function () {
    if ($('.phone_div').find('.col-md-6').length == 1) {
        showNotification('At least one phone number is required and cannot be deleted.', 'red');
        return;
    }
    var action = $(this).attr('data-action');
    var id = $(this).attr('id'); 
    if (action == 'deleteFromDatabase') {
        $.ajax({
            type: 'POST',
            url: '/admin/deleteOrganizationPhone',
            data: {
                'id': id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == 'success') {
                    $(`.delete-${id}`).remove();
                    showNotification('This Phone Number is deleted', 'green')
                    return;
                } else {
                    showNotification('Unable to delete this phone number right now', 'red')
                    return;

                }
            },
            error: function (error) {
                showNotification('Unable to delete this phone number right now', 'red')
                return;

            }
        });
    } else {
        $(`.delete-${id}`).remove();
        showNotification('This Phone Number is deleted', 'green')
        return;
    }


});