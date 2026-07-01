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
                        <h3>Forgot Your Password?</h3>
                        <p></p>
                        <form method="POST" action="{{ route('client.email') }}">
                            @csrf
                            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}"  type="text" placeholder="Enter Email Address" required> 
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    <strong>Email send to your registered email. Please check your email inbox</strong>
                                </div>
                            @endif
                            <div class="form-button">
                                <button type="submit" class="btn ibtn">Send Password Reset Link </button> 
                            </div>
                        </form>
                         
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="js/jquery-3.3.1.slim.min.js"></script> 
<script src="js/popper.min.js"></script> 
<script src="js/bootstrap.min.js"></script>

</body>
</html>
