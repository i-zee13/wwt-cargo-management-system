<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet">
    {{-- <link href="/css/login-style.css" rel="stylesheet"> --}}
</head>
<style>
    .error {
        text-align: center;
        padding: 6.25rem 1rem;
    }
    .card {
        height: 100vh;
    }

    .error .error-div {
        width: 100%;
        height: 8.125rem;
        font-size: 6.875rem;
        line-height: 1;
        font-weight: 900;
        color: #303030;
        text-align: center;
        margin: auto auto 1.875rem;
    }

    .error .error-div span {
        background-image: url(../images/wwt-logo.png);
        background-position: center center;
        background-repeat: no-repeat;
        background-size: contain;
        width: 140px;
        height: 140px;
        display: inline-block;
        margin-bottom: -1.563rem;
        margin-left: .938rem;
        margin-right: .938rem;
        border: outset 8px var(--bs-secondary) !important;
        border-radius: 54px;
    }

    .error h2 {
        font-size: 1.375rem;
        line-height: normal;
        margin-bottom: 15px;
    }

    .error .btn-primary {
        margin-top: 1.563rem
    }

    @media screen and (max-width: 1024px) {
        .error .error-div {
            height: 6.25rem;
            font-size: 5.625rem
        }

        .error .error-div span {
            width: 6.25rem;
            height: 6.25rem;
            margin-bottom: -1.125rem
        }
    }

    @media screen and (max-width: 767px) {
        .error .error-div {
            height: 5rem;
            font-size: 4.375rem;
            margin-bottom: 1.25rem
        }

        .error .error-div span {
            width: 5rem;
            height: 5rem;
        }

        .error h2 {
            font-size: 1.125rem
        }

        .error .btn-primary {
            font-size: .875rem;
            margin-top: 1.25rem
        }
    }
</style>

<body style="overflow: hidden">

    <div class="row">
        <div class="col-12">
            <div class="card m-0"  >

                <div class="row">
                    <div class="col-12 error">
                        <div class="error-div">4<span></span>4</div>
                        <h2>Oops! 404 – page not Found</h2>
                        <p>We’re sorry, the page you requested could not be found.</p>
                        <button type="button" class="btn btn-primary go-back">Go Back</button>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).on('click','.go-back',function(){
         window.history.back();
    });
</script>
    <body>

</html>
