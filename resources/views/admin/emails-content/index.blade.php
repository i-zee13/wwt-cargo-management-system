@extends('layouts.master')

@section('content') 
<div class="row">
    <div class="col-12">
        <div class="card mt-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    <div class="col mt-auto mb-auto pl-0 pr-0"> 
                        <h2>{{ __('fields.' . \Illuminate\Support\Str::of($formTitle)->lower()->replace(' ', '_')) }}</h2>

                    </div>
                </div>
            </div>
            <div class="card-body"> 
                <div class="row">
                    <div class="col-lg-12">
                     


                        <form style=" width: 100%" autocomplete="off" id="saveEmailForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                            <input type="hidden" id="pageType" name="pageType" value="{{ @$type }}">
                            <div class="col-lg-12 col-md-12 form-group">
                                    <label for="subject" class="form-label">{{__('fields.subject')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="subject" class="form-control   required_content" id="subject"
                                            data-name="Full Order" placeholder="{{__('fields.enter_subject')}}"
                                            value="{{ @$record->subject }}">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="headerText" class="form-label">{{__('fields.header_text')}}*</label>
                                    <div class="icon-input">
                                        <input type="text" name="headerText" class="form-control   required_content" id="headerText"
                                            data-name="Full Order" placeholder="{{__('fields.enter_header_text')}}"
                                            value="{{ @$record->header_text }}">
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12 form-group">
                                    <label for="buttonText" class="form-label">{{__('fields.button_text')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="buttonText" class="form-control   required_content" id="buttonText"
                                            data-name="Full Order" placeholder="{{__('fields.enter_button_text')}}"
                                            value="{{ @$record->button_text }}">
                                    </div>

                                </div>
                                <div class="col-lg-12 col-md-12 form-group">
                                    <label for="body_text" class="form-label">{{__('fields.body_text')}} *</label>
                                    <textarea rows="3" class="form-control"id="body_text"
                                        name="body_text">{{@$record->body_text ?? '' }}</textarea>
                                </div>
                                <div class="col-lg-12 col-md-12 form-group">
                                    <label for="footerText" class="form-label">{{__('fields.footer_text')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="footerText" class="form-control   required_content" id="footerText"
                                            data-name="Full Order" placeholder="{{__('fields.enter_footer_text')}}"
                                            value="{{ @$record->footer_text }}">
                                    </div>

                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-12 text-end">
                        <div class="row align-items-end g-3">
                            <div class="col-lg-5 col-md-6 text-start">
                                <label for="test_email" class="form-label">{{ __('fields.test_email_address') }}</label>
                                <input type="email" name="test_email" id="test_email" class="form-control"
                                    placeholder="{{ __('fields.enter_test_email') }}">
                            </div>
                            <div class="col-lg-7 col-md-6 text-end">
                                <button type="button" class="btn btn-outline-primary me-2" id="sendTestEmail" title="{{ __('fields.send_test_email') }}">{{ __('fields.send_test_email') }}</button>
                                <button class="btn btn-primary" id="saveContent" title="Save">{{ __('fields.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ url('ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('ckfinder/ckfinder.js') }}"></script>
    <script>
 $('.content_head').text("{{ __('fields.' . \Illuminate\Support\Str::of($pageTitle)->lower()->replace(' ', '_')) }}");
 
        var format_tags_shows = "p;h1;h2;h3;h4;h5;h6";  

        CKEDITOR.replace('body_text', {
    height: "100px",
    toolbarStartupExpanded: false,
    contentsCss: ["../css/menu.css?v=6.4"],
    format_tags: format_tags_shows,
    removePlugins: "blockquote,about,TextField,div,clipboard,pastefromword,justify,print,preview,maximize",  
    removeButtons: "Underline,Italic,Strike,Subscript,Superscript,Anchor,Styles,Flash,Smiley,SpecialChar,Image,Table,HorizontalRule", 
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'TextColor'] },  
        { name: 'paragraph', items: ['Format'] }, 
        { name: 'styles', items: ['FontSize'] }  
    ],
    fontSize_sizes: '16/16px;18/18px;20/20px;24/24px;28/28px;32/32px',  
    enterMode: CKEDITOR.ENTER_BR,  
    shiftEnterMode: CKEDITOR.ENTER_P  
});
  
  </script>
    <script src="/js/custom/emailContent.js?v=1.5.0.1"></script>
@endpush