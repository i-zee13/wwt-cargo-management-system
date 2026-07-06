@php
    $currentLocale = session('locale', config('app.locale'));
    $isSpanish = $currentLocale === 'es';
    $compact = $compact ?? false;
@endphp
<div class="lang-switch{{ $compact ? ' lang-switch--compact' : '' }}" role="group" aria-label="{{ __('fields.language') }}">
    <button type="button"
        class="lang-switch__option{{ $isSpanish ? '' : ' is-active' }}"
        data-lang="en"
        aria-pressed="{{ $isSpanish ? 'false' : 'true' }}">{{ __('fields.english') }}</button>
    <button type="button"
        class="lang-switch__option{{ $isSpanish ? ' is-active' : '' }}"
        data-lang="es"
        aria-pressed="{{ $isSpanish ? 'true' : 'false' }}">{{ __('fields.spanish') }}</button>
</div>
<input type="checkbox" id="languageToggle" class="lang-switch__input" {{ $isSpanish ? 'checked' : '' }} tabindex="-1" aria-hidden="true">
