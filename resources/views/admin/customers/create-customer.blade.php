@extends('layouts.master')
@section('content')
<style>
    .btn-outline-primary {
        color: black;
        border: 1px solid var(--bs-primary);
    }

    .iti--inline-dropdown {
        width: 100%;
    }
</style>
<?php $countries = getAllCountry(); ?>
<div class="row">
    <div class="col-12">
        <div class="card mt-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    <div class="col mt-auto mb-auto pl-0 pr-0">
                        <h2>Customer Details</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" id="customer_city" name="customer_city" value="{{ @$customer->city_id }}">
                        <input type="hidden" id="customer_state" name="customer_state"
                            value="{{ @$customer->state_id }}">
                        <form style="display: flex; width: 100%" autocomplete="off" id="saveCustomerForm" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            <input type="text" id="operation" class="operation" hidden>
                            <input type="hidden" id="customer_updating_id" name="customer_updating_id"
                                value="{{ @$customer->id }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="name" class="form-label">First Name *</label>
                                    <div class="icon-input">
                                        <input type="text" name="first_name" class="form-control required-customer avoidSpecialChars"
                                            id="name" data-name="Full Name" placeholder="Enter Customer First Name"
                                            value="{{ @$customer->first_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="name" class="form-label">Last Name *</label>
                                    <div class="icon-input">
                                        <input type="text" name="last_name" class="form-control required-customer avoidSpecialChars"
                                            id="name" data-name="Last Name" placeholder="Enter Customer Last Name"
                                            value="{{ @$customer->last_name }}"> 
                                    </div> 
                                </div> 
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="icon-input">
                                        <input type="text" name="phone" class="form-control  w-100 phone_field"
                                            id="phone" placeholder="Enter Customer Phone Number"
                                            value="{{ @$customer->phone_number }}"> 
                                    </div>
                                </div> 
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="country" class="form-label">Country *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_countries required-customer select_class"
                                                id="country" name="country" data-name="Country" style="width: 100%;">
                                                <option value="0">Select Country</option>
                                                @foreach ($countries as $row)
                                                <option value="{{ $row->id }}" @if (@$customer->country_id &&
                                                    @$customer->country_id == $row->id) selected @endif>
                                                    {{ $row->name }}
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
                                    <label for="state" class="form-label">State *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_states required-customer select_class"
                                                id="state" name="state" data-name="State" style="width: 100%;">
                                                <option value="0">Select State</option>

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
                                    <label for="city" class="form-label">City *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_cities required-customer select_class"
                                                id="city" name="city" data-name="City" style="width: 100%;">
                                                <option value="0">Select City</option>

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
                                {{-- <div class="col-lg-6 col-md-12 form-group">
                                    <label for="datepicker" class="form-label">Date of birth </label>
                                    <div class="icon-input">
                                        <input type="text" name="dob" class=" datepicker form-control" id="datepicker"
                                            placeholder="Enter Date of birth" value="{{ @$customer->dob }}">
                                    </div>
                                </div> --}}
                                <div class="col-md-12 form-group">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        autocomplete="off" placeholder="Enter Customer Address"
                                        value="{{ @$customer->address }}">
                                </div>
                                <div class="card-header mb-3">
                                    <h2>Create Customer</h2>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 form-group">
                                            <label for="email" class="form-label">Email ID *</label>
                                            <div class="icon-input">
                                                <input type="text" name="email" class="form-control required-customer"
                                                    id="email" placeholder="Enter Customer Email ID"
                                                    value="{{ @$customer->email }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 form-group  password-div">
                                            <label for="password" class="form-label passwordLabel">Password*</label>
                                            <div class="icon-input">
                                                <input type="password" name="password"
                                                    class="form-control required-customer password-input" id="password"
                                                    placeholder="Enter Customer Password" data-name="Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 form-group">
                                            <label for="customerPictureLabel" class="form-label">Customer
                                                Picture</label>
                                            <div class="upload-pic"></div>
                                            <input type="file" class="dropify " id="customerPicture"
                                                name="customerPicture" accept="image/*"
                                                data-default-file="{{ isset($customer->customer_picture) && $customer->customer_picture != '' ? asset($customer->customer_picture) : '' }}"
                                                data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </form>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" id="savecustomer"
                            title="{{@$customer->id?'Update':'Save'}}">{{@$customer->id?'Update':'Save'}}</button>
                        <a href="{{route('customers-list')}}" class="btn btn-light me-2">Cancel</a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection
@push('js') 
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script> 
<script>
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
    $('.content_head').text('Customer Management') 
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
            emp_city = $('#customer_city').val();
            emp_state = $('#customer_state').val();
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
<script src="{{ asset('js/custom/customers.js') }}"></script>

@endpush