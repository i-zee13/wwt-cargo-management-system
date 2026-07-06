(function ($) {
  function syncLangSwitch() {
    var $toggle = $('#languageToggle');
    if (!$toggle.length) {
      return;
    }

    var isEs = $toggle.prop('checked');
    $('.lang-switch__option').each(function () {
      var active = $(this).data('lang') === (isEs ? 'es' : 'en');
      $(this).toggleClass('is-active', active).attr('aria-pressed', active ? 'true' : 'false');
    });
  }

  function initLanguageToggle() {
    var $toggle = $('#languageToggle');
    if (!$toggle.length) {
      return;
    }

    if (typeof activeLang !== 'undefined' && activeLang) {
      $toggle.prop('checked', activeLang === 'es');
    }

    syncLangSwitch();
  }

  $(initLanguageToggle);

  $(document).on('click', '.lang-switch__option', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var lang = $(this).data('lang');
    var checked = lang === 'es';
    var $toggle = $('#languageToggle');

    if ($toggle.prop('checked') === checked) {
      return;
    }

    $toggle.prop('checked', checked).trigger('change');
  });

  $(document).on('change', '#languageToggle', function () {
    syncLangSwitch();

    var languageToggle = $(this).prop('checked') ? 'es' : 'en';
    $.ajax({
      type: 'POST',
      url: '/change-language',
      data: { languageToggle: languageToggle },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      cache: false,
      success: function () {
        location.reload();
      },
      error: function (xhr) {
        console.log('Error:', xhr.responseText);
      }
    });
  });

  window.syncLangSwitch = syncLangSwitch;
})(jQuery);
