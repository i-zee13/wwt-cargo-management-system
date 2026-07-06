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
    <link rel="stylesheet" type="text/css" href="/css/select2-bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/css/select2.min.css"> 
    <!-- Custom fonts for this template-->
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
    {{--
    <link href="/css/login-style.css" rel="stylesheet"> --}}
</head>

<body>
    <style>
        .iti--inline-dropdown {
            width: 100%;
        }

        .logo_name {
            z-index: 99999999
        }
 
        .top-left-logo {
            position: absolute;
            top: 20px; 
            left: 20px; 
            z-index: 1000;
             
        }

        .top-left-logo .logo_name img {
            width: 300px; 
            height: auto;
            max-width: 100%; 
            margin-bottom: 20px;
        } 
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            background-color: #f8f9fa;
            /* Light background for contrast */
        }

        .container {
            width: 100%;

        }

        /* Form box styling */
        .row.justify-content-center .col-lg-8 {
            background-color: #edf1f2;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form heading */
        .text-center h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Labels */
        .form-label {
            color: #555;
            font-weight: 500;
            margin-bottom: 5px;
        }

        /* Input fields */
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: none;
            padding: 10px 15px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Invalid feedback */
        .invalid-feedback {
            color: #d9534f;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Select dropdowns */
        .select_class {
            padding: 10px;
            border-radius: 5px;
        }

        /* Buttons */
        .btn-primary {

            padding: 10px 20px;
            font-size: 16px;
            margin-top: 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Links */
        .forgot-password-link {
            font-size: 14px;
            color: #007bff;
            opacity: 0.85;
            transition: color 0.2s;
        }

        .forgot-password-link:hover {
            color: #0056b3;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .row.justify-content-center .col-lg-8 {
                padding: 20px;
            }
            .top-left-logo .logo_name img {
            width: 160px; 
            height: auto;
            max-width: 100%; 
            margin-bottom: 20px;
        } 
        }
    </style>
    <div class="register-page__topbar">
        <div class="auth-page__lang">
            @include('includes.language-toggle')
        </div>
    </div>
    <div class="row top-left-logo ">
    <div class=" col-md-12">
                    <span class="logo_name">
                        <img src="{{getOrganizationData()->logo}}" alt="">
                    </span>
                </div>  
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8 col-md-10 col-12" style="background-color:#edf1f2;">
                <div class="py-4">
                    <div class="text-center mb-4">
                        <h2>{{ __('fields.create_account') }}</h2>
                    </div>
                    <form method="post" action="{{ route('customer.submitSignUp')}}" id="registerForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12 form-group">
                                <label for="first_name" class="form-label">{{ __('fields.first_name') }}*</label>
                                <input id="first_name" type="text"
                                    class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                    name="first_name" value="{{ old('first_name') }}"
                                    placeholder="{{ __('fields.enter_first_name') }}"  autofocus>
                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group">
                                <label for="last_name" class="form-label">{{ __('fields.last_name') }}*</label>
                                <input id="last_name" type="text"
                                    class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                    name="last_name" value="{{ old('last_name') }}"
                                    placeholder="{{ __('fields.enter_last_name') }}" required>
                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group">
                                <label for="email" class="form-label">{{ __('fields.email') }}*</label>
                                <input id="email" type="email"
                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                    value="{{ old('email') }}" placeholder="{{ __('fields.enter_email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group">
                                <label for="confirm_email" class="form-label">{{ __('fields.confirm_email') }}*</label>
                                <input id="confirm_email" type="email"
                                    class="form-control {{ $errors->has('confirm_email') ? ' is-invalid' : '' }}"
                                    name="confirm_email" value="{{ old('confirm_email') }}"
                                    placeholder="{{ __('fields.enter_confirm_email') }}" required>
                                @if ($errors->has('confirm_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-12 form-group mt-3">
                                <label for="phone" class="form-label">{{ __('fields.phone_number') }}*</label>
                                <input type="text" name="phone"
                                    class="form-control phone_field {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    id="phone" placeholder="{{ __('fields.enter_phone') }}" value="{{ old('phone') }}"
                                    required>
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                         
                            <div class="col-lg-6 col-md-12 mt-3">
                                <label for="country" class="form-label">{{ __('fields.select_country') }}*</label>
                                <div class="form-s2">
                                    <select
                                        class="form-control required_main select_class {{ $errors->has('country') ? ' is-invalid' : '' }}"
                                        id="country" name="country" data-name="country" style="width: 100%;" required>
                                        <option value="">{{ __('fields.select_country') }}</option>
                                        @foreach (@$countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 mt-3">
                                <label for="state" class="form-label">{{ __('fields.select_state') }}*</label>
                                <div class="form-s2">
                                    <select
                                        class="form-control required_main select_class {{ $errors->has('state') ? ' is-invalid' : '' }}"
                                        id="state" name="state" data-name="state" style="width: 100%;" required>
                                        <option value="">{{ __('fields.select_state') }}</option>
                                         
                                    </select>
                                    @if ($errors->has('state'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 form-group mt-3">
                                <label for="postal_code" class="form-label">{{ __('fields.postal_code') }}</label>
                                <input type="text" name="postal_code"
                                    class="form-control {{ $errors->has('postal_code') ? ' is-invalid' : '' }}"
                                    id="postal_code" placeholder="{{ __('fields.enter_postal_code') }}"
                                    value="{{ old('postal_code') }}">
                                @if ($errors->has('postal_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('postal_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-12 col-md-12 form-group mt-3">
                                <label for="address" class="form-label">{{ __('fields.address') }}*</label>
                                <input type="text" name="address"
                                    class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" id="address"
                                    placeholder="{{ __('fields.enter_address') }}" value="{{ old('address') }}" required>
                                @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group">
                                <label for="password" class="form-label">{{ __('fields.password') }}*</label>
                                <input id="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" placeholder="{{ __('fields.enter_password') }}" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group">
                                <label for="confirm_password" class="form-label">{{ __('fields.confirm_password') }}*</label>
                                <input id="confirm_password" type="password"
                                    class="form-control{{ $errors->has('confirm_password') ? ' is-invalid' : '' }}"
                                    name="confirm_password" placeholder="{{ __('fields.confirm_password') }}" required>
                                @if ($errors->has('confirm_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-12 mt-3">
                                <label for="branch" class="form-label">{{ __('fields.branch') }}*</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select
                                            class="form-control required_content select_class {{ $errors->has('branch') ? ' is-invalid' : '' }}"
                                            id="branch" name="branch" data-name="branch" style="width: 100%;" required>
                                            <option value="">{{ __('fields.select_branch') }}</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('branch') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->branch }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('branch'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('branch') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-12 mt-3">
                                <label for="client_type" class="form-label">{{ __('fields.person_or_company') }}</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select
                                            class="form-control required_content select_class {{ $errors->has('client_type') ? ' is-invalid' : '' }}"
                                            id="client_type" name="client_type" data-name="client_type" style="width: 100%;">
                                            <option value="">{{ __('fields.select_person_or_company') }}</option>
                                            <option value="person">Person</option>
                                            <option value="company">Company</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('client_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('client_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group mt-3 company_name_div" style="display:none">
                                <label for="company_name" class="form-label">{{ __('fields.company_name') }}</label>
                                <input id="company_name" type="company_name"
                                    class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}"
                                    name="company_name" placeholder="{{ __('fields.enter_company_name') }}"  >
                                @if ($errors->has('company_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            
                            <div class="col-lg-6 col-md-12 mt-3">
                                <label for="document_type" class="form-label">{{ __('fields.document_type') }}*</label>
                                <div class="icon-input">
                                    <div class="form-s2">
                                        <select
                                            class="form-control required_content select_class {{ $errors->has('document_type') ? ' is-invalid' : '' }}"
                                            id="document_type" name="document_type" data-name="document_type" style="width: 100%;" required>
                                            <option value="">{{ __('fields.select_document_type') }}</option>
                                            @foreach ($document_types as $document_type)
                                                <option value="{{ $document_type->id }}" {{ old('document_type') == $document_type->id ? 'selected' : '' }}>
                                                    {{ $document_type->document_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('document_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('document_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 form-group mt-3">
                                <label for="document_number" class="form-label">{{ __('fields.document_number') }}*</label>
                                <input id="document_number" type="document_number"
                                    class="form-control{{ $errors->has('document_number') ? ' is-invalid' : '' }}"
                                    name="document_number" placeholder="{{ __('fields.enter_document_number') }}" required>
                                @if ($errors->has('document_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('document_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                                <div class="col-12 text-end">
                                    @if (Route::has('customer-login'))
                                        <a style="text-decoration: none; opacity: 0.75; color: var(--bs-body-color);"
                                            class="forgot-password-link" href="{{ route('customer-login') }}"
                                            title="Login ?">
                                            {{__('fields.already_have_account')}}
                                        </a>
                                    @endif

                                </div>
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary submitBtn">{{__('fields.sign_up')}}</button>
                                    @if (Route::has('customer.request'))
                                        <a style="text-decoration: none; opacity: 0.75; color: var(--bs-body-color);"
                                            class="forgot-password-link" href="{{ route('customer.request') }}"
                                            title="Forgot Password?">
                                            Forgot password?
                                        </a>
                                    @endif

                                </div>

                            </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script src="/js/jquery-3.3.1.min.js"></script>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/lang-toggle.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
             var translations = @json([
        'sign_up' => __('fields.sign_up'),
        'signing_up' => __('fields.signing_up'),
        // Add more translations as needed
    ]);
    $('.select_class').select2();
        const inputField = document.querySelector(".phone_field");
        const iti = window.intlTelInput(inputField, {
            initialCountry: "us",
            nationalMode: false,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js"
        });
   

        inputField.addEventListener('keypress', function (e) {
            if (!/[\d\s+\-()]/.test(e.key)) {
                e.preventDefault();
            }
        });
        inputField.addEventListener('input', function () {
            if (inputField.value.length === 0) {
                iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
            }
        });
        if (inputField.value.length === 0) {
            iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
        }
        $(document).ready(function () { 
    $('.submitBtn').text(translations.sign_up).attr('disabled', false);
});

$('#registerForm').on('submit', function () {
    $('.submitBtn').attr('disabled', true);
    $('.submitBtn').text(translations.signing_up);
});
var states = @json($states); 
$(document).on('change', '#country', function() {
        var selectedCountry = $(this).val(); 
        $('#state').empty(); 
        if($(this).val()){
            var filteredStates = states.filter(x=> x.country_id == $(this).val());
            if(filteredStates){
                filteredStates.forEach(element => {
                    $('#state').append(`<option value="${element.id}">${element.name}</option>`) 
                });

            }
        }
        
    });
    $(document).on('change', '#client_type', function() {
        if($(this).val() && $(this).val() == 'company'){
            $('.company_name_div').fadeIn();
        }else{
            $('.company_name_div').fadeOut();
            $('#company_name').val('');
        }


    });


    </script>
    
</body>

</html>