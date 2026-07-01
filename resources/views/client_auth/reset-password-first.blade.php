<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>IronHorse - Investor</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="/investor/css/bootstrap.min.css">

    <link href="/investor/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/investor/css/menu.css" rel="stylesheet"> 
    <link href="/investor/css/login-style.css" rel="stylesheet"> 
</head>

<body>
    <div class="form-body without-side">
        <div class="website-logo">
            <div class="logo">
                <img class="logo-size" src="/investor/images/iron-horse-residential-2.svg" alt="">
            </div>
        </div>
        <div class="row">
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <h3>Update Your Password First</h3>
                        <form id="updatePasswordForm" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{encrypt(GetActiveGuardDetail()->id)}}">
                            <input class="form-control" type="password" id="new_password" name="new_password" placeholder="New Password"> 
                            <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password"> 
                            <style>
                                .success-feedback {
                                    margin-top: 0.25rem;
                                    font-size: 80%;
                                    color: #89d500;
                                    font-weight: bolder;
                                    margin-bottom: 5px;
                                }
                                .invalid-feedback{
                                    font-weight: bolder;
                                    color: red;
                                    margin-bottom: 5px;
                                }
                            </style>
                                <span class="invalid-feedback" role="alert" style="display: none">
                            </span>
                                <span class="success-feedback" role="alert" style="display: none;">
                            </span>
                            <div class="form-button">
                                <button type="button" id="update_userpassword" class="btn ibtn">Update Password</button> 
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/jquery-3.3.1.min.js')}}"></script>
    {{-- <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}"></script> --}}
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.form.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '#update_userpassword', function() {
                if ($('#new_password').val() == "" || $('#confirm_password').val() == "") {
                    $('.invalid-feedback').css('display','block');
                    $('.invalid-feedback').html('Fill all required fileds');
                    return;
                }
                if ($('#new_password').val() != "" || $('#confirm_password').val() != "") {
                    if ($('#new_password').val() != $('#confirm_password').val()) {
                        $('.invalid-feedback').css('display','block');
                        $('.invalid-feedback').html('New Password and Confirm Password does not match!');
                        return;
                    }
                    if ($('#new_password').val().length < 6 || $('#confirm_password').val().length < 6) {
                        $('.invalid-feedback').css('display','block')
                        $('.invalid-feedback').html('New Password and Confirm Password should have atleast 6 characters');
                        return;
                    }
                }
                $('.invalid-feedback').hide();
                $(this).text('PROCESSING...');
                $(this).attr("disabled", "disabled");
                $('#updatePasswordForm').ajaxSubmit({
                    type	: "POST",
                    url		: "/update_user_password_first",
                    cache	: false,	
                    success	: function(response) {
                        console.log(response)
                        $("#update_userpassword").removeAttr('disabled');
                        $("#update_userpassword").text('Update Password');
                        if (JSON.parse(response) == "success") {
                            $('.success-feedback').css('display','block');
                            $('.success-feedback').html('Password updated successfully');
                            setTimeout(() => {
                                window.location = '/investor/index';
                            }, 1000);
                        } else if (JSON.parse(response) == "failed") {
                            $('.invalid-feedback').html('Unable to update at this moment');
                        }
                    }
                });
            });
        })
    </script>

</body>

</html>
