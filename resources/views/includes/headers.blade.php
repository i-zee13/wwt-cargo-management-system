<header>
    <div class="row column-reverse mt-4">
        <div class="col-lg-8 col-md-12 mt-auto mb-auto">

            @if ($controller == 'HomeController')
                <div class="row">
                    <div class="col mt-auto mb-auto">
                        @if (GetActiveGuardDetail()->is_web == 1)
                        <h4 class="m-0 text-muted">{{ __('fields.admin_dashboard') }}</h4>
                        
                        @endif
                        <h1 class="m-0"> {{$greeting . ', ' . Auth::user()->first_name . ' ' . Auth::user()->last_name  }}!
                        </h1>
                    </div>
                    <!-- <div class="col-auto dashboard-search">
                                <div class="search">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z" stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <input class="form-control" type="text" placeholder="Search for anything">
                                </div>
                            </div> -->
                </div>
            @else
                <div class="col-lg-6 col-md-12">
                    <h1 class="content_head"></h1>
                    {{-- <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.33301 5.53304L5.99968 1.44971L10.6663 5.53304L10.6663 11.3664H7.74968V9.03304C7.74968 8.56891 7.5653 8.12379 7.23711 7.7956C6.90893 7.46742 6.46381 7.28304 5.99968 7.28304C5.53555 7.28304 5.09043 7.46742 4.76224 7.7956C4.43405 8.12379 4.24968 8.56891 4.24968 9.03304V11.3664H1.33301L1.33301 5.53304Z"
                                            stroke="" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a></li>
                            <li class="breadcrumb-item first_crumb"><a href="#"></a>
                            </li>
                            <li class="breadcrumb-item second_crumb"><a href="#"></a>
                            </li>

                        </ol>
                    </nav> --}}
                </div>
            @endif
        </div>

        <div class="col-lg-4 col-md-12 d-flex">

            <div class="logo-sm bx-menu">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 41.5 41.7"
                    style="enable-background:new 0 0 41.5 41.7;" xml:space="preserve">
                    <g>
                        <path class="st0" d="M10.7,0.8h20c5.5,0,10,4.5,10,10v20c0,5.5-4.5,10-10,10h-20c-5.5,0-10-4.5-10-10v-20C0.7,5.3,5.2,0.8,10.7,0.8
                                                        z"></path>
                    </g>
                    <path class="st1" d="M11.8,26.2h17.9 M11.8,20.8h17.9 M11.8,15.4h17.9">
                    </path>
                </svg>

            </div>


            <div class="form-check form-switch lang_div">

            </div>
            <ul class="nav">

                <li class="nav-item dropdown sign-in-user">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.167 10.375H3.83366C2.08476 10.375 0.666992 11.7928 0.666992 13.5417V15.125H13.3337V13.5417C13.3337 11.7928 11.9159 10.375 10.167 10.375Z"
                                stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M7.00033 7.20833C8.74923 7.20833 10.167 5.79057 10.167 4.04167C10.167 2.29276 8.74923 0.875 7.00033 0.875C5.25142 0.875 3.83366 2.29276 3.83366 4.04167C3.83366 5.79057 5.25142 7.20833 7.00033 7.20833Z"
                                stroke="" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <ul class="dropdown-menu">
                        @if (GetActiveGuardDetail()->is_web)
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                <input class="form-check-input" type="checkbox" id="languageToggle" >
                                <label class="form-check-label" for="languageToggle" id="toggleLabel"> {{__('fields.spanish')}}</label> 
                                </a>
                            </li>

                            <li><a class="dropdown-item" href="/admin/Profile/{{Auth::user()->id}}"> {{__('fields.view_profile')}}</a></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">{{__('fields.sign_out')}}</a></li>
                        @else
                        <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <input class="form-check-input" type="checkbox" id="languageToggle"
                                        onchange="toggleLanguage()">
                                    <label class="form-check-label" for="languageToggle" id="toggleLabel"> {{__('fields.spanish')}}</label>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="/customer-profile/{{Auth::user()->id}}"> {{__('fields.view_profile')}}</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('customer-logout') }}">{{__('fields.sign_out')}}</a></li>

                        @endif
                    </ul>
                </li>
            </ul>


        </div>

    </div>
</header>