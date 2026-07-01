@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
<style>
    .language-tabs {
        overflow-x: auto;
    }

    .language-tabs .nav-pills {
        width: max-content;
    }

    .language-tabs .nav-pills .nav-link img {
        width: auto;
        height: 1.25rem;
        display: block;
        margin: 0rem auto 0.625rem auto;
        opacity: 0.35;
        filter: grayscale(1);
        border-radius: 0.3125rem;
    }

    .language-tabs .nav-pills .nav-link.active img,
    .language-tabs .nav-pills .show>.nav-link img {
        opacity: 1;
        filter: none;
    }

    .category-icon {
        width: 2.125rem;
        height: 2.125rem;
        margin-right: 0.625rem;
    }

    .card-header .heading02 {
        font-size: 1.125rem;
        color: var(--bs-primary);
    }

    audio {
        background-color: #F6F6F6 !important;
        padding: 0 !important;
        border-radius: var(--border-radius) !important;
        height: 1.875rem !important;
        margin-top: 0.1875rem;
    }

    audio::-webkit-media-controls-panel {
        background-color: #F6F6F6 !important;
        padding: 0 !important;
        border-radius: var(--border-radius) !important;
        height: 1.875rem !important;
    }

    /* Firefox */
    audio::-moz-focus-inner {
        background: #F6F6F6 !important;
        padding: 0 !important;
        border-radius: var(--border-radius) !important;
        height: 1.875rem !important;
    }

    /* Older versions of Firefox */
    audio::--moz-media-controls-background {
        background: #F6F6F6 !important;
        padding: 0 !important;
        border-radius: var(--border-radius) !important;
        height: 1.875rem !important;
    }

    .description-view {
        background-color: #FAFAFA;
        border: solid 0.0625rem #E1E1E1;
        padding: 0.75rem;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        letter-spacing: normal;
        margin-top: 0.625rem;
        font-weight: normal;
    }

    .info-label {
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .info-label span {
        font-size: 0.8125rem;
        margin-bottom: 0px;
    }

    .info-label b {
        background-color: #FAFAFA;
        margin-right: 8px;
        border-radius: var(--border-radius);
        margin-top: 8px;
        padding: 6px 13px;
        display: inline-block;
        font-weight: 500;
    }

    .attachments-detail {
        padding-top: 0.9375rem;
    }

    .attachments-detail strong {
        font-weight: 700;
    }

    .video-card {
        background-color: #fff;
        box-shadow: 0rem 0rem 0.625rem 0rem rgba(0, 0, 0, 0.15);
        position: relative;
        color: #6F6F6F;
        font-size: 0.875rem;
        -webkit-transition: all 0.2s;
        -moz-transition: all 0.2s;
        transition: all 0.2s;
        overflow: hidden;
        margin-top: 0.625rem;
        border-radius: var(--border-radius);
        font-size: 0.8125rem;
        padding-bottom: 0.9375rem;
        margin-bottom: 0.9375rem;
    }

    .video-card:hover {
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.15);
    }

    .mb-15 {
        margin-bottom: 15px !important;
    }

    .VideoView {
        display: block;
        border-radius: var(--border-radius);
        cursor: pointer;
    }

    .video-card img,
    .VideoView img {
        width: 100%;
        height: auto;
        border-radius: var(--border-radius);
    }

    .video-card h3 {
        font-size: 0.9375rem;
        color: #282828;
        line-height: 1.3125rem;
        height: 2.6875rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        margin-bottom: 0.625rem;
    }

    .video-card strong {
        font-weight: 700;
    }

    #modal-video-preview img {
        width: 100%;
        height: auto;
    }

    .btn-edit {
        padding: 0.5rem 0.9375rem;
        font-size: 0.875rem;
    }

    .btn-edit svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.3125rem;
    }

    .modal .nav-tabs {
        margin-bottom: 10px;
    }

    .modal .nav-tabs .nav-item .nav-link {
        padding: 8px 18px;
    }

    .card-header h2 {
        color: #000;
    }

    .seo-tabs .info-label b {
        font-weight: normal;
        font-size: 13px;
    }

    .og-img-view {
        width: 100%;
        height: auto;
        border-radius: var(--border-radius);
        margin-top: 8px;
        max-width: 375px;
    }

    hr {
        opacity: 0.1;
    }

    @media only screen and (max-width: 768px) {
        #responsive-thumbnail {
            content: attr(data-mobile-src);
        }
    }

    .tab-btn-action,
    .tab-btn-action:hover,
    .tab-btn-action:focus {
        float: right !important;
        margin-right: 10px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
        text-align: center;
        display: flex;
    }

    .tab-btn-action svg {
        margin: auto !important;
        width: 18px !important;
    }

    .btn-outline-primary {
        border: solid 0.0625rem #4D815C !important;
    }

    .modal .plyr audio,
    .modal .plyr iframe,
    .modal .plyr video {
        height: 400px;
        min-height: 400px;
    }

    .btn-edit-detail {
        float: right !important;
        margin-right: 10px;
        width: 40px;
        height: 40px;
        border-radius: 40px;
        display: flex;
        text-align: center;
        display: flex;
        padding: 0;
    }
</style>
<div class="modal fade" id="modal-video-preview" tabindex="-1" aria-labelledby="modal-video-previewLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header ps-2 pe-3 border-0 pb-0">
                <h2 class="modal-title" id="modal-video-previewLabel">Preview</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link video-tabs" id="desktop-front-view-tab" data-bs-toggle="tab"
                            data-bs-target="#desktop-front-view" type="button" role="tab"
                            aria-controls="desktop-front-view" aria-selected="true">Desktop Front View</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link video-tabs" id="desktop-side-view-tab" data-bs-toggle="tab"
                            data-bs-target="#desktop-side-view" type="button" role="tab"
                            aria-controls="desktop-side-view" aria-selected="false">Desktop Side View</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link video-tabs" id="mobile-front-view-tab" data-bs-toggle="tab"
                            data-bs-target="#mobile-front-view" type="button" role="tab"
                            aria-controls="mobile-front-view" aria-selected="false">Mobile Front View</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link video-tabs" id="mobile-side-view-tab" data-bs-toggle="tab"
                            data-bs-target="#mobile-side-view" type="button" role="tab" aria-controls="mobile-side-view"
                            aria-selected="false">Mobile Side View</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="desktop-front-view" role="tabpanel"
                        aria-labelledby="desktop-front-view-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <video src="" class="player-desktop-front" controls
                                    data-plyr-config='{ "title": "Desktop Front View Video" }'></video>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="mobile-front-view" role="tabpanel"
                        aria-labelledby="mobile-front-view-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <video src="" class="player-mobile-front" controls
                                    data-plyr-config='{ "title": "Mobile Front View Video" }'></video>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="desktop-side-view" role="tabpanel"
                        aria-labelledby="desktop-side-view-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <video src="" class="player-desktop-side" controls
                                    data-plyr-config='{ "title": "Desktop Side View Video" }'></video>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="mobile-side-view" role="tabpanel"
                        aria-labelledby="mobile-side-view-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <video src="" class="player-mobile-side" controls
                                    data-plyr-config='{ "title": "Mobile Side View Video" }'></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-10 pb-20">
    <div class="col-12">
        <input type="hidden" value="{{ $PageTitles['page'] ?? '' }}" id="page_title">
        <div class="tabs-header border-0 pr-0">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                @isset($detail_records)
                @foreach ($detail_records as $key => $record)
                @php
                $language_title = DB::table('languages')
                ->where('language_id', $record->language_id)
                ->pluck('language_title')
                ->first();
                @endphp
                @if ($language_title)
                <li class="nav-item" role="presentation">
                    <button class="nav-link custom_header_change {{ $key == 0 ? 'active' : '' }} tabs"
                        id="pills-001-{{ $record->language_id }}-tab" r_id="{{ $record->language_id }}"
                        record-id="{{ $record->id }}" data-bs-toggle="pill"
                        data-bs-target="#pills-001-{{ $record->language_id }}" language_id="{{ $record->language_id }}"
                        type="button" role="tab" aria-controls="pills-001{{ $record->language_id }}" key="{{ $key }}"
                        aria-selected="true">
                        {{ $language_title }}
                    </button>
                </li>
                @endif
                @endforeach

                <li class="nav-item col edit-item" role="presentation">
                    <a href="{{ @$route }}" class="btn btn-primary btn-edit-detail">
                        <svg class="btn-icon m-auto" width="12" height="15" viewBox="0 0 16 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 7.16667L14 5.16667L11.3333 2.5L9.33329 4.5M12 7.16667L5.33329 13.8333H2.66663V11.1667L9.33329 4.5M12 7.16667L9.33329 4.5"
                                stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg></a>
                </li>
                <li class="nav-item col delete-record-div detail-delete-div" role="presentation"
                    style="display: none; ">
                    <a title="Delete" href="javascript:void(0)" type="button" id=""
                        class="btn btn-outline-primary tab-btn-action btn-delete delete-record" collection-type=""
                        language-id="">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.3451 8.01958L10.8404 8.0885L10.3451 8.01958ZM10.1702 9.27626L10.6655 9.34518L10.1702 9.27626ZM1.83042 9.27627L2.32564 9.20735L1.83042 9.27627ZM1.65553 8.01958L1.1603 8.0885L1.65553 8.01958ZM4.12276 13.9911L3.92836 14.4518H3.92836L4.12276 13.9911ZM2.31705 11.8735L2.78637 11.701L2.31705 11.8735ZM9.6836 11.8735L10.1529 12.0459L9.6836 11.8735ZM7.87789 13.9911L7.6835 13.5305L7.87789 13.9911ZM1.83142 5.45263C1.8053 5.17772 1.56127 4.97604 1.28637 5.00216C1.01146 5.02828 0.809781 5.27231 0.835901 5.54721L1.83142 5.45263ZM11.1648 5.54721C11.1909 5.27231 10.9892 5.02828 10.7143 5.00216C10.4394 4.97604 10.1954 5.17772 10.1692 5.45262L11.1648 5.54721ZM11.3337 4.66659C11.6098 4.66659 11.8337 4.44273 11.8337 4.16659C11.8337 3.89044 11.6098 3.66659 11.3337 3.66659V4.66659ZM0.666992 3.66659C0.39085 3.66659 0.166992 3.89044 0.166992 4.16659C0.166992 4.44273 0.39085 4.66659 0.666992 4.66659L0.666992 3.66659ZM4.16699 11.4999C4.16699 11.7761 4.39085 11.9999 4.66699 11.9999C4.94313 11.9999 5.16699 11.7761 5.16699 11.4999H4.16699ZM5.16699 6.16659C5.16699 5.89044 4.94313 5.66659 4.66699 5.66659C4.39085 5.66659 4.16699 5.89044 4.16699 6.16659H5.16699ZM6.83366 11.4999C6.83366 11.7761 7.05752 11.9999 7.33366 11.9999C7.6098 11.9999 7.83366 11.7761 7.83366 11.4999H6.83366ZM7.83366 6.16659C7.83366 5.89044 7.6098 5.66659 7.33366 5.66659C7.05752 5.66659 6.83366 5.89044 6.83366 6.16659H7.83366ZM8.66699 4.16659V4.66659H9.16699V4.16659H8.66699ZM3.33366 4.16659H2.83366V4.66659H3.33366V4.16659ZM9.8499 7.95066L9.67501 9.20735L10.6655 9.34518L10.8404 8.0885L9.8499 7.95066ZM2.32564 9.20735L2.15075 7.95066L1.1603 8.0885L1.33519 9.34519L2.32564 9.20735ZM6.00033 13.6666C4.98088 13.6666 4.61728 13.6571 4.31715 13.5305L3.92836 14.4518C4.45975 14.676 5.07071 14.6666 6.00033 14.6666L6.00033 13.6666ZM1.33519 9.34519C1.52166 10.6851 1.62303 11.4344 1.84772 12.0459L2.78637 11.701C2.60803 11.2156 2.51933 10.5991 2.32564 9.20735L1.33519 9.34519ZM4.31715 13.5305C3.70219 13.271 3.1303 12.6371 2.78637 11.701L1.84772 12.0459C2.25746 13.1611 2.98836 14.0551 3.92836 14.4518L4.31715 13.5305ZM9.67501 9.20735C9.48132 10.5991 9.39262 11.2156 9.21428 11.701L10.1529 12.0459C10.3776 11.4344 10.479 10.6851 10.6655 9.34518L9.67501 9.20735ZM6.00033 14.6666C6.92994 14.6666 7.5409 14.676 8.07229 14.4518L7.6835 13.5305C7.38337 13.6571 7.01977 13.6666 6.00033 13.6666L6.00033 14.6666ZM9.21428 11.701C8.87035 12.6371 8.29846 13.271 7.6835 13.5305L8.07229 14.4518C9.01229 14.0551 9.74319 13.1611 10.1529 12.0459L9.21428 11.701ZM2.15075 7.95066C2.00267 6.88661 1.8921 6.09127 1.83142 5.45263L0.835901 5.54721C0.899118 6.21256 1.0134 7.03295 1.1603 8.0885L2.15075 7.95066ZM10.8404 8.0885C10.9873 7.03295 11.1015 6.21256 11.1648 5.54721L10.1692 5.45262C10.1086 6.09127 9.99798 6.8866 9.8499 7.95066L10.8404 8.0885ZM11.3337 3.66659L0.666992 3.66659L0.666992 4.66659L11.3337 4.66659V3.66659ZM5.16699 11.4999L5.16699 6.16659H4.16699L4.16699 11.4999H5.16699ZM7.83366 11.4999L7.83366 6.16659H6.83366L6.83366 11.4999H7.83366ZM8.16699 3.49992V4.16659H9.16699V3.49992H8.16699ZM8.66699 3.66659L3.33366 3.66659V4.66659L8.66699 4.66659V3.66659ZM3.83366 4.16659V3.49992H2.83366V4.16659H3.83366ZM6.00033 1.33325C7.19694 1.33325 8.16699 2.3033 8.16699 3.49992H9.16699C9.16699 1.75102 7.74923 0.333252 6.00033 0.333252V1.33325ZM6.00033 0.333252C4.25142 0.333252 2.83366 1.75102 2.83366 3.49992H3.83366C3.83366 2.3033 4.80371 1.33325 6.00033 1.33325V0.333252Z"
                                fill="" />
                        </svg>
                    </a>
                </li>
                @endisset
            </ul>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="tab-content" id="pills-tabContent">
                @foreach ($detail_records as $key => $record)
                <?php
                $lang_id = isset($record->lang_id) ? $record->lang_id : 0;
                ?>
                <div class="tab-pane fade  {{ $key == 0 ? 'active show' : '' }}"
                    id="pills-001-{{ $record->language_id }}" role="tabpanel"
                    aria-labelledby="pills-001-{{ $record->language_id }}-tab" tabindex="0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col mt-auto mb-auto">
                                <h2 class="heading02">
                                    <img class="category-icon" src="{{ $record->icon ? asset($record->icon) : '' }}"
                                        alt="">
                                    {{ $record->title ?? '' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-6">

                                <div class="row">
                                    @if ($key == 0)
                                    <div class="col-12 pb-3">
                                        <div class="info-label"><span>Collection</span>
                                            <?php $collection_title = explode(',', $record->collection_title); ?>
                                            @foreach ($collection_title as $p)
                                            <b>{{ $p ?? 'N/A' }}</b>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="col-12 pb-3">
                                        <div class="info-label"><span>Personas</span>
                                            <?php $personas = explode(',', $record->persona_title); ?>

                                            @foreach ($personas as $p)
                                            <b>{{ $p ?? 'N/A' }}</b>
                                            @endforeach


                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-6 pb-3">
                                        <div class="info-label"><span>Is Sensitive</span>
                                            {{ $record->is_sensitive == 1 ? 'Yes' : 'No' }}
                                        </div>
                                    </div>
                                    <div class="col-6 pb-3">
                                        <div class="info-label"><span>Accessibility</span>
                                            {{ $record->content_accessibility == 1 ? 'Paid' : 'Free' }}
                                        </div>
                                    </div>
                                    <div class="col-12 pb-3">
                                        <div class="info-label"><span class="mb-0">Audio
                                                File</span>
                                            <audio controls>
                                                <source
                                                    src="{{ isset($record->audio_file) && $record->audio_file != '' ? asset($record->audio_file) : '' }}"
                                                    type="audio/mp3">
                                            </audio>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($record->video)
                            <input type="hidden" value="true" id="has_video">
                            <div class="col-6">
                                <?php
                                    $desktop_front_json = '';
                                    $mobile_front_json = '';
                                    $desktop_side_json = '';
                                    $mobile_side_json = '';
                                    if ($record->desktop_front != null) {
                                        $desktop_front_json = json_encode($record->desktop_front);
                                    }
                                    if ($record->mobile_front != null) {
                                        $mobile_front_json = json_encode($record->mobile_front);
                                    }
                                    if ($record->desktop_side != null) {
                                        $desktop_side_json = json_encode($record->desktop_side);
                                    }
                                    if ($record->mobile_side != null) {
                                        $mobile_side_json = json_encode($record->mobile_side);
                                    }
                                    ?>
                                <input type="hidden" class="videos video_urls_{{ $record->language_id }}"
                                    data-desktop-front="{{ $desktop_front_json }}" id="{{ $record->id }}"
                                    data-mobile-front="{{ $mobile_front_json }}"
                                    data-desktop-side="{{ $desktop_side_json }}"
                                    data-mobile-side="{{ $mobile_side_json }}">
                                <input type="hidden" class="title_{{ $record->language_id }}"
                                    value="{{ $record->title }}" id="{{ $record->id }}">

                                <a class="VideoView" data-bs-toggle="modal" data-bs-target="#modal-video-preview"
                                    data-id="{{ $record->id }}">
                                    <img style="max-height: 348px;" id="responsive-thumbnail"
                                        src="{{ $record->dekstop_thumbnail ? asset($record->dekstop_thumbnail) : asset('images/thumb.jpg') }}"
                                        onerror="this.onerror=null;this.src='/images/thumb.jpg';"
                                        data-mobile-src="{{ $record->mobile_thumbnail ? asset($record->mobile_thumbnail) : asset('images/thumb-mobile.jpg') }}"
                                        alt="Thumbnail" />
                                </a>


                            </div>
                            @endif
                            @include('includes.seo-section-detail', [
                            'post_type' => \App\Models\SeoModel::SCHEMA_TYPE_FingerContentPage,
                            'slug_prefix' => '',
                            'post' => $record,
                            ])

                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                @php
                                     
                                @endphp
                                @if($previousRecord)
                                <a href="{{ @$routeNextPrevious ? $routeNextPrevious.'/'.$previousRecord->previous_id : 'javascript:void(0);' }}" class="btn btn-light" title="{{ @$previousRecord->previous_title }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                    </svg>
                                    Previous
                     
                                </a>
                                @endif
                        
                                @if($nextRecord)
                                <a href="{{ @$routeNextPrevious ? $routeNextPrevious.'/'.$nextRecord->next_id : 'javascript:void(0);' }}" title="{{ @$nextRecord->next_title }} " class="btn btn-primary">
                                    Next
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script>
    $('.content_head').text('{{ $PageTitles['pageTitle'] ?? '' }}');
    var player = new Plyr($('.player'));
    var side_player = new Plyr($('.side-player'));
    $(function() {
        window.fs_test = $('.multi').fSelect();
        $('.tabs').first().trigger('click');

    });
    $('.drop').dropify({
        messages: {
            default: '<strong>Upload Audio File</strong>',
        }
    });

    var playerFrontDesktop = null;
    var playerFrontMobile = null;
    var playerSideDesktop = null;
    var playerSideMobile = null;

    $('.tabs').on('click', function() {
        $('#mobile-front-view-tab', '#mobile-side-view-tab', '#desktop-side-view-tab', '#desktop-front-view-tab').hide()
        if (playerFrontDesktop) playerFrontDesktop.destroy();
        if (playerFrontMobile) playerFrontMobile.destroy();
        if (playerSideDesktop) playerSideDesktop.destroy();
        if (playerSideMobile) playerSideMobile.destroy();
        pauseAllAudio();

        var id = $(this).attr('r_id');
        var isMobile = window.innerWidth < 768;
        $('#modal-video-previewLabel').text($(`.title_${id}`).val());
        var videoSources = {
            front: {
                mobile: $(`.video_urls_${id}`).attr('data-mobile-front'),
                desktop: $(`.video_urls_${id}`).attr('data-desktop-front')
            },
            side: {
                mobile: $(`.video_urls_${id}`).attr('data-mobile-side'),
                desktop: $(`.video_urls_${id}`).attr('data-desktop-side')
            }
        };

        function getVideoSource(type, isMobile) {
            return isMobile ? videoSources[type].mobile : videoSources[type].desktop;
        }

        function loadPlayer(playerSelector, source, title) {
            if (source) {
                var videos = JSON.parse(source);
                var playerInstance = new Plyr(playerSelector);
                playerInstance.source = {
                    type: 'video',
                    title: title,
                    sources: videos.map(function(video) {
                        return {
                            src: video.src,
                            type: video.type,
                            size: parseInt(video.size, 10),
                            previewThumbnails: true
                        };
                    })
                };
                return playerInstance;
            }
            return null;
        }

        var frontSourceDesktop = getVideoSource('front', false);
        var frontSourceMobile = getVideoSource('front', true);
        var sideSourceDesktop = getVideoSource('side', false);
        var sideSourceMobile = getVideoSource('side', true);
        let tabClicked = false;

        const tabs = [{
                key: 0,
                source: frontSourceDesktop,
                playerSelector: '.player-desktop-front',
                tabSelector: '#desktop-front-view-tab',
                description: 'Desktop Front View Video'
            },
            {
                key: 1,
                source: frontSourceMobile,
                playerSelector: '.player-mobile-front',
                tabSelector: '#mobile-front-view-tab',
                description: 'Mobile Front View Video'
            },
            {
                key: 2,
                source: sideSourceDesktop,
                playerSelector: '.player-desktop-side',
                tabSelector: '#desktop-side-view-tab',
                description: 'Desktop Side View Video'
            },
            {
                key: 3,
                source: sideSourceMobile,
                playerSelector: '.player-mobile-side',
                tabSelector: '#mobile-side-view-tab',
                description: 'Mobile Side View Video'
            }
        ];

        tabs.forEach(tab => {
            if (tab.source) {
                if (tab.key == 0) playerFrontDesktop = loadPlayer(tab.playerSelector, tab.source, tab.description);
                if (tab.key == 1) playerFrontMobile = loadPlayer(tab.playerSelector, tab.source, tab.description);
                if (tab.key == 2) playerSideDesktop = loadPlayer(tab.playerSelector, tab.source, tab.description);
                if (tab.key == 3) playerSideMobile = loadPlayer(tab.playerSelector, tab.source, tab.description);
                $(tab.tabSelector).show();
                if (!tabClicked) {
                    $(tab.tabSelector).click();
                    tabClicked = true;
                }
            } else {
                $(tab.tabSelector).hide();
            }
        });

        // if (frontSourceDesktop) {
        //     playerFrontDesktop = loadPlayer('.player-desktop-front', frontSourceDesktop,
        //         'Desktop Front View Video');
        //     $('#desktop-front-view-tab').show().click();
        // } else {
        //     $('#desktop-front-view-tab').hide();
        // }

        // if (frontSourceMobile) {
        //     console.log('21', frontSourceMobile);
        //     playerFrontMobile = loadPlayer('.player-mobile-front', frontSourceMobile,
        //         'Mobile Front View Video');
        //     $('#mobile-front-view-tab').show().click();
        // } else {
        //     $('#mobile-front-view-tab').hide();
        // }

        // if (sideSourceDesktop) {
        //     console.log('213', sideSourceDesktop);
        //     playerSideDesktop = loadPlayer('.player-desktop-side', sideSourceDesktop,
        //         'Desktop Side View Video');
        //     $('#desktop-side-view-tab').show().click();
        // } else {
        //     $('#desktop-side-view-tab').hide();
        // }

        // if (sideSourceMobile) {
        //     console.log('4', sideSourceDesktop);
        //     playerSideMobile = loadPlayer('.player-mobile-side', sideSourceMobile, 'Mobile Side View Video');
        //     $('#mobile-side-view-tab').show().click();
        // } else {
        //     $('#mobile-side-view-tab').hide();
        // }

    });

    // $('.video-tabs').on('click', function() {
    // if (playerFrontDesktop) playerFrontDesktop.pause();
    // if (playerFrontMobile) playerFrontMobile.pause();
    // if (playerSideDesktop) playerSideDesktop.pause();
    // if (playerSideMobile) playerSideMobile.pause();
    // });


    $('.video-tabs').on('click', function() {
        var playerToSetFullscreen = null;
        if ($(this).attr('id') === 'desktop-front-view-tab') {
            if (playerFrontDesktop) {
                playerToSetFullscreen = playerFrontDesktop;
            }
        }
        if ($(this).attr('id') === 'desktop-side-view-tab') {
            if (playerSideDesktop) {
                playerToSetFullscreen = playerSideDesktop;
            }
        }
        if ($(this).attr('id') === 'mobile-front-view-tab') {
            if (playerFrontMobile) {
                playerToSetFullscreen = playerFrontMobile;
            }
        }
        if ($(this).attr('id') === 'mobile-side-view-tab') {
            if (playerSideMobile) {
                playerToSetFullscreen = playerSideMobile;
            }
        }
        if (playerFrontDesktop) playerFrontDesktop.pause();
        if (playerFrontMobile) playerFrontMobile.pause();
        if (playerSideDesktop) playerSideDesktop.pause();
        if (playerSideMobile) playerSideMobile.pause();

        if (playerToSetFullscreen) {
            playerToSetFullscreen.on('enterfullscreen', function() {
                $(playerToSetFullscreen.elements.container).find('video').attr('style', 'height: 100% !important')
            });

            playerToSetFullscreen.on('exitfullscreen', function() {
                $(playerToSetFullscreen.elements.container).find('video').attr('style', 'height: 400px !important');
            });
        }
    });








    $(document).on('click', '.btn-close', function() {
        if (playerFrontDesktop) playerFrontDesktop.pause();
        if (playerFrontMobile) playerFrontMobile.pause();
        if (playerSideDesktop) playerSideDesktop.pause();
        if (playerSideMobile) playerSideMobile.pause();
    })
    $('#modal-video-preview').on('hidden.bs.modal', function() {
        if (playerFrontDesktop) playerFrontDesktop.pause();
        if (playerFrontMobile) playerFrontMobile.pause();
        if (playerSideDesktop) playerSideDesktop.pause();
        if (playerSideMobile) playerSideMobile.pause();
    });
    document.addEventListener('DOMContentLoaded', function() {

        function updateThumbnail() {
            try {
                var img = document.getElementById('responsive-thumbnail');
                var mobileSrc = img.getAttribute('data-mobile-src');
                var desktopSrc = img.getAttribute('src');

                if (window.innerWidth <= 768) {
                    img.setAttribute('src', mobileSrc);
                } else {
                    img.setAttribute('src', desktopSrc);
                }
            } catch (error) {
                // Handle any errors here 
            }
        }

        updateThumbnail();
        window.addEventListener('resize', updateThumbnail);

    });



    function pauseAllAudio() {
        $('audio').each(function() {
            this.pause();
            this.currentTime = 0;
        });
    }
    $(document).ready(function() {
        var pageTitle = $('#page_title').val().trim();

        if (pageTitle == 'word') {
            $.getScript('/js/custom/dicitionary_words.js')
                .done(function(script, textStatus) {
                    console.log('dictionary_words.js loaded successfully');
                })
                .fail(function(jqxhr, settings, exception) {
                    console.log('Failed to load dictionary_words.js');
                });
        } else if (pageTitle === 'phrase') {
            $.getScript('/js/custom/phrases.js')
                .done(function(script, textStatus) {
                    console.log('phrases.js loaded successfully');
                })
                .fail(function(jqxhr, settings, exception) {
                    console.log('Failed to load phrases.js');
                });
        } else {
            console.log('No matching script to load');
        }
    });
</script>
@endpush