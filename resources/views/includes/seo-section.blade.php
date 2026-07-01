{{--
    How to use
    @include('_partials.seo.metabox', ['post_type' => 'product', 'slug_prefix' => 'product', 'post' => $product])
    post_type => 'product, webpage, post, etc'
--}}

@php
$randomNumber = hash('sha256', microtime() . mt_rand());
$uniqueNumber = substr($randomNumber, 0, 10);
if (isset($post_type)) {
$schema_type = App\Models\SeoModel::getSchemaType($post_type);
}

if (isset($post->slug) && empty($slug_prefix)) {
$seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . '/' . $post?->slug ?? '');
} elseif (isset($post, $post->slug) && !empty($slug_prefix)) {
$seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . "/{$slug_prefix}/" . $post->slug ?? '');
} elseif (!isset($post, $post->slug)) {
$seo_canonical_url = null;
} elseif (!empty($schema_type) && isset($post, $post->slug)) {
$seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . "/{$schema_type}/" . $post->slug ?? '');
} elseif (isset($post, $post->slug)) {
$seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . '/' . $post->slug ?? '');
} else {
$seo_canonical_url = config('app.url') . '/';
}
@endphp

<style>
    .no-video-place {
        padding: 50px 30px;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: normal;
        margin-top: 0.625rem;
        text-align: center;
        margin-bottom: 15px;
    }

    .table>:not(caption)>*>* {
        background-color: #fff;
    }

    .table td .btn {
        font-size: 0.6875rem;
        padding: 0.375rem 0.625rem;
        line-height: 1;
        border-radius: 0.375rem;
        margin-right: 0.3125rem;
    }

    .bi-play-btn {
        color: var(--bs-primary);
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .video-section {
        padding: 0 15px 0px 15px;
        border: 0.0625rem solid #e5e5e5;
        border-radius: var(--border-radius);
        margin-bottom: 30px;
    }

    .container,
    .container-lg,
    .container-md,
    .container-sm,
    .container-xl,
    .container-xxl {
        max-width: 1200px;
    }
</style>
<div class="card-header border-0">
    <div class="row">
        <div class="col-12 mt-auto mb-auto">
            <h2>Seo Information</h2>
        </div>
    </div>
</div>


<div class="row m-0">
    <input type="hidden" id="model_id" name="seo[model_id]" value="{{ isset($post, $post->id) ? $post->id : '' }}">
    <input type="hidden" id="schema_type" name="seo[schema_type]" value="{{ $schema_type }}">
    <input type="hidden" id="slug_prefix" name="seo[slug_prefix]" value="{{ $slug_prefix ?? null }}">
    <div class="tabs-header border-0 pr-0">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-{{ $uniqueNumber }}-001-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $uniqueNumber }}-001" type="button" role="tab" aria-controls="pills-{{ $uniqueNumber }}-001" aria-selected="true">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-{{ $uniqueNumber }}-002-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $uniqueNumber }}-002" type="button" role="tab" aria-controls="pills-{{ $uniqueNumber }}-002" aria-selected="false">Advanced</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-{{ $uniqueNumber }}-003-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $uniqueNumber }}-003" type="button" role="tab" aria-controls="pills-{{ $uniqueNumber }}-003" aria-selected="false">Rich Text</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-{{ $uniqueNumber }}-004-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $uniqueNumber }}-004" type="button" role="tab" aria-controls="pills-{{ $uniqueNumber }}-004" aria-selected="false">Social/OG
                    <small>(Admin
                        Only)</small></button>
            </li>

        </ul>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="tab-content" id="pills-tabContent">

            <div class="tab-pane fade show active" id="pills-{{ $uniqueNumber }}-001" role="tabpanel" aria-labelledby="pills-{{ $uniqueNumber }}-001-tab" tabindex="0">

                <div class="card-body">
                    <div class="col-12">
                        <div class="mb-3 row">
                            <!-- Changed from "form-group" to "mb-3" for margin bottom spacing -->
                            <label for="seo_page_title" class="col-sm-2 col-form-label">Post Title</label>
                            <div class="col-sm-10">
                                <input type="text" id="{{ $uniqueNumber }}_seo_page_title" class="form-control" name="seo[seo_page_title]" value="{{ $post->seo?->post_title ?? '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row"> <!-- Changed from "form-group" to "mb-3" -->
                            <label for="seo_permalink" class="col-sm-2 col-form-label">Permalink</label>
                            <div class="col-sm-10">
                                <input type="text" id="{{ $uniqueNumber }}_seo_permalink" class="form-control" name="seo[seo_permalink]" value="{{ $post->seo?->permalink ?? ($post->slug ?? '') }}" autocomplete="off" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row"> <!-- Changed from "form-group" to "mb-3" -->
                            <label class="col-sm-2 col-form-label">Meta Keywords</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="2" id="{{ $uniqueNumber }}_meta_keywords" name="seo[meta_keywords]">{{ $post->seo?->meta_keywords ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row"> <!-- Changed from "form-group" to "mb-3" -->
                            <label for="seo_meta_description" class="col-sm-2 col-form-label">Meta
                                Description</label>
                            <div class="col-sm-10">
                                <textarea rows="3" id="{{ $uniqueNumber }}_seo_meta_description" class="form-control" name="seo[seo_meta_description]">{{ $post->seo?->meta_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade" id="pills-{{ $uniqueNumber }}-002" role="tabpanel" aria-labelledby="pills-{{ $uniqueNumber }}-002-tab" tabindex="0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="seo_robots_meta" class="col-sm-2 col-form-label">Robots Meta</label>
                                <div class="col-sm-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="seo_robots_meta_index" name="seo[seo_robots_meta_index]" value="index" data-value-meaning="1" {{ isset($post->seo, $post->seo->robot_index) ? ($post->seo->robot_index == '1' ? 'checked' : '') : 'checked' }}>
                                        <label class="form-check-label" for="seo_robots_meta_index">Index</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="seo_robots_meta_noindex" name="seo[seo_robots_meta_index]" value="noindex" data-value-meaning="0" required {{ isset($post->seo, $post->seo->robot_index) ? ($post->seo->robot_index == '0' ? 'checked' : '') : 'checked' }}>
                                        <label class="form-check-label" for="seo_robots_meta_noindex">No
                                            Index</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="seo_robots_meta_follow" name="seo[seo_robots_meta_follow]" value="follow" data-value-meaning="1" required {{ isset($post->seo, $post->seo->robot_follow) ? ($post->seo->robot_follow == '1' ? 'checked' : '') : 'checked' }}>
                                        <label class="form-check-label" for="seo_robots_meta_follow">Follow</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="seo_robots_meta_nofollow" name="seo[seo_robots_meta_follow]" value="nofollow" data-value-meaning="0" required {{ isset($post->seo, $post->seo->robot_follow) ? ($post->seo->robot_follow == '0' ? 'checked' : '') : 'checked' }}>
                                        <label class="form-check-label" for="seo_robots_meta_nofollow">No
                                            Follow</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="seo_canonical_url" class="col-sm-2 col-form-label">Canonical
                                    URL</label>
                                <div class="col-sm-10">
                                    <input type="text" id="seo_canonical_url" class="form-control" name="seo[seo_canonical_url]" value="{{ $post->seo?->canonical_url ?? $seo_canonical_url }}" placeholder="{{ $post->seo?->canonical_url ?? $seo_canonical_url }}" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade" id="pills-{{ $uniqueNumber }}-003" role="tabpanel" aria-labelledby="pills-{{ $uniqueNumber }}-003-tab" tabindex="0">

                <div class="card-body">
                    <div class="col-12">
                        <div class="mb-3 row">
                            <label for="seo_schema" class="col-sm-2 col-form-label">Text</label>
                            <div class="col-sm-10">
                                <textarea id="{{ $uniqueNumber }}_seo_schema" class="form-control" readonly autocomplete="off">{{ isset($post->seo->meta_structure_tags) ? $post->seo->meta_structure_tags : '' }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-pane fade" id="pills-{{ $uniqueNumber }}-004" role="tabpanel" aria-labelledby="pills-{{ $uniqueNumber }}-004-tab" tabindex="0">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="og_title" class="col-sm-2 col-form-label">OG Title</label>
                                <div class="col-sm-10">
                                    <input type="text" id="{{ $uniqueNumber }}_og_title" name="seo[og_title]" class="form-control" value="{{ $post->seo?->og_title ?? ($post->seo?->post_title ?? '') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="og_description" class="col-sm-2 col-form-label">OG Description</label>
                                <div class="col-sm-10">
                                    <textarea rows="3" id="{{ $uniqueNumber }}_og_description" class="form-control" name="seo[og_description]">{{ $post->seo?->og_description ?? ($post->seo?->meta_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="og_image" class="col-sm-2 col-form-label">OG Image</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="seo[hidden_og_image]" value="{{ $post->seo?->og_image ?? '' }}">
                                    <input type="file" id="{{ $uniqueNumber }}_og_image" name="seo[og_image]" class="dropify" data-default-file="{{ isset($post->seo, $post->seo->og_image) ? asset($post->seo->og_image) : '' }}" data-old_input="hidden_og_image" accept="image/*" data-allowed-file-extensions="jpg png jpeg JPEG webp " />
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="seo_twitter_title" class="col-sm-2 col-form-label">Twitter
                                    Title</label>
                                <div class="col-sm-10">
                                    <input type="text" id="{{ $uniqueNumber }}_seo_twitter_title" name="seo[seo_twitter_title]" class="form-control" value="{{ $post->seo->twitter_title ?? ($post->seo->page_title ?? '') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="seo_twitter_description" class="col-sm-2 col-form-label">Twitter
                                    Description</label>
                                <div class="col-sm-10">
                                    <textarea rows="3" id="{{ $uniqueNumber }}_seo_twitter_description" class="form-control" name="seo[seo_twitter_description]">{{ $post->seo->twitter_meta_description ?? ($post->seo->meta_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label for="twitter_thumbnail" class="col-sm-2 col-form-label">Twitter
                                    Thumbnail</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="seo[hidden_twitter_image]" value="{{ $post->seo?->twitter_thumbnail ?? '' }}">
                                    <input type="file" id="{{ $uniqueNumber }}_twitter_thumbnail" name="seo[twitter_image]" class="dropify" data-default-file="{{ isset($post->seo, $post->seo->twitter_thumbnail) ? asset($post->seo->twitter_thumbnail) : '' }}" data-old_input="hidden_twitter_image" accept="image/*" data-allowed-file-extensions="jpg png jpeg JPEG webp " />
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-12">
                        <div class="mb-3">
                            <label for="twitter_card_type" class="form-label ">Card Type</label>
                            <div class="icon-input">
                                <div class="form-s2">
                                    <select class="form-group select_class" id="{{ $uniqueNumber }}_twitter_card_type" name="seo[twitter_card_type]">
                                        <option value="summary_card" {{ isset($post->seo, $post->seo->twitter_card_type) ? ($post->seo->twitter_card_type == 'summary_card' ? 'selected' : '') : '' }} selected>Summary Card</option>
                                        <option value="summary_large_image" {{ isset($post->seo, $post->seo->twitter_card_type) ? ($post->seo->twitter_card_type == 'summary_large_image' ? 'selected' : '') : '' }}>
                                            Summary Card with Large Image</option>
                                        <option value="app" {{ isset($post->seo, $post->seo->twitter_card_type) ? ($post->seo->twitter_card_type == 'app' ? 'selected' : '') : '' }}>
                                            App Card</option>
                                        <option value="player" {{ isset($post->seo, $post->seo->twitter_card_type) ? ($post->seo->twitter_card_type == 'player' ? 'selected' : '') : '' }}>
                                            Player Card</option>
                                    </select>
                                </div>
                                <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.6667 11.5212L8.21859 9.07309M9.5 5.97949C9.5 3.56325 7.54125 1.60449 5.125 1.60449C2.70875 1.60449 0.75 3.56325 0.75 5.97949C0.75 8.39574 2.70875 10.3545 5.125 10.3545C7.54125 10.3545 9.5 8.39574 9.5 5.97949Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>



</div>