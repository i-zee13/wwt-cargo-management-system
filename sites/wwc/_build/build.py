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
from i18n import LOCALES, get_locale, load_ui, load_home, t, asset, abs_url
from layouts import render, svc_url, svc_name, wa_link

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
def crumbs(trail):
    out = ['<div class="crumb">']
    for i, (label, href) in enumerate(trail):
        if i:
            out.append('<span class="sep">/</span>')
        if href:
            out.append('<a href="%s">%s</a>' % (href, html.escape(label)))
        else:
            out.append('<span aria-current="page">%s</span>' % html.escape(label))
    out.append("</div>")
    return "".join(out)


def page_hero(h1, lead, trail, locale="es"):
    bg = asset("assets/lx/img/l1.webp", locale)
    return f"""
<section id="subheader" class="text-light" data-bgimage="url({bg})">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <h1>{h1}</h1>
        <p class="lead">{lead}</p>
        {crumbs(trail)}
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
    return f"""      <div class="col-lg-3 col-md-6">
        <a href="{svc_url(s)}" class="d-block hover relative">
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
    for st in content(locale)["STATS"]:
        cells += f"""      <div class="col-md-3 text-center">
        <h2 class="mb-1">{html.escape(st['num'])}{html.escape(st['suffix'])}</h2>
        <p class="mb-0">{html.escape(st['label'])}</p>
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


def cta_band(locale="es", title=None, text=None, primary=None, secondary=None):
    ui = load_ui(locale)
    title = title or t(ui, "cta.ready_title")
    text = text or t(ui, "cta.ready_text")
    p_label, p_href = primary or (t(ui, "register_free"), None)
    s_label, s_href = secondary or (t(ui, "talk_whatsapp"), None)
    p_href = p_href or SITE["register_url"]
    s_href = s_href or wa_link(locale)
    return f"""
<section class="bg-color text-light pt-50 pb-50">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-8">
        <h3 class="mb-2 fs-32">{html.escape(title)}</h3>
        <p class="mb-0">{html.escape(text)}</p>
      </div>
      <div class="col-md-4 text-lg-end">
        <a class="btn-main fx-slide me-2 mb-2" href="{p_href}" data-hover="{html.escape(p_label)}"><span>{html.escape(p_label)}</span></a>
        <a class="btn-main btn-line fx-slide" href="{s_href}" target="_blank" rel="noopener" data-hover="{html.escape(s_label)}"><span>{html.escape(s_label)}</span></a>
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
    os.makedirs(os.path.dirname(path), exist_ok=True)
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
    for sl in h["slides"]:
        tags_html = "".join("<div><h6>%s</h6></div>" % html.escape(x) for x in sl["tags"])
        slide_html += f"""
                        <div class="swiper-slide">
                            <div class="swiper-inner" data-bgimage="url({img('assets/lx/img/' + sl['img'], locale)})">
                                <div class="sw-caption">
                                    <div class="container">
                                        <div class="row gx-5 align-items-center">
                                            <div class="col-lg-6">
                                                <div class="sw-text-wrapper">
                                                    <h1 class="text-uppercase">{html.escape(sl['title'])}</h1>
                                                    <div>
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
                        <div class="col-lg-4 col-md-6">
                            <a href="{p['href']}">
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
                        <div class="col-lg-3 col-md-6">
                            <a href="{svc_url(s)}" class="d-block hover relative">
                                <img src="{img('assets/lx/img/' + icons[i], locale)}" class="w-70px mb-3 hover-jello infinite" alt="">
                                <h4>{html.escape(svc_name(s, locale))}</h4>
                                <p>{html.escape(sf(s, 'blurb', locale))}</p>
                            </a>
                        </div>"""

    steps = ""
    for pstep in c["PROCESS"]:
        steps += f"""
                        <div class="col-lg-3 col-md-6">
                            <h4>{html.escape(pstep['title'])}</h4>
                            <p>{html.escape(pstep['text'])}</p>
                        </div>"""

    why = ""
    for item in c["WHY_US"]:
        why += f"""
                        <div class="col-lg-4 col-md-6">
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
                                        <a href="{g['href']}">
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
        '<span class="d-item-txt">%s</span><span class="d-item-block"></span>' % html.escape(s)
        for s in STORES)

    rating_text = html.escape(h["rating"]["text"].format(
        rating=SITE["google_rating"], reviews=SITE["google_reviews"]))
    sv, pr, wh = h["services"], h["process"], h["why"]
    st, fq = h["stores"], h["faq"]

    body = f"""
            <section class="text-light no-top no-bottom relative rounded-1 overflow-hidden m-3 sm-mt-90">
                <div class="mh-800">
                    <div class="swiper">
                      <div class="swiper-wrapper">
{slide_html}
                      </div>
                      <div class="swiper-button-prev"></div>
                      <div class="swiper-button-next"></div>
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
                            <div class="subtitle">{html.escape(sv['subtitle'])}</div>
                            <h2 class="split">{html.escape(sv['title'])}</h2>
                            <p>{html.escape(sv['lead'])}</p>
                        </div>
                    </div>
                    <div class="row g-4 gx-5">{svc_html}</div>
                    <div class="text-center mt-4">
                        <a class="btn-main" href="servicios.html" data-hover="{html.escape(sv['button'])}"><span>{html.escape(sv['button'])}</span></a>
                    </div>
                </div>
            </section>

            <section>
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="subtitle">{html.escape(pr['subtitle'])}</div>
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
                            <div class="subtitle">{html.escape(wh['subtitle'])}</div>
                            <h2 class="split">{html.escape(wh['title'])}</h2>
                        </div>
                    </div>
                    <div class="row g-4">{why}</div>
                </div>
            </section>

            <section class="bg-dark text-light pt-0 overflow-hidden">
                <div class="de-marquee-list-1">{stores}</div>
                <div class="text-center pb-5">
                    <a class="btn-main btn-line" href="donde-comprar.html" data-hover="{html.escape(st['hover'])}"><span>{html.escape(st['button'])}</span></a>
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
                            <div class="subtitle id-color">{html.escape(fq['subtitle'])}</div>
                            <h2 class="split">{html.escape(fq['title'])}</h2>
                            <a class="btn-main" href="preguntas-frecuentes.html" data-hover="{html.escape(fq['hover'])}"><span>{html.escape(fq['button'])}</span></a>
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
    cards = "".join(service_card(s, locale) for s in SERVICES)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.services"), None)]
    body = page_hero(
        _("Catorce servicios, una sola cadena logística", "Fourteen services, one logistics chain"),
        _("Desde una compra personal en Amazon hasta la importación de un contenedor completo. Todo con infraestructura propia en Estados Unidos y China.",
          "From a personal Amazon purchase to importing a full container. All with our own infrastructure in the United States and China."),
        trail, locale
    ) + f"""
{trust_strip(locale)}

<section class="section">
  <div class="container">
    <div class="row g-4">
{cards}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container container-narrow">
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("No sabés cuál necesitás", "Not sure which one you need")}</span>
      <h2 class="balance">{_("Contanos qué querés mover y te decimos cómo", "Tell us what you want to move and we'll tell you how")}</h2>
      <p class="lead">{_("Un mensaje corto con lo que necesitás es suficiente para que te orientemos sobre el servicio que te conviene y su costo aproximado.",
                         "A short message about what you need is enough for us to point you to the right service and give you an approximate cost.")}</p>
    </div>
    <div class="text-center" data-reveal>
      <a class="btn btn--primary btn--lg" href="contacto.html">{_("Pedir asesoramiento", "Get advice")} {icon('arrow-right')}</a>
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


# ══════════════════════════════════════════════════════════════ SERVICE DETAIL
def build_service(s, locale="es"):
    _ = tr(locale)
    ui = load_ui(locale)
    name = svc_name(s, locale)
    trail = [(t(ui, "common.home_crumb"), "index.html"), (t(ui, "nav.services"), "servicios.html"), (name, None)]

    benefits = "".join(
        f'<li>{icon("check")}<span>{html.escape(b)}</span></li>' for b in sf(s, "benefits", locale))

    body_sections = ""
    sections = s["sections"] if not is_en(locale) else s.get("sections_en", [])
    for heading, paras in sections:
        body_sections += "<h2>%s</h2>\n" % html.escape(heading)
        for p in paras:
            body_sections += (p if p.lstrip().startswith("<") else "<p>%s</p>" % p) + "\n"
    if is_en(locale) and not sections:
        body_sections = ("<p>Every project is different. Tell us about yours and we'll send a tailored "
                         "proposal with costs and timelines.</p>\n")

    side_nav = "".join(
        '<a href="%s"%s>%s</a>' % (svc_url(o), ' aria-current="page"' if o["slug"] == s["slug"] else "",
                                   html.escape(svc_name(o, locale)))
        for o in SERVICES)

    related = [o for o in SERVICES if o["slug"] != s["slug"]][:3]
    rel_cards = "".join(service_card(o, locale, badge=False) for o in related)
    wa_text = _("Hola, quiero consultar por: ", "Hello, I'd like to ask about: ") + name

    body = page_hero(html.escape(sf(s, "h1", locale)), html.escape(sf(s, "intro", locale)), trail, locale) + f"""
<section class="section">
  <div class="container">
    <div class="layout-sidebar">

      <div class="prose" data-reveal>
        <div class="callout">
          <b>{_("En resumen", "In short")}</b>
          {html.escape(sf(s, 'blurb', locale))}
        </div>

        <h2>{_("Lo que obtenés", "What you get")}</h2>
        <ul class="check-list">{benefits}</ul>

{body_sections}
        <div class="callout" style="border-color:var(--amber)">
          <b>{_("Siguiente paso", "Next step")}</b>
          {html.escape(sf(s, 'cta', locale))}
        </div>

        <div class="flex gap-3 wrap mt-5">
          <a class="btn btn--primary" href="contacto.html">{_("Pedir cotización", "Request a quote")} {icon('arrow-right')}</a>
          <a class="btn btn--ghost" href="{wa_link(locale, wa_text)}" target="_blank" rel="noopener">{icon('whatsapp')} {_("Consultar por WhatsApp", "Ask on WhatsApp")}</a>
        </div>
      </div>

      <aside class="sidebar">
        <div class="side-card">
          <h4>{_("Todos los servicios", "All services")}</h4>
          <nav class="side-nav" aria-label="{_("Otros servicios", "Other services")}">{side_nav}</nav>
        </div>
        <div class="side-card side-card--dark">
          <h4>{_("Hablemos de tu caso", "Let's talk about your case")}</h4>
          <p>{_("Contanos qué necesitás mover y te respondemos con una propuesta concreta.", "Tell us what you need to move and we'll reply with a concrete proposal.")}</p>
          <a class="btn btn--primary btn--block btn--sm" href="tel:{SITE['phone_raw']}">{icon('phone')} {SITE['phone_display']}</a>
          <div style="height:.6rem"></div>
          <a class="btn btn--ghost btn--block btn--sm" href="mailto:{SITE['email']}">{icon('mail')} {_("Escribinos", "Email us")}</a>
        </div>
        <div class="side-card">
          <h4>{html.escape(t(ui, 'track.title'))}</h4>
          <form class="form-grid" data-track-form>
            <div class="field">
              <label for="trk-side">{_("Código de seguimiento", "Tracking code")}</label>
              <input id="trk-side" name="codigo" type="text" placeholder="WWC-128374" required>
            </div>
            <button class="btn btn--dark btn--block btn--sm" type="submit">{icon('search')} {html.escape(t(ui, 'track.button'))}</button>
          </form>
        </div>
      </aside>

    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container">
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("También te puede interesar", "You may also like")}</span>
      <h2>{_("Otros servicios", "Other services")}</h2>
    </div>
    <div class="row g-4">
{rel_cards}    </div>
  </div>
</section>

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
    for o in content(locale)["OFFICES"]:
        lines = "<br>".join(html.escape(l) for l in o["lines"])
        offices += f"""      <article class="card">
        <div class="card-icon">{icon('pin')}</div>
        <h3>{html.escape(o['city'])}</h3>
        <p><strong>{html.escape(o['label'])}</strong><br>{lines}</p>
      </article>
"""
    body = page_hero(
        _("Veinte años construyendo una puerta al mundo", "Twenty years building a doorway to the world"),
        _("Empezamos resolviendo un problema concreto: la gente quería comprar afuera y no podía. Hoy movemos carga entre tres continentes con infraestructura propia.",
          "We started by solving a concrete problem: people wanted to buy abroad and couldn't. Today we move cargo across three continents with our own infrastructure."),
        trail, locale
    ) + f"""
{trust_strip(locale)}

<section class="section">
  <div class="container">
    <div class="wwc-split">
      <div data-reveal="left">
        <span class="eyebrow">{_("Quiénes somos", "Who we are")}</span>
        <h2>{_("Un operador logístico, no un intermediario", "A logistics operator, not a middleman")}</h2>
        <p><strong>{SITE['legal']}</strong> {_("nació de una observación simple: miles de personas en Latinoamérica querían comprar productos que no llegaban a su país, y las que lo intentaban se topaban con tarjetas rechazadas, tiendas que no enviaban al exterior y costos que aparecían recién al final.",
          "was born from a simple observation: thousands of people in Latin America wanted to buy products that didn't reach their country, and those who tried ran into declined cards, stores that didn't ship abroad and costs that only showed up at the end.")}</p>
        <p>{_("Más de dos décadas después seguimos resolviendo ese problema, pero el alcance creció. Hoy operamos almacenes propios en Miami y en tres ciudades de China, tenemos oficina regional en Asunción, y atendemos tanto a la persona que compra unas zapatillas como a la empresa que importa pallets todos los meses.",
          "More than two decades later we're still solving that problem, but our reach has grown. Today we run our own warehouses in Miami and in three Chinese cities, have a regional office in Asunción, and serve both the person buying a pair of sneakers and the company importing pallets every month.")}</p>
        <p>{_("La diferencia que más valoran nuestros clientes es la que menos se ve desde afuera: cuando algo se complica —una retención en aduana, un proveedor que entrega mal, un paquete que no aparece— del otro lado hay alguien que ya pasó por eso cientos de veces.",
          "The difference our customers value most is the one that's least visible from the outside: when something goes wrong — a customs hold, a supplier who delivers badly, a package that doesn't show up — there's someone on the other end who has been through it hundreds of times.")}</p>
        <ul class="check-list">
          <li>{icon('check')}<span><strong>{_("Infraestructura propia", "Our own infrastructure")}</strong>{_(", no espacio alquilado ni manipulación tercerizada", ", not rented space or outsourced handling")}</span></li>
          <li>{icon('check')}<span><strong>{_("Equipo hispanohablante", "Spanish-speaking team")}</strong> {_("por WhatsApp, teléfono y correo", "by WhatsApp, phone and email")}</span></li>
          <li>{icon('check')}<span><strong>{_("Tecnología desarrollada en casa", "In-house technology")}</strong>{_(", por quienes operan la logística", ", built by the people who run the logistics")}</span></li>
          <li>{icon('check')}<span><strong>{_("Precio cerrado antes de despachar", "Fixed price before dispatch")}</strong>{_(", con los conceptos desglosados", ", with itemized charges")}</span></li>
        </ul>
      </div>
      <div class="split-media" data-reveal="right">
        <img src="{img('assets/lx/img/s2.webp', locale)}" alt="{_("Operación logística de World Wide Commerce", "World Wide Commerce logistics operation")}" width="800" height="600" loading="lazy">
        <div class="media-tag">{icon('warehouse')} 2355 NW 70th Ave · Miami</div>
      </div>
    </div>
  </div>
</section>

{stats_band(locale)}

<section class="section">
  <div class="container">
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("Presencia", "Presence")}</span>
      <h2 class="balance">{_("Dónde estamos", "Where we are")}</h2>
      <p class="lead">{_("Operación propia en los tres puntos que importan: el origen de la mercadería, el nodo de consolidación y el mercado de destino.",
                         "Our own operations at the three points that matter: the origin of the goods, the consolidation hub and the destination market.")}</p>
    </div>
    <div class="wwc-grid wwc-g3" data-stagger>
{offices}    </div>
  </div>
</section>

<section class="section section--paper">
  <div class="container">
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("Cómo trabajamos", "How we work")}</span>
      <h2 class="balance">{_("Tres compromisos que sostenemos", "Three commitments we keep")}</h2>
    </div>
    <div class="wwc-grid wwc-g3" data-stagger>
      <article class="card">
        <div class="card-icon">{icon('shield')}</div>
        <h3>{_("Transparencia en el precio", "Price transparency")}</h3>
        <p>{_("Te mostramos el presupuesto desglosado antes de despachar. Si durante el proceso aparece un costo no previsto, lo autorizás vos antes de que se genere. Sin impuestos ocultos: lo decimos porque es la queja más común sobre este rubro.",
              "We show you an itemized quote before dispatch. If an unexpected cost comes up along the way, you approve it before it's incurred. No hidden fees: we say it because it's the most common complaint in this industry.")}</p>
      </article>
      <article class="card">
        <div class="card-icon">{icon('users')}</div>
        <h3>{_("Una persona, no un formulario", "A person, not a form")}</h3>
        <p>{_("Cada cuenta tiene un interlocutor que conoce su historial. No vas a tener que explicar tu caso de nuevo cada vez que escribís, ni esperar que un sistema de tickets te asigne a alguien distinto.",
              "Every account has a contact who knows its history. You won't have to explain your case again every time you write, or wait for a ticket system to assign you to someone different.")}</p>
      </article>
      <article class="card">
        <div class="card-icon">{icon('check')}</div>
        <h3>{_("Verificación antes de embarcar", "Verification before shipping")}</h3>
        <p>{_("Inspeccionamos y fotografiamos la mercadería cuando ingresa al almacén. Si algo llegó dañado o incompleto te enterás mientras todavía se puede reclamar, no cuando el paquete ya cruzó el continente.",
              "We inspect and photograph goods when they arrive at the warehouse. If something arrived damaged or incomplete, you find out while you can still file a claim, not after the package has crossed the continent.")}</p>
      </article>
    </div>
  </div>
</section>

<!-- RESEÑAS / REVIEWS
     Intentionally EMPTY of testimonials. Do not add invented reviews.
     Paste REAL Google reviews here (76 reviews, 5.0 average) using:
       <article class="quote-card">
         <div class="stars">icon('star') x5</div>
         <blockquote>Literal text of the real review.</blockquote>
         <div class="quote-meta"><span class="avatar">XX</span><div><b>Customer name</b><span>Google review</span></div></div>
       </article>
     Alternative: embed the official Google Reviews / Trustindex widget. -->
<section class="section">
  <div class="container container-narrow text-center">
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("Opiniones", "Reviews")}</span>
      <h2 class="balance">{_(f"{SITE['google_rating']} de 5 en Google, sobre {SITE['google_reviews']} reseñas", f"{SITE['google_rating']} out of 5 on Google, based on {SITE['google_reviews']} reviews")}</h2>
      <p class="lead">{_("Nuestra calificación la escriben los clientes, no nuestro equipo de marketing.", "Our rating is written by customers, not by our marketing team.")}</p>
    </div>
    <div class="stars mt-5" style="justify-content:center;transform:scale(1.6)" aria-hidden="true">
      {icon('star')}{icon('star')}{icon('star')}{icon('star')}{icon('star')}
    </div>
    <p class="mt-6"><a class="btn btn--ghost" href="https://www.google.com/search?q=worldwide+commerce+miami+reviews" target="_blank" rel="noopener">{_("Leer las reseñas en Google", "Read our reviews on Google")} {icon('arrow-right')}</a></p>
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
          f'<li><a href="https://www.{dom}" target="_blank" rel="noopener nofollow">{icon("chevron-right")}{html.escape(lbl)}</a></li>'
          for lbl, dom in stores)
        blocks += f"""      <article class="card">
        <div class="card-icon">{icon(ic)}</div>
        <h3>{html.escape(_(*names))}</h3>
        <ul class="side-nav" style="margin-top:.5rem">{items}</ul>
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
    <div class="callout mb-6" data-reveal>
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
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("Si la tienda no te deja", "If the store won't let you")}</span>
      <h2 class="balance">{_("¿Te rechazaron la tarjeta o no envían al exterior?", "Card declined or no shipping abroad?")}</h2>
      <p class="lead">{_("Es más común de lo que parece, y tiene solución: compramos nosotros a tu nombre con medios de pago locales y el producto entra directo a tu casillero.",
                         "It's more common than it seems, and it has a fix: we buy on your behalf with local payment methods and the item goes straight to your mailbox.")}</p>
    </div>
    <div class="text-center" data-reveal>
      <a class="btn btn--primary btn--lg" href="servicio-realizamos-tus-compras.html">{_("Ver el servicio de compras asistidas", "See the assisted shopping service")} {icon('arrow-right')}</a>
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
    <div class="section-head center" data-reveal>
      <span class="eyebrow">{_("Demoras", "Delays")}</span>
      <h2 class="balance">{_("¿Tu envío no se mueve hace días?", "Hasn't your shipment moved in days?")}</h2>
    </div>
    <div data-reveal>
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
    <div data-reveal>
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

      <div data-reveal>
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
          <a class="btn btn--primary btn--block btn--sm" href="{wa_link(locale)}" target="_blank" rel="noopener">{icon('whatsapp')} WhatsApp</a>
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
    <div class="prose" data-reveal>
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
    <a class="btn-main me-2" href="index.html" data-hover="{e('nav.home')}"><span>{e('not_found.back_home')}</span></a>
    <a class="btn-main btn-line" href="contacto.html" data-hover="{e('not_found.contact')}"><span>{e('not_found.contact')}</span></a>
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
<li><a href="servicios.html">{_("Conocé los 14 servicios", "Discover the 14 services")}</a> {_("que ofrecemos", "we offer")}</li>
<li><a href="donde-comprar.html">{_("Mirá dónde podés comprar", "See where you can shop")}</a> {_("con tu casillero", "with your mailbox")}</li>
<li><a href="preguntas-frecuentes.html">{_("Revisá las preguntas frecuentes", "Check the frequently asked questions")}</a></li>
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
<p>El tratamiento de datos personales se rige por nuestra <a href="privacidad.html">Política de privacidad</a>.</p>
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
<p>The processing of personal data is governed by our <a href="privacidad.html">Privacy Policy</a>.</p>
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
            ("nosotros.html", "0.7", "monthly"), ("donde-comprar.html", "0.7", "monthly"),
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
Disallow: /gracias.html
Disallow: /404.html
Disallow: /en/gracias.html
Disallow: /en/404.html
Disallow: /_build/

Sitemap: {SITE['domain']}/sitemap.xml
"""


def build_manifest():
    return json.dumps({
      "name": SITE["name"], "short_name": SITE["short"],
      "description": "Comprá en USA o China y recibí en Argentina.",
      "start_url": "/index.html", "display": "standalone",
      "background_color": "#081021", "theme_color": "#081021", "lang": "es",
      "icons": [{"src": "assets/img/brand/favicon.svg", "sizes": "any", "type": "image/svg+xml"},
                {"src": "assets/img/brand/icon-192.png", "sizes": "192x192", "type": "image/png"},
                {"src": "assets/img/brand/icon-512.png", "sizes": "512x512", "type": "image/png"}]
    }, ensure_ascii=False, indent=2)


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

    total = sum(n for _, n in written)
    print("Generated %d files, %.1f KB of markup\n" % (len(written), total / 1024))
    for f, n in written:
        print("  %-60s %6.1f KB" % (f, n / 1024))


if __name__ == "__main__":
    main()
