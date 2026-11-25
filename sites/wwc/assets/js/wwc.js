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

  /* Origen / Destino quote widget */
  document.querySelectorAll("[data-wwc-route-quote]").forEach(function (box) {
    var dataEl = box.querySelector(".wwc-route-data");
    var origin = box.querySelector("[data-route-origin]");
    var dest = box.querySelector("[data-route-dest]");
    var result = box.querySelector("[data-route-result]");
    var modesEl = box.querySelector("[data-route-modes]");
    var hint = box.querySelector("[data-route-hint]");
    var cta = box.querySelector("[data-route-cta]");
    var matrix = {};
    try { matrix = JSON.parse(dataEl.textContent); } catch (e) { return; }

    function syncDisabled() {
      var o = origin.value;
      var d = dest.value;
      Array.prototype.forEach.call(origin.options, function (opt) {
        if (!opt.value) return;
        opt.disabled = !!d && opt.value === d;
      });
      Array.prototype.forEach.call(dest.options, function (opt) {
        if (!opt.value) return;
        opt.disabled = !!o && opt.value === o;
      });
    }

    function render() {
      syncDisabled();
      var o = origin.value;
      var d = dest.value;
      if (o && d && o === d) {
        if (hint) hint.hidden = false;
        if (result) result.hidden = true;
        return;
      }
      if (hint) hint.hidden = true;
      if (!o || !d) {
        if (result) result.hidden = true;
        return;
      }
      var modes = matrix[o + "|" + d] || [];
      if (!modes.length) {
        if (result) result.hidden = true;
        return;
      }
      modesEl.innerHTML = modes.map(function (m) {
        var unit = m.unit === "kg" ? "/kg" : "/CBM";
        return '<div class="wwc-route-mode"><div><b>' + m.label +
          '</b><small>' + m.transit + '</small></div>' +
          '<div class="wwc-route-mode__price">desde USD ' + m.from_usd + unit + "</div></div>";
      }).join("");
      if (cta) {
        var base = cta.getAttribute("href").split("?")[0];
        cta.setAttribute("href", base + "?destino=" + encodeURIComponent(d) + "&origen=" + encodeURIComponent(o));
      }
      result.hidden = false;
    }

    origin.addEventListener("change", render);
    dest.addEventListener("change", render);
  });

  /* Contact: preselect destination from ?destino= */
  (function () {
    var sel = document.querySelector("[data-destino-select]");
    if (!sel) return;
    var params = new URLSearchParams(window.location.search);
    var dest = params.get("destino");
    if (dest) {
      sel.value = dest;
      try { sel.dispatchEvent(new Event("change", { bubbles: true })); } catch (e) {}
    }
  })();

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

    function splitFeat(text) {
      var t = String(text || ""), title = t, desc = "";
      if (t.indexOf(": ") > -1) {
        var a = t.split(": "); title = a.shift(); desc = a.join(": ");
      } else if (t.indexOf(" — ") > -1) {
        var b = t.split(" — "); title = b.shift(); desc = b.join(" — ");
      } else if (t.indexOf(". ") > -1 && t.length > 60) {
        var c = t.split(". "); title = c.shift().replace(/\.$/, ""); desc = c.join(". ").replace(/\.$/, "");
      }
      return { title: title, desc: desc };
    }

    function render(item, push) {
      if (!item) return;
      root.setAttribute("data-active", item.slug);
      root.querySelectorAll("[data-svc-slug]").forEach(function (a) {
        var on = a.getAttribute("data-svc-slug") === item.slug;
        a.classList.toggle("bg-color", on);
        a.classList.toggle("text-light", on);
        a.classList.toggle("bg-light", !on);
        a.classList.toggle("relative", on);
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
        feats.innerHTML = (item.benefits || []).map(function (b, i) {
          var f = splitFeat(b);
          return '<div class="col-lg-6">' +
            '<div class="relative">' +
            '<i class="abs fs-40 p-4 bg-dark rounded-1 text-light fa-solid fa-check"></i>' +
            '<div class="ps-100 ms-4"><h4>' + esc(f.title) + "</h4>" +
            (f.desc ? "<p>" + esc(f.desc) + "</p>" : "") +
            "</div></div></div>";
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
