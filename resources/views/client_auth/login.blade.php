<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
</head>
<style>
.flash-message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    padding: 15px 30px;
    border-radius: 1px solid black;
    font-size: 1rem;
    font-weight: bold;
    color: white;
    background: linear-gradient(135deg, black, #333); 
    display: flex;
    align-items: center;
    gap: 10px;
    animation: bounce-in 1s ease, fade-out 1s ease 9s forwards;
    transition: transform 0.3s ease;
}

.flash-message i {
    font-size: 1.5rem;
    animation: pulse 1.5s infinite; /* Pulsating icon */
}

.flash-message span {
    font-size: 1.2rem;
}

@keyframes bounce-in {
    0% {
        transform: translateX(-50%) translateY(-100px) scale(0.5);
        opacity: 0;
    }
    50% {
        transform: translateX(-50%) translateY(20px) scale(1.2);
        opacity: 1;
    }
    100% {
        transform: translateX(-50%) translateY(0) scale(1);
    }
}

@keyframes fade-out {
    from {
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }
    to {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px) scale(0.8);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
}
</style>

<body>
@if (session('error'))
    <div id="flash-message" class="flash-message">
        <span>{{ session('error') }}</span>
    </div>
@endif 
    <div class="login-page">
        <div class="container-fluid">
            <div class="login-page__topbar">
                <span class="logo_name">
                    <img src="{{getOrganizationData()->logo}}" alt="">
                </span>
                <div class="auth-page__lang">
                    @include('includes.language-toggle')
                </div>
            </div>
           
            <div class="row">
                <div class="col-6 leftside" style="background-color:#edf1f2;">
                    <div class="login-form">
                        <div class="row">
                            <div class="col-md-12">
                                <h1>{{ __('fields.client_login') }}</h1>
                                <form method="POST" action="{{ route('customer-mylogin') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{ __('fields.enter_email') }}</label>
                                        <input id="email" type="email"
                                            class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            name="email" value="{{ old('email') }}" placeholder="{{ __('fields.enter_email') }}" required
                                            autofocus>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">{{ __('fields.password') }}</label>
                                        <input id="password" type="password"
                                            class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            name="password" placeholder="{{ __('fields.password') }}" required>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row">

                                        <div class="col-12 text-end mt-2 mb-auto">
                                            @if (Route::has('client.request'))
                                                <a style="
                                                    text-decoration: none;
                                                    opacity: 0.75;
                                                    color: var(--bs-body-color);
                                                    " class="forgot-password-lin" href="{{ route('client.request') }}"
                                                    title="Forgot Password?">{{ __('fields.forget_password') }}?</a>
                                            @endif
                                            <br>
                                            @if (Route::has('customer.register'))
                                                <a style="
                                                            text-decoration: none;
                                                            opacity: 0.75;
                                                            color: var(--bs-body-color);" class="signup-link"
                                                    href="{{ route('customer.register') }}" title="Sign Up">{{ __('fields.no_account') }}</a>
                                            @endif
                                        </div>
                                        <div class="col-6 mt-2">
                                            <button type="submit" class="btn btn-primary">{{ __('fields.login') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 rightside"></div>
            </div>
        </div>
    </div>

    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/lang-toggle.js"></script>

</body>

</html>