<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">

</head>

<body>
    <div class="login-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <span class="logo_name">
                        <img src="/images/mindyourlanguageInc.png" alt="">
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-6 leftside">

                    <div class="login-form">
                        <form method="POST" action="{{ route('client.email') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">

                                    <a class="btn btn-back-page" href="{{ route('customer-logout') }}">
                                        <svg width="29" height="16" viewBox="0 0 29 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M28.6309 8.04785L3.56655 8.04785" stroke=""
                                                stroke-width="2.34978" />
                                            <path d="M8.26562 15L1.99955 8.04781L8.26562 0.999999" stroke=""
                                                stroke-width="2.34978" />
                                        </svg>
                                    </a>

                                    <h1>Reset your client password</h1>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Enter email address</label>
                                        <input type="email" class="form-control" id="email"
                                            {{ $errors->has('email') ? ' is-invalid' : '' }}
                                            placeholder="zeeshan@gmail.com" name="email" required=""
                                            value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="f_next_btn btn btn-primary"
                                                title="Next">Send Reset Password Link</button>
                                        </div>

                                        <div class="col-12" style="margin-top: 20px">
                                            @if (session('status'))
                                                <div class="alert alert-success" role="alert">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-6 rightside"></div>
            </div>
        </div>
    </div>

    <script src="/js/jquery-3.3.1.min.js"></script> 
    <script src="/js/bootstrap.bundle.min.js"></script>

</body>

</html>