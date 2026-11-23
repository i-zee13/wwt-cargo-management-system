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
})();
