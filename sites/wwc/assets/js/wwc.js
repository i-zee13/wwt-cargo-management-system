(function () {
  var TRACK = "https://wwcsys.worldwidecommerce.us";
  document.querySelectorAll("[data-track-form]").forEach(function (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var input = form.querySelector('input[name="codigo"]');
      var code = (input && input.value || "").trim();
      if (!code) { if (input) input.focus(); return; }
      window.open(TRACK + "/?tracking=" + encodeURIComponent(code), "_blank", "noopener");
    });
  });
  var contact = document.querySelector("[data-contact-form]");
  if (contact) {
    contact.addEventListener("submit", function (e) {
      var ok = true;
      contact.querySelectorAll("[required]").forEach(function (f) {
        var valid = f.type === "email"
          ? /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(f.value.trim())
          : f.value.trim().length > 0;
        if (!valid && ok) { f.focus(); ok = false; }
      });
      e.preventDefault();
      if (ok) window.location.href = "gracias.html";
    });
  }
  var y = new Date().getFullYear();
  document.querySelectorAll("[data-year]").forEach(function (el) { el.textContent = y; });

  if (window.jQuery && jQuery.fn.owlCarousel) {
    var $q = jQuery("#wwc-quotes");
    if ($q.length) {
      $q.owlCarousel({
        items: 1,
        loop: true,
        margin: 0,
        dots: true,
        nav: false,
        autoplay: true,
        autoplayTimeout: 6500,
        autoplayHoverPause: true,
        smartSpeed: 600
      });
    }
  }

  /* Service detail: sidebar switches content without full reload */
  (function () {
    var root = document.querySelector("[data-svc-single]");
    var dataEl = document.getElementById("wwc-services-data");
    if (!root || !dataEl) return;
    var data;
    try { data = JSON.parse(dataEl.textContent); } catch (e) { return; }
    var bySlug = {};
    (data.items || []).forEach(function (item) { bySlug[item.slug] = item; });

    function esc(s) {
      return String(s == null ? "" : s)
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
    }

    function render(item, push) {
      if (!item) return;
      root.setAttribute("data-active", item.slug);
      root.querySelectorAll("[data-svc-slug]").forEach(function (a) {
        a.classList.toggle("is-active", a.getAttribute("data-svc-slug") === item.slug);
      });
      var panel = root.querySelector("[data-svc-panel]");
      if (panel) {
        panel.classList.remove("is-swap");
        void panel.offsetWidth;
        panel.classList.add("is-swap");
      }
      var title = root.querySelector("[data-svc-title]");
      var intro = root.querySelector("[data-svc-intro]");
      var img = root.querySelector("[data-svc-img]");
      var blurb = root.querySelector("[data-svc-blurb]");
      var feats = root.querySelector("[data-svc-feats]");
      var wa = root.querySelector("[data-svc-wa]");
      if (title) title.textContent = item.h1;
      if (intro) intro.textContent = item.intro;
      if (blurb) blurb.textContent = item.blurb;
      if (img) img.src = (data.assetPrefix || "") + item.img;
      if (feats) {
        feats.innerHTML = (item.benefits || []).map(function (b) {
          return '<div class="wwc-svc-feat"><i class="fa-solid fa-check"></i><div><h4>' + esc(b) + "</h4></div></div>";
        }).join("");
      }
      if (wa) wa.href = data.waBase + encodeURIComponent(data.waPrefix + item.nav);

      var hero = document.querySelector("#subheader h1");
      if (hero) hero.textContent = item.nav;
      var crumbs = document.querySelector("#subheader .crumb");
      if (crumbs) {
        crumbs.innerHTML =
          '<a href="./">' + esc(data.homeCrumb) + "</a>" +
          '<span class="sep">/</span><a href="' + esc(data.servicesHref) + '">' + esc(data.servicesCrumb) + "</a>" +
          '<span class="sep">/</span><span>' + esc(item.nav) + "</span>";
      }
      document.title = item.title;
      var meta = document.querySelector('meta[name="description"]');
      if (meta && item.meta) meta.setAttribute("content", item.meta);

      if (push) {
        try { history.pushState({ svc: item.slug }, item.title, item.href); } catch (e) {}
      }
      try { window.scrollTo({ top: Math.max(0, root.offsetTop - 90), behavior: "smooth" }); } catch (e) {}
    }

    root.querySelectorAll("[data-svc-slug]").forEach(function (a) {
      a.addEventListener("click", function (e) {
        var slug = a.getAttribute("data-svc-slug");
        var item = bySlug[slug];
        if (!item) return;
        e.preventDefault();
        if (root.getAttribute("data-active") === slug) return;
        render(item, true);
      });
    });

    window.addEventListener("popstate", function (e) {
      var slug = (e.state && e.state.svc) || (location.pathname.split("/").pop() || "").replace(/\.html$/, "")
        .replace(/^servicio-/, "");
      // path may be /servicio-slug or /en/servicio-slug
      var parts = location.pathname.split("/").filter(Boolean);
      var last = parts[parts.length - 1] || "";
      last = last.replace(/\.html$/, "");
      if (last.indexOf("servicio-") === 0) slug = last.slice("servicio-".length);
      if (bySlug[slug]) render(bySlug[slug], false);
    });
  })();
})();
