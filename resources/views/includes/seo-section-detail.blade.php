@php
    $randomNumber = hash('sha256', microtime() . mt_rand());
    $uniqueNumber = substr($randomNumber, 0, 10);
    if (isset($post_type)) {
        $schema_type = App\Models\SeoModel::getSchemaType($post_type);
    }

    if (isset($post->slug) && empty($slug_prefix)) {
        $seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . '/' . $post?->slug ?? 'NA');
    } elseif (isset($post, $post->slug) && !empty($slug_prefix)) {
        $seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . "/{$slug_prefix}/" . $post->slug ?? 'NA');
    } elseif (!isset($post, $post->slug)) {
        $seo_canonical_url = null;
    } elseif (!empty($schema_type) && isset($post, $post->slug)) {
        $seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . "/{$schema_type}/" . $post->slug ?? 'NA');
    } elseif (isset($post, $post->slug)) {
        $seo_canonical_url = $seo->seo_canonical_url ?? (config('app.url') . '/' . $post->slug ?? 'NA');
    } else {
        $seo_canonical_url = config('app.url') . '/';
    }
@endphp

<div class="col-12 seo-tabs">
    <div class="col-12 card-header pl-0 border-0 mt-auto mb-auto">
        <h2>SEO Information</h2>
    </div>
    <div class="tabs-header border-0 pr-0">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="general-{{ @$uniqueNumber }}-tab" data-bs-toggle="tab"
                    data-bs-target="#general-{{ @$uniqueNumber }}" type="button" role="tab"
                    aria-controls="general-{{ @$uniqueNumber }}" aria-selected="true">General</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="advanced-{{ @$uniqueNumber }}-tab" data-bs-toggle="tab"
                    data-bs-target="#advanced-{{ @$uniqueNumber }}" type="button" role="tab"
                    aria-controls="advanced-{{ @$uniqueNumber }}" aria-selected="false">Advanced</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="rich-text-{{ @$uniqueNumber }}-tab" data-bs-toggle="tab"
                    data-bs-target="#rich-text-{{ @$uniqueNumber }}" type="button" role="tab"
                    aria-controls="rich-text-{{ @$uniqueNumber }}" aria-selected="false">Rich Text</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="social-{{ @$uniqueNumber }}-tab" data-bs-toggle="tab"
                    data-bs-target="#social-{{ @$uniqueNumber }}" type="button" role="tab"
                    aria-controls="social-{{ @$uniqueNumber }}" aria-selected="false">Social/OG (Admin Only)</button>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="general-{{ @$uniqueNumber }}" role="tabpanel"
            aria-labelledby="general-{{ @$uniqueNumber }}-tab">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 pb-3">
                        <div class="info-label"><span>Post
                                Title</span> <b>{{@$post->seo?->post_title ?? 'NA' }} </b>
                        </div>
                    </div>
                    <div class="col-6 pb-3">
                        <div class="info-label">
                            <span>Permalink</span> <b>{{ @$post->seo?->permalink ?? ($post->slug ?? 'NA') }}</b>
                        </div>
                    </div>

                    <div class="col-12 pb-3">
                        <div class="info-label"><span>Meta
                                Keywords</span>

                            @php
                                $keywords = $post->seo?->meta_keywords ?? 'NA';
                                $keywordsArray = explode(',', $keywords);
                            @endphp

                            @foreach ($keywordsArray as $keyword)
                                <b>{{ trim($keyword) }}</b>
                            @endforeach

                        </div>
                    </div>

                    <div class="col-12 pb-3">
                        <div class="info-label"><span>Meta
                                Description</span>
                            <b>{{ @$post->seo?->meta_description ?? 'NA' }}.</b>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="advanced-{{ @$uniqueNumber }}" role="tabpanel"
            aria-labelledby="advanced-{{ @$uniqueNumber }}-tab">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 pb-3">
                        <div class="info-label"><span>Robots
                                Meta</span> <b>
                                {{ isset($post->seo, $post->seo->robot_index) ? ($post->seo->robot_index == '1' ? 'index' : 'No Index') : 'NA' }}</b>
                            <b>
                                {{ isset($post->seo, $post->seo->robot_follow) ? ($post->seo->robot_follow == '1' ? 'Follow' : 'No Follow') : 'NA' }}</b>
                        </div>
                    </div>
                    <div class="col-6 pb-3">
                        <div class="info-label"><span>Canonical
                                URL</span>
                            <b>{{ @$post->seo?->canonical_url ?? $seo_canonical_url }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="rich-text-{{ @$uniqueNumber }}" role="tabpanel"
            aria-labelledby="rich{{ @$uniqueNumber }}-text-tab">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 pb-3">
                        <div class="info-label">
                            <span>Text</span>
                            <b>
                                {{ isset($post->seo->meta_structure_tags) ? $post->seo->meta_structure_tags : 'NA' }}
                            </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="social-{{ @$uniqueNumber }}" role="tabpanel"
            aria-labelledby="social-{{ @$uniqueNumber }}-tab">


            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12 pb-3">
                                <div class="info-label"><span>OG
                                        Title</span>
                                    <b>{{ @$post->seo?->og_title ?? ($post->seo?->post_title ?? 'NA') }}</b>
                                </div>
                            </div>
                            <div class="col-12 pb-3">
                                <div class="info-label">
                                    <span>OG Description</span>
                                    <b>{{ @$post->seo?->og_description ?? ($post->seo?->meta_description ?? 'NA') }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 pb-3">
                        <div class="info-label">
                            <span>OG Image</span>
                            <img class="og-img-view"
                                src="{{ isset($post->seo, $post->seo->og_image) ? asset($post->seo->og_image) : 'NA' }}"
                                alt="">
                        </div>
                    </div>

                    <div class="col-12">
                        <hr>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-12 pb-3">
                                <div class="info-label">
                                    <span>Twitter Title</span>
                                    <b>{{ @$post->seo->twitter_title ?? ($post->seo->page_title ?? 'NA') }}</b>
                                </div>
                            </div>
                            <div class="col-12 pb-3">
                                <div class="info-label">
                                    <span>Twitter
                                        Description</span>
                                    <b>{{ @$post->seo->twitter_meta_description ?? ($post->seo->meta_description ?? 'NA') }}
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 pb-3">
                        <div class="info-label">
                            <span>Twitter Thumbnail</span>
                            <img class="og-img-view"
                                src="{{ isset($post->seo, $post->seo->twitter_thumbnail) ? asset($post->seo->twitter_thumbnail) : 'NA' }}"
                                alt="">
                        </div>
                    </div>

                    <div class="col-12 pb-3">
                        <div class="info-label">
                            <span>Card Type</span>
                            <b>
                                @isset($post->seo, $post->seo->twitter_card_type)
                                    @if ($post->seo->twitter_card_type == 'summary_card')
                                        Summary Card
                                    @elseif ($post->seo->twitter_card_type == 'summary_large_image')
                                        Summary Large Image
                                    @elseif ($post->seo->twitter_card_type == 'app')
                                        App
                                    @elseif ($post->seo->twitter_card_type == 'player')
                                        Player
                                    @endif
                                @endisset
                            </b>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
