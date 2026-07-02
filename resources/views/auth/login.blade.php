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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
.logo_name{
    z-index: 99999999
}
.toggle_div { 
    color: #333333;
    position: absolute;
    top: 3rem;
    right: 5.875rem;
    z-index: 99999999;
}

@media (max-width: 768px) {
    .toggle_div {
        top: 2rem;
        right: 0rem;
        font-size: 0.9rem; 
        text-align: right;
        font-size: 12px;
    }

  
}

</style>
<body>
    <div class="login-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <span class="logo_name">
                        <img src="{{getOrganizationData()->logo}}" alt="">
                    </span>
                </div>
                  <div class="col-lg-6 col-md-12 toggle_div">        
                <input class="form-check-input" type="checkbox" id="languageToggle"
                      >
                    <label class="form-check-label" for="languageToggle" id="toggleLabel"> {{__('fields.spanish')}}</label> 
                </div> 
            </div>
            <div class="row">
                <div class="col-6 leftside" style="background-color:#edf1f2;">
                    <div class="login-form">
                        <div class="row">
                            <div class="col-md-12">
                                <h1>{{ __('fields.admin_login') }}</h1>
                                <form method="POST" action="/admin/mylogin">
                                    @csrf
                                    <input type="hidden" name="_token" value="{{ $csrf_token }}">
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{ __('fields.enter_username') }}</label>
                                        <input id="username" type="username"
                                            class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                            name="username" value="{{ old('username') }}" placeholder="{{ __('fields.enter_username') }}"
                                            required autofocus>
                                        @if ($errors->has('username'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('username') }}</strong>
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
                                        <div class="col-6">

                                            <button type="submit" class="btn btn-primary">{{ __('fields.login') }}</button>
                                        </div>
                                        <div class="col-6 text-end mt-auto mb-auto">
                                            @if (Route::has('password.request'))
                                                <a style="
                                        text-decoration: none;
                                        opacity: 0.75;
                                        color: var(--bs-body-color);
                                        "
                                                    class="forgot-password-lin" href="{{ route('password.request') }}"
                                                    title="Forgot Password?">{{ __('fields.forget_password') }}</a>
                                            @endif
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

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
         $(document).find('#languageToggle').prop('checked', "{{ session('locale', config('app.locale')) }}" === 'es');
         
$(document).on('change', '#languageToggle', function() {
    let languageToggle = $(this).prop('checked') ? 'es' : 'en';
    $.ajax({
        type: "POST",
        url: "/change-language",
        data: { 
            languageToggle: languageToggle
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false,
        success: function (response) {
            console.log("Language changed successfully:", response);
            location.reload();
        },
        error: function (xhr) {
            console.log("Error:", xhr.responseText);
            // Handle errors if needed
        }
    });
});
    </script>

</body>

</html>
