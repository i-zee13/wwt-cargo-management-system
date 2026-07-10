var clients = [];
$(document).ready(function () {
    
});
 
$(document).on('click', '#sendTestEmail', function () {
    var ref = $(this);
    var testEmail = $('#test_email').val().trim();
    var body_text = CKEDITOR.instances['body_text'].getData();

    if (!testEmail) {
        $('#notifDiv').fadeIn().css('background', 'red').text('Please enter a test email address.');
        setTimeout(function () { $('#notifDiv').fadeOut(); }, 3000);
        $('#test_email').focus();
        return;
    }

    var dirty = false;
    $('.required_content').each(function () {
        if (!$(this).val() || $(this).val().trim() === '') {
            dirty = true;
        }
    });

    if (dirty || !body_text || !body_text.trim()) {
        $('#notifDiv').fadeIn().css('background', 'red').text('Please provide all required information (*) before sending a test email.');
        setTimeout(function () { $('#notifDiv').fadeOut(); }, 3000);
        return;
    }

    ref.attr('disabled', true).text(Lang.get('fields.processing'));

    $.ajax({
        type: 'POST',
        url: '/admin/send-test-email',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            test_email: testEmail,
            pageType: $('#pageType').val(),
            subject: $('#subject').val(),
            headerText: $('#headerText').val(),
            buttonText: $('#buttonText').val(),
            footerText: $('#footerText').val(),
            body_text: body_text,
        },
        success: function (response) {
            ref.attr('disabled', false).text(Lang.get('fields.send_test_email'));
            $('#notifDiv').fadeIn().css('background', response.status === 'success' ? 'green' : 'red').text(response.msg);
            setTimeout(function () { $('#notifDiv').fadeOut(); }, 4000);
        },
        error: function () {
            ref.attr('disabled', false).text(Lang.get('fields.send_test_email'));
            $('#notifDiv').fadeIn().css('background', 'red').text('Failed to send test email.');
            setTimeout(function () { $('#notifDiv').fadeOut(); }, 4000);
        }
    });
});

$(document).on('click', '#saveContent', function () {
    var dirty = false;
    var ref = $(this);
    var count = 1;
    let body_text = CKEDITOR.instances['body_text'].getData();
    $('.required_content').each(function () {
        if (!$(this).val() || $(this).val().trim() == '') {
            if(count == 1){
                $(this).focus();
            } 
            count = count+1; 
            dirty= true; 
        } 
    });
    if(dirty || !body_text || !body_text.trim()){
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

    $('#saveEmailForm').ajaxSubmit({
        type: "POST",
        url: '/admin/save-email-content',
        cache: false,
        data:{body_text:body_text},
        success: function (response) {
            $(ref).attr('disabled',false); 
            $(ref).text(Lang.get('fields.save'));
            if (response.status == "success") { 
                $('#notifDiv').text(response.msg); 
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'green');
                setTimeout(() => {
                    $('#notifDiv').fadeOut();
                    window.location.reload()
                }, 3000);
            }else if (response.status == "error") { 
                $('#notifDiv').text(response.msg); 
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                setTimeout(() => {
                    $('#notifDiv').fadeOut(); 
                }, 3000);
            } 
              else {
                $('#notifDiv').fadeIn();
                $('#notifDiv').css('background', 'red');
                $('#notifDiv').text('Failed to save Email Content at the moment');
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
            $('#notifDiv').text('Failed to save Email Content at the moment');
            setTimeout(() => {
                $('#notifDiv').fadeOut();
            }, 3000);
        }
    });

});