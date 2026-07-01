@extends('layouts.master')
@section('content')
    <?php $countries = getAllCountry(); ?>
    <style>
        .iti--inline-dropdown {
            width: 100%;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card mt-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-auto pr-0">

                        </div>
                        <div class="col mt-auto mb-auto pl-0 pr-0">
                            <h2>{{__('fields.employee_management')}}</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" id="employee_city" name="employee_city" value="{{ @$employee->city }}">
                            <input type="hidden" id="employee_state" name="employee_state" value="{{ @$employee->state }}">
                            <form style="display: flex; width: 100%" autocomplete="off" id="saveEmployeeForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="text" id="operation" class="operation" hidden>
                                <input type="hidden" id="employee_updating_id" name="employee_updating_id"
                                    value="{{ @$employee->id }}">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="name" class="form-label">{{__('fields.first_name')}} *</label>
                                        <div class="icon-input">
                                            <input type="text" name="first_name" class="form-control required"
                                                id="name" data-name="Full Name" placeholder="{{__('fields.enter_first_name')}}"
                                                value="{{ @$employee->first_name }}">


                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="name" class="form-label">{{__('fields.last_name')}} *</label>
                                        <div class="icon-input">
                                            <input type="text" name="last_name" class="form-control required"
                                                id="name" data-name="Last Name" placeholder="{{__('fields.enter_last_name')}}"
                                                value="{{ @$employee->last_name }}">


                                        </div>

                                    </div>
                                    <div class="card-header mb-3">
                                        <h2>{{__('fields.create_employee')}}</h2>
                                    </div>
                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="email" class="form-label">{{__('fields.email')}} *</label>
                                        <div class="icon-input">
                                            <input type="text" name="email" class="form-control required"
                                                id="email" placeholder="{{__('fields.email')}}"
                                                value="{{ @$employee->email }}">

                                        </div>
                                        {{-- <div class="invalid-feedback">Please provide a valid date.
                                    </div> --}}
                                    </div>

                                    <div class="col-lg-6 col-md-12 form-group  password-div">
                                        <label for="password" class="form-label passwordLabel">{{__('fields.password')}}*</label>
                                        <div class="icon-input">
                                            <input type="text" name="password"
                                                class="form-control required password-input" id="password"
                                                placeholder="{{__('fields.enter_password')}}" data-name="Password">
                                        </div>

                                    </div>

                                    <div class="col-lg-12 col-md-12 form-group">
                                        <label for="employeePictureLabel" class="form-label">{{__('fields.employee_picture')}}</label>
                                        <div class="upload-pic"></div>
                                        <input type="file" class="dropify " id="employeePicture" name="employeePicture"
                                            accept="image/*"
                                            data-default-file="{{ isset($employee->picture) && $employee->picture != '' ? asset($employee->picture) : '' }}"
                                            data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                                    </div>
                                    <div class="card-header mb-3">
                                        <h2>{{__('fields.additional_details')}}</h2>
                                    </div>

                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="designation" class="form-label">{{__('fields.role')}} *</label>
                                        <div class="icon-input">
                                            <div class="form-s2">
                                                <select class="form-control required select_class" id="designation"
                                                    name="designation" style="width: 100%;">
                                                    <option selected value="0">{{__('fields.select_role')}}</option>
                                                    @foreach ($designations as $des)
                                                        <option value="{{ $des->id }}"
                                                            @if (@$employee->designation && @$employee->designation == $des->id) selected @endif>
                                                            {{ $des->designation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                    </div>

                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="branch" class="form-label">{{__('fields.branch')}}*</label>
                                        <div class="icon-input">
                                            <div class="form-s2">
                                                <select class="form-control required select_class" id="branch"
                                                    name="branch" style="width: 100%;">
                                                    <option selected value="0">{{__('fields.select_branch')}}</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            @if (@$employee->branch_id && @$employee->branch_id == $branch->id) selected @endif>
                                                            {{ $branch->branch }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 form-group">
                                        <label for="department" class="form-label">{{__('fields.language')}}*</label>
                                        <div class="icon-input">
                                            <div class="form-s2">
                                                <select class="form-control required select_class" id="language"
                                                    name="language" style="width: 100%;">
                                                    <option selected value="0">{{__('fields.select_language')}}</option>
                                                    @foreach ($languages as $lang)
                                                        <option value="{{ $lang->language_id }}"
                                                            @if (@$employee->language_id && @$employee->language_id == $lang->language_id) selected @endif>
                                                            {{ $lang->language_title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z"
                                                    stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>



                                </div>

                            </form>
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary" id="saveEmployee" title="Save">{{__('fields.save')}}</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

    <script>  $('.content_head').text('{{__('fields.employee_management')}}')
        const inputField = document.querySelector(".phone_field");
        const iti = window.intlTelInput(inputField, {
            initialCountry: "us",
            nationalMode: false,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js"
        });

        inputField.addEventListener('keypress', function(e) {
            if (!/[\d\s+\-()]/.test(e.key)) {
                e.preventDefault();
            }
        });
        inputField.addEventListener('input', function() {
            if (inputField.value.length === 0) {
                iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
            }
        });
        if (inputField.value.length === 0) {
            iti.setNumber('+' + iti.getSelectedCountryData().dialCode);
        }
        $('.content_head').text('Employee Management')
        var states = [];
        var cities = [];
        var emp_city = 0;
        var emp_state = 0;

        $.get("/get-states-cities", function(response) {
            states = response.states;
            cities = response.cities;
            $('.emp_countries').trigger('change');
        });

        $(document).ready(function() {
            emp_city = $('#employee_city').val();
            emp_state = $('#employee_state').val();
        });

        $(document).on('change', '.emp_countries', function() {
            $('.emp_states').html(`<option  value="0">Select State</option>`);
            $('.emp_cities').html(`<option  value="0">Select City</option>`);

            var country_id = $(this).val();

            if (country_id > 0) {
                // Filter states based on the selected country
                var country_states = states.filter(x => x.country_id == country_id);

                if (country_states.length > 0) {
                    country_states.forEach(element => {
                        var isSelected = emp_state && emp_state == element['id'] ? 'selected' : '';
                        $('.emp_states').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                }


                if (emp_state) {
                    $('.emp_states').val(emp_state).trigger('change');
                }
            }
        });

        $(document).on('change', '.emp_states', function() {
            $('.emp_cities').html(`<option   value="0">Select City</option>`);

            var state_id = $(this).val();

            if (state_id > 0) {

                var state_cities = cities.filter(x => x.state_id && x.state_id == state_id);

                if (state_cities.length > 0) {
                    state_cities.forEach(element => {
                        var isSelected = emp_city && emp_city == element['id'] ? 'selected' : '';
                        $('.emp_cities').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                }
            }
        });
    </script>
@endpush
