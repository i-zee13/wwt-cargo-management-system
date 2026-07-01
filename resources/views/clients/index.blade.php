@extends('layouts.master')
@section('data-sidebar')
<style>
    .iti--inline-dropdown {
        width: 100%;
    }
    .dt-buttons { 
float: right!important;
} 
.dt-buttons button {
padding: 5px 20px 4px 7px;
margin-bottom: 10px;
margin-top: 10px;
margin-right: 5px;
font-size: 13px;
border-radius: 25px;
line-height: 1;
} 
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.0/font/bootstrap-icons.css">
<form style="width: 100%" id="saveClientForm" autocomplete="off">
    @csrf
    <input type="text" id="operation" name="operation" hidden>
    <input type="hidden" id="opp_id" name="opp_id" hidden>
    <input type="text" id="opp_name_input" name="opp_name_input" hidden>
    <div class="offcanvas offcanvas-end AccRights offcanvas-width" tabindex="-1" id="access-right"
        aria-labelledby="access-rightLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-heading">{{__('fields.client_details')}}</h2>
            <button type="button" class="btn-close close_sidebar" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-wrap p-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 form-group   "  >
                        <label for="suite" class="form-label">{{__('fields.suite')}}</label>
                        <div class="icon-input">
                            <input type="text" name="suite" class="form-control  " id="suite" value=""
                                placeholder="{{__('fields.enter_suite')}} ">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 form-group">
                        <label for="first_name" class="form-label">{{__('fields.first_name')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="first_name" class="form-control required_content" id="first_name"
                                value="" placeholder="{{__('fields.enter_first_name')}} ">
                        </div>

                    </div>
                    <input type="hidden" id="branch_name" value="" name="branch_name">
                    <div class="col-lg-6 col-md-12 form-group">
                        <label for="last_name" class="form-label">{{__('fields.last_name')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="last_name" class="form-control required_content" id="last_name"
                                value="" placeholder="{{__('fields.enter_last_name')}}  ">
                        </div>
                    </div>
                     
                    <div class="col-lg-6 col-md-12 form-group   ">
                        <label for="phone" class="form-label">{{__('fields.phone_number')}} </label>
                        <div class="icon-input">
                            <input type="text" name="phone" class="form-control phone_field" id="phone"
                                placeholder="{{__('fields.phone_number')}}" value="">
                        </div>
                        {{-- <div class="invalid-feedback">Please provide a valid date.
                        </div> --}}
                    </div>

                    <div class="col-lg-6 col-md-12  ">
                        <label for="country" class="form-label">{{__('fields.country')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control  required_content select_class" id="country" name="country"
                                    data-name="country" style="width: 100%;">
                                    <option value="">{{__('fields.select_country')}}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12  ">
                        <label for="state" class="form-label">{{__('fields.state')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control  required_content select_class" id="state" name="state"
                                    data-name="state" style="width: 100%;">
                                    <option value="">{{__('fields.select_state')}}</option>


                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 form-group   ">
                        <label for="postal_code" class="form-label">{{__('fields.postal_code')}} </label>
                        <div class="icon-input">
                            <input type="text" name="postal_code" class="form-control  " id="postal_code"
                                placeholder="{{__('fields.enter_postal_code')}}" value="">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 form-group  mt-2">
                        <label for="address" class="form-label">{{__('fields.address')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="address" class="form-control required_content" id="address"
                                placeholder="{{__('fields.enter_address')}}" value="">
                        </div>
                    </div>
                   
                    <div class="col-lg-6 col-md-12     ">
                        <label for="branch" class="form-label">{{__('fields.branch')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required_content select_class" id="branch" name="branch"
                                    data-name="branch" style="width: 100%;">
                                    <option value="">{{__('fields.select_branch')}}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->branch }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-2 ">
                        <label for="branch" class="form-label">{{__('fields.document_type')}} *</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control required_content select_class" id="document_type"
                                    name="document_type" data-name="branch" style="width: 100%;">
                                    <option value="">{{__('fields.select_document_type')}}</option>
                                    @foreach ($document_types as $document)
                                        <option value="{{ $document->id }}">
                                            {{ $document->document_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 form-group  mt-2">
                        <label for="document_number" class="form-label">{{__('fields.document_number')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="document_number" class="form-control required_content"
                                id="document_number" value="" placeholder="{{__('fields.enter_document_number')}} ">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 mt-2">
                        <label for="client_type" class="form-label">{{ __('fields.person_or_company') }}</label>
                        <div class="icon-input">
                            <div class="form-s2">
                                <select class="form-control   select_class " id="client_type"
                                    name="client_type" data-name="client_type" style="width: 100%;" required>
                                    <option value="">{{ __('fields.select_person_or_company') }}</option>
                                    <option value="person">Person</option>
                                    <option value="company">Company</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 form-group mt-3 company_name_div" style="display:none">
                                <label for="company_name" class="form-label">{{ __('fields.company_name') }}</label>
                                <input id="company_name" type="company_name"
                                    class="form-control "
                                    name="company_name" placeholder="{{ __('fields.enter_company_name') }}"  >
                                 
                            </div> 
                  
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto pr-0">

                            </div>
                            <div class="col mt-auto mb-auto pl-0 pr-0">
                                <h2>{{__('fields.user_credientials')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12  mt-2">
                        <label for="email" class="form-label">{{__('fields.email')}} *</label>
                        <div class="icon-input">
                            <input type="text" name="email" class="form-control required_content" id="email" value=""
                                placeholder="{{__('fields.email')}}  ">
                        </div>

                    </div>
                    <div class="col-lg-12 mt-2">
                        <label for="password" class="form-label password_label">{{__('fields.password')}} *</label>
                        <div class="icon-input">
                            <input type="password" name="password" class="form-control required_content" id="password"
                                value="" placeholder="{{__('fields.enter_password')}}  ">
                        </div>

                    </div>


                </div>
            </div>

        </div>
        <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary me-2" id="saveBtn">{{__('fields.save')}}</button>
            <button type="button" class="btn btn btn-light close_sidebar"
                data-bs-dismiss="offcanvas">{{__('fields.close')}}</button>
        </div>
    </div>
</form>
@endsection
@section('content')

<div class="row">
    <div class="col col-xsm-w pr-0">
        <div class="search">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M15 15L11.8525 11.8525M13.5 7.875C13.5 4.7684 10.9816 2.25 7.875 2.25C4.7684 2.25 2.25 4.7684 2.25 7.875C2.25 10.9816 4.7684 13.5 7.875 13.5C10.9816 13.5 13.5 10.9816 13.5 7.875Z"
                    stroke="#4d815c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input class="form-control" id="customSearchInput" type="text"
                placeholder="{{__('fields.search_clients_here')}}">
        </div>
    </div>
    <div class="col-auto col-xsm-w">
        <button class="btn btn-primary openDataSidebarForAddingClient mt-0 ">+ {{__('fields.new_client')}}</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    <div class="col mt-auto mb-auto pl-0 pr-0">
                        <h2>{{__('fields.clients_list')}}</h2>
                    </div>
                </div>
            </div>
            <div style="min-height: 400px" id="tblLoader">
                <img src="/images/loader.gif" width="30px" height="auto"
                    style="position: absolute; left: 50%; top: 45%;">
            </div>
            <div class="card-body clients_list p-0" style="display: none">
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('.content_head').text('{{__('fields.client_management')}}')  
    </script>


    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

    <script>
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
        var states = @json($states);
        $(document).on('change', '#country', function () {
            var selectedCountry = $(this).val();
            $('#state').empty();
            if ($(this).val()) {
                var filteredStates = states.filter(x => x.country_id == $(this).val());
                if (filteredStates) {
                    $('#state').append(`<option value="">{{__('fields.select_state')}}</option>`)
                    filteredStates.forEach(element => {
                        $('#state').append(`<option value="${element.id}">${element.name}</option>`)
                    });

                }
            }
            
        });
    </script>
    <script src="/js/custom/clients.js?v=1.5.0.1"></script>

    <!-- Buttons Extension JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

@endpush