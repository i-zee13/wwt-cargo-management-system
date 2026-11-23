/* =============================================================
   World Wide Commerce — main.js
   Vanilla JS. No dependencies, no jQuery.
   Modules: sticky header, mobile nav, dropdowns, scroll reveal,
            counters, accordion, tracking form, back-to-top,
            current-year, form validation
   ============================================================= */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- 1. Sticky header shadow ---------------------- */
  function initStickyHeader() {
    var header = document.querySelector('.site-header');
    if (!header) return;
    var onScroll = function () {
      header.classList.toggle('is-stuck', window.scrollY > 8);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---------- 2. Mobile nav toggle ------------------------- */
  function initMobileNav() {
    var header = document.querySelector('.site-header');
    var toggle = document.querySelector('.nav-toggle');
    if (!header || !toggle) return;

    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });

    // Close when a real link is tapped
    header.querySelectorAll('.nav-menu a').forEach(function (a) {
      a.addEventListener('click', function () {
        header.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && header.classList.contains('nav-open')) {
        header.classList.remove('nav-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        toggle.focus();
      }
    });
  }

  /* ---------- 3. Dropdown menus (click + hover) ------------ */
  function initDropdowns() {
    document.querySelectorAll('.nav-menu > li > button').forEach(function (btn) {
      var li = btn.parentElement;
      btn.setAttribute('aria-expanded', 'false');
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var wasOpen = li.classList.contains('open');
        // close siblings
        document.querySelectorAll('.nav-menu > li.open').forEach(function (o) {
          o.classList.remove('open');
          var b = o.querySelector('button');
          if (b) b.setAttribute('aria-expanded', 'false');
        });
        if (!wasOpen) {
          li.classList.add('open');
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });
    document.addEventListener('click', function () {
      document.querySelectorAll('.nav-menu > li.open').forEach(function (o) {
        o.classList.remove('open');
        var b = o.querySelector('button');
        if (b) b.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ---------- 4. Scroll reveal ----------------------------- */
  function initReveal() {
    var targets = document.querySelectorAll('[data-reveal],[data-stagger]');
    if (!targets.length) return;

    if (reduceMotion || !('IntersectionObserver' in window)) {
      targets.forEach(function (t) { t.classList.add('in'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    targets.forEach(function (t) { io.observe(t); });
  }

  /* ---------- 5. Animated counters ------------------------- */
  function animateCount(el) {
    var target = parseFloat(el.dataset.count);
    var decimals = (el.dataset.count.split('.')[1] || '').length;
    var duration = 1700;
    var start = null;

    if (reduceMotion) {
      el.textContent = target.toFixed(decimals);
      return;
    }

    function frame(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / duration, 1);
      // easeOutExpo
      var eased = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);
      el.textContent = (target * eased).toFixed(decimals);
      if (p < 1) requestAnimationFrame(frame);
      else el.textContent = target.toFixed(decimals);
    }
    requestAnimationFrame(frame);
  }

  function initCounters() {
    var counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;
    if (!('IntersectionObserver' in window)) {
      counters.forEach(animateCount);
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { animateCount(e.target); io.unobserve(e.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { io.observe(c); });
  }

  /* ---------- 6. Accordion -------------------------------- */
  function initAccordion() {
    document.querySelectorAll('.acc-trigger').forEach(function (btn) {
      var item = btn.closest('.acc-item');
      var panel = item.querySelector('.acc-panel');
      btn.setAttribute('aria-expanded', 'false');

      btn.addEventListener('click', function () {
        var isOpen = item.classList.contains('open');
        // Single-open behaviour within the same accordion group
        var group = item.closest('.accordion');
        if (group) {
          group.querySelectorAll('.acc-item.open').forEach(function (other) {
            if (other !== item) {
              other.classList.remove('open');
              other.querySelector('.acc-panel').style.maxHeight = null;
              other.querySelector('.acc-trigger').setAttribute('aria-expanded', 'false');
            }
          });
        }
        if (isOpen) {
          item.classList.remove('open');
          panel.style.maxHeight = null;
          btn.setAttribute('aria-expanded', 'false');
        } else {
          item.classList.add('open');
          panel.style.maxHeight = panel.scrollHeight + 'px';
          btn.setAttribute('aria-expanded', 'true');
        }
      });
    });

    // Recalculate open panel heights on resize
    window.addEventListener('resize', function () {
      document.querySelectorAll('.acc-item.open .acc-panel').forEach(function (p) {
        p.style.maxHeight = p.scrollHeight + 'px';
      });
    });
  }

  /* ---------- 7. Tracking form ---------------------------- */
  /* Sends the code to the existing WWC tracking system.
     Change TRACK_BASE if your endpoint differs.            */
  var TRACK_BASE = 'https://wwcsys.worldwidecommerce.us';

  function initTrackForm() {
    document.querySelectorAll('[data-track-form]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var input = form.querySelector('input[name="codigo"]');
        var code = (input.value || '').trim();
        if (!code) {
          input.focus();
          input.setAttribute('aria-invalid', 'true');
          return;
        }
        input.removeAttribute('aria-invalid');
        window.open(TRACK_BASE + '/?tracking=' + encodeURIComponent(code), '_blank', 'noopener');
      });
    });
  }

  /* ---------- 8. Back to top ------------------------------ */
  function initBackToTop() {
    var btn = document.querySelector('.fab--top');
    if (!btn) return;
    var onScroll = function () {
      btn.classList.toggle('show', window.scrollY > 600);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* ---------- 9. Current year ----------------------------- */
  function initYear() {
    var y = new Date().getFullYear();
    document.querySelectorAll('[data-year]').forEach(function (el) {
      el.textContent = y;
    });
  }

  /* ---------- 10. Contact form (front-end validation) ----- */
  /* NOTE: this only validates. Wire the POST to your backend
     or a service like Formspree in the action attribute.    */
  function initContactForm() {
    var form = document.querySelector('[data-contact-form]');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      var ok = true;
      form.querySelectorAll('[required]').forEach(function (f) {
        var valid = f.type === 'email'
          ? /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(f.value.trim())
          : f.value.trim().length > 0;
        f.setAttribute('aria-invalid', valid ? 'false' : 'true');
        f.style.borderColor = valid ? '' : 'var(--coral)';
        if (!valid && ok) { f.focus(); ok = false; }
      });
      if (!ok) e.preventDefault();
    });
  }

  /* ---------- 11. Lazy-load native fallback --------------- */
  function initLazy() {
    if ('loading' in HTMLImageElement.prototype) return;
    document.querySelectorAll('img[loading="lazy"][data-src]').forEach(function (img) {
      img.src = img.dataset.src;
    });
  }

  /* ---------- Boot ---------------------------------------- */
  function boot() {
    initStickyHeader();
    initMobileNav();
    initDropdowns();
    initReveal();
    initCounters();
    initAccordion();
    initTrackForm();
    initBackToTop();
    initYear();
    initContactForm();
    initLazy();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
