@extends('layouts.master')

@section('content')


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col mt-auto mb-auto">
                        <h2 class="heading02">{{__('fields.file_download')}}</h2>
                    </div>
                    <div class="col-auto pr-0">
                        <a href="{{url('/modules/imports/samples/' . $page['sample-url'])}}" download
                            class="btn add_button "><i class="fa fa-download"></i> <span>{{__('fields.download_sample')}}</span></a>
                    </div>
                </div>
            </div>
            <div class="card-body ">
                <form id="upload-file" action="{{url($page['url'])}}" method="POST" enctype="multipart/form-data">
                @csrf    
                <div class="row">
                        <div class="col-md-12">
                            <input type="file" name="file" id="input-file-now" class="dropify"
                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required>
                        </div>
                        <div class="col-12 PT-10 text-end">
                            <button type="submit" title="Upload CLients"
                                class="btn btn-primary m-0 mt-5 uploadClients">{{__('fields.upload')}}</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
        <div class="card mb-30 error_card" style="display: none"></div>
    </div>

</div>
</div>
@endsection

@push('js')
    <script>
         $('.content_head').text('{{__('fields.clients_bulk_upload')}}')  
        $('.first_crumb').text('Import')
        $('.second_crumb').hide();
        $('.dropify').dropify({
    messages: {
        'default': '{{__('fields.drag_drop_upload')}}',
        'replace': '{{__('fields.drag_drop_upload')}}',
        'remove': 'Remove',
        'error': 'Oops, something went wrong!'
    },
    error: {
        'fileExtension': 'Only Excel files (xls, xlsx) are allowed.'
    }
});

    </script>
    <script src="/js/custom/imports.js"></script>
@endpush