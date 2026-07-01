var clients = [];
$(document).ready(function () {
    
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