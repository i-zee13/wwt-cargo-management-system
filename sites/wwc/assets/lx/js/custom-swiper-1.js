document.querySelectorAll(".swiper").forEach(function (el) {
  new Swiper(el, {
    autoplay: { delay: 4000, disableOnInteraction: false },
    loop: true,
    spaceBetween: 0,
    effect: "creative",
    speed: 1500,
    creativeEffect: {
      prev: { scale: 1.1, opacity: 0, translate: [0, 0, 0] },
      next: { scale: 1.3, opacity: 0, translate: [0, 0, 0] }
    },
    navigation: {
      nextEl: el.querySelector(".swiper-button-next"),
      prevEl: el.querySelector(".swiper-button-prev")
    }
  });
});
