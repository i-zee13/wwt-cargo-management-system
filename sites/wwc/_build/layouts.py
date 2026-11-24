# -*- coding: utf-8 -*-
"""Reusable bilingual layout (Laravel @extends('layouts.app') equivalent)."""
import html
import json
from urllib.parse import quote

from config import SITE, OFFICES, NAV
from services import SERVICES
from i18n import (LOCALES, get_locale, load_ui, t, asset, page_href,
                  switch_href, other_locale, abs_url)

NAV_KEYS = {
    "index.html": "home",
    "servicios.html": "services",
    "nosotros.html": "about",
    "presencia.html": "presence",
    "donde-comprar.html": "stores",
    "rastreo.html": "tracking",
    "preguntas-frecuentes.html": "faq",
    "contacto.html": "contact",
}

ROBOTS_DEFAULT = "index,follow,max-image-preview:large"


def svc_url(s):
    return "servicio-%s.html" % s["slug"]


def svc_name(s, locale):
    if get_locale(locale).code == "en":
        return s.get("nav_en") or s["nav"]
    return s["nav"]


def wa_link(locale, text=None, region=None):
    text = text or t(load_ui(locale), "wa_text")
    num = SITE["whatsapp_china"] if region == "china" else SITE["whatsapp"]
    return "https://wa.me/%s?text=%s" % (num, quote(text))


def lang_switcher(locale, current_file):
    loc = get_locale(locale)
    ui = load_ui(loc)
    href = html.escape(switch_href(current_file, loc))
    label = html.escape(t(ui, "lang.label"))
    items = []
    for code in LOCALES:
        if code == loc.code:
            items.append('<a href="%s" class="active" hreflang="%s" lang="%s" aria-current="true" title="%s">%s</a>'
                         % (html.escape(page_href(current_file, loc)), code, code,
                            html.escape(t(ui, "lang." + code)), code.upper()))
        else:
            items.append('<a href="%s" hreflang="%s" lang="%s" title="%s">%s</a>'
                         % (href, code, code, html.escape(t(ui, "lang." + code)), code.upper()))
    return '<div class="wwc-lang" role="group" aria-label="%s">%s</div>' % (
        label, "".join(items))


def topbar(locale, current_file):
    loc = get_locale(locale)
    ui = load_ui(loc)
    e = lambda key: html.escape(t(ui, key))
    return f"""
        <div class="wwc-topbar">
            <div class="container">
                <div class="wwc-topbar-inner">
                    <div class="wwc-topbar-left">
                        <a href="tel:{SITE['phone_raw']}"><i class="fa-solid fa-phone"></i><span>{SITE['phone_display']}</span></a>
                        <a href="mailto:{SITE['email']}"><i class="fa-solid fa-envelope"></i><span>{SITE['email']}</span></a>
                    </div>
                    <div class="wwc-topbar-right">
                        <a href="{SITE['login_url']}">{e('login')}</a>
                        <a href="{SITE['register_url']}">{e('register_free')}</a>
                        {lang_switcher(loc, current_file)}
                    </div>
                </div>
            </div>
        </div>
"""


# ══════════════════════════════════════════════════════════════ HEAD
def head(locale="es", *, title, desc, url="index.html", keywords="", og_type="website",
         schema=None, canonical=None, noindex=False, og_image=None):
    loc = get_locale(locale)
    ui = load_ui(loc)
    alt = get_locale(other_locale(loc))
    canonical = canonical or abs_url(SITE["domain"], url, loc)
    robots = "noindex,follow" if noindex else ROBOTS_DEFAULT
    a = lambda p: html.escape(asset(p, loc))
    og_img = og_image or (SITE["domain"].rstrip("/") + "/assets/img/og-image.png")
    og_alt = html.escape(t(ui, "og_alt"))
    schema_html = ""
    if schema:
        for blob in (schema if isinstance(schema, list) else [schema]):
            schema_html += '\n<script type="application/ld+json">%s</script>' % json.dumps(
                blob, ensure_ascii=False, separators=(",", ":"))
    hreflang = ""
    if not noindex:
        for code in LOCALES:
            hreflang += '<link rel="alternate" hreflang="%s" href="%s">\n' % (
                code, abs_url(SITE["domain"], url, code))
        hreflang += '<link rel="alternate" hreflang="x-default" href="%s">\n' % abs_url(
            SITE["domain"], url, "es")
    kw = '<meta name="keywords" content="%s">\n' % html.escape(keywords) if keywords else ""
    return f"""<!DOCTYPE html>
<html lang="{loc.html_lang}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{html.escape(title)}</title>
<meta name="description" content="{html.escape(desc)}">
{kw}<meta name="author" content="{SITE['legal']}">
<meta name="application-name" content="{SITE['name']}">
<meta name="robots" content="{robots}">
<meta name="googlebot" content="{robots}">
<meta name="referrer" content="strict-origin-when-cross-origin">
<meta name="format-detection" content="telephone=yes">
<meta name="color-scheme" content="light">
<link rel="canonical" href="{canonical}">
{hreflang}
<!-- Open Graph -->
<meta property="og:type" content="{og_type}">
<meta property="og:site_name" content="{SITE['name']}">
<meta property="og:locale" content="{loc.og_locale}">
<meta property="og:locale:alternate" content="{alt.og_locale}">
<meta property="og:title" content="{html.escape(title)}">
<meta property="og:description" content="{html.escape(desc)}">
<meta property="og:url" content="{canonical}">
<meta property="og:image" content="{og_img}">
<meta property="og:image:secure_url" content="{og_img}">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{og_alt}">
<link rel="image_src" href="{og_img}">

<!-- Twitter / X -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{html.escape(title)}">
<meta name="twitter:description" content="{html.escape(desc)}">
<meta name="twitter:image" content="{og_img}">
<meta name="twitter:image:alt" content="{og_alt}">

<!-- Geo -->
<meta name="geo.region" content="US-FL">
<meta name="geo.placename" content="Miami">
<meta name="ICBM" content="25.7959,-80.2871">

<link rel="icon" href="{a('assets/img/brand/favicon.svg')}" type="image/svg+xml">
<link rel="alternate icon" href="{a('favicon.ico')}">
<link rel="apple-touch-icon" sizes="180x180" href="{a('assets/img/brand/apple-touch-icon.png')}">
<link rel="manifest" href="{a('site.webmanifest')}">
<meta name="theme-color" content="#101010">
<meta name="msapplication-TileColor" content="#101010">

<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

<link rel="stylesheet" href="{a('assets/lx/css/bootstrap.min.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/plugins.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/swiper.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/swiper-custom-1.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/style.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/coloring.css')}">
<link rel="stylesheet" href="{a('assets/lx/css/scheme-01.css')}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{a('assets/lx/css/wwc.css')}?v=15">{schema_html}
</head>
<body>
<a class="skip-link" href="#content">{html.escape(t(ui, 'skip_content'))}</a>
<div id="wrapper">
<div id="de-loader"></div>
<a href="#" id="back-to-top"></a>
"""


# ══════════════════════════════════════════════════════════════ HEADER
def header(locale="es", current_file="index.html"):
    loc = get_locale(locale)
    ui = load_ui(loc)
    ph = lambda f: html.escape(page_href(f, loc))
    items = ""
    for n in NAV:
        label = html.escape(t(ui, "nav." + NAV_KEYS[n["href"]], n["label"]))
        if n.get("children") == "SERVICES":
            links = "".join(
                '<li><a href="%s">%s</a></li>' % (ph(svc_url(s)), html.escape(svc_name(s, loc)))
                for s in SERVICES)
            items += f"""                                    <li class="menu-item-has-children has-child"><a class="menu-item" href="{ph(n['href'])}">{label}</a><span></span>
                                        <ul>
                                            <li><a href="{ph('servicios.html')}">{html.escape(t(ui, 'nav.all_services'))}</a></li>
                                            {links}
                                        </ul>
                                    </li>
"""
        else:
            items += f'                                    <li><a class="menu-item" href="{ph(n["href"])}">{label}</a></li>\n'

    logo = html.escape(asset("assets/img/brand/logo-lockup.png", loc))
    register = html.escape(t(ui, "register_free"))
    return f"""
        <div class="wwc-header-stick">
        {topbar(loc, current_file)}
        <header class="header-static transparent">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="de-flex sm-pt10">
                            <div class="de-flex-col">
                                <div id="logo">
                                    <a href="{ph('index.html')}" class="wwc-lockup" aria-label="{html.escape(t(ui, 'brand_home_aria'))}">
                                        <img class="logo-main" src="{logo}" alt="World Wide Commerce">
                                        <img class="logo-scroll" src="{logo}" alt="">
                                        <img class="logo-mobile" src="{logo}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="de-flex-col header-col-mid">
                                <ul id="mainmenu">
{items}                                </ul>
                            </div>
                            <div class="de-flex-col">
                                <div class="menu_side_area">
                                    <a href="{SITE['register_url']}" class="btn-main btn-line fx-slide" data-hover="{register}"><span>{register}</span></a>
                                    <span id="menu-btn"></span>
                                </div>
                                <div id="btn-extra"><span></span><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        </div>
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>
"""


# ══════════════════════════════════════════════════════════════ FOOTER
def footer(locale="es", current_file="index.html"):
    loc = get_locale(locale)
    ui = load_ui(loc)
    ph = lambda f: html.escape(page_href(f, loc))
    e = lambda key: html.escape(t(ui, key))
    svc_links = "".join('<li><a href="%s">%s</a></li>' % (ph(svc_url(s)), html.escape(svc_name(s, loc)))
                        for s in SERVICES[:6])
    extra_svcs = "".join("<li>%s</li>" % html.escape(svc_name(s, loc)) for s in SERVICES[:8])
    miami = html.escape(", ".join(OFFICES[0]["lines"][:2]))
    logo = html.escape(asset("assets/img/brand/logo-lockup.png", loc))
    arrow = html.escape(asset("assets/lx/img/up-right-arrow.webp", loc))
    wa = wa_link(loc)
    company = [
        ("index.html", e("nav.home")),
        ("nosotros.html", e("nav.about")),
        ("presencia.html", e("nav.presence")),
        ("donde-comprar.html", e("nav.stores")),
        ("rastreo.html", e("nav.tracking")),
        ("preguntas-frecuentes.html", e("nav.faq")),
        ("contacto.html", e("nav.contact")),
        ("terminos.html", e("footer.terms")),
        ("privacidad.html", e("footer.privacy")),
    ]
    company_links = "\n".join(
        '                                        <li><a href="%s">%s</a></li>' % (ph(f), label)
        for f, label in company)
    scripts = "\n".join(
        '<script src="%s"></script>' % html.escape(
            asset(p, loc) + ("?v=10" if p.endswith(("custom-marquee.js", "custom-swiper-1.js", "wwc.js")) else "")
        ) for p in (
            "assets/lx/js/plugins.js", "assets/lx/js/designesia.js", "assets/lx/js/swiper.js",
            "assets/lx/js/custom-swiper-1.js", "assets/lx/js/custom-marquee.js", "assets/js/wwc.js"))
    return f"""        </div>

        <footer class="text-light section-dark">
            <div class="container">
                <div class="row g-4 justify-content-between">
                    <div class="col-md-6">
                        <a href="{ph('index.html')}" class="wwc-lockup d-inline-flex mb-3" aria-label="{e('brand_home_aria')}">
                            <img src="{logo}" class="wwc-logo" alt="World Wide Commerce">
                        </a>
                        <div class="spacer-single"></div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="widget">
                                    <h5>{e('footer.services')}</h5>
                                    <ul>
                                        {svc_links}
                                        <li><a href="{ph('servicios.html')}">{e('common.see_all')}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget">
                                    <h5>{e('footer.company')}</h5>
                                    <ul>
{company_links}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="social-icons mb-sm-30">
                            <a href="{SITE['facebook']}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="{SITE['instagram']}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="{wa}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="mailto:{SITE['email']}" aria-label="{e('labels.email_aria')}"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2>{e('footer.contact')}</h2>
                            <img src="{arrow}" class="w-60px op-5" alt="">
                        </div>
                        <div class="widget">
                            <div class="op-5 fs-15">{e('labels.email')}</div>
                            <h3><a href="mailto:{SITE['email']}">{SITE['email']}</a></h3>
                            <div class="spacer-20"></div>
                            <div class="op-5 fs-15">{e('labels.phone')}</div>
                            <h3><a href="tel:{SITE['phone_raw']}">{SITE['phone_display']}</a></h3>
                            <div class="spacer-20"></div>
                            <div class="op-5 fs-15">{e('labels.miami_office')}</div>
                            <h3>{miami}</h3>
                            <div class="spacer-20"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="subfooter">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            © <span data-year>2026</span> {SITE['legal']}. {e('footer.rights')}
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div id="extra-wrap" class="text-light">
        <div id="btn-close"><span></span><span></span></div>
        <div id="extra-content">
            <img src="{logo}" class="wwc-logo" alt="World Wide Commerce">
            <div class="spacer-30-line"></div>
            <h5>{e('footer.services')}</h5>
            <ul class="ul-check">{extra_svcs}</ul>
            <div class="spacer-30-line"></div>
            <h5>{e('footer.contact')}</h5>
            <div><i class="fa-solid fa-phone me-2 op-5"></i><a href="tel:{SITE['phone_raw']}">{SITE['phone_display']}</a></div>
            <div><i class="fa-solid fa-location-dot me-2 op-5"></i>{miami}</div>
            <div><i class="fa-solid fa-envelope me-2 op-5"></i><a href="mailto:{SITE['email']}">{SITE['email']}</a></div>
            <div class="spacer-30-line"></div>
            <h5>{e('nav.about')}</h5>
            <p>{e('side_about')}</p>
            <div class="social-icons">
                <a href="{SITE['facebook']}" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="{SITE['instagram']}" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i></a>
                <a href="{wa}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>

{scripts}
</body>
</html>
"""


# ══════════════════════════════════════════════════════════════ RENDER
def render(content, locale="es", current_file="index.html", **meta):
    """head + header + content + footer. meta: title, desc, url, keywords,
    og_type, schema, canonical, noindex."""
    loc = get_locale(locale)
    meta.setdefault("url", current_file)
    return (head(loc, **meta)
            + header(loc, current_file)
            + content
            + footer(loc, current_file))
