@extends('layouts.master')
<style>
    .border-l-0 {
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
    }

    .border-r-0 {
        border-top-right-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
    }

    .form-s2 .select2-container .select2-selection--single {
        border-top-right-radius: 0px !important;
        border-right: none;
        border-bottom-right-radius: 0px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b,
    .fs-arrow {
        display: block !important;
    }

    .iti--inline-dropdown {
        width: 100%;
    }
</style>
@section('content')
<form enctype="multipart/form-data" id="organizationForm" style="margin-bottom:30px" autocomplete="off">
    <div class="row">

        <div class="col-9">
            <div class="card mt-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-auto pr-0">

                        </div>
                        <div class="col mt-auto mb-auto pl-0 pr-0"> 
                            <h2>{{ __('fields.business_details') }}</h2> 
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div style="min-height: 400px" id="tblLoader">
                                <img src="/images/loader.gif" width="30px" height="auto"
                                    style="position: absolute; left: 50%; top: 45%;">
                            </div> 
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="name" class="form-label">{{ __('fields.business_legal_name') }}*</label>
                                    <div class="icon-input">
                                        <input type="text" name="name" class="form-control required avoidSpecialChars"
                                            id="name" data-name="Full Name" placeholder="{{ __('fields.enter_business_legal_name') }}"
                                            value="{{ @$record->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="phone" class="form-label">{{ __('fields.phone_number') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="phone" class="form-control phone_field " id="phone"
                                            data-name="Phone Number" placeholder="{{ __('fields.enter_phone_number') }}"
                                            value="{{ @$record->phone }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="email" class="form-label">{{ __('fields.email') }}*</label>
                                    <div class="icon-input">
                                        <input type="text" name="email" class="form-control required" id="email"
                                            data-name="Email" placeholder="{{ __('fields.enter_email') }}" value="{{ @$record->email }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="registration_number" class="form-label">{{ __('fields.business_reg_no') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="registration_number" class="form-control  "
                                            id="registration_number" data-name="registration_number"
                                            placeholder="{{ __('fields.enter_business_reg_no') }}"
                                            value="{{ @$record->registration_number }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="vat_number" class="form-label">{{ __('fields.vat_number') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="vat_number" class="form-control  " id="vat_number"
                                            data-name="vat_number" placeholder="{{ __('fields.enter_vat_number') }}"
                                            value="{{ @$record->vat_number }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="postal_code" class="form-label">{{ __('fields.postal_code') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="postal_code" class="form-control  " id="postal_code"
                                            data-name="postal_code" placeholder="{{ __('fields.enter_postal_code') }}"
                                            value="{{ @$record->postal_code }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="address" class="form-label">{{ __('fields.address') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="address" class="form-control  " id="address"
                                            data-name="address" placeholder="{{ __('fields.enter_address') }}"
                                            value="{{ @$record->address }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="instagram_link" class="form-label">{{ __('fields.instagram_link') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="instagram_link"
                                            class="form-control  url-link-organization" id="instagram_link"
                                            data-name="instagram_link" placeholder="{{ __('fields.enter_instagram_link') }}"
                                            value="{{ @$record->instagram_link }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="linkedin_link" class="form-label">{{ __('fields.linkedin_link') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="linkedin_link"
                                            class="form-control url-link-organization " id="linkedin_link"
                                            data-name="linkedin_link" placeholder="{{ __('fields.enter_linkedin_link') }}"
                                            value="{{ @$record->linkedin_link }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="facebook_link" class="form-label">{{ __('fields.facebook_link') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="facebook_link"
                                            class="form-control url-link-organization " id="facebook_link"
                                            data-name="facebook_link" placeholder="{{ __('fields.enter_facebook_link') }}"
                                            value="{{ @$record->facebook_link }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="youtube_link" class="form-label">{{ __('fields.youtube_link') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="youtube_link"
                                            class="form-control  url-link-organization" id="youtube_link"
                                            data-name="youtube_link" placeholder="{{ __('fields.enter_youtube_link') }}"
                                            value="{{ @$record->youtube_link }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="twitter_link" class="form-label">{{ __('fields.twitter_link') }}</label>
                                    <div class="icon-input">
                                        <input type="text" name="twitter_link" class="form-control  " id="twitter_link"
                                            data-name="twitter_link" placeholder="{{ __('fields.enter_twitter_link') }}"
                                            value="{{ @$record->twitter_link }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-header mb-3">
                                    <div class="row">
                                        <div class="col mt-auto mb-auto">
                                            <h2 class="heading02">{{ __('fields.business_phone_numbers') }}</h2>
                                        </div>
                                        <div class="col-auto  ">
                                            <button class="btn btn-add-guest add_phone_no " type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-circle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                                </svg> {{ __('fields.add_new') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row phone_div">

                            </div>



                            
                            <div class="col-lg-12 col-md-12 text-end">
                                <button type="button" class="btn btn-primary" id="submit-button">{{ __('fields.save') }}</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card mt-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 mt-auto mb-auto">
                            <h2>{{ __('fields.business_logo') }}*</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="upload-pic"></div>
                            <input type="file" class="dropify organization_logo" accept="image/*"
                                data-allowed-file-extensions="jpg png jpeg tif tiff svg pjp webp xbm jxl jfif bmp avif ico gif"
                                data-default-file="{{ @$record->logo }}" value="{{ @$record->logo }}"
                                name="organization_logo" id="input-file-now" />
                            <input type="hidden" value="{{ @$record->logo }}" name="organization_logo_hidden"
                                id="organization_logo_hidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form>
        @endsection

        @push('js')
        <script>
            $('.content_head').text('{{ __('fields.business_details') }}')
        </script>
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>
        <script>

        </script>
        <script src="/js/custom/organization.js"></script>
        @endpush