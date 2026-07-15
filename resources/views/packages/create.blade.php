@extends('layouts.master')
@section('content')

<style>
    .iti--inline-dropdown {
        width: 100%;
    }
    strong{
        font-weight: 800;
    }
    .mr-1{
        margin-right: 10px;
    }
    img {
            max-width: 100%;
            height: auto;
            width:auto
        }
</style>
<div class="modal fade preview" id="ViewDocumentImg" tabindex="-1" role="dialog" aria-labelledby="DetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content top_border">
                <div class="modal-header">
                    <h5 class="modal-title" id="DetailModalLabel">{{__('fields.document_preview')}}</span></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">

                    </div>
                </div>
                <div class="modal-footer border-0 p-10">
                    <a href="" class="btn btn-primary btn_modal_download" download target="_blank">{{__('fields.download')}}</a>
                    <button type="submit" class="btn btn-cancel" data-dismiss="modal" aria-label="Close">{{__('fields.close')}}</button>
                </div>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-12">
        <div class="card mt-0">
        @if($package)
    <div class="card-header mb-3">
                 <div class="row"> 
                    <div class="col mt-auto mb-auto   pr-0">
                        <h2>{{__('fields.label')}}  </h2>
                    </div>
                    <div class="col-auto pr-0 text-end mr-1" >
                    <a href="{{ GetActiveGuardDetail()->is_web ? url('/admin/package-print-wh/' . @$package->id) : url('/print-customer-packages-wh/' . @$package->id) }}"
   class="btn btn-outline-primary me-2" target="_blank">
   {{ __('fields.print_wh') }}
</a>
                    <a href="{{ GetActiveGuardDetail()->is_web ? url('/admin/package-print-label/' . @$package->id) : url('/print-customer-packages-label/' . @$package->id) }}" 
   class="btn btn-primary">
   {{ __('fields.print_label') }}
</a>

                    </div>
                </div>
    </div>
    <div class="col-lg-12 col-md-12 p-3">
        <div class="row">
         
        <div class="col-md-12 form-group col-lg-12 d-flex justify-content-center align-items-center">
             <img src="{{ @$package->waybill != null ? url('/barcodes/' . str_replace(' ', '-', $package->waybill) . '.png') : '' }}" alt="Barcode Image">
        </div>
            <div class="col-md-12 form-group col-lg-12  ">
                <strong>{{__('fields.pacakge_waybill')}}:</strong> {{@$package->waybill}}
            </div>
            <div class="col-lg-4 col-md-12 form-group">
                <strong>{{__('fields.client_suite')}}:</strong> {{ @$package->client_suite ?? '' }}
            </div>
            <div class="col-md-12 form-group col-lg-4 client_name_div">
                <strong>{{__('fields.client_name')}}:</strong>  {{GetActiveGuardDetail()->is_web != 1?GetActiveGuardDetail()->first_name .' ' .GetActiveGuardDetail()->last_name:''}}
            </div>
         
            
            <div class="col-md-12 form-group">
                <strong>{{__('fields.package_description')}}:</strong> {{ @$package->description ?? '' }}
            </div>  
            <div class="col-md-6 form-group">
                <strong>{{__('fields.weight')}}:</strong> {{ @$package->kg ?? '' }}
            </div>
            <div class="col-md-12 form-group">
                <strong>{{__('fields.grand_total')}}:</strong> {{ @$package->grand_total ?? '' }}
            </div>
         
        </div> 
        </div>
 
@endif
            <div class="card-header">
                <div class="row">
                    <div class="col-auto pr-0">

                    </div>
                    
                    
                    <div class="col mt-auto mb-auto pl-0 pr-0">
                        <h2>{{__('fields.package_details')}}</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                
                    <div class="col-lg-12"> 
                        <form style="display: flex; width: 100%" autocomplete="off" id="savePackageForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" id="operation" class="operation" hidden>
                            <input type="hidden" id="package_id" name="package_id" value="{{ @$package->id }}">
                            <input type="hidden" id="client_id" name="client_id" value="{{ GetActiveGuardDetail()->is_web ? @$package->client_id :GetActiveGuardDetail()->id}}">
                            <input type="hidden" id="origin_name" name="origin_name" value="">
                            <div class="row">
                               
                                <div class="col-lg-4 col-md-12  ">
                                    <label for="type" class="form-label">{{__('fields.type')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control required select_class" id="type"
                                                name="type" data-name="type" style="width: 100%;">
                                                <option value="">{{__('fields.select_type')}}</option>
                                                <option value="air" {{@$package->type == 'air' ? 'selected' : ''}}>{{__('fields.air')}}
                                                </option>
                                                <option value="land" {{@$package->type == 'land' ? 'selected' : ''}}> 
                                                {{__('fields.land')}}</option>
                                                <option value="maritime" {{@$package->type == 'maritime' ? 'selected' : ''}}>
                                                {{__('fields.maritime')}}</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                             
                                <div class="col-lg-4 col-md-12">
                                    <label for="origin" class="form-label">{{__('fields.origin')}} *</label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control required select_class" id="origin"
                                                name="origin" data-name="origin" style="width: 100%;">
                                                <option value="">{{__('fields.select_origin')}}</option>
                                                
                                                @foreach ($origins as $origin)
                                                    <option value="{{ $origin->id }}"
                                                        {{@$package->origin_id == $origin->id ? 'selected' : ''}} rate="{{$origin->rate}}">
                                                        {{ $origin->origin_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="original_tracking" class="form-label">{{__('fields.original_tracking')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="original_tracking" class="form-control required"
                                            id="original_tracking" data-name="original_tracking"
                                            placeholder="{{__('fields.original_tracking')}}"
                                            value="{{ @$package->original_tracking }}">
                                    </div>

                                </div>
                                @if (@$package && @$package->waybill)
                                    <div class="col-lg-4 col-md-12 form-group">
                                        <label for="waybill" class="form-label">{{__('fields.waybill')}} *</label>
                                        <div class="icon-input">
                                            <input type="text" name="waybill" class="form-control   required"readonly
                                                id="waybill" data-name="Full Order" placeholder="{{__('fields.enter_waybill_package')}}"
                                                value="{{ @$package->waybill }}">
                                        </div>

                                    </div>
                                @endif
                                @if (GetActiveGuardDetail()->is_web == 1)
                                <div class="col-lg-8 col-md-12">
                                    <label for="customer-select-id" class="form-label">{{__('fields.customers')}} </label>
                                    <div class="icon-input">
                                        <div class="form-s2">
                                            <select class="form-control  select_class " id="customer-select-id"
                                                name="customer-select-id" data-name="customer-select-id" style="width: 100%;">
                                                <option value="">{{__('fields.select_customer')}}</option>
                                                
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{@$package->client_id == $customer->id ? 'selected' : ''}}>
                                                        {{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->suite }})
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif 
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="suit" class="form-label">{{__('fields.suite')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="suite" class="form-control  required" id="suite"
                                            placeholder="{{__('fields.enter_suite')}} " {{GetActiveGuardDetail()->is_web != 1? 'readonly':''}} value="{{GetActiveGuardDetail()->is_web? @$package->client_suite :GetActiveGuardDetail()->suite }}">
                                    </div>
                                </div>
                                <input type="hidden" hidden value="{{@$customers}}"id="all_customers">
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="first_name" class="form-label">{{__('fields.client_first_name')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="first_name" disabled class="form-control  "
                                            id="first_name" placeholder="{{__('fields.client_first_name')}}"  {{GetActiveGuardDetail()->is_web != 1? 'readonly':''}} 
                                            value="{{ GetActiveGuardDetail()->is_web? @$package->clientDetail->first_name : GetActiveGuardDetail()->first_name }}">

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="last_name" class="form-label">{{__('fields.client_last_name')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="last_name" disabled class="form-control  "
                                            id="last_name" placeholder="{{__('fields.client_last_name')}}"  {{GetActiveGuardDetail()->is_web != 1? 'readonly':''}} 
                                            value="{{GetActiveGuardDetail()->is_web? @$package->clientDetail->last_name : GetActiveGuardDetail()->last_name }}">

                                    </div>
                                </div> 
                                <div class="col-lg-12 col-md-12 form-group">
                                    <label for="description" class="form-label">{{__('fields.description')}}</label>
                                    <textarea rows="3" class="form-control" name="description"
                                         >{{$package->description ?? '' }}</textarea> 
                                </div>
                                @if (GetActiveGuardDetail()->is_web == 1)
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="kg" class="form-label">{{__('fields.kg')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="kg" class="form-control only_decimals_no" id="kg"
                                            placeholder="{{__('fields.enter_kg')}}" value="{{ @$package->kg }}">
                                    </div> 
                                </div>
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="cbm" class="form-label">{{__('fields.cbm')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="cbm" class="form-control only_decimals_no" id="cbm"
                                            placeholder="{{__('fields.enter_cbm')}}" value="{{ @$package->cbm }}"> 
                                    </div>

                                </div>
                                @endif
                              
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="package_date" class="form-label">{{__('fields.date')}} *</label>
                                    <div class="icon-input">
                                        <input type="text" name="package_date" readonly class="form-control required" id="package_date" 
                                            value="{{ isset($package) ? \Carbon\Carbon::parse($package->created_at)->format('d/M/Y') : \Carbon\Carbon::now()->format('d/M/Y') }}">
                                    </div> 
                                </div>
                                @if (GetActiveGuardDetail()->is_web == 1)
                                <div class="col-lg-4 col-md-12 form-group">
                                    <label for="grand_total" class="form-label">{{__('fields.grand_total')}} </label>
                                    <div class="icon-input">
                                        <input type="text" name="grand_total"  class="form-control   only_decimals_no" id="grand_total" readonly
                                            placeholder="" value="{{ @$package->grand_total }}"> 
                                    </div> 
                                </div>
                                @endif
                                <div class="col-lg-6 col-md-12 form-group mt-3">
                                 <label for="taxOnInvoice" class="form-label">Is Package Insured *</label>
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input required_persona" type="radio"
                                                    name="is_insured" id="insured" value="insured" 
                                                    {{ @$package->is_insured == 'insured' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="insured">
                                                    Insured
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="is_insured" id="not-insured" value="not-insured"  
                                                    {{ @$package->is_insured != 'insured' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="not-insured">
                                                    Not Insured
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12  form-group  mt-3  "
                                    style="padding-top: 20px;">
                                    <label for="mobileThumbnail" class="form-label d-flex justify-content-between">{{__('fields.invoice_image')}} 
                                    @if(@$package->invoice_image)    
                                        <p>
                                                <strong class="" >
                                                        <a  data-toggle="modal"
                                                            data-target="#ViewDocumentImg"  style="cursor:pointer;color:#eb973c;"
                                                            data-img="{{asset($package->invoice_image)}}"
                                                            class="link-doc intake-doc-preview"> {{__('fields.view_invoice')}} </a>
                                                </strong>
                                        </p>
                                        @endif
                                    </label>
                                    <div class="upload-pic"></div>
                                    <input type="hidden" name="hidden_invoice_image" id="hidden_invoice_image"
                                        value="{{ @$package->invoice_image }}">
                                    <input type="file" class="dropify " id="invoice_image" name="invoice_image"
                                        accept="image/*" data-old_input="hidden_invoice_image"
                                        data-default-file="{{ isset($package->invoice_image) && $package->invoice_image != '' ? asset($package->invoice_image) : '' }}"
                                        data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                                </div> 
                                @if (GetActiveGuardDetail()->is_web == 1)
                                <div class="col-lg-6 col-md-12  form-group  mt-3  "
                                    style="padding-top: 20px;">
                                    <label for="mobileThumbnail" class="form-label d-flex justify-content-between">{{__('fields.other_document')}} 

                                    @if(@$package->other_document)    
                                        <p>
                                            <strong class="" >
                                                    <a  data-toggle="modal"
                                                        data-target="#ViewDocumentImg"  style="cursor:pointer;color:#eb973c;"
                                                        data-img="{{asset($package->other_document)}}"
                                                        class="link-doc intake-doc-preview"> {{__('fields.view_docs')}} </a>
                                            </strong>
                                        </p>
                                        @endif



                                    </label>
                                    <div class="upload-pic"></div>
                                    <input type="hidden" name="hidden_other_document" id="hidden_other_document"
                                        value="{{ @$package->other_document }}">
                                    <input type="file" class="dropify " id="other_document" name="other_document"
                                        accept="image/*" data-old_input="hidden_other_document"
                                        data-default-file="{{ isset($package->other_document) && $package->other_document != '' ? asset($package->other_document) : '' }}"
                                        data-allowed-file-extensions="tiff jfif bmp gif svg png jpeg svgz jpg webp ico xbm dib pjp apng tif pjpeg avif">
                                </div>
                                @endif
                                <div class="card-header mb-3">
                                    <h2>{{__('fields.comments')}}</h2>
                                </div>
                                <div class="col-lg-12 col-md-12 form-group">
                                    <label for="comments" class="form-label">{{__('fields.comments')}}</label>
                                    <textarea id="comments" class="comments" cols="30" rows="10"
                                        name="comments">{{ isset($package->comments) ? $package->comments : '' }}</textarea>
                                </div> 
                            </div>

                        </form>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" id="savePackage" title="Save">{{__('fields.save')}}</button>
                        <a href="{{GetActiveGuardDetail()->is_web?'/admin/packages':'/customer-packages'}}" class="btn btn-light me-2 actionBtns" id="btn-cancel"
                            title="Cancel">{{__('fields.cancel')}}</a>
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
        initializeCKEditor("comments", "en", false); 
        $('.content_head').text('{{__('fields.package_management')}}') ;
        
        $(document).on('click','.intake-doc-preview',function(){
            var file                =   $(this).attr('data-img');  
            $('.btn_modal_download').attr('href', `${file}`);
            $('.preview  .modal-body').empty(); 
                $('.preview .modal-body').html('<img src="'+`${file}`+'" class="cnicCardimg" />'); 
        });
    </script>
    <script src="/js/custom/packages.js"></script>
@endpush