<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{--
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/menu_icons/favicon.ico') }}" type="image/x-icon">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,800" rel="stylesheet">
    <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    @if ($controller !== 'Products')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @endif 
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link href="/css/dropzone.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="/css/datatables.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" type="text/css" href="/css/select2-bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/css/select2.min.css"> 
    <link rel="stylesheet" type="text/css" href="/css/dropify.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/fSelect.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/tag-editor/1.0.20/jquery.tag-editor.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/tag-editor/1.0.20/jquery.tag-editor.min.css.map" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
    @stack('css')


    <style>
        /* body {
            display: none
        } */

        #notifDiv {
            display: none;
            background: red;
            color: white;
            font-weight: 400;
            font-size: 15px;
            width: 350px;
            position: fixed;
            top: 80%;
            left: 10%;
            z-index: 10000;
            padding: 10px 20px
        }

        #addMoreProductsInOrder:hover {
            color: white !important
        }

        #product-cl-sec {
            box-shadow: 0px 0px 100px 0px rgba(0, 0, 0, 0.5);
        }

        .overlay-for-sidebar {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            z-index: 998;
            opacity: 0;
        }

        /* .select2 {
            width: 100% !important;
            z-index: 999
        } */

        .dz-image img {
            width: 100%;
            height: 100%;
        }

        .peventsDisabled {
            pointer-events: none
        }

        .datepicker-dropdown {
            z-index: 1060 !important;
        }

        #repDelayBtn:hover,
        #addProdBtn:hover,
        #markComplBtn:hover {
            color: white !important
        }

        .select2 {
            width: 100% !important;
        }



        .capitalize {
            text-transform: capitalize;
        }

        .mark,
        mark {
            padding: 0px;
            background-color: #ffff00;
        }

        .blur-div {
            -webkit-filter: blur(10px);
            -o-filter: blur(10px);
            -webkit-transition: all 0.7s;
            -moz-transition: all 0.7s;
            transition: all 0.7s;
        }

        .overlay-blure {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            z-index: 998;
            opacity: 0;
        }

        .blur-div .overlay-blure {
            display: block;
            opacity: 1;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {

            justify-content: center;
        }
    </style>



    <style>
        .bootstrap-datetimepicker-widget a {
            color: #dfaa09;
        }
    </style>
</head>


<body id="page-top" class="no-scroll"> 
    <main> 
        <div class="loader-page">
            <div class="loader-img">
                <img class="hue-animation" src="{{getOrganizationData()->logo}}" alt="Loading">
                <img src="{{getOrganizationData()->logo}}" alt="">
            </div>
        </div> 
        @if (explode('/', Request::path())[0] != 'CompletedOrderDetail')
        @include('includes.nav-new')
        @endif
        <div id="notifDiv">
        </div>

        <div class="wrapper" style="display: none">
            @include('includes.modals-web')
            <input type="hidden" id="auth_user_id" value="{{ Auth::user()->id }}">
            <div class="container container-div-bl">
                <div class="row">
                    <div class="col-12">
                        @include('includes.headers')

                        @yield('data-sidebar')
                        <div id="contentContainerDiv" style="padding-bottom: 30px;">
                            <div class="content dashboard-page">

                                @yield('content')

                            </div>
                        </div>
                        @include('includes.footer')
                    </div>
                </div>
            </div>
        </div>
        
    </main>


    {{-- <script src="/js/jquery-3.3.1.slim.min.js"></script> --}}
    <script src="/js/jquery-3.3.1.min.js"></script>

    <script src="/js/bootstrap.bundle.min.js"></script>

    <script src="/js/popper.min.js"></script>
    <script src="{{ url('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ url('ckfinder/ckfinder.js') }}"></script> 
    <script src="/js/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/js/dropify.min.js"></script>
    <script src="/js/custom.js"></script>
    <script src="/js/jquery.form.min.js"></script>
    <script src="/js/selectize.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/jquery.mCustomScrollbar.min.js"></script>

    <script src="/js/bootstrap-datepicker.js?v=1.1"></script>
    <script src="/js/time-picker-bootstrap.js?v=1.1"></script>
    <script src="/js/time-picker-movement.js?v=1.1"></script>
    <script src="/js/dropzone.js"></script>
    <script src="/js/jquery.twbsPagination.min.js"></script>
    <script src="/js/jquery.twbsPagination.min.js"></script>
    <script src="/js/dropzone-data.js"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    @php
    $auth_seg = 1;
    if (!isset($empDesignation)) {
        if (GetActiveGuardDetail()->is_web == 1) {
            $designation = GetActiveGuardDetail()->designation;
            $designationData = DB::select("SELECT * from designations where id = $designation limit 1");
            $empDesignation = !empty($designationData) ? $designationData[0]->designation : 0;
            $auth_seg = 2;
        } 
    }

    $isWebPayload = ['is_web' => GetActiveGuardDetail()->is_web];
    $loggedInUserPayload = [
        'user_id' => GetActiveGuardDetail()->id,
        'name' => GetActiveGuardDetail()->first_name,
        'picture' => GetActiveGuardDetail()->picture,
    ];
    
@endphp
    <script>
        var is_web = @json($isWebPayload);
        var rightsGiven = @json($userPermissions ?? []);
        var allControllersData = @json($allControllers ?? []);
        var controller = @json($controller ?? '');
        var designation = @json(isset($empDesignation) ? $empDesignation : '');
        var activeLang = @json(isset($activeLang) ? $activeLang : config('app.locale'));
        var fallbackLang = @json(config('app.fallback_locale'));
        var controllerAction = @json($action ?? '');
        var currentSegment = @json(Request::segment($auth_seg));
        var csrfToken = $('[name="csrf_token"]').attr('content');
        var loggedInUser = @json($loggedInUserPayload);
        
        //$(".sortable").sortable();
    </script>

<script src="{{ route('translations.js') }}"></script>
    <script src="/js/lang-toggle.js"></script>
    <script src="/js/master.js?v={{ time() }}"></script>
    <script src="/js/specialChar.js?v={{ time() }}"></script>

    <script src="/js/custom/nav.js?v=1.2.0"></script>
    <script src="/js/list.js"></script>
    <script src="{{ asset('/js/fSelect.js') }}"></script>
    <script></script>
    @yield('page-scripts')
    @stack('js')
    

</body>

</html>