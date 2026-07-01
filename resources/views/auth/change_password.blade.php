<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/style.min.css" rel="stylesheet">
    <link href="/css/menu.min.css?v=1.0" rel="stylesheet">
</head>

<body class="bg_main">
    <div id="wrapper">
        <div class="log_con">
            <div class="container-fluid">
                <!-- Row -->
                <div class="table-struct full-width">
                    <div class="table-cell vertical-align-middle auth-form-wrap">
                        <div class="auth-form">
                            <div class="row m-0">
                                <div class="col-md-6">
                                    <div class="login-left">
                                        <div class="logo-company"> <img src="" alt="" /> </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="background-color: #f5f5f5">
                                    <div class="login-right">
                                        <h3>Change <span>Password</span></h3>
                                        <form method="POST" id="changePasswordForm">
                                            @csrf
                                            <div class="form-group">
                                                <div class="user"> <span class="fa fa-user-alt"></span>
                                                    <input id="pass_username" type="username" class="form-control required"
                                                        name="pass_username" placeholder="Username"> 
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="clearfix"></div>
                                                <div class="pass"> <span class="fa fa-unlock"></span>
                                                    <input id="old_pass" type="password" class="form-control required"
                                                        name="old_pass" placeholder="Old Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="clearfix"></div>
                                                <div class="pass"> <span class="fa fa-unlock"></span>
                                                    <input id="new_password" type="password" class="form-control required"
                                                        name="new_password" placeholder="New Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="clearfix"></div>
                                                <div class="pass"> <span class="fa fa-unlock"></span>
                                                    <input id="confirm_password" type="password" class="form-control required"
                                                        name="confirm_password" placeholder="Confirm Password">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="button" class="btn btn-info btn-login change_password">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Log_footer"> Copyright © 2024 {{ config('app.name') }} All rights reserved.<br>
                             </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/jquery-3.3.1.slim.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/datatables.min.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/js/dropify.min.js"></script>
    <script src="/js/custom.js"></script>
    <script src="/js/jquery.form.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        $(document).ready(function() {
            $(document).on('click', '.change_password', function(){
                let dirty = false;
                $('.required').each(function () {
                    $(this).css('border', '0px solid red');
                    if (!$(this).val()) {
                        $(this).css('border', '1px solid red');
                        dirty = true;
                    }
                });

                if (dirty) {
                   alert('Please fill all fields!');
                    return;
                }

                if($('input[name="new_password"]').val().length < 6){
                   alert('Password need to be atleast 6 characters!');
                    return;
                }
                if($('input[name="new_password"]').val() != $('input[name="confirm_password"]').val()){
                    alert('New Password does not match!');
                    return;
                }
                var thisRef = $(this);
                thisRef.attr('disabled', 'disabled');
                thisRef.text('Processing');
                $('#changePasswordForm').ajaxSubmit({
                    type: "POST",
                    url: '/ChangeUserPassword',
                    cache: false,
                    success: function (response) {
                        thisRef.removeAttr('disabled');
                        thisRef.text('Submit');
                        if (JSON.parse(response) == 200) {
                            location.replace("/login");
                        } else if(JSON.parse(response) == 201){
                            alert('Invalid credientials!');
                        } else {
                            if(JSON.parse(response) == 101){
                                alert('New Password and old password cannot be same!');
                            }else{
                                alert('Failed to change password at the moment!');
                            }
                        }   
                    }
                });
            })
        });
    </script>
</body>

</html>
