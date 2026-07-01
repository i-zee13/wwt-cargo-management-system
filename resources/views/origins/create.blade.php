@extends('layouts.master')
@section('content')
 
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
                        <h2>{{__('fields.origin_details')}}</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">  
                        <input type="hidden" id="employee_city" name="employee_city" value="{{ @$origin->city_id }}">
                        <input type="hidden" id="employee_state" name="employee_state" value="{{ @$origin->state_id }}">
                        <input type="hidden" id="shipper_city_id" name="shipper_city_id" value="{{ @$origin->shipperDetails->shipper_city_id }}">
                        <input type="hidden" id="shipper_state_id" name="shipper_state_id" value="{{ @$origin->shipperDetails->shipper_state_id }}">
                        <input type="hidden" id="consignee_city_id" name="consignee_city_id" value="{{ @$origin->consigneeDetails->consignee_city_id }}">
                        <input type="hidden" id="consignee_state_id" name="consignee_state_id" value="{{ @$origin->consigneeDetails->consignee_state_id }}">
                        <input type="hidden" id="carrier_state_id" name="carrier_state_id" value="{{ @$origin->CarrierDetails->carrier_state_id }}">
                        <input type="hidden" id="carrier_city_id" name="carrier_city_id" value="{{ @$origin->CarrierDetails->carrier_city_id }}">
                         
                        <form style="display: flex; width: 100%" autocomplete="off" id="saveOriginForm" enctype="multipart/form-data">
                            @csrf
                            <input type="text" id="operation" class="operation" hidden>
                            <input type="hidden" id="origin_id" name="origin_id"
                                value="{{ @$origin->id }}">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="name" class="form-label">{{__('fields.origin_name')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="name" class="form-control required" id="name"
                                            data-name="Full Name" placeholder="{{__('fields.enter_origin_name')}}"
                                            value="{{ @$origin->origin_name }}"> 
                                    </div>

                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="order_no" class="form-label">{{__('fields.order')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="order_no" class="form-control onlyNumbers required" id="order_no"
                                            data-name="Full Order" placeholder="{{__('fields.enter_order_number')}}"
                                            value="{{ @$origin->order_no }}"> 
                                    </div>

                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="phone" class="form-label">{{__('fields.phone_number')}}</label>
                                    <div class="icon-input">
                                        <input type="text" name="phone" class="form-control phone_field" id="phone"
                                            placeholder="{{__('fields.enter_phone_number')}}" value="{{ @$origin->phone }}">
                                    </div> 
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="email" class="form-label">{{__('fields.email_id')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="email" class="form-control required" id="email"
                                            placeholder="{{__('fields.enter_email_id')}}" value="{{ @$origin->email }}">

                                    </div> 
                                </div> 
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="country" class="form-label">{{__('fields.country')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_countries required select_class"
                                                id="country" name="country" data-name="Country" style="width: 100%;">
                                                <option value="0">{{__('fields.select_country')}}</option>
                                                @foreach ($countries as $row)
                                                <option value="{{ $row->id }}" iso-code="{{$row->iso}}" @if (@$origin->country_id &&
                                                    @$origin->country_id == $row->id) selected @endif>
                                                    {{ $row->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="state" class="form-label">{{__('fields.state')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_states required select_class" id="state"
                                                name="state" data-name="State" style="width: 100%;">
                                                <option value="0">{{__('fields.select_state')}}</option>

                                            </select>
                                        </div>
                                         
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="city" class="form-label">{{__('fields.city')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control emp_cities required select_class" id="city"
                                                name="city" data-name="City" style="width: 100%;">
                                                <option value="0">{{__('fields.select_city')}}</option>

                                            </select>
                                        </div>
                                       
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="iso" class="form-label">{{__('fields.iso')}}</label>
                                    <input type="text" class="form-control" id="iso" name="iso"
                                        placeholder="{{__('fields.enter_iso_code')}} " value="{{ @$origin->iso }}" readonly>
                               
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="zip_code" class="form-label">{{__('fields.zip_code')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="zip_code" class="form-control " id="zip_code"
                                            placeholder="{{__('fields.enter_zip_code')}}" value="{{ @$origin->zip_code }}">

                                    </div>
                                     
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="address" class="form-label">{{__('fields.address_1')}}*</label>
                                    <input type="text" class="form-control required" id="address" name="address"
                                        placeholder="{{__('fields.enter_address_1')}}" value="{{ @$origin->address }}">
                                  
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="address" class="form-label">{{__('fields.address_2')}}</label>
                                    <input type="text" class="form-control" id="address_2" name="address_2"
                                        placeholder="{{__('fields.enter_address_2')}}" value="{{ @$origin->address_2 }}">
                                     
                                </div>
                               
                                
                               
                                <div class="card-header mb-3">
                                    <h2>{{__('fields.shipper_details')}}</h2>
                                </div>
                                  
                                <div class="col-md-6 form-group">
                                    <label for="shipper_name" class="form-label">{{__('fields.shipper_name')}}</label>
                                    <input type="text" class="form-control" id="shipper_name" name="shipper_name"
                                        placeholder="{{__('fields.enter_shipper_name')}}" value="{{ @$origin->shipperDetails->shipper_name }}">
                                     
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="shipper_address" class="form-label">{{__('fields.shipper_address')}}</label>
                                    <input type="text" class="form-control" id="shipper_address" name="shipper_address"
                                        placeholder="{{__('fields.enter_shipper_address')}}" value="{{ @$origin->shipperDetails->shipper_address }}">
                                     
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="shipper_country" class="form-label">{{__('fields.shipper_country')}} </label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control shipper_countries   select_class"
                                                id="shipper_country" name="shipper_country" data-name="Country" style="width: 100%;">
                                                <option value="0">{{__('fields.select_country')}}</option>
                                                @foreach ($countries as $row)
                                                <option value="{{ $row->id }}" iso-code="{{$row->iso}}" @if (@$origin->shipperDetails->shipper_country_id &&
                                                    @$origin->shipperDetails->shipper_country_id == $row->id) selected @endif>
                                                    {{ $row->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="shipper_state" class="form-label">{{__('fields.shipper_state')}} </label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control shipper_states   select_class" id="shipper_state"
                                                name="shipper_state" data-name="State" style="width: 100%;">
                                                <option value="0">{{__('fields.select_state')}}</option>

                                            </select>
                                        </div>
                                         
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="shipper_city" class="form-label">{{__('fields.shipper_city')}} </label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control shipper_cities   select_class" id="shipper_city"
                                                name="shipper_city" data-name="City" style="width: 100%;">
                                                <option value="0">{{__('fields.select_city')}}</option>

                                            </select>
                                        </div>
                                       
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="card-header mb-3">
                                    <h2>{{__('fields.carrier_details')}}</h2>
                                </div>
                                  
                                <div class="col-md-4 form-group">
                                    <label for="carrier_name" class="form-label">{{__('fields.carrier_name')}}</label>
                                    <input type="text" class="form-control" id="carrier_name" name="carrier_name"
                                        placeholder="{{__('fields.enter_carrier_name')}}" value="{{ @$origin->CarrierDetails->carrier_name }}">
                                     
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="carrier_ruc" class="form-label">{{__('fields.carrier_ruc')}}</label>
                                    <input type="text" class="form-control" id="carrier_ruc" name="carrier_ruc"
                                        placeholder="{{__('fields.enter_carrier_ruc')}}" value="{{ @$origin->CarrierDetails->carrier_ruc }}">
                                     
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="carrier_address" class="form-label">{{__('fields.carrier_address')}}</label>
                                    <input type="text" class="form-control" id="carrier_address" name="carrier_address"
                                        placeholder="{{__('fields.enter_shipper_name')}}" value="{{ @$origin->CarrierDetails->carrier_address }}">
                                     
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="carrier_country" class="form-label">{{__('fields.carrier_country')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control carrier_countries   select_class"
                                                id="carrier_country" name="carrier_country" data-name="Country" style="width: 100%;">
                                                <option value="0">{{__('fields.select_country')}}</option>
                                                @foreach ($countries as $row)
                                                <option value="{{ $row->id }}" iso-code="{{$row->iso}}" @if (@$origin->CarrierDetails->carrier_country_id &&
                                                    @$origin->CarrierDetails->carrier_country_id == $row->id) selected @endif>
                                                    {{ $row->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="carrier_state" class="form-label">{{__('fields.carrier_state')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control carrier_states select_class" id="carrier_state"
                                                name="carrier_state" data-name="State" style="width: 100%;">
                                                <option value="0">{{__('fields.select_state')}}</option>

                                            </select>
                                        </div>
                                         
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="carrier_city" class="form-label">{{__('fields.carrier_city')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control carrier_cities select_class" id="carrier_city"
                                                name="carrier_city" data-name="City" style="width: 100%;">
                                                <option value="0">{{__('fields.city')}}</option>

                                            </select>
                                        </div>
                                       
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="card-header mb-3">
                                    <h2>{{__('fields.consignee_details')}}</h2>
                                </div>
                                  
                                <div class="col-md-4 form-group">
                                    <label for="consignee_name" class="form-label">{{__('fields.consignee_name')}}</label>
                                    <input type="text" class="form-control" id="consignee_name" name="consignee_name"
                                        placeholder="{{__('fields.enter_consignee_name')}}" value="{{ @$origin->consigneeDetails->consignee_name }}">
                                     
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="consignee_ruc" class="form-label">{{__('fields.consignee_ruc')}}</label>
                                    <input type="text" class="form-control" id="consignee_ruc" name="consignee_ruc"
                                        placeholder="{{__('fields.enter_consignee_ruc')}}" value="{{  @$origin->consigneeDetails->consignee_ruc }}">
                                     
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="consignee_address" class="form-label">{{__('fields.consignee_address')}}</label>
                                    <input type="text" class="form-control" id="consignee_address" name="consignee_address"
                                        placeholder="{{__('fields.enter_consignee_address')}}" value="{{  @$origin->consigneeDetails->consignee_address }}">
                                     
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="consignee_country" class="form-label">{{__('fields.consignee_country')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control consignee_countries select_class"
                                                id="consignee_country" name="consignee_country" data-name="Country" style="width: 100%;">
                                                <option value="0">{{__('fields.country')}}</option>
                                                @foreach ($countries as $row)
                                                <option value="{{ $row->id }}" iso-code="{{$row->iso}}" @if ( @$origin->consigneeDetails->consignee_country_id &&
                                                    @$origin->consigneeDetails->consignee_country_id== $row->id) selected @endif>
                                                    {{ $row->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="consignee_state" class="form-label">{{__('fields.consignee_state')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control consignee_states select_class" id="consignee_state"
                                                name="consignee_state" data-name="State" style="width: 100%;">
                                                <option value="0">{{__('fields.state')}}</option>

                                            </select>
                                        </div>
                                         
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="consignee_city" class="form-label">{{__('fields.consignee_city')}}</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control consignee_cities select_class" id="consignee_city"
                                                name="consignee_city" data-name="City" style="width: 100%;">
                                                <option value="0">{{__('fields.city')}}</option>

                                            </select>
                                        </div>
                                       
                                    </div>
                                    {{-- <div class="invalid-feedback">Please select a Company.</div> --}}
                                </div>


                            </div>

                        </form>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" id="saveOrigin" title="Save">{{__('fields.save')}}</button>
                        <a href="/admin/origins" class="btn btn-light me-2 actionBtns" id="btn-cancel"
                            title="Cancel">{{__('fields.cancel')}}</a>
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
        $('.content_head').text('{{__('fields.origin_management')}}') 
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

    
        var states = [];
        var cities = [];
        var emp_city = 0;
        var emp_state = 0;
        var carrier_state = 0;
        var carrier_city = 0;
        var consignee_city = 0;
        var consignee_state = 0;
        var shipper_state = 0;
        var shipper_city = 0;

        $.get("/admin/get-states-cities", function(response) {
            states = response.states;
            cities = response.cities;
            $('.emp_countries').trigger('change');
            $('.carrier_countries').trigger('change');
            $('.shipper_countries').trigger('change');
            $('.consignee_countries').trigger('change');
        });

        $(document).ready(function() {
            emp_city = $('#employee_city').val();
            emp_state = $('#employee_state').val();
            carrier_city = $('#carrier_city_id').val();
            carrier_state = $('#carrier_state_id').val();
            shipper_city = $('#shipper_city_id').val();
            shipper_state = $('#shipper_state_id').val();
            consignee_state = $('#consignee_state_id').val();
            consignee_city= $('#consignee_city_id').val();
        });

        $(document).on('change', '.emp_countries', function() {
            $('.emp_states').html(`<option  value="0">{{__('fields.select_state')}}</option>`);
            $('.emp_cities').html(`<option  value="0">{{__('fields.select_city')}}</option>`); 
            $('#iso').val('');
            var country_id = $(this).val(); 
            if (country_id > 0) { 
                const iso_code =  $('.emp_countries option:selected').attr('iso-code')
                $('#iso').val(iso_code);
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
            $('.emp_cities').html(`<option   value="0">{{__('fields.select_city')}}</option>`);

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
        $(document).on('change', '.shipper_countries', function() {
            
            $('.shipper_states').html(`<option  value="0">{{__('fields.select_state')}}</option>`);
            $('.shipper_cities').html(`<option  value="0">{{__('fields.select_city')}}</option>`);  
            var country_id = $(this).val(); 
            if (country_id > 0) {   
                var country_states = states.filter(x => x.country_id == country_id);
                console.log(country_states,shipper_state);
                if (country_states.length > 0) {
                    country_states.forEach(element => {
                        var isSelected = shipper_state && shipper_state == element['id'] ? 'selected' : '';
                        $('.shipper_states').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                } 
                if (shipper_state) {
                    $('.shipper_states').val(shipper_state).trigger('change');
                }
            }
        });

        $(document).on('change', '.shipper_states', function() {

            $('.shipper_cities').html(`<option   value="0">{{__('fields.select_city')}}</option>`); 
            var state_id = $(this).val(); 
            if (state_id > 0) { 
                var state_cities = cities.filter(x => x.state_id && x.state_id == state_id);

                if (state_cities.length > 0) {
                    state_cities.forEach(element => {
                        var isSelected = shipper_city && shipper_city == element['id'] ? 'selected' : '';
                        $('.shipper_cities').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                }
            }
        });
        $(document).on('change', '.consignee_countries', function() {
            $('.consignee_states').html(`<option  value="0">{{__('fields.select_state')}}</option>`);
            $('.consignee_cities').html(`<option  value="0">{{__('fields.select_city')}}</option>`); 
            
            var country_id = $(this).val(); 
            if (country_id > 0) {  
                var country_states = states.filter(x => x.country_id == country_id);

                if (country_states.length > 0) {
                    country_states.forEach(element => {
                        var isSelected = consignee_state && consignee_state == element['id'] ? 'selected' : '';
                        $('.consignee_states').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                } 
                if (consignee_state) {
                    $('.consignee_states').val(consignee_state).trigger('change');
                }
            }
        });

        $(document).on('change', '.consignee_states', function() {
            $('.consignee_cities').html(`<option   value="0">{{__('fields.select_city')}}</option>`);

            var state_id = $(this).val();

            if (state_id > 0) {

                var state_cities = cities.filter(x => x.state_id && x.state_id == state_id);

                if (state_cities.length > 0) {
                    state_cities.forEach(element => {
                        var isSelected = consignee_city && consignee_city == element['id'] ? 'selected' : '';
                        $('.consignee_cities').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                }
            }
        });
        $(document).on('change', '.carrier_countries', function() {
            $('.carrier_states').html(`<option  value="0">{{__('fields.select_state')}}</option>`);
            $('.carrier_cities').html(`<option  value="0">{{__('fields.select_city')}}</option>`); 
           
            var country_id = $(this).val(); 
            if (country_id > 0) {  
                var country_states = states.filter(x => x.country_id == country_id); 
                if (country_states.length > 0) {
                    country_states.forEach(element => {
                        var isSelected = carrier_state && carrier_state == element['id'] ? 'selected' : '';
                        $('.carrier_states').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                } 
                if (carrier_state) {
                    $('.carrier_states').val(carrier_state).trigger('change');
                }
            }
        });

        $(document).on('change', '.carrier_states', function() {
            $('.carrier_cities').html(`<option   value="0">{{__('fields.select_city')}}</option>`);

            var state_id = $(this).val();

            if (state_id > 0) {

                var state_cities = cities.filter(x => x.state_id && x.state_id == state_id);

                if (state_cities.length > 0) {
                    state_cities.forEach(element => {
                        var isSelected = carrier_city && carrier_city == element['id'] ? 'selected' : '';
                        $('.carrier_cities').append(
                            `<option value="${element.id}" ${isSelected}>${element.name}</option>`
                        );
                    });
                }
            }
        });
</script>
<script src="/js/custom/origins.js"></script>
@endpush