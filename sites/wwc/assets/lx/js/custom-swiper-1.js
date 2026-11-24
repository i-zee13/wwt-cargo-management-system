document.querySelectorAll(".swiper").forEach(function (el) {
  var isMobile = window.matchMedia("(max-width: 767px)").matches;
  var progress = el.querySelector(".wwc-hero-progress > span");
  var delay = 4500;

  var swiper = new Swiper(el, {
    autoplay: { delay: delay, disableOnInteraction: false },
    loop: true,
    spaceBetween: 0,
    effect: isMobile ? "fade" : "creative",
    fadeEffect: { crossFade: true },
    speed: isMobile ? 700 : 1500,
    creativeEffect: {
      prev: { scale: 1.1, opacity: 0, translate: [0, 0, 0] },
      next: { scale: 1.3, opacity: 0, translate: [0, 0, 0] }
    },
    pagination: {
      el: el.querySelector(".swiper-pagination"),
      clickable: true
    },
    navigation: {
      nextEl: el.querySelector(".swiper-button-next"),
      prevEl: el.querySelector(".swiper-button-prev")
    },
    on: {
      autoplayTimeLeft: function (s, time, ratio) {
        if (progress) progress.style.transform = "scaleX(" + (1 - ratio) + ")";
      },
      slideChangeTransitionStart: function () {
        if (progress) progress.style.transform = "scaleX(0)";
      }
    }
  });

  void swiper;
});
