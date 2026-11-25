# -*- coding: utf-8 -*-
"""Reusable marketing widgets for WWC client feedback features."""
import html
import json
from urllib.parse import quote

from config import SITE
from i18n import load_ui, t, page_href, get_locale
from routes_data import (
    ORIGINS, DESTINATIONS, DEST_CARDS, RATE_TABLE, ASIA_ENTITY, ASIA_EXTENDED,
    STORE_RETAIL, STORE_WHOLESALE_CN, MAP_POINTS, ORIGIN_IDS, DEST_IDS,
    quote_matrix,
)


def _tr(locale):
    en = get_locale(locale).code == "en"
    return lambda es, en_: en_ if en else es


def _ph(filename, locale):
    return html.escape(page_href(filename, locale))


def _wa(locale, text, region=None):
    num = SITE["whatsapp_china"] if region == "china" else SITE["whatsapp"]
    return "https://wa.me/%s?text=%s" % (num, quote(text))


def route_quote_widget(locale="es", compact=False):
    """Origen / Destino selects + rate result + Cotizá gratis."""
    _ = _tr(locale)
    ui = load_ui(locale)
    o_opts = "".join(
        '<option value="%s">%s</option>' % (
            html.escape(o["id"]),
            html.escape(o["en"] if get_locale(locale).code == "en" else o["es"]),
        ) for o in ORIGINS)
    d_opts = "".join(
        '<option value="%s">%s</option>' % (
            html.escape(d["id"]),
            html.escape(d["en"] if get_locale(locale).code == "en" else d["es"]),
        ) for d in DESTINATIONS)
    matrix = quote_matrix("en" if get_locale(locale).code == "en" else "es")
    contact = _ph("contacto.html", locale)
    cls = "wwc-route-quote" + (" wwc-route-quote--compact" if compact else "")
    return f"""
<div class="{cls}" data-wwc-route-quote>
  <script type="application/json" class="wwc-route-data">{json.dumps(matrix, ensure_ascii=False)}</script>
  <div class="wwc-route-quote__fields">
    <label class="wwc-route-quote__field">
      <span>{_("Origen", "Origin")}</span>
      <select data-route-origin>
        <option value="">{_("Elegí país de origen…", "Choose origin country…")}</option>
        {o_opts}
      </select>
    </label>
    <label class="wwc-route-quote__field">
      <span>{_("Destino", "Destination")}</span>
      <select data-route-dest>
        <option value="">{_("Elegí país de destino…", "Choose destination country…")}</option>
        {d_opts}
      </select>
    </label>
  </div>
  <div class="wwc-route-quote__result" data-route-result hidden>
    <div data-route-modes></div>
    <a class="btn-main fx-slide" data-route-cta href="{contact}" data-hover="{_("Cotizá gratis", "Get a free quote")}"><span>{_("Cotizá gratis", "Get a free quote")}</span></a>
  </div>
  <p class="wwc-route-quote__hint" data-route-hint hidden>{_("No podés elegir el mismo país como origen y destino.", "You can't pick the same country as origin and destination.")}</p>
</div>
"""


def world_map_svg(locale="es"):
    """Stylized trade-lanes map: Asia → Miami → LatAm, with soft arcs and hub glow."""
    _ = _tr(locale)
    en = get_locale(locale).code == "en"
    labels = {
        "china": ("China", "China"), "taiwan": ("Taiwán", "Taiwan"),
        "thailand": ("Tailandia", "Thailand"), "bangladesh": ("Bangladés", "Bangladesh"),
        "miami": ("Miami Hub", "Miami Hub"),
        "argentina": ("Argentina", "Argentina"), "usa": ("EE. UU.", "USA"),
        "mexico": ("México", "Mexico"), "colombia": ("Colombia", "Colombia"),
        "ecuador": ("Ecuador", "Ecuador"), "peru": ("Perú", "Peru"),
        "bolivia": ("Bolivia", "Bolivia"), "chile": ("Chile", "Chile"),
        "brazil": ("Brasil", "Brazil"), "paraguay": ("Paraguay", "Paraguay"),
    }
    # Label offsets (dx, dy) so LatAm names don't stack
    label_off = {
        "china": (0, -16), "taiwan": (18, 4), "thailand": (0, 18), "bangladesh": (-8, -14),
        "miami": (0, -20),
        "usa": (-28, -4), "mexico": (-30, 6), "colombia": (22, -2), "ecuador": (-26, 4),
        "peru": (22, 2), "bolivia": (-24, 8), "chile": (20, 10), "brazil": (26, 0),
        "paraguay": (-8, 16), "argentina": (0, 18),
    }
    primary_dest = {"argentina", "brazil", "mexico", "usa", "colombia", "chile"}

    def lab(k):
        return labels[k][1] if en else labels[k][0]

    def lane(x1, y1, x2, y2, bend, cls):
        mx_ = (x1 + x2) / 2
        my_ = (y1 + y2) / 2 + bend
        c1x, c1y = x1 + (mx_ - x1) * 0.55, y1 + (my_ - y1) * 0.9
        c2x, c2y = x2 - (x2 - mx_) * 0.55, y2 + (my_ - y2) * 0.9
        d = "M%.1f %.1f C %.1f %.1f, %.1f %.1f, %.1f %.1f" % (x1, y1, c1x, c1y, c2x, c2y, x2, y2)
        return (
            '<path class="wwc-map__lane-glow %s" d="%s"/>'
            '<path class="wwc-map__lane %s" d="%s"/>' % (cls, d, cls, d)
        )

    mx, my = MAP_POINTS["miami"]
    paths = []
    for i, oid in enumerate(ORIGIN_IDS):
        ox, oy = MAP_POINTS[oid]
        paths.append(lane(ox, oy, mx, my, -55 - i * 12, "wwc-map__lane--in"))
    for i, did in enumerate(DEST_IDS):
        dx, dy = MAP_POINTS[did]
        bend = 18 if dy < my else 35 + (i % 4) * 8
        if dx < mx:
            bend = -abs(bend) * 0.4
        paths.append(lane(mx, my, dx, dy, bend if dy >= my else -28, "wwc-map__lane--out"))

    def hub(kind, key, r=5):
        x, y = MAP_POINTS[key]
        ox, oy = label_off.get(key, (0, 14))
        show = kind != "dest" or key in primary_dest
        text = (
            '<text class="wwc-map__label" x="%.1f" y="%.1f" text-anchor="middle">%s</text>'
            % (ox, oy, html.escape(lab(key)))
        ) if show else ""
        return (
            '<g class="wwc-map__hub wwc-map__hub--%s" transform="translate(%.1f %.1f)">'
            '<circle r="%.1f" class="wwc-map__ring"/>'
            '<circle r="%.1f" class="wwc-map__dot"/>'
            '%s%s</g>'
        ) % (
            kind, x, y,
            r + 6 if kind == "miami" else r + 3.5,
            r + 2 if kind == "miami" else r,
            '<circle r="16" class="wwc-map__pulse"/>' if kind in ("miami", "origin") else "",
            text,
        )

    hubs = [hub("origin", oid, 5.5) for oid in ORIGIN_IDS]
    hubs.append(hub("miami", "miami", 7))
    hubs += [hub("dest", did, 4.2 if did in primary_dest else 3.2) for did in DEST_IDS]

    return f"""
<figure class="wwc-map wow fadeInUp" aria-label="{_("Mapa de rutas WWC: Asia y USA hacia Latinoamérica vía Miami", "WWC route map: Asia and USA to Latin America via Miami")}">
  <svg class="wwc-map__svg" viewBox="0 0 1000 500" role="img" xmlns="http://www.w3.org/2000/svg">
    <title>{_("Rutas logísticas WWC", "WWC logistics lanes")}</title>
    <defs>
      <linearGradient id="wwcMapOcean" x1="0" y1="0" x2="0.9" y2="1">
        <stop offset="0%" stop-color="#07131f"/>
        <stop offset="55%" stop-color="#0e2438"/>
        <stop offset="100%" stop-color="#163a52"/>
      </linearGradient>
      <radialGradient id="wwcMapVignette" cx="50%" cy="45%" r="65%">
        <stop offset="55%" stop-color="#000" stop-opacity="0"/>
        <stop offset="100%" stop-color="#000" stop-opacity=".35"/>
      </radialGradient>
      <linearGradient id="wwcLaneIn" x1="0" y1="0" x2="1" y2="0">
        <stop offset="0%" stop-color="#f2a33c" stop-opacity=".15"/>
        <stop offset="50%" stop-color="#f2a33c" stop-opacity="1"/>
        <stop offset="100%" stop-color="#fff" stop-opacity=".9"/>
      </linearGradient>
      <linearGradient id="wwcLaneOut" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="#fff" stop-opacity=".95"/>
        <stop offset="100%" stop-color="#7dd3fc" stop-opacity=".85"/>
      </linearGradient>
      <filter id="wwcSoftGlow" x="-40%" y="-40%" width="180%" height="180%">
        <feGaussianBlur stdDeviation="3.5" result="b"/>
        <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
      </filter>
    </defs>
    <rect width="1000" height="500" fill="url(#wwcMapOcean)" rx="20"/>
    <g class="wwc-map__grid" opacity=".18">
      {''.join('<line x1="%d" y1="40" x2="%d" y2="460" stroke="#9ec9e8" stroke-width=".6"/>' % (x, x) for x in range(80, 960, 70))}
      {''.join('<line x1="60" y1="%d" x2="940" y2="%d" stroke="#9ec9e8" stroke-width=".6"/>' % (y, y) for y in range(70, 460, 55))}
    </g>
    <path class="wwc-map__land" d="M95 155c48-62 145-88 220-58 62 25 98 78 92 138-8 78-62 128-138 138-72 10-148-28-178-92-28-58-22-92 4-126z"/>
    <path class="wwc-map__land" d="M235 285c38-8 72 18 82 58 14 52-8 108-52 128-48 22-102-8-118-58-18-55 22-118 88-128z"/>
    <path class="wwc-map__land" d="M700 145c72-42 168-38 228 12 52 42 58 108 22 162-42 62-128 82-198 52-78-34-108-108-78-168 12-24 32-42 56-58z"/>
    <path class="wwc-map__land" d="M760 300c42 0 78 32 82 72 6 48-28 88-72 92-48 4-86-32-90-74-4-48 32-90 80-90z"/>
    <g class="wwc-map__lanes" filter="url(#wwcSoftGlow)">{''.join(paths)}</g>
    <g class="wwc-map__hubs">{''.join(hubs)}</g>
    <rect width="1000" height="500" fill="url(#wwcMapVignette)" rx="20" pointer-events="none"/>
  </svg>
  <figcaption class="wwc-map__cap">
    <span class="wwc-map__leg wwc-map__leg--origin">{_("Orígenes Asia", "Asia origins")}</span>
    <span class="wwc-map__leg wwc-map__leg--hub">{_("Hub Miami", "Miami hub")}</span>
    <span class="wwc-map__leg wwc-map__leg--dest">{_("Destinos LatAm + USA", "LatAm + USA destinations")}</span>
  </figcaption>
</figure>
"""


def pago_teaser(locale="es"):
    _ = _tr(locale)
    href = _ph("donde-comprar.html", locale) + "#pago-proveedores"
    return f"""
<section class="wwc-pago-teaser">
  <div class="container">
    <div class="wwc-pago-teaser__inner wow fadeInUp">
      <div>
        <div class="subtitle">{_("Pago a proveedores", "Supplier payments")}</div>
        <h2 class="split">{_("¿No tenés forma de pagar en China o USA?", "No way to pay in China or the USA?")}</h2>
        <p>{_("Compramos y pagamos a tu proveedor en el país de origen por vos, y llevamos la mercadería hasta tu país.",
               "We buy from and pay your supplier in the origin country for you, then ship the goods to your country.")}</p>
      </div>
      <a class="btn-main fx-slide" href="{href}" data-hover="{_("Ver pago a proveedores", "See supplier payments")}"><span>{_("Ver pago a proveedores", "See supplier payments")}</span></a>
    </div>
  </div>
</section>
"""


def pago_proveedores_section(locale="es"):
    """High-priority supplier payment block for Dónde comprar."""
    _ = _tr(locale)
    wa_es = "Hola, quiero que paguen a mi proveedor en China/USA."
    wa_en = "Hi, I want you to pay my supplier in China/USA."
    wa = _wa(locale, wa_en if get_locale(locale).code == "en" else wa_es)
    steps = [
        (_("Nos enviás la factura o el link de tu proveedor",
           "Send us your supplier's invoice or web link"), "fa-file-invoice"),
        (_("Pagamos en el origen en RMB (¥) o USD",
           "We pay at origin in RMB (¥) or USD"), "fa-yuan-sign"),
        (_("Consolidamos y enviamos a tu país con tracking",
           "We consolidate and ship to your country with tracking"), "fa-truck-fast"),
    ]
    steps_html = "".join(
        '<div class="wwc-pago__step wow fadeInUp" data-wow-delay=".%ss">'
        '<i class="fa-solid %s" aria-hidden="true"></i><p>%s</p></div>'
        % (i, ic, html.escape(tx)) for i, (tx, ic) in enumerate(steps))
    return f"""
<section class="wwc-pago" id="pago-proveedores">
  <div class="container">
    <div class="wwc-pago__card wow fadeInUp">
      <div class="subtitle">{_("Prioridad", "Priority")}</div>
      <h2 class="split">{_("¿Tenés dificultades para comprar o pagar a proveedores en el exterior?",
                          "Having trouble buying from or paying suppliers abroad?")}</h2>
      <p class="lead">{_("¿No tenés forma de pagar en China o USA? Compramos y pagamos a tu proveedor en el país de origen por vos, y llevamos la mercadería hasta tu país.",
                         "No way to pay in China or the USA? We buy from and pay your supplier in the origin country for you, then ship the goods to your country.")}</p>
      <p>{_("No frenes tu negocio. Gestionamos el pago directo a fábricas y tiendas en Asia en yuanes chinos o dólares, consolidamos tu mercadería y la preparamos para enviar a tu país.",
             "Don't pause your business. We arrange direct payment to factories and stores in Asia in Chinese yuan or US dollars, consolidate your goods and prepare them for shipment to your country.")}</p>
      <h3>{_("Compra asistida y pago a proveedores en origen",
             "Assisted purchasing and supplier payment at origin")}</h3>
      <p>{_("Si no contás con medios de pago internacionales, tarjetas habilitadas o cuentas en yuanes (RMB), pagamos nosotros a tu proveedor en China, Asia o USA bajo factura comercial y nos ocupamos de toda la logística hasta tu puerta.",
             "If you don't have international payment methods, enabled cards or RMB accounts, we pay your supplier in China, Asia or the USA under a commercial invoice and handle logistics through to your door.")}</p>
      <div class="wwc-pago__steps">{steps_html}</div>
      <div class="wwc-pago__actions">
        <a class="btn-main fx-slide" href="{wa}" target="_blank" rel="noopener" data-hover="{_("Pedí tu cotización de pago", "Request your payment quote")}"><span>{_("Pedí tu cotización de pago", "Request your payment quote")}</span></a>
      </div>
    </div>
  </div>
</section>
"""


def destination_cards(locale="es"):
    _ = _tr(locale)
    en = get_locale(locale).code == "en"
    cards = ""
    for i, c in enumerate(DEST_CARDS):
        name = c["en"] if en else c["es"]
        text = c["text_en"] if en else c["text_es"]
        href = _ph("contacto.html", locale) + "?destino=" + c["id"]
        cards += f"""
      <a class="wwc-dest-card wow fadeInUp" href="{href}" data-wow-delay=".{i % 5}s">
        <span class="wwc-dest-card__flag" aria-hidden="true">{c['flag']}</span>
        <h3>{html.escape(name)}</h3>
        <p>{html.escape(text)}</p>
        <span class="wwc-dest-card__cta">{_("Cotizar este destino", "Quote this destination")} →</span>
      </a>"""
    pago_href = _ph("donde-comprar.html", locale) + "#pago-proveedores"
    cards += f"""
      <a class="wwc-dest-card wwc-dest-card--pago wow fadeInUp" href="{pago_href}">
        <span class="wwc-dest-card__flag" aria-hidden="true">💳</span>
        <h3>{_("Compra asistida y pago a proveedores", "Assisted buying & supplier payments")}</h3>
        <p>{_("Compramos y pagamos a tu proveedor en China, Asia o USA y consolidamos el envío a tu país.",
               "We buy from and pay your supplier in China, Asia or the USA and consolidate shipping to your country.")}</p>
        <span class="wwc-dest-card__cta">{_("Ver sección de pago", "See payment section")} →</span>
      </a>"""
    return f"""
<section class="wwc-destinations">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">{_("Puerta a puerta e import/export", "Door-to-door & import/export")}</span>
      <h2 class="split">{_("¿Entregamos en tu país?", "Do we deliver to your country?")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".15s">{_("Courier aéreo, carga consolidada (LCL) o contenedor completo (FCL), con despacho aduanero según destino.",
                         "Air courier, consolidated cargo (LCL) or full container (FCL), with customs clearance by destination.")}</p>
    </div>
    <div class="wwc-dest-grid">{cards}
    </div>
  </div>
</section>
"""


def asia_entity_block(locale="es", footer=False):
    _ = _tr(locale)
    en = get_locale(locale).code == "en"
    a = ASIA_ENTITY
    stmt = a["statement_en"] if en else a["statement_es"]
    hk_l = a["hk_label_en"] if en else a["hk_label_es"]
    cn_l = a["cn_label_en"] if en else a["cn_label_es"]
    hk = "<br>".join(html.escape(x) for x in a["hk_lines"])
    cn = "<br>".join(html.escape(x) for x in a["cn_lines"])
    if footer:
        return f"""
            <div class="wwc-asia-footer">
              <p><strong>{html.escape(stmt)}</strong></p>
              <div class="wwc-asia-footer__cols">
                <div><span class="op-5 fs-14">{html.escape(hk_l)}</span><div class="fs-14 mt-1">{hk}</div></div>
                <div><span class="op-5 fs-14">{html.escape(cn_l)}</span><div class="fs-14 mt-1">{cn}</div></div>
              </div>
            </div>"""
    return f"""
<section class="wwc-asia-entity">
  <div class="container">
    <div class="wwc-asia-entity__card wow fadeInUp">
      <div class="subtitle">{_("Operaciones en Asia", "Asia operations")}</div>
      <h2 class="split">{html.escape(a['name'])}</h2>
      <p class="lead">{html.escape(stmt)}</p>
      <div class="wwc-asia-entity__grid">
        <article>
          <h3>{html.escape(hk_l)}</h3>
          <p>{hk}</p>
        </article>
        <article>
          <h3>{html.escape(cn_l)}</h3>
          <p>{cn}</p>
        </article>
      </div>
    </div>
  </div>
</section>
"""


def rates_estimate_section(locale="es"):
    _ = _tr(locale)
    en = get_locale(locale).code == "en"
    rt = RATE_TABLE
    rows = ""
    name_of = {d["id"]: (d["en"] if en else d["es"]) for d in DESTINATIONS}
    for s in rt["samples"]:
        rows += (
            "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>"
            % (html.escape(name_of[s["dest"]]), html.escape(s["air"]),
               html.escape(s["sea"]),
               html.escape(s["transit_en"] if en else s["transit_es"]))
        )
    note = rt["note_en"] if en else rt["note_es"]
    return f"""
<section class="wwc-rates" id="tarifas-estimadas">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">{_("Antes de registrarte", "Before you register")}</span>
      <h2 class="split">{_("Precios y plazos orientativos", "Indicative prices and transit times")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".15s">{_("Aéreo desde USD %s/kg · Marítimo desde USD %s/CBM",
                         "Air from USD %s/kg · Ocean from USD %s/CBM") % (rt['air_from_usd_kg'], rt['sea_from_usd_cbm'])}</p>
    </div>
    <div class="table-responsive wow fadeInUp">
      <table class="wwc-rates__table">
        <thead>
          <tr>
            <th>{_("Destino", "Destination")}</th>
            <th>{_("Aéreo", "Air")}</th>
            <th>{_("Marítimo", "Ocean")}</th>
            <th>{_("Plazo estimado", "Estimated transit")}</th>
          </tr>
        </thead>
        <tbody>{rows}</tbody>
      </table>
    </div>
    <p class="wwc-rates__note text-center">{html.escape(note)}</p>
  </div>
</section>
"""


def asia_extended_section(locale="es"):
    _ = _tr(locale)
    en = get_locale(locale).code == "en"
    cards = ""
    for i, a in enumerate(ASIA_EXTENDED):
        cards += f"""
      <article class="card wow fadeInUp" data-wow-delay=".{i}s">
        <div class="card-badge">{_("Asia extendida", "Extended Asia")}</div>
        <h3>{html.escape(a['en'] if en else a['es'])}</h3>
        <p>{html.escape(a['industries_en'] if en else a['industries_es'])}</p>
      </article>"""
    return f"""
<section class="section" id="asia-extendida">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow wow fadeInUp">Asia Extendida</span>
      <h2 class="split">{_("Más allá de China: Taiwán, Tailandia y Bangladés",
                          "Beyond China: Taiwan, Thailand and Bangladesh")}</h2>
      <p class="lead wow fadeInUp" data-wow-delay=".15s">{_("Industrias destacadas por país de origen para sourcing y consolidación.",
                         "Featured industries by origin country for sourcing and consolidation.")}</p>
    </div>
    <div class="wwc-grid wwc-g3">{cards}
    </div>
  </div>
</section>
"""


def store_split_blocks(locale="es", icon_fn=None):
    """Retail vs China wholesale store lists."""
    _ = _tr(locale)

    def list_html(stores):
        return "".join(
            (
                '<li><a class="wwc-store-link" href="https://www.%s" target="_blank" rel="noopener nofollow">'
                '<span class="wwc-store-link__icon">'
                '<img src="https://www.google.com/s2/favicons?domain=%s&amp;sz=64" alt="" width="20" height="20" loading="lazy" decoding="async">'
                '</span><span>%s</span></a></li>'
            ) % (html.escape(dom), html.escape(dom), html.escape(lbl))
            for lbl, dom in stores)

    ic = icon_fn or (lambda n: "")
    retail = f"""
      <article class="card wow fadeInUp">
        <div class="card-icon">{ic('cart')}</div>
        <h3>{_("Tiendas minoristas (USA & China)", "Retail stores (USA & China)")}</h3>
        <p class="mb-3">{_("Paquetes chicos con envío frecuente a casillero. Ideal AliExpress, Temu, DHgate y marketplaces USA.",
                           "Small parcels often shipped to your mailbox. Ideal for AliExpress, Temu, DHgate and US marketplaces.")}</p>
        <ul class="wwc-store-list">{list_html(STORE_RETAIL)}</ul>
      </article>"""
    wholesale = f"""
      <article class="card wow fadeInUp" data-wow-delay=".15s">
        <div class="card-icon">{ic('china')}</div>
        <h3>{_("Plataformas mayoristas y fábricas en China", "China wholesale platforms & factories")}</h3>
        <p class="mb-3"><strong>{_("Pagamos a proveedores de 1688 y Alibaba en yuanes (RMB)",
                                   "We pay 1688 and Alibaba suppliers in Chinese yuan (RMB)")}</strong>
           {_("y consolidamos la mercadería en nuestro depósito.",
              "and consolidate goods in our warehouse.")}</p>
        <ul class="wwc-store-list">{list_html(STORE_WHOLESALE_CN)}</ul>
      </article>"""
    return retail + wholesale
