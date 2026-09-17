@php
    $websiteUrl = rtrim((string) (config('app.marketing_url') ?: 'https://wwt.com.py'), '/');
@endphp
<a href="{{ $websiteUrl }}"
   class="btn btn-outline-secondary back-to-website-btn"
   title="{{ __('fields.back_to_website') }}">
    <i class="fas fa-globe me-1" aria-hidden="true"></i>
    {{ __('fields.back_to_website') }}
</a>
