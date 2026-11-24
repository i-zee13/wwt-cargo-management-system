# -*- coding: utf-8 -*-
"""
World Wide Commerce — static site generator (bilingual ES/EN).

Run:  python3 _build/build.py
Output: ES pages in the project root, EN pages in en/, plus sitemap.xml / robots.txt

Everything comes from config.py, services.py, content_en.py, lang/*.py and layouts.py.
"""
import os, sys, json, datetime, html, re

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)
sys.path.insert(0, HERE)

from config import SITE, OFFICES as OFFICES_ES, NAV, PROCESS as PROCESS_ES, STATS as STATS_ES, \
    STORES, FAQS as FAQS_ES, WHY_US as WHY_US_ES, ICONS
from services import SERVICES
import content_en
from i18n import LOCALES, get_locale, load_ui, load_home, t, asset, abs_url, page_href
from layouts import render, svc_url, svc_name, wa_link


def ph(filename, locale="es"):
    return html.escape(page_href(filename, locale))

TODAY = datetime.date.today().isoformat()


# ══════════════════════════════════════════════════════════════ HELPERS
def is_en(locale):
    return get_locale(locale).code == "en"


def tr(locale):
    en = is_en(locale)
    return lambda es, en_: en_ if en else es


def sf(s, key, locale):
    """Service field for the locale, falling back to Spanish."""
    if is_en(locale):
        return s.get(key + "_en") or s[key]
    return s[key]


def content(locale):
    if is_en(locale):
        offices = []
        for o, e in zip(OFFICES_ES, content_en.OFFICES):
            offices.append(dict(o, city=e["city"], label=e["label"], lines=e["lines"]))
        return {"PROCESS": content_en.PROCESS, "STATS": content_en.STATS,
                "WHY_US": content_en.WHY_US, "FAQS": content_en.FAQS, "OFFICES": offices}
    return {"PROCESS": PROCESS_ES, "STATS": STATS_ES, "WHY_US": WHY_US_ES,
            "FAQS": FAQS_ES, "OFFICES": OFFICES_ES}


def icon(name, cls=""):
    svg = ICONS.get(name, "")
    if cls and svg:
        svg = svg.replace("<svg ", '<svg class="%s" ' % cls, 1)
    return svg


def img(path, locale):
    return html.escape(asset(path, locale))


# ══════════════════════════════════════════════════════════════ PARTIALS
def crumbs(trail, locale="es"):
    out = ['<div class="crumb">']
    for i, (label, href) in enumerate(trail):
        if i:
            out.append('<span class="sep">/</span>')
        if href:
            out.append('<a href="%s">%s</a>' % (ph(href, locale), html.escape(label)))
        else:
            out.append('<span aria-current="page">%s</span>' % html.escape(label))
    out.append("</div>")
    return "".join(out)


def page_hero(h1, lead, trail, locale="es", bg=None, extra_class=""):
    bg = asset(bg or "assets/lx/img/l1.webp", locale)
    cls = ("text-light relative overflow-hidden " + extra_class).strip()
    lead_html = f'<p class="lead wow fadeInUp" data-wow-delay=".2s">{lead}</p>' if lead else ""
    return f"""
<section id="subheader" class="{cls}" data-bgimage="url({bg})">
  <div class="container relative z-2">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <h1 class="split">{h1}</h1>
        {lead_html}
        <div class="wow fadeInUp" data-wow-delay=".15s">{crumbs(trail, locale)}</div>
      </div>
    </div>
  </div>
  <div class="de-overlay"></div>
</section>
"""


def service_card(s, locale="es", badge=True):
    icons = ["1.png", "2.png", "3.png", "4.png"]
    idx = next(i for i, o in enumerate(SERVICES) if o["slug"] == s["slug"])
    ui = load_ui(locale)
    badge_html = '<span class="card-badge">%s</span>' % html.escape(t(ui, "common.new_badge")) \
        if (badge and s["new"]) else ""
    delay = ".%ss" % (idx % 4)
    return f"""      <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{delay}">
        <a href="{ph(svc_url(s), locale)}" class="d-block hover relative">
          {badge_html}
          <img src="{img('assets/lx/img/' + icons[idx % 4], locale)}" class="w-70px mb-3" alt="">
          <h4>{html.escape(svc_name(s, locale))}</h4>
          <p>{html.escape(sf(s, 'blurb', locale))}</p>
        </a>
      </div>
"""


def trust_strip(locale="es"):
    labels = load_home(locale)["trust"]
    bits = "".join('<div class="col-6 col-md-3"><h5 class="mb-0">%s</h5></div>' % html.escape(x) for x in labels)
    return '<section class="pt-40 pb-40"><div class="container"><div class="row g-3 text-center">%s</div></div></section>' % bits


def stats_band(locale="es"):
    cells = ""
    for i, st in enumerate(content(locale)["STATS"]):
        num = st["num"]
        delay = ".%ss" % (i * 2)
        # Animate whole numbers with timer; keep decimals/stars as plain text
        if num.replace(".", "", 1).isdigit() and "." not in num:
            num_html = '<span class="timer" data-to="%s" data-speed="2500">0</span>%s' % (
                html.escape(num), html.escape(st["suffix"]))
        else:
            num_html = "%s%s" % (html.escape(num), html.escape(st["suffix"]))
        cells += f"""      <div class="col-md-3 text-center">
        <div class="de_count wow fadeInUp" data-wow-delay="{delay}">
          <h2 class="mb-1 fs-40">{num_html}</h2>
          <p class="mb-0">{html.escape(st['label'])}</p>
        </div>
      </div>
"""
    return f"""
<section class="bg-dark text-light">
  <div class="container">
    <div class="row g-4">
{cells}    </div>
  </div>
</section>
"""


def _pretty_href(href, locale):
    if not href:
        return href
    if href.startswith("http://") or href.startswith("https://") or href.startswith("mailto:") or href.startswith("tel:") or href.startswith("wa.me") or "wa.me" in href:
        return href
    if href.endswith(".html") or href in ("index.html", "index"):
        return page_href(href, locale)
    return href


def quote_split(locale="es"):
    """Left photo + right quote slider (homepage / marketing)."""
    h = load_home(locale)
    quotes = h.get("quotes") or []
    if not quotes:
        return ""
    slides = ""
    for q in quotes:
        slides += f"""
                        <div class="item">
                            <blockquote class="wwc-quote-text">{html.escape(q['text'])}</blockquote>
                            <div class="wwc-quote-by">{html.escape(q['by'])}</div>
                        </div>"""
    photo = img("assets/lx/img/testimonial-delivery.webp", locale)
    _ = tr(locale)
    alt = html.escape(_("Cliente recibiendo su paquete WWC", "Customer receiving their WWC package"))
    return f"""
            <section class="mx-3 mb-3 p-0">
                <div class="container-fluid px-0">
                    <div class="row g-0 rounded-1 overflow-hidden wwc-quote-split">
                        <div class="col-lg-6">
                            <div class="wwc-quote-photo">
                                <img src="{photo}" alt="{alt}" width="1000" height="1000" loading="lazy">
                            </div>
                        </div>
                        <div class="col-lg-6 bg-dark text-light">
                            <div class="wwc-quote-panel">
                                <div class="wwc-quote-mark" aria-hidden="true">“</div>
                                <div id="wwc-quotes" class="owl-carousel owl-theme">
{slides}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
"""


def cta_band(locale="es", title=None, text=None, primary=None, secondary=None):
    ui = load_ui(locale)
    title = title or t(ui, "cta.ready_title")
    text = text or t(ui, "cta.ready_text")
    p_label, p_href = primary or (t(ui, "register_free"), None)
    s_label, s_href = secondary or (t(ui, "talk_whatsapp"), None)
    p_href = _pretty_href(p_href or SITE["register_url"], locale)
    s_href = _pretty_href(s_href or wa_link(locale), locale)
    s_blank = ' target="_blank" rel="noopener"' if s_href.startswith("http") else ""
    return f"""
<section class="bg-color text-light pt-50 pb-50">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-8">
        <h3 class="mb-2 fs-32 split">{html.escape(title)}</h3>
        <p class="mb-0 wow fadeInUp" data-wow-delay=".15s">{html.escape(text)}</p>
      </div>
      <div class="col-md-4 text-lg-end">
        <a class="btn-main fx-slide me-2 mb-2" href="{html.escape(p_href)}" data-hover="{html.escape(p_label)}"><span>{html.escape(p_label)}</span></a>
        <a class="btn-main btn-line fx-slide" href="{html.escape(s_href)}"{s_blank} data-hover="{html.escape(s_label)}"><span>{html.escape(s_label)}</span></a>
      </div>
    </div>
  </div>
</section>
"""


def accordion(items, start_id=0):
    bits = []
    for i, it in enumerate(items):
        bits.append(f"""<div class="accordion-section-title" data-tab="#acc{start_id+i}">{html.escape(it['q'])}</div>
<div class="accordion-section-content" id="acc{start_id+i}">{it['a']}</div>""")
    return '<div class="accordion"><div class="accordion-section">' + "".join(bits) + "</div></div>"


def track_widget(locale="es"):
    ui = load_ui(locale)
    e = lambda k: html.escape(t(ui, k))
    return f"""      <div class="h-100 relative bg-color p-5 text-light rounded-1">
        <div class="subtitle">{e('track.subtitle')}</div>
        <h2>{e('track.title')}</h2>
        <p>{e('track.lead')}</p>
        <form data-track-form>
          <div class="row align-items-center">
            <div class="col-md-8">
              <input class="form-control" name="codigo" type="text" placeholder="{e('track.placeholder')}" required>
            </div>
            <div class="col-md-4">
              <button class="btn-main bg-white text-dark w-100" type="submit">{e('track.button')}</button>
            </div>
          </div>
        </form>
      </div>
"""


def write(filename, content_, locale="es"):
    out_dir = get_locale(locale).out_dir
    rel = os.path.join(out_dir, filename) if out_dir else filename
    path = os.path.join(ROOT, rel)
    parent = os.path.dirname(path)
    if parent:
        os.makedirs(parent, exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        f.write(content_)
    return rel, len(content_)


# ══════════════════════════════════════════════════════════════ SCHEMA
def org_schema(locale="es"):
    _ = tr(locale)
    return {
      "@context": "https://schema.org", "@type": "Organization",
      "@id": SITE["domain"] + "/#organization",
      "name": SITE["name"], "legalName": SITE["legal"], "url": SITE["domain"] + "/",
      "logo": {"@type": "ImageObject", "url": SITE["domain"] + "/assets/img/brand/logo.svg"},
      "description": _("Operador logístico internacional con infraestructura propia en Miami y China. Casillero, importación, exportación, fulfillment y logística a medida.",
                       "International logistics operator with its own infrastructure in Miami and China. Mailbox, import, export, fulfillment and custom logistics."),
      "foundingDate": SITE["founded"],
      "telephone": SITE["phone_display"], "email": SITE["email"],
      "sameAs": [SITE["facebook"], SITE["instagram"]],
      "address": {"@type": "PostalAddress", "streetAddress": OFFICES_ES[0]["street"],
                  "addressLocality": "Miami", "addressRegion": "FL",
                  "postalCode": "33122", "addressCountry": "US"},
      "aggregateRating": {"@type": "AggregateRating", "ratingValue": SITE["google_rating"],
                          "reviewCount": SITE["google_reviews"], "bestRating": "5"},
      "areaServed": [{"@type": "Country", "name": "Argentina"}, {"@type": "Country", "name": "Paraguay"},
                     {"@type": "Country", "name": "United States"}, {"@type": "Country", "name": "China"}],
      "contactPoint": {"@type": "ContactPoint", "telephone": SITE["phone_display"],
                       "contactType": "customer service", "areaServed": ["AR", "PY", "US", "CN"],
                       "availableLanguage": ["Spanish", "English", "Portuguese"]},
    }


def localbiz_schema(locale="es"):
    return {
      "@context": "https://schema.org", "@type": "LocalBusiness",
      "@id": SITE["domain"] + "/#miami",
      "name": SITE["name"] + " — Miami", "image": SITE["domain"] + "/assets/img/og-image.png",
      "url": abs_url(SITE["domain"], "", locale), "telephone": SITE["phone_display"], "email": SITE["email"],
      "priceRange": "$$",
      "address": {"@type": "PostalAddress", "streetAddress": OFFICES_ES[0]["street"],
                  "addressLocality": "Miami", "addressRegion": "FL", "postalCode": "33122", "addressCountry": "US"},
      "parentOrganization": {"@id": SITE["domain"] + "/#organization"},
    }


def breadcrumb_schema(trail, locale="es"):
    items = []
    for i, (label, href) in enumerate(trail, 1):
        e = {"@type": "ListItem", "position": i, "name": label}
        if href:
            e["item"] = abs_url(SITE["domain"], href, locale)
        items.append(e)
    return {"@context": "https://schema.org", "@type": "BreadcrumbList", "itemListElement": items}


def faq_schema(faqs):
    return {"@context": "https://schema.org", "@type": "FAQPage", "mainEntity": [
      {"@type": "Question", "name": f["q"],
       "acceptedAnswer": {"@type": "Answer", "text": re.sub(r"<[^>]+>", "", f["a"])}} for f in faqs]}


def service_schema(s, locale="es"):
    url = abs_url(SITE["domain"], svc_url(s), locale)
    return {"@context": "https://schema.org", "@type": "Service",
      "name": sf(s, "h1", locale), "description": sf(s, "meta", locale),
      "serviceType": svc_name(s, locale),
      "provider": {"@id": SITE["domain"] + "/#organization"},
      "url": url,
      "areaServed": [{"@type": "Country", "name": "Argentina"}, {"@type": "Country", "name": "Paraguay"}],
      "availableChannel": {"@type": "ServiceChannel", "serviceUrl": url,
                           "servicePhone": SITE["phone_display"]}}


# ══════════════════════════════════════════════════════════════ HOME
def build_home(locale="es"):
    ui = load_ui(locale)
    h = load_home(locale)
    c = content(locale)
    register = html.escape(t(ui, "register_free"))
    read_more = html.escape(t(ui, "common.read_more"))

    slide_html = ""
    for i, sl in enumerate(h["slides"]):
        tags_html = "".join("<div><h6>%s</h6></div>" % html.escape(x) for x in sl["tags"])
        slide_html += f"""
                        <div class="swiper-slide" data-slide-i="{i}">
                            <div class="swiper-inner" data-bgimage="url({img('assets/lx/img/' + sl['img'], locale)})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row gx-5 align-items-center">
                                            <div class="col-lg-6">
                                                <div class="sw-text-wrapper">
                                                    <div class="wwc-slide-kicker anim-order-1" aria-hidden="true"></div>
                                                    <h1 class="text-uppercase anim-order-1">{html.escape(sl['title'])}</h1>
                                                    <div class="anim-order-2">
                                                        <p>{html.escape(sl['text'])}</p>
                                                        <div class="spacer-half"></div>
                                                        <a class="btn-main fx-slide" href="{SITE['register_url']}" data-hover="{register}"><span>{register}</span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="abs w-100 bottom-0 z-2 pb-4 sm-hide">
                                    <div class="container"><div class="d-flex justify-content-between">{tags_html}</div></div>
                                </div>
                                <div class="sw-overlay op-4"></div>
                            </div>
                        </div>"""

    photo_html = ""
    for p in h["photos"]:
        badge_html = ""
        if p["badge"]:
            badge_html = (
                '<div class="abs p-4 m-1 z-2"><div class="p-4 mb-4 bg-color text-light rounded-1 text-center">'
                '<h1 class="fs-84 mb-1">%s</h1><div class="fs-16 lh-1-5">%s</div></div></div>'
                % (p["badge"], html.escape(h["years_label"])))
        photo_html += f"""
                        <div class="col-lg-4 col-md-6 wow scaleIn">
                            <a href="{html.escape(_pretty_href(p['href'], locale))}">
                                <div class="hover rounded-1 relative overflow-hidden text-light">
                                    {badge_html}
                                    <div class="abs p-40 bottom-0 z-3">
                                        <h3 class="border-top pt-3">{html.escape(p['title'])}</h3>
                                        <p class="mb-0 hover-mh-60">{read_more}</p>
                                    </div>
                                    <img src="{img('assets/lx/img/' + p['img'], locale)}" class="w-100 hover-scale-1-2" alt="">
                                    <div class="gradient-edge-bottom h-50 op-5"></div>
                                </div>
                            </a>
                        </div>"""

    icons = ["1.png", "2.png", "3.png", "4.png"]
    svc_html = ""
    for i, s in enumerate(SERVICES[:4]):
        svc_html += f"""
                        <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay=".{i}s">
                            <a href="{ph(svc_url(s), locale)}" class="d-block hover relative">
                                <img src="{img('assets/lx/img/' + icons[i], locale)}" class="w-70px mb-3 hover-jello infinite" alt="">
                                <h4>{html.escape(svc_name(s, locale))}</h4>
                                <p>{html.escape(sf(s, 'blurb', locale))}</p>
                            </a>
                        </div>"""

    steps = ""
    for i, pstep in enumerate(c["PROCESS"]):
        steps += f"""
                        <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay=".{i}s">
                            <h4>{html.escape(pstep['title'])}</h4>
                            <p>{html.escape(pstep['text'])}</p>
                        </div>"""

    why = ""
    for i, item in enumerate(c["WHY_US"]):
        why += f"""
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".{i * 2}s">
                            <div class="relative">
                                <i class="abs fs-40 p-4 bg-dark rounded-1 text-light fa-solid fa-check"></i>
                                <div class="ps-100 ms-4">
                                    <h4>{html.escape(item['title'])}</h4>
                                    <p>{html.escape(item['text'])}</p>
                                </div>
                            </div>
                        </div>"""

    gal = ""
    for g in h["gallery"]:
        gal += f"""
                                    <div class="item">
                                        <a href="{html.escape(_pretty_href(g['href'], locale))}">
                                            <div class="hover rounded-1 relative overflow-hidden text-light">
                                                <div class="abs p-40 bottom-0 z-3 w-50">
                                                    <h3>{html.escape(g['title'])}</h3>
                                                    <p class="mb-0 hover-mh-60">{read_more}</p>
                                                </div>
                                                <img src="{img('assets/lx/img/' + g['img'], locale)}" class="w-100 hover-scale-1-2" alt="">
                                                <div class="gradient-edge-bottom h-50 op-5"></div>
                                            </div>
                                        </a>
                                    </div>"""

    faqs = c["FAQS"]
    names = h["faq"]["groups"]
    groups = [(names[0], faqs[:6]), (names[1], faqs[10:16])]
    tabs_nav = ""
    tabs_body = ""
    for i, (name, items) in enumerate(groups):
        active = ' class="active-tab"' if i == 0 else ""
        tabs_nav += "<li%s>%s</li>" % (active, html.escape(name))
        shown = "" if i else ' style="display:list-item"'
        tabs_body += "<li%s>%s</li>" % (shown, accordion(items, start_id=i * 10))

    stores = "".join(
        (
            '<span class="wwc-store-chip">'
            '<span class="wwc-store-chip__icon">'
            '<img src="https://www.google.com/s2/favicons?domain=%s&amp;sz=128" '
            'alt="" width="32" height="32" loading="lazy" decoding="async">'
            '</span>'
            '<span class="wwc-store-chip__name">%s</span>'
            '</span>'
        ) % (html.escape(dom), html.escape(name))
        for name, dom in STORES)

    rating_text = html.escape(h["rating"]["text"].format(
        rating=SITE["google_rating"], reviews=SITE["google_reviews"]))
    sv, pr, wh = h["services"], h["process"], h["why"]
    st, fq = h["stores"], h["faq"]

    body = f"""
            <section class="text-light no-top no-bottom relative rounded-1 overflow-hidden m-3 sm-mt-90 wwc-hero-slider">
                <div class="mh-800 wwc-hero-frame">
                    <div class="swiper">
                      <div class="swiper-wrapper">
{slide_html}
                      </div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
                      <div class="swiper-pagination wwc-hero-dots"></div>
                      <div class="wwc-hero-progress" aria-hidden="true"><span></span></div>
                    </div>
                </div>
            </section>

            <section class="mx-3 p-0">
                <div class="container-fluid"><div class="row g-3">{photo_html}</div></div>
            </section>

            <section>
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="subtitle wow fadeInUp">{html.escape(sv['subtitle'])}</div>
                            <h2 class="split">{html.escape(sv['title'])}</h2>
                            <p class="wow fadeInUp" data-wow-delay=".2s">{html.escape(sv['lead'])}</p>
                        </div>
                    </div>
                    <div class="row g-4 gx-5">{svc_html}</div>
                    <div class="text-center mt-4">
                        <a class="btn-main" href="{ph('servicios.html', locale)}" data-hover="{html.escape(sv['button'])}"><span>{html.escape(sv['button'])}</span></a>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="subtitle wow fadeInUp">{html.escape(pr['subtitle'])}</div>
                            <h2 class="split">{html.escape(pr['title'])}</h2>
                        </div>
                    </div>
                    <div class="row g-4">{steps}</div>
                </div>
            </section>

            <section class="mx-3 mt-3 p-0">
                <div class="container-fluid">
                    <div class="row g-3">
                        <div class="col-lg-6">{track_widget(locale)}</div>
                        <div class="col-lg-6">
                            <div class="relative">
                                <div class="m-5 p-40 overlay-black-6 bg-blur abs bottom-0 mb-5 rounded-1 text-white z-2 xs-hide">
                                    <p>{rating_text}</p>
                                    <div class="fw-600">World Wide Commerce</div>
                                    <div class="fs-14 op-6">{html.escape(h['rating']['places'])}</div>
                                </div>
                                <div class="bg-light relative overflow-hidden rounded-1">
                                    <img src="{img('assets/lx/img/s4.webp', locale)}" class="w-100" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="subtitle wow fadeInUp">{html.escape(wh['subtitle'])}</div>
                            <h2 class="split">{html.escape(wh['title'])}</h2>
                        </div>
                    </div>
                    <div class="row g-4">{why}</div>
                </div>
            </section>

            <section class="bg-dark text-light pt-0 overflow-hidden wwc-stores-band">
                <div class="wwc-stores-marquee" data-wwc-marquee>{stores}</div>
                <div class="text-center pb-5">
                    <a class="btn-main btn-line" href="{ph('donde-comprar.html', locale)}" data-hover="{html.escape(st['hover'])}"><span>{html.escape(st['button'])}</span></a>
                </div>
            </section>

{stats_band(locale)}

            <section id="section-gallery" class="pt-0 overflow-hidden">
                <div class="container-fluid">
                    <div class="owl-custom-nav menu-float" data-target="#carousel-1">
                        <a class="btn-next"></a>
                        <a class="btn-prev"></a>
                        <div id="carousel-1" class="owl-2-cols-center owl-carousel owl-theme">{gal}</div>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">
                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="subtitle id-color wow fadeInUp">{html.escape(fq['subtitle'])}</div>
                            <h2 class="split">{html.escape(fq['title'])}</h2>
                            <a class="btn-main" href="{ph('preguntas-frecuentes.html', locale)}" data-hover="{html.escape(fq['hover'])}"><span>{html.escape(fq['button'])}</span></a>
                        </div>
                        <div class="col-lg-7">
                            <div class="de-tab">
                                <ul class="d-tab-nav mb-4">{tabs_nav}</ul>
                                <ul class="d-tab-content">{tabs_body}</ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="p-0 overflow-hidden">
                <div class="container">
                    <img src="{img('assets/lx/img/c1.webp', locale)}" class="w-100" alt="">
                </div>
            </section>

{quote_split(locale)}
{cta_band(locale)}
"""
    return render(body, locale, "index.html",
        title=h["meta"]["title"], desc=h["meta"]["desc"], keywords=h["meta"]["keywords"],
        schema=[org_schema(locale), localbiz_schema(locale), {
            "@context": "https://schema.org", "@type": "WebSite",
            "url": abs_url(SITE["domain"], "", locale), "name": SITE["name"],
            "inLanguage": get_locale(locale).html_lang,
            "publisher": {"@id": SITE["domain"] + "/#organization"}}])


# ══════════════════════════════════════════════════════════════ SERVICES INDEX
def build_services_index(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.services"), None)]
    # Cycle logistics photos across the 14 service tiles
    tile_imgs = [
        "s1.webp", "l2.webp", "l4.webp", "s2.webp", "s3.webp", "l1.webp", "s4.webp",
        "l3.webp", "homepage-1.webp", "homepage-2.webp", "testimonial-delivery.webp",
        "homepage-3.webp", "hero-servicios.webp", "homepage-4.webp",
    ]
    tiles = ""
    for i, s in enumerate(SERVICES):
        num = "%02d" % (i + 1)
        flip = " is-flip" if (i // 2) % 2 == 1 else ""
        delay = ".%ss" % (i % 4)
        blurb = html.escape(sf(s, "blurb", locale))
        name = html.escape(svc_name(s, locale))
        href = ph(svc_url(s), locale)
        src = img("assets/lx/img/" + tile_imgs[i % len(tile_imgs)], locale)
        tiles += f"""
      <a class="wwc-svc-tile{flip} wow fadeInUp" href="{href}" data-wow-delay="{delay}">
        <div class="wwc-svc-tile__media">
          <span class="wwc-svc-tile__num" aria-hidden="true">{num}</span>
          <img src="{src}" alt="" width="640" height="480" loading="lazy">
        </div>
        <div class="wwc-svc-tile__body">
          <h3>{name}</h3>
          <p>{blurb}</p>
        </div>
      </a>
"""
    body = page_hero(
        _("Nuestros servicios", "Our Services"),
        "",
        trail, locale,
        bg="assets/lx/img/hero-servicios.webp",
        extra_class="wwc-hero-port m-3 rounded-1 sm-mt-90",
    ) + f"""
<section class="pt-40 pb-40">
  <div class="container-fluid px-3 px-lg-4">
    <div class="wwc-svc-grid">
{tiles}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container container-narrow">
    <div class="section-head center wow fadeInUp">
      <span class="eyebrow">{_("No sabés cuál necesitás", "Not sure which one you need")}</span>
      <h2 class="split">{_("Contanos qué querés mover", "Tell us what you want to move")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".15s">{_("Un mensaje corto alcanza para orientarte sobre el servicio y el costo aproximado.",
                         "A short message is enough to point you to the right service and approximate cost.")}</p>
    </div>
    <div class="text-center wow fadeInUp">
      <a class="btn-main fx-slide" href="{ph('contacto.html', locale)}" data-hover="{_("Pedir asesoramiento", "Get advice")}"><span>{_("Pedir asesoramiento", "Get advice")}</span></a>
    </div>
  </div>
</section>

{cta_band(locale)}
"""
    return render(body, locale, "servicios.html",
        title=_("Servicios de Logística Internacional | World Wide Commerce",
                "International Logistics Services | World Wide Commerce"),
        desc=_("14 servicios de logística internacional: casillero en Miami y China, compras asistidas, importación, exportación, fulfillment, mudanzas y software a medida.",
               "14 international logistics services: mailbox in Miami and China, assisted shopping, import, export, fulfillment, moving and custom software."),
        keywords=_("servicios logisticos internacionales, casillero miami, importacion exportacion, fulfillment miami, logistica argentina",
                   "international logistics services, miami mailbox, import export, miami fulfillment, latin america shipping"),
        schema=[breadcrumb_schema(trail, locale), {
          "@context": "https://schema.org", "@type": "ItemList",
          "name": _("Servicios de World Wide Commerce", "World Wide Commerce services"),
          "itemListElement": [{"@type": "ListItem", "position": i, "name": svc_name(s, locale),
                               "url": abs_url(SITE["domain"], svc_url(s), locale)}
                              for i, s in enumerate(SERVICES, 1)]}])


SVC_TILE_IMGS = [
    "s1.webp", "l2.webp", "l4.webp", "s2.webp", "s3.webp", "l1.webp", "s4.webp",
    "l3.webp", "homepage-1.webp", "homepage-2.webp", "testimonial-delivery.webp",
    "homepage-3.webp", "hero-servicios.webp", "homepage-4.webp",
]


def _svc_payload(s, locale, idx):
    """JSON-ready service detail for client-side sidebar switching."""
    benefits = list(sf(s, "benefits", locale))[:6]
    return {
        "slug": s["slug"],
        "href": page_href(svc_url(s), locale),
        "nav": svc_name(s, locale),
        "h1": sf(s, "h1", locale),
        "blurb": sf(s, "blurb", locale),
        "intro": sf(s, "intro", locale),
        "cta": sf(s, "cta", locale),
        "benefits": benefits,
        "img": "assets/lx/img/" + SVC_TILE_IMGS[idx % len(SVC_TILE_IMGS)],
        "title": sf(s, "seo_title", locale),
        "meta": sf(s, "meta", locale),
    }


# ══════════════════════════════════════════════════════════════ SERVICE DETAIL
def build_service(s, locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    name = svc_name(s, locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.services"), "servicios.html"), (name, None)]
    idx = next(i for i, o in enumerate(SERVICES) if o["slug"] == s["slug"])
    payload = [_svc_payload(o, locale, i) for i, o in enumerate(SERVICES)]
    current = payload[idx]
    feats = "".join(
        '<div class="wwc-svc-feat"><i class="fa-solid fa-check"></i><div><h4>%s</h4></div></div>'
        % html.escape(b) for b in current["benefits"])
    side_nav = "".join(
        '<a class="wwc-svc-nav__item%s" href="%s" data-svc-slug="%s">%s</a>' % (
            " is-active" if o["slug"] == s["slug"] else "",
            html.escape(page_href(svc_url(o), locale)),
            html.escape(o["slug"]),
            html.escape(svc_name(o, locale)))
        for o in SERVICES)
    data_json = json.dumps({
        "assetPrefix": "" if not is_en(locale) else "../",
        "homeCrumb": t(ui, "common.home_crumb"),
        "servicesCrumb": t(ui, "nav.services"),
        "servicesHref": page_href("servicios.html", locale),
        "featuresTitle": _("Ventajas clave", "Key Features"),
        "quoteLabel": _("Pedir cotización", "Request a quote"),
        "quoteHref": page_href("contacto.html", locale),
        "waPrefix": _("Hola, quiero consultar por: ", "Hello, I'd like to ask about: "),
        "waBase": "https://wa.me/%s?text=" % SITE["whatsapp"],
        "items": payload,
    }, ensure_ascii=False).replace("<", "\\u003c")

    body = page_hero(
        html.escape(name),
        "",
        trail, locale,
        bg="assets/lx/img/hero-servicios.webp",
        extra_class="wwc-hero-port m-3 rounded-1 sm-mt-90",
    ) + f"""
<section class="pt-50 pb-50">
  <div class="container">
    <div class="wwc-svc-single" data-svc-single data-active="{html.escape(s['slug'])}">
      <aside class="wwc-svc-nav" aria-label="{_("Servicios", "Services")}">
{side_nav}      </aside>
      <div class="wwc-svc-panel" data-svc-panel>
        <div class="wwc-svc-panel__top">
          <div class="wwc-svc-panel__copy">
            <h2 data-svc-title>{html.escape(current['h1'])}</h2>
            <p data-svc-intro>{html.escape(current['intro'])}</p>
          </div>
          <div class="wwc-svc-panel__media">
            <img data-svc-img src="{img(current['img'], locale)}" alt="" width="640" height="480" loading="eager">
            <div class="wwc-svc-panel__card" data-svc-blurb>{html.escape(current['blurb'])}</div>
          </div>
        </div>
        <h3 class="wwc-svc-panel__features-title">{_("Ventajas clave", "Key Features")}</h3>
        <div class="wwc-svc-feats" data-svc-feats>{feats}</div>
        <div class="wwc-svc-panel__actions">
          <a class="btn-main fx-slide" data-svc-quote href="{ph('contacto.html', locale)}" data-hover="{_("Pedir cotización", "Request a quote")}"><span>{_("Pedir cotización", "Request a quote")}</span></a>
          <a class="btn-main btn-line fx-slide" data-svc-wa href="{wa_link(locale, _('Hola, quiero consultar por: ', "Hello, I'd like to ask about: ") + name)}" target="_blank" rel="noopener" data-hover="WhatsApp"><span>WhatsApp</span></a>
        </div>
      </div>
    </div>
  </div>
</section>
<script type="application/json" id="wwc-services-data">{data_json}</script>

{cta_band(locale)}
"""
    return render(body, locale, svc_url(s),
        title=sf(s, "seo_title", locale), desc=sf(s, "meta", locale),
        keywords=s["kw"] if not is_en(locale) else "", og_type="article",
        schema=[breadcrumb_schema(trail, locale), service_schema(s, locale)])


# ══════════════════════════════════════════════════════════════ NOSOTROS / ABOUT
def build_about(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.about"), None)]
    offices = ""
    for i, o in enumerate(content(locale)["OFFICES"]):
        lines = "<br>".join(html.escape(l) for l in o["lines"])
        offices += f"""      <article class="card wow fadeInUp" data-wow-delay=".{i * 2}s">
        <div class="card-icon">{icon('pin')}</div>
        <h3>{html.escape(o['city'])}</h3>
        <p><strong>{html.escape(o['label'])}</strong><br>{lines}</p>
      </article>
"""
    body = page_hero(
        _("Nosotros", "About Us"),
        "",
        trail, locale,
        bg="assets/lx/img/hero-nosotros.webp",
        extra_class="m-3 rounded-1 sm-mt-90 wwc-hero-team",
    ) + f"""
<section>
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6 order-2 order-lg-1">
        <div class="relative wwc-about-overlap">
          <div class="wwc-about-overlap__main wow scaleIn">
            <img src="{img('assets/lx/img/l1.webp', locale)}" class="w-100 rounded-1" alt="{_("Logística internacional WWC", "WWC international logistics")}" width="800" height="600" loading="lazy">
          </div>
          <img src="{img('assets/lx/img/s1.webp', locale)}" class="wwc-about-overlap__inset rounded-1 soft-shadow wow scaleIn" data-wow-delay=".2s" alt="{_("Operación de carga", "Freight operation")}" width="320" height="400" loading="lazy">
        </div>
      </div>
      <div class="col-lg-6 order-1 order-lg-2">
        <div class="ps-lg-3">
          <div class="subtitle id-color wow fadeInUp" data-wow-delay=".2s">{_("Quiénes somos", "Who we are")}</div>
          <h2 class="split">{_("Movemos carga, conectamos el mundo", "Moving cargo, connecting the world")}</h2>
          <p class="mb-0 wow fadeInUp" data-wow-delay=".5s"><strong>{SITE['legal']}</strong> {_("es un operador logístico con infraestructura propia en Miami y China. Casillero, consolidación, aduana y entrega puerta a puerta — sin intermediarios ni sorpresas en el precio.",
            "is a logistics operator with our own infrastructure in Miami and China. Mailbox, consolidation, customs and door-to-door delivery — no middlemen and no price surprises.")}</p>
        </div>
      </div>

      <div class="spacer-single"></div>

      <div class="row g-4">
        <div class="col-lg-12">
          <h2 class="split mb-0">{_("Nuestra expertise", "Our expertise")}</h2>
        </div>
        <div class="col-lg-6">
          <div class="skill-bar style-2 wow fadeInUp" data-wow-delay=".1s">
            <h5>{_("Casillero y puerta a puerta", "Mailbox and door-to-door")}</h5>
            <div class="de-progress">
              <div class="value">95%</div>
              <div class="progress-bar" data-value="95%"></div>
            </div>
          </div>
          <div class="skill-bar style-2 wow fadeInUp" data-wow-delay=".2s">
            <h5>{_("Aduana e importación", "Customs and import")}</h5>
            <div class="de-progress">
              <div class="value">90%</div>
              <div class="progress-bar" data-value="90%"></div>
            </div>
          </div>
          <div class="skill-bar style-2 wow fadeInUp" data-wow-delay=".3s">
            <h5>{_("Almacenamiento y fulfillment", "Warehousing and fulfillment")}</h5>
            <div class="de-progress">
              <div class="value">85%</div>
              <div class="progress-bar" data-value="85%"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row g-4">
            <div class="col-md-6 text-center">
              <div class="de_count wow fadeInRight" data-wow-delay=".0s">
                <h3 class="fs-40 mb-0"><span class="timer" data-to="20" data-speed="2500">0</span>+</h3>
                {_("Años de experiencia", "Years of experience")}
              </div>
            </div>
            <div class="col-md-6 text-center">
              <div class="de_count wow fadeInRight" data-wow-delay=".2s">
                <h3 class="fs-40 mb-0"><span class="timer" data-to="3" data-speed="2500">0</span></h3>
                {_("Países con operación propia", "Countries with our own ops")}
              </div>
            </div>
            <div class="col-md-6 text-center">
              <div class="de_count wow fadeInRight" data-wow-delay=".4s">
                <h3 class="fs-40 mb-0"><span class="timer" data-to="76" data-speed="2500">0</span>+</h3>
                {_("Reseñas en Google", "Google reviews")}
              </div>
            </div>
            <div class="col-md-6 text-center">
              <div class="de_count wow fadeInRight" data-wow-delay=".6s">
                <h3 class="fs-40 mb-0"><span class="timer" data-to="4" data-speed="2500">0</span></h3>
                {_("Almacenes Miami + China", "Warehouses Miami + China")}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container relative z-1">
    <div class="row g-4 gx-5">
      <div class="col-lg-6">
        <div class="relative">
          <div class="abs bottom-0 end-0 m-5 z-2">
            <div class="p-4 mb-4 bg-color text-light rounded-1 text-center wow fadeInUp">
              <h1 class="fs-84 mb-1">{SITE['years']}</h1>
              <div class="fs-16 lh-1-5">{_("Años de excelencia", "Years of excellence")}</div>
            </div>
          </div>
          <img src="{img('assets/lx/img/s3.webp', locale)}" class="w-100 rounded-1 wow scaleIn" alt="{_("Equipo e infraestructura WWC", "WWC team and infrastructure")}" width="800" height="600" loading="lazy">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="h-100 relative">
          <div class="subtitle id-color wow fadeInUp" data-wow-delay=".2s">{_("Tu socio de envíos", "Your shipping partner")}</div>
          <h2 class="split">{_("Operador propio, no intermediario", "Our own operator, not a middleman")}</h2>
          <div class="mt-4 wow fadeInUp" data-wow-delay=".5s">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-4 mb-4">
              <a class="btn-main fx-slide me-3" href="{SITE['register_url']}" data-hover="{html.escape(t(ui, 'register_free'))}"><span>{html.escape(t(ui, 'register_free'))}</span></a>
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <img src="{img('assets/lx/img/1.webp', locale)}" class="w-50px circle ms-min-10" alt="">
                  <img src="{img('assets/lx/img/2.webp', locale)}" class="w-50px circle ms-min-10" alt="">
                  <img src="{img('assets/lx/img/3.webp', locale)}" class="w-50px circle ms-min-10" alt="">
                </div>
                <div class="fw-600 fs-14 lh-1-5"><span class="fs-16 fw-bold text-dark">{SITE['google_rating']}★</span><br>{SITE['google_reviews']} {_("reseñas Google", "Google reviews")}</div>
              </div>
            </div>
            <p class="mb-0">{_("Cuando algo se complica —aduana, proveedor o un paquete que no aparece— del otro lado hay un equipo que ya lo resolvió cientos de veces. Precio cerrado, seguimiento claro e infraestructura propia en Miami y China.",
              "When something goes wrong — customs, a supplier, or a missing package — there's a team on the other end that has solved it hundreds of times. Fixed pricing, clear tracking, and our own warehouses in Miami and China.")}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">{_("Presencia", "Presence")}</span>
      <h2 class="split">{_("Dónde estamos", "Where we are")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".2s">{_("Operación propia en origen, consolidación y destino.", "Our own ops at origin, consolidation and destination.")}</p>
    </div>
    <div class="wwc-grid wwc-g3">
{offices}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">{_("Cómo trabajamos", "How we work")}</span>
      <h2 class="split">{_("Tres compromisos", "Three commitments")}</h2>
    </div>
    <div class="wwc-grid wwc-g3">
      <article class="card wow fadeInUp" data-wow-delay=".0s">
        <div class="card-icon">{icon('shield')}</div>
        <h3>{_("Transparencia en el precio", "Price transparency")}</h3>
        <p>{_("Presupuesto desglosado antes de despachar. Sin impuestos ocultos.", "Itemized quote before dispatch. No hidden fees.")}</p>
      </article>
      <article class="card wow fadeInUp" data-wow-delay=".15s">
        <div class="card-icon">{icon('users')}</div>
        <h3>{_("Una persona, no un ticket", "A person, not a ticket")}</h3>
        <p>{_("Un interlocutor que conoce tu historial. Respuesta humana por WhatsApp, teléfono o correo.", "A contact who knows your history. Human replies by WhatsApp, phone or email.")}</p>
      </article>
      <article class="card wow fadeInUp" data-wow-delay=".3s">
        <div class="card-icon">{icon('check')}</div>
        <h3>{_("Verificación antes de embarcar", "Check before shipping")}</h3>
        <p>{_("Inspección y fotos al ingresar al almacén, mientras todavía se puede reclamar.", "Inspection and photos on warehouse intake, while claims are still possible.")}</p>
      </article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container container-narrow text-center">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">{_("Opiniones", "Reviews")}</span>
      <h2 class="split">{_(f"{SITE['google_rating']} en Google", f"{SITE['google_rating']} on Google")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".2s">{_(f"Sobre {SITE['google_reviews']} reseñas reales de clientes.", f"Based on {SITE['google_reviews']} real customer reviews.")}</p>
    </div>
    <p class="mt-4 wow fadeInUp" data-wow-delay=".3s"><a class="btn-main fx-slide" href="https://www.google.com/search?q=worldwide+commerce+miami+reviews" target="_blank" rel="noopener" data-hover="{_("Ver reseñas", "See reviews")}"><span>{_("Leer reseñas en Google", "Read Google reviews")}</span></a></p>
  </div>
</section>

{cta_band(locale)}
"""
    return render(body, locale, "nosotros.html",
        title=_("Nosotros | World Wide Commerce — 20 años de logística", "About Us | World Wide Commerce — 20 Years of Logistics"),
        desc=_("Somos un operador logístico con almacenes propios en Miami y China. Conocé nuestra historia, nuestra infraestructura y cómo trabajamos.",
               "We're a logistics operator with our own warehouses in Miami and China. Learn about our history, our infrastructure and how we work."),
        keywords=_("worldwide commerce group, operador logistico miami, empresa logistica internacional, quienes somos",
                   "worldwide commerce group, miami logistics operator, international logistics company, about us"),
        schema=[breadcrumb_schema(trail, locale), org_schema(locale)])


# ══════════════════════════════════════════════════════════════ PRESENCIA / GLOBAL HUBS
def build_presence(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.presence"), None)]
    hubs = [
        {
            "id": "miami",
            "region": None,
            "code": "US",
            "img": "s3.webp",
            "kicker": _("Hub Estados Unidos", "United States hub"),
            "title": _("Miami", "Miami"),
            "role": _("Casa central y almacén propio", "Headquarters and own warehouse"),
            "lead": _("Acá recibimos tus compras de EE. UU., inspeccionamos, consolidamos y preparamos el despacho hacia Latinoamérica.",
                      "Here we receive your US purchases, inspect, consolidate and prepare dispatch to Latin America."),
            "points": [
                _("Casillero exclusivo en 2355 NW 70th Ave, Miami", "Exclusive mailbox at 2355 NW 70th Ave, Miami"),
                _("Consolidación, fotos e inspección al ingreso", "Consolidation, photos and intake inspection"),
                _("Compras asistidas cuando tu tarjeta no pasa", "Assisted shopping when your card is declined"),
                _("Equipo hispanohablante por WhatsApp y teléfono", "Spanish-speaking team by WhatsApp and phone"),
            ],
            "wa_label": _("WhatsApp Miami", "WhatsApp Miami"),
            "wa_display": SITE["whatsapp_display"],
        },
        {
            "id": "china",
            "region": "china",
            "code": "CN",
            "img": "l1.webp",
            "kicker": _("Hub China", "China hub"),
            "title": _("China", "China"),
            "role": _("Almacenes en Shenzhen, Shanghái y Guangzhou", "Warehouses in Shenzhen, Shanghai and Guangzhou"),
            "lead": _("Comprá en proveedores chinos o marketplaces: consolidamos en origen y movemos la carga con la misma cadena que en Miami.",
                      "Shop Chinese suppliers or marketplaces: we consolidate at origin and move cargo on the same chain as Miami."),
            "points": [
                _("Consolidación en tres ciudades clave", "Consolidation in three key cities"),
                _("Compras a proveedores y marketplaces locales", "Buying from local suppliers and marketplaces"),
                _("Control de calidad antes de embarcar", "Quality checks before shipping"),
                _("WhatsApp dedicado para operación China", "Dedicated WhatsApp for China operations"),
            ],
            "wa_label": _("WhatsApp China", "WhatsApp China"),
            "wa_display": SITE["whatsapp_china_display"],
        },
        {
            "id": "argentina",
            "region": None,
            "code": "AR",
            "img": "testimonial-delivery.webp",
            "kicker": _("Mercado destino", "Destination market"),
            "title": _("Argentina", "Argentina"),
            "role": _("Entrega puerta a puerta y acompañamiento local", "Door-to-door delivery and local support"),
            "lead": _("El destino de la mayoría de nuestros envíos: aduana clara, seguimiento y entrega en domicilio sin sorpresas de último momento.",
                      "Where most of our shipments land: clear customs, tracking and door delivery without last-minute surprises."),
            "points": [
                _("Puerta a puerta en todo el país", "Door-to-door nationwide"),
                _("Orientación de aduana e impuestos antes de despachar", "Customs and tax guidance before dispatch"),
                _("Seguimiento hasta la entrega", "Tracking through to delivery"),
                _("Atención en español, cercana al cliente", "Spanish support close to the customer"),
            ],
            "wa_label": _("WhatsApp atención", "WhatsApp support"),
            "wa_display": SITE["whatsapp_display"],
        },
    ]
    cards = ""
    for i, h in enumerate(hubs):
        flip = " is-flip" if i % 2 == 1 else ""
        pts = "".join("<li>%s</li>" % html.escape(p) for p in h["points"])
        wa = wa_link(locale, region=h["region"])
        cards += f"""
      <article class="wwc-hub{flip} wow fadeInUp" id="hub-{h['id']}" data-wow-delay=".{i * 15}s">
        <div class="wwc-hub__media">
          <img src="{img('assets/lx/img/' + h['img'], locale)}" alt="" width="800" height="600" loading="lazy">
          <span class="wwc-hub__badge"><b>{h['code']}</b> {html.escape(h['title'])}</span>
        </div>
        <div class="wwc-hub__body">
          <div class="subtitle id-color">{html.escape(h['kicker'])}</div>
          <h2>{html.escape(h['title'])}</h2>
          <p class="wwc-hub__role">{html.escape(h['role'])}</p>
          <p>{html.escape(h['lead'])}</p>
          <ul class="wwc-hub__list">{pts}</ul>
          <a class="btn-main fx-slide" href="{wa}" target="_blank" rel="noopener" data-hover="{html.escape(h['wa_label'])}"><span>{icon('whatsapp')} {html.escape(h['wa_label'])} · {html.escape(h['wa_display'])}</span></a>
        </div>
      </article>
"""
    body = page_hero(
        _("Presencia global", "Global presence"),
        _("Una sola operación entre Miami, China y Argentina — almacenes propios en origen y entrega confiable en destino.",
          "One operation across Miami, China and Argentina — our own warehouses at origin and reliable delivery at destination."),
        trail, locale,
        bg="assets/lx/img/hero-servicios.webp",
        extra_class="wwc-hero-port m-3 rounded-1 sm-mt-90",
    ) + f"""
<section class="pt-50 pb-20">
  <div class="container">
    <div class="row g-4 justify-content-center text-center">
      <div class="col-lg-8">
        <div class="subtitle wow fadeInUp">{_("Cómo trabajamos el mundo", "How we cover the world")}</div>
        <h2 class="split">{_("No somos tres empresas. Somos una cadena.", "Not three companies. One chain.")}</h2>
        <p class="lead wow fadeInUp" data-wow-delay=".2s">{_("Comprás en USA o China, nosotros operamos el almacén, y lo entregamos en Argentina. El mismo equipo, el mismo precio cerrado, el mismo seguimiento.",
          "You shop in the USA or China, we run the warehouse, and we deliver in Argentina. Same team, same fixed pricing, same tracking.")}</p>
      </div>
    </div>
    <div class="wwc-hub-strip wow fadeInUp" data-wow-delay=".25s">
      <a href="#hub-miami"><span class="wwc-hub-strip__code">US</span><b>Miami</b><small>{_("Almacén", "Warehouse")}</small></a>
      <span class="wwc-hub-strip__line" aria-hidden="true"></span>
      <a href="#hub-china"><span class="wwc-hub-strip__code">CN</span><b>China</b><small>{_("Almacén", "Warehouse")}</small></a>
      <span class="wwc-hub-strip__line" aria-hidden="true"></span>
      <a href="#hub-argentina"><span class="wwc-hub-strip__code">AR</span><b>Argentina</b><small>{_("Entrega", "Delivery")}</small></a>
    </div>
  </div>
</section>

<section class="pb-50">
  <div class="container">
    <div class="wwc-hubs">
{cards}    </div>
  </div>
</section>

{cta_band(locale,
    title=_("¿Desde qué hub empezamos?", "Which hub should we start from?"),
    text=_("Escribinos por WhatsApp Miami o China y te orientamos según de dónde sale tu mercadería.",
           "Message us on WhatsApp Miami or China and we'll guide you based on where your goods ship from."),
    primary=(_("WhatsApp Miami", "WhatsApp Miami"), wa_link(locale)),
    secondary=(_("WhatsApp China", "WhatsApp China"), wa_link(locale, region="china")),
)}
"""
    return render(body, locale, "presencia.html",
        title=_("Presencia global: Miami, China y Argentina | WWC",
                "Global presence: Miami, China and Argentina | WWC"),
        desc=_("Operamos con almacenes en Miami y China y entrega puerta a puerta en Argentina. Una sola cadena logística internacional.",
               "We operate warehouses in Miami and China with door-to-door delivery in Argentina. One international logistics chain."),
        keywords=_("almacen miami, almacen china, envios argentina, logistica internacional, worldwide commerce presencia",
                   "miami warehouse, china warehouse, shipping argentina, international logistics, worldwide commerce presence"),
        schema=[breadcrumb_schema(trail, locale), org_schema(locale)])


# ══════════════════════════════════════════════════════════════ DÓNDE COMPRAR / WHERE TO BUY
STORE_CATS = [
 (("Generalistas y marketplaces", "Generalists and marketplaces"), "cart",
  [("Amazon","amazon.com"),("eBay","ebay.com"),("Walmart","walmart.com"),
   ("Target","target.com"),("Costco","costco.com"),("Best Buy","bestbuy.com")]),
 (("Proveedores en China", "Suppliers in China"), "china",
  [("AliExpress","aliexpress.com"),("Alibaba","alibaba.com"),("1688","1688.com"),
   ("Temu","temu.com"),("DHgate","dhgate.com"),("Made-in-China","made-in-china.com")]),
 (("Indumentaria y calzado", "Apparel and footwear"), "box",
  [("Zara","zara.com"),("Victoria's Secret","victoriassecret.com"),("Zappos","zappos.com"),
   ("Nike","nike.com"),("Carter's","carters.com"),("Old Navy","oldnavy.com")]),
 (("Tecnología y fotografía", "Tech and photography"), "code",
  [("B&H Photo","bhphotovideo.com"),("Newegg","newegg.com"),("Apple","apple.com"),
   ("Adorama","adorama.com"),("Micro Center","microcenter.com"),("GameStop","gamestop.com")]),
 (("Bebés, juguetes y hogar", "Baby, toys and home"), "users",
  [("Disney Store","shopdisney.com"),("The Home Depot","homedepot.com"),("Wayfair","wayfair.com"),
   ("Lowe's","lowes.com"),("Bed Bath & Beyond","bedbathandbeyond.com"),("IKEA","ikea.com")]),
 (("Salud, belleza y deporte", "Health, beauty and sports"), "shield",
  [("Ulta Beauty","ulta.com"),("Sephora","sephora.com"),("iHerb","iherb.com"),
   ("Dick's Sporting Goods","dickssportinggoods.com"),("REI","rei.com"),("GNC","gnc.com")]),
]


def build_stores(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.stores"), None)]
    blocks = ""
    for names, ic, stores in STORE_CATS:
        items = "".join(
          (
            '<li><a class="wwc-store-link" href="https://www.%s" target="_blank" rel="noopener nofollow">'
            '<span class="wwc-store-link__icon">'
            '<img src="https://www.google.com/s2/favicons?domain=%s&amp;sz=64" alt="" width="20" height="20" loading="lazy" decoding="async">'
            '</span><span>%s</span></a></li>'
          ) % (html.escape(dom), html.escape(dom), html.escape(lbl))
          for lbl, dom in stores)
        blocks += f"""      <article class="card">
        <div class="card-icon">{icon(ic)}</div>
        <h3>{html.escape(_(*names))}</h3>
        <ul class="wwc-store-list">{items}</ul>
      </article>
"""
    body = page_hero(
        _("Dónde comprar con tu casillero WWC", "Where to shop with your WWC mailbox"),
        _("Usá tu dirección de Miami o de China como domicilio de entrega en cualquiera de estas tiendas. Y en miles más: la lista es orientativa, no un límite.",
          "Use your Miami or China address as the delivery address at any of these stores. And thousands more: the list is a guide, not a limit."),
        trail, locale
    ) + f"""
<section class="section">
  <div class="container">
    <div class="callout mb-6 wow fadeInUp">
      <b>{_("Antes de comprar, recordá dos cosas", "Before you buy, remember two things")}</b>
      {_("Primero: usá siempre la dirección exacta del almacén <strong>junto con tu número de casillero</strong>, que recibís al registrarte. Sin ese número el paquete tarda mucho más en asociarse a tu cuenta. Segundo: si no estás seguro de que un producto se pueda transportar, preguntanos antes de pagarlo.",
         "First: always use the warehouse's exact address <strong>together with your mailbox number</strong>, which you receive when you sign up. Without that number, the package takes much longer to be matched to your account. Second: if you're not sure a product can be shipped, ask us before you pay for it.")}
    </div>
    <div class="wwc-grid wwc-g3" data-stagger>
{blocks}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container container-narrow">
    <div class="section-head center wow fadeInUp">
      <span class="eyebrow">{_("Si la tienda no te deja", "If the store won't let you")}</span>
      <h2 class="balance">{_("¿Te rechazaron la tarjeta o no envían al exterior?", "Card declined or no shipping abroad?")}</h2>
      <p class="lead">{_("Es más común de lo que parece, y tiene solución: compramos nosotros a tu nombre con medios de pago locales y el producto entra directo a tu casillero.",
                         "It's more common than it seems, and it has a fix: we buy on your behalf with local payment methods and the item goes straight to your mailbox.")}</p>
    </div>
    <div class="text-center wow fadeInUp">
      <a class="btn btn--primary btn--lg" href="{ph('servicio-realizamos-tus-compras.html', locale)}">{_("Ver el servicio de compras asistidas", "See the assisted shopping service")} {icon('arrow-right')}</a>
    </div>
  </div>
</section>

{cta_band(locale)}
"""
    return render(body, locale, "donde-comprar.html",
        title=_("Dónde Comprar en USA y China | World Wide Commerce", "Where to Shop in the USA and China | World Wide Commerce"),
        desc=_("Las mejores tiendas de Estados Unidos y China para comprar con tu casillero WWC: Amazon, eBay, Walmart, AliExpress, Alibaba y muchas más.",
               "The best US and Chinese stores to shop with your WWC mailbox: Amazon, eBay, Walmart, AliExpress, Alibaba and many more."),
        keywords=_("tiendas para comprar en usa, donde comprar en estados unidos, tiendas chinas online, comprar en amazon desde argentina",
                   "stores to shop in the usa, where to buy in the united states, chinese online stores, buy from amazon and ship to latin america"),
        schema=[breadcrumb_schema(trail, locale)])


# ══════════════════════════════════════════════════════════════ RASTREO / TRACKING
def build_tracking(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.tracking"), None)]
    states = [
        (_("Recibido en almacén", "Received at warehouse"),
         _("El paquete ingresó a nuestra instalación y quedó asociado a tu casillero.", "The package arrived at our facility and was matched to your mailbox."), "warehouse"),
        (_("Verificado", "Verified"),
         _("Inspeccionamos, pesamos, medimos y fotografiamos el contenido.", "We inspected, weighed, measured and photographed the contents."), "check"),
        (_("Consolidado", "Consolidated"),
         _("Agrupamos tus compras y reempacamos para reducir el volumen.", "We grouped your purchases and repacked them to reduce volume."), "box"),
        (_("Despachado", "Dispatched"),
         _("La carga salió del país de origen con su documentación completa.", "The cargo left the country of origin with its complete documentation."), "plane"),
        (_("En aduana", "In customs"),
         _("La mercadería está en proceso de despacho aduanero en destino.", "The goods are going through customs clearance at destination."), "file"),
        (_("En distribución", "Out for delivery"),
         _("Liberada y en camino hacia tu domicilio.", "Cleared and on its way to your address."), "truck"),
    ]
    cards = "".join(f"""      <article class="card">
        <div class="card-icon">{icon(ic)}</div>
        <h3>{html.escape(ti)}</h3>
        <p>{html.escape(d)}</p>
      </article>
""" for ti, d, ic in states)
    faqs = [
      {"q": _("Mi paquete figura entregado en el almacén pero no aparece en mi cuenta",
              "My package shows as delivered to the warehouse but doesn't appear in my account"),
       "a": _("Suele ocurrir cuando la tienda despachó sin el número de casillero, o lo escribió incompleto. En ese caso el bulto llega pero no podemos asociarlo automáticamente. Escribinos con el número de seguimiento de la tienda y el nombre que figura en el envío, y lo identificamos manualmente.",
              "This usually happens when the store shipped without the mailbox number, or wrote it incompletely. The parcel arrives but we can't match it automatically. Write to us with the store's tracking number and the name on the shipment, and we'll identify it manually.")},
      {"q": _("El estado dice «en aduana» desde hace varios días", "The status has said “in customs” for several days"),
       "a": _("Los tiempos de despacho aduanero no dependen de nosotros y varían según el tipo de mercadería, el valor declarado y la carga de trabajo del organismo. Si la demora excede lo habitual para tu tipo de envío, nos comunicamos con el despachante y te informamos qué está pendiente.",
              "Customs clearance times are outside our control and vary by type of goods, declared value and the agency's workload. If the delay exceeds what's normal for your type of shipment, we contact the customs broker and let you know what's pending.")},
      {"q": _("Quiero cambiar la dirección de entrega", "I want to change the delivery address"),
       "a": _("Es posible mientras la carga no haya sido despachada desde el país de origen. Una vez emitida la documentación de embarque, el cambio de destinatario o de domicilio se vuelve mucho más complejo. Avisanos cuanto antes si necesitás modificarla.",
              "It's possible as long as the cargo hasn't been dispatched from the country of origin. Once the shipping documents are issued, changing the recipient or address becomes much more complex. Let us know as soon as possible if you need to change it.")},
      {"q": _("¿Cada cuánto se actualiza el seguimiento?", "How often is tracking updated?"),
       "a": _("Los estados se actualizan a medida que la carga avanza por cada punto de control. Entre dos hitos puede pasar más de un día sin movimientos visibles, especialmente durante el tránsito internacional, y eso es normal.",
              "Statuses update as the cargo moves through each checkpoint. More than a day can pass between two milestones with no visible movement, especially during international transit, and that's normal.")},
    ]
    body = page_hero(
        _("¿Dónde está mi envío?", "Where is my shipment?"),
        _("Ingresá tu código de seguimiento y te mostramos en qué etapa del recorrido está tu paquete.",
          "Enter your tracking code and we'll show you which stage of the journey your package is at."),
        trail, locale
    ) + f"""
<section>
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-5">{track_widget(locale)}</div>
      <div class="col-lg-7">
        <p>{_("¿No tenés el código a mano? Entrá a tu cuenta o escribinos por WhatsApp con tu número de casillero.",
              "Don't have your code handy? Log in to your account or message us on WhatsApp with your mailbox number.")}</p>
        <a class="btn-main btn-line me-2" href="{SITE['login_url']}" data-hover="{_("Ingresar", "Log in")}"><span>{_("Ingresar a mi cuenta", "Log in to my account")}</span></a>
        <a class="btn-main" href="{wa_link(locale, _('Hola, quiero consultar el estado de un envío.', "Hello, I'd like to check the status of a shipment."))}" target="_blank" rel="noopener" data-hover="WhatsApp"><span>WhatsApp</span></a>
      </div>
    </div>
  </div>
</section>
<section class="bg-light">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">{_("Estados del envío", "Shipment statuses")}</span>
      <h2>{_("Qué significa cada etapa", "What each stage means")}</h2>
    </div>
    <div class="wwc-grid wwc-g3">
{cards}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container container-narrow">
    <div class="section-head center wow fadeInUp">
      <span class="eyebrow">{_("Demoras", "Delays")}</span>
      <h2 class="balance">{_("¿Tu envío no se mueve hace días?", "Hasn't your shipment moved in days?")}</h2>
    </div>
    <div class="wow fadeInUp">
{accordion(faqs, start_id=100)}
    </div>
  </div>
</section>

{cta_band(locale,
    title=_("¿Necesitás ayuda con un envío puntual?", "Need help with a specific shipment?"),
    text=_("Escribinos por WhatsApp con tu número de casillero y lo revisamos en el momento.",
           "Message us on WhatsApp with your mailbox number and we'll check it right away."),
    primary=(t(ui, "talk_whatsapp"), wa_link(locale)),
    secondary=(_("Ir a contacto", "Go to contact"), "contacto.html"))}
"""
    return render(body, locale, "rastreo.html",
        title=_("Rastrear Envío | World Wide Commerce", "Track Your Shipment | World Wide Commerce"),
        desc=_("Consultá el estado de tu envío con tu código de seguimiento o tu número de casillero WWC. Seguimiento en tiempo real en cada etapa.",
               "Check your shipment status with your tracking code or WWC mailbox number. Real-time tracking at every stage."),
        keywords=_("rastrear envio wwc, seguimiento paquete miami, tracking worldwide commerce, estado de mi envio",
                   "track shipment wwc, miami package tracking, worldwide commerce tracking, shipment status"),
        schema=[breadcrumb_schema(trail, locale)])


# ══════════════════════════════════════════════════════════════ FAQ
def build_faq(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    faqs = content(locale)["FAQS"]
    trail = [(t(ui, "common.home_crumb"), "index.html"), (_("Preguntas frecuentes", "Frequently asked questions"), None)]
    body = page_hero(
        _("Preguntas frecuentes", "Frequently asked questions"),
        _("Las consultas que más recibimos, respondidas sin vueltas. Si falta la tuya, escribinos y la agregamos.",
          "The questions we get most, answered straight. If yours is missing, write to us and we'll add it."),
        trail, locale
    ) + f"""
<section class="section">
  <div class="container container-narrow">
    <div class="wow fadeInUp">
{accordion(faqs)}
    </div>
  </div>
</section>

{cta_band(locale,
    title=_("¿No encontraste tu respuesta?", "Didn't find your answer?"),
    text=_("Escribinos por WhatsApp o mandanos un correo. Contestamos consultas concretas con respuestas concretas.",
           "Message us on WhatsApp or send us an email. We answer specific questions with specific answers."),
    primary=(t(ui, "talk_whatsapp"), wa_link(locale)),
    secondary=(_("Formulario de contacto", "Contact form"), "contacto.html"))}
"""
    return render(body, locale, "preguntas-frecuentes.html",
        title=_("Preguntas Frecuentes | World Wide Commerce", "FAQ | World Wide Commerce"),
        desc=_("Respuestas sobre casillero en Miami, consolidación, costos, productos restringidos, compras asistidas, seguimiento y envíos a Argentina.",
               "Answers about your Miami mailbox, consolidation, costs, restricted items, assisted shopping, tracking and shipping to Latin America."),
        keywords=_("preguntas frecuentes casillero miami, como funciona casillero, costos envio argentina, productos prohibidos envio",
                   "miami mailbox faq, how a mailbox works, shipping costs latin america, prohibited items shipping"),
        schema=[breadcrumb_schema(trail, locale), faq_schema(faqs)])


# ══════════════════════════════════════════════════════════════ CONTACTO / CONTACT
def build_contact(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.contact"), None)]
    opts = "".join('<option value="%s">%s</option>' % (html.escape(svc_name(s, locale)), html.escape(svc_name(s, locale)))
                   for s in SERVICES)
    offices = ""
    for o in content(locale)["OFFICES"]:
        lines = "<br>".join(html.escape(l) for l in o["lines"])
        offices += f"""        <div class="office">
          <b>{icon('pin')}{html.escape(o['city'])}</b>
          <span>{html.escape(o['label'])}<br>{lines}</span>
        </div>
"""
    f = lambda k: html.escape(t(ui, "form." + k))
    required = html.escape(t(ui, "form.required")).replace("*", '<span style="color:var(--coral)">*</span>')
    body = page_hero(
        _("Hablemos de lo que necesitás mover", "Let's talk about what you need to move"),
        _("Contanos qué querés enviar, desde dónde y hacia dónde. Con esos datos podemos darte una orientación concreta de costo y plazo.",
          "Tell us what you want to ship, from where and to where. With that we can give you a concrete idea of cost and timing."),
        trail, locale
    ) + f"""
<section class="section">
  <div class="container">
    <div class="layout-sidebar">

      <div class="wow fadeInUp">
        <h2>{_("Pedir una cotización", "Request a quote")}</h2>
        <p class="lead mb-6">{required}</p>

        <!-- DEVELOPER NOTE: this form does NOT submit anywhere yet; the backend is missing.
             Options: a) action="https://formspree.io/f/YOUR_ID" method="POST"
                      b) your own PHP/Node endpoint that sends the mail
                      c) Netlify Forms: add `netlify` to the <form>
             Change the action and you're done. Validation already lives in main.js -->
        <form class="form-grid" data-contact-form action="#" method="POST">
          <div class="wwc-grid wwc-g2" style="gap:var(--s-4)">
            <div class="field">
              <label for="nombre">{f('name')} <span class="req">*</span></label>
              <input id="nombre" name="nombre" type="text" required autocomplete="name">
            </div>
            <div class="field">
              <label for="email">{f('email')} <span class="req">*</span></label>
              <input id="email" name="email" type="email" required autocomplete="email">
            </div>
          </div>
          <div class="wwc-grid wwc-g2" style="gap:var(--s-4)">
            <div class="field">
              <label for="tel">{f('phone')}</label>
              <input id="tel" name="telefono" type="tel" autocomplete="tel" placeholder="+54 9 11 ...">
            </div>
            <div class="field">
              <label for="pais">{f('country')}</label>
              <input id="pais" name="pais" type="text" placeholder="{_("Argentina", "Argentina")}">
            </div>
          </div>
          <div class="field">
            <label for="servicio">{f('service')}</label>
            <select id="servicio" name="servicio">
              <option value="">{_("Seleccioná un servicio…", "Select a service…")}</option>
              {opts}
              <option value="No estoy seguro">{_("No estoy seguro / necesito asesoramiento", "Not sure / I need advice")}</option>
            </select>
          </div>
          <div class="field">
            <label for="mensaje">{f('message')} <span class="req">*</span></label>
            <textarea id="mensaje" name="mensaje" required placeholder="{_("Ej.: necesito traer 3 cajas de repuestos desde Miami a Córdoba, unos 40 kg en total.", "E.g.: I need to bring 3 boxes of spare parts from Miami to Córdoba, about 40 kg in total.")}"></textarea>
            <span class="field-hint">{_("Cuanto más preciso sea el peso, el volumen y el destino, más exacta será la cotización.", "The more precise the weight, volume and destination, the more accurate the quote.")}</span>
          </div>
          <button class="btn btn--primary btn--lg" type="submit">{f('send')} {icon('arrow-right')}</button>
          <p class="form-note">{_("Al enviar aceptás que te contactemos por los medios indicados. No compartimos tus datos con terceros.", "By submitting you agree that we may contact you through the means provided. We don't share your data with third parties.")}</p>
        </form>
      </div>

      <aside class="sidebar">
        <div class="side-card side-card--dark">
          <h4>{_("Contacto directo", "Direct contact")}</h4>
          <p>{_("Si preferís la vía rápida, escribinos por WhatsApp. Es el canal donde respondemos antes.", "If you prefer the fast lane, message us on WhatsApp. It's the channel where we reply fastest.")}</p>
          <a class="btn btn--primary btn--block btn--sm" href="{wa_link(locale)}" target="_blank" rel="noopener">{icon('whatsapp')} WhatsApp Miami · {SITE['whatsapp_display']}</a>
          <div style="height:.6rem"></div>
          <a class="btn btn--primary btn--block btn--sm" href="{wa_link(locale, region='china')}" target="_blank" rel="noopener">{icon('whatsapp')} WhatsApp China · {SITE['whatsapp_china_display']}</a>
          <div style="height:.6rem"></div>
          <a class="btn btn--ghost btn--block btn--sm" href="tel:{SITE['phone_raw']}">{icon('phone')} {SITE['phone_display']}</a>
          <div style="height:.6rem"></div>
          <a class="btn btn--ghost btn--block btn--sm" href="mailto:{SITE['email']}">{icon('mail')} {_("Correo", "Email")}</a>
        </div>
        <div class="side-card">
          <h4>{_("Nuestras oficinas", "Our offices")}</h4>
{offices}        </div>
      </aside>

    </div>
  </div>
</section>
"""
    return render(body, locale, "contacto.html",
        title=_("Contacto | World Wide Commerce — Miami, Asunción, China", "Contact | World Wide Commerce — Miami, Asunción, China"),
        desc=_("Escribinos para cotizar un envío, importar mercadería o consultar por cualquiera de nuestros servicios. Teléfono, WhatsApp, correo y oficinas.",
               "Contact us to quote a shipment, import goods or ask about any of our services. Phone, WhatsApp, email and offices."),
        keywords=_("contacto worldwide commerce, cotizar envio miami argentina, telefono casillero miami",
                   "contact worldwide commerce, quote shipment miami, miami mailbox phone"),
        schema=[breadcrumb_schema(trail, locale), localbiz_schema(locale), {
          "@context": "https://schema.org", "@type": "ContactPage",
          "url": abs_url(SITE["domain"], "contacto.html", locale),
          "about": {"@id": SITE["domain"] + "/#organization"}}])


# ══════════════════════════════════════════════════════════════ SIMPLE PAGES
def build_simple(locale, slug, title, seo_title, desc, lead, body_html, noindex=False):
    ui = load_ui(locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (title, None)]
    body = page_hero(html.escape(title), html.escape(lead), trail, locale) + f"""
<section class="section">
  <div class="container container-narrow">
    <div class="prose wow fadeInUp">
{body_html}
    </div>
  </div>
</section>
"""
    return render(body, locale, slug + ".html", title=seo_title, desc=desc,
                  noindex=noindex, schema=[breadcrumb_schema(trail, locale)])


def build_404(locale="es"):
    ui = load_ui(locale)
    e = lambda k: html.escape(t(ui, k))
    body = f"""
<section id="subheader" class="text-light" data-bgimage="url({asset('assets/lx/img/s3.webp', locale)})">
  <div class="container">
    <h1>404</h1>
    <p class="lead">{e('not_found.text')}</p>
    <a class="btn-main me-2" href="{ph('index.html', locale)}" data-hover="{e('nav.home')}"><span>{e('not_found.back_home')}</span></a>
    <a class="btn-main btn-line" href="{ph('contacto.html', locale)}" data-hover="{e('not_found.contact')}"><span>{e('not_found.contact')}</span></a>
  </div>
  <div class="de-overlay"></div>
</section>
"""
    return render(body, locale, "404.html", title=t(ui, "not_found.title"),
                  desc=t(ui, "not_found.desc"), noindex=True)


def build_thanks(locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    body = f"""<div class="callout"><b>{_("¿Necesitás una respuesta más rápida?", "Need a faster answer?")}</b>
{_("Escribinos por WhatsApp al", "Message us on WhatsApp at")} <a href="{wa_link(locale)}">{SITE['phone_display']}</a> {_("y te atendemos en el momento.", "and we'll help you right away.")}</div>
<h2>{_("Mientras esperás", "While you wait")}</h2>
<ul>
<li><a href="{ph('servicios.html', locale)}">{_("Conocé los 14 servicios", "Discover the 14 services")}</a> {_("que ofrecemos", "we offer")}</li>
<li><a href="{ph('donde-comprar.html', locale)}">{_("Mirá dónde podés comprar", "See where you can shop")}</a> {_("con tu casillero", "with your mailbox")}</li>
<li><a href="{ph('preguntas-frecuentes.html', locale)}">{_("Revisá las preguntas frecuentes", "Check the frequently asked questions")}</a></li>
<li><a href="{SITE['register_url']}">{_("Creá tu cuenta gratis", "Create your free account")}</a> {_("y obtené tu dirección en Miami", "and get your Miami address")}</li>
</ul>"""
    return build_simple(locale, "gracias", t(ui, "thanks.title"), t(ui, "thanks.seo_title"),
                        t(ui, "thanks.desc"), t(ui, "thanks.text"), body, noindex=True)


TERMS_BODY_ES = """<div class="callout"><b>Aviso para el desarrollador</b>
Este texto es una ESTRUCTURA BASE, no un documento legal definitivo. Hacelo revisar por un abogado antes de publicar, especialmente las cláusulas de responsabilidad, los plazos y la jurisdicción.</div>

<h2>1. Objeto</h2>
<p>Estos términos regulan el uso del sitio y la contratación de los servicios logísticos prestados por WORLDWIDE COMMERCE GROUP LLC.</p>
<h2>2. Registro y cuenta</h2>
<p>El registro es gratuito. El usuario es responsable de la veracidad de los datos declarados y del uso de su número de casillero.</p>
<h2>3. Mercadería admitida y restricciones</h2>
<p>No se transportan mercaderías prohibidas por la normativa de origen o destino, incluyendo mercadería peligrosa, inflamables, baterías de litio sueltas, armas, sustancias controladas y perecederos. El usuario declara el contenido real de sus envíos.</p>
<h2>4. Cotizaciones y pagos</h2>
<p>Las cotizaciones se emiten sobre los datos de peso, volumen y destino informados, y pueden ajustarse si la medición real difiere. Los tributos y tasas de destino son a cargo del usuario cuando corresponda.</p>
<h2>5. Plazos</h2>
<p>Los plazos informados son estimativos y no incluyen demoras atribuibles a aduanas, transportistas o causas de fuerza mayor.</p>
<h2>6. Responsabilidad</h2>
<p>La responsabilidad por pérdida o daño se rige por el valor declarado y la cobertura contratada. [COMPLETAR CON ASESORAMIENTO LEGAL]</p>
<h2>7. Protección de datos</h2>
<p>El tratamiento de datos personales se rige por nuestra <a href="privacidad">Política de privacidad</a>.</p>
<h2>8. Jurisdicción</h2>
<p>[COMPLETAR: jurisdicción y legislación aplicable]</p>"""

TERMS_BODY_EN = """<div class="callout"><b>Developer notice</b>
This text is a BASE STRUCTURE, not a final legal document. Have a lawyer review it before publishing, especially the liability clauses, deadlines and jurisdiction.</div>

<h2>1. Purpose</h2>
<p>These terms govern the use of this site and the contracting of the logistics services provided by WORLDWIDE COMMERCE GROUP LLC.</p>
<h2>2. Registration and account</h2>
<p>Registration is free. The user is responsible for the accuracy of the data provided and for the use of their mailbox number.</p>
<h2>3. Accepted goods and restrictions</h2>
<p>We do not transport goods prohibited by the regulations of the origin or destination country, including hazardous goods, flammables, loose lithium batteries, weapons, controlled substances and perishables. The user declares the true contents of their shipments.</p>
<h2>4. Quotes and payments</h2>
<p>Quotes are issued based on the weight, volume and destination provided, and may be adjusted if the actual measurement differs. Destination taxes and duties are the user's responsibility where applicable.</p>
<h2>5. Timeframes</h2>
<p>Stated timeframes are estimates and do not include delays attributable to customs, carriers or force majeure.</p>
<h2>6. Liability</h2>
<p>Liability for loss or damage is governed by the declared value and the coverage contracted. [COMPLETE WITH LEGAL ADVICE]</p>
<h2>7. Data protection</h2>
<p>The processing of personal data is governed by our <a href="privacidad">Privacy Policy</a>.</p>
<h2>8. Jurisdiction</h2>
<p>[COMPLETE: jurisdiction and applicable law]</p>"""

PRIVACY_BODY_ES = """<div class="callout"><b>Aviso para el desarrollador</b>
Estructura base. Ajustala a los datos que realmente recolectás y a la normativa aplicable (Ley 25.326 en Argentina, GDPR si tenés tráfico europeo, CCPA si aplica). Hacela revisar antes de publicar.</div>

<h2>1. Responsable del tratamiento</h2>
<p>WORLDWIDE COMMERCE GROUP LLC, con domicilio en 2355 NW 70th Ave, Unit D-06, Miami, FL 33122, Estados Unidos.</p>
<h2>2. Datos que recolectamos</h2>
<ul>
<li>Datos de identificación y contacto que informás al registrarte o al consultarnos</li>
<li>Datos necesarios para la operación logística y aduanera de tus envíos</li>
<li>Datos de navegación mediante cookies y herramientas de analítica</li>
</ul>
<h2>3. Finalidad</h2>
<p>Prestar el servicio contratado, cumplir obligaciones legales y aduaneras, responder consultas y, si lo autorizás, enviarte comunicaciones comerciales.</p>
<h2>4. Compartición con terceros</h2>
<p>Compartimos únicamente los datos necesarios con transportistas, despachantes de aduana y autoridades, cuando la operación o la ley lo requieren. No vendemos datos personales.</p>
<h2>5. Cookies</h2>
<p>El sitio utiliza cookies propias y de terceros con fines técnicos y analíticos. [COMPLETAR según las herramientas que instales]</p>
<h2>6. Tus derechos</h2>
<p>Podés solicitar acceso, rectificación o supresión de tus datos escribiendo a <a href="mailto:clientes@worldwidecommerce.us">clientes@worldwidecommerce.us</a>.</p>
<h2>7. Conservación</h2>
<p>Conservamos los datos mientras exista relación comercial y durante los plazos que exija la normativa aduanera y fiscal.</p>"""

PRIVACY_BODY_EN = """<div class="callout"><b>Developer notice</b>
Base structure. Adjust it to the data you actually collect and to the applicable regulations (Law 25.326 in Argentina, GDPR if you have European traffic, CCPA if applicable). Have it reviewed before publishing.</div>

<h2>1. Data controller</h2>
<p>WORLDWIDE COMMERCE GROUP LLC, located at 2355 NW 70th Ave, Unit D-06, Miami, FL 33122, United States.</p>
<h2>2. Data we collect</h2>
<ul>
<li>Identification and contact details you provide when registering or contacting us</li>
<li>Data needed for the logistics and customs handling of your shipments</li>
<li>Browsing data through cookies and analytics tools</li>
</ul>
<h2>3. Purpose</h2>
<p>To provide the contracted service, meet legal and customs obligations, answer inquiries and, if you authorize it, send you marketing communications.</p>
<h2>4. Sharing with third parties</h2>
<p>We share only the data necessary with carriers, customs brokers and authorities, when the operation or the law requires it. We do not sell personal data.</p>
<h2>5. Cookies</h2>
<p>This site uses first-party and third-party cookies for technical and analytics purposes. [COMPLETE according to the tools you install]</p>
<h2>6. Your rights</h2>
<p>You may request access, rectification or deletion of your data by writing to <a href="mailto:clientes@worldwidecommerce.us">clientes@worldwidecommerce.us</a>.</p>
<h2>7. Retention</h2>
<p>We keep data for as long as a commercial relationship exists and for the periods required by customs and tax regulations.</p>"""


def build_terms(locale="es"):
    _ = tr(locale)
    return build_simple(locale, "terminos",
        _("Términos y condiciones", "Terms and conditions"),
        _("Términos y Condiciones | World Wide Commerce", "Terms and Conditions | World Wide Commerce"),
        _("Términos y condiciones de uso del sitio y de los servicios logísticos de WORLDWIDE COMMERCE GROUP LLC: mercadería admitida, cotizaciones, plazos y responsabilidad.",
          "Terms and conditions for the use of the site and the logistics services of WORLDWIDE COMMERCE GROUP LLC: accepted goods, quotes, timeframes and liability."),
        _("Condiciones que regulan el uso de este sitio y la prestación de nuestros servicios.",
          "Conditions governing the use of this site and the provision of our services."),
        _(TERMS_BODY_ES, TERMS_BODY_EN))


def build_privacy(locale="es"):
    _ = tr(locale)
    return build_simple(locale, "privacidad",
        _("Política de privacidad", "Privacy policy"),
        _("Política de Privacidad | World Wide Commerce", "Privacy Policy | World Wide Commerce"),
        _("Cómo recolectamos, usamos y protegemos tus datos personales en World Wide Commerce: qué datos pedimos, con quién los compartimos y qué derechos tenés sobre ellos.",
          "How we collect, use and protect your personal data at World Wide Commerce: what data we ask for, who we share it with and what rights you have over it."),
        _("Cómo tratamos tus datos personales y qué derechos tenés sobre ellos.",
          "How we handle your personal data and what rights you have over it."),
        _(PRIVACY_BODY_ES, PRIVACY_BODY_EN))


# ══════════════════════════════════════════════════════════════ SEO FILES
def build_sitemap():
    urls = [("index.html", "1.0", "weekly"), ("servicios.html", "0.9", "monthly"),
            ("nosotros.html", "0.7", "monthly"), ("presencia.html", "0.8", "monthly"),
            ("donde-comprar.html", "0.7", "monthly"),
            ("rastreo.html", "0.8", "monthly"), ("preguntas-frecuentes.html", "0.7", "monthly"),
            ("contacto.html", "0.8", "monthly")]
    urls += [(svc_url(s), "0.85", "monthly") for s in SERVICES]
    urls += [("terminos.html", "0.3", "yearly"), ("privacidad.html", "0.3", "yearly")]
    body = ""
    for u, p, cf in urls:
        for loc in LOCALES:
            body += f"""  <url>
    <loc>{abs_url(SITE['domain'], u, loc)}</loc>
    <lastmod>{TODAY}</lastmod>
    <changefreq>{cf}</changefreq>
    <priority>{p}</priority>
  </url>
"""
    return f"""<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{body}</urlset>
"""


def build_robots():
    return f"""# robots.txt — {SITE['name']}
User-agent: *
Allow: /
Disallow: /gracias
Disallow: /404
Disallow: /en/gracias
Disallow: /en/404
Disallow: /_build/

Sitemap: {SITE['domain']}/sitemap.xml
"""


def build_manifest():
    return json.dumps({
      "name": SITE["name"], "short_name": SITE["short"],
      "description": "Comprá en USA o China y recibí en Argentina.",
      "start_url": "/", "display": "standalone",
      "background_color": "#081021", "theme_color": "#081021", "lang": "es",
      "icons": [{"src": "assets/img/brand/favicon.svg", "sizes": "any", "type": "image/svg+xml"},
                {"src": "assets/img/brand/icon-192.png", "sizes": "192x192", "type": "image/png"},
                {"src": "assets/img/brand/icon-512.png", "sizes": "512x512", "type": "image/png"}]
    }, ensure_ascii=False, indent=2)


def build_htaccess():
    return """# Clean URLs — serve page.html as /page
Options -MultiViews
RewriteEngine On

# Redirect /index.html → /
RewriteCond %{THE_REQUEST} \\s/+index\\.html[\\s?] [NC]
RewriteRule ^index\\.html$ / [R=301,L]

# Redirect /path.html → /path
RewriteCond %{THE_REQUEST} \\s/+(.+?)\\.html[\\s?] [NC]
RewriteRule ^ %1 [R=301,L]

# Internally map /path → /path.html when the file exists
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME}.html -f
RewriteRule ^(.*)$ $1.html [L]

ErrorDocument 404 /404.html
"""


# ══════════════════════════════════════════════════════════════ MAIN
def main():
    written = []

    def w(f, c, locale="es"):
        written.append(write(f, c, locale))

    for locale in LOCALES:
        w("index.html", build_home(locale), locale)
        w("servicios.html", build_services_index(locale), locale)
        for s in SERVICES:
            w(svc_url(s), build_service(s, locale), locale)
        w("nosotros.html", build_about(locale), locale)
        w("presencia.html", build_presence(locale), locale)
        w("donde-comprar.html", build_stores(locale), locale)
        w("rastreo.html", build_tracking(locale), locale)
        w("preguntas-frecuentes.html", build_faq(locale), locale)
        w("contacto.html", build_contact(locale), locale)
        w("gracias.html", build_thanks(locale), locale)
        w("terminos.html", build_terms(locale), locale)
        w("privacidad.html", build_privacy(locale), locale)
        w("404.html", build_404(locale), locale)

    w("sitemap.xml", build_sitemap())
    w("robots.txt", build_robots())
    w("site.webmanifest", build_manifest())
    w(".htaccess", build_htaccess())

    total = sum(n for _, n in written)
    print("Generated %d files, %.1f KB of markup\n" % (len(written), total / 1024))
    for f, n in written:
        print("  %-60s %6.1f KB" % (f, n / 1024))


if __name__ == "__main__":
    main()
