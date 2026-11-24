$(function () {
  $(".wwc-stores-marquee[data-wwc-marquee]").each(function () {
    var $el = $(this);
    if ($el.data("wwcMarqueeInit")) return;
    $el.data("wwcMarqueeInit", true);
    $el.marquee({
      direction: "left",
      duration: 42000,
      gap: 0,
      delayBeforeStart: 0,
      duplicated: true,
      startVisible: true,
      pauseOnHover: true
    });
  });

  $(".de-marquee-list-1").not("[data-wwc-marquee]").marquee({
    direction: "left",
    duration: 45000,
    gap: 48,
    delayBeforeStart: 0,
    duplicated: true,
    startVisible: true
  });

  $(".de-marquee-list-2").marquee({
    direction: "right",
    duration: 45000,
    gap: 48,
    delayBeforeStart: 0,
    duplicated: true,
    startVisible: true
  });
});
