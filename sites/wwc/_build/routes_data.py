# -*- coding: utf-8 -*-
"""Origins, destinations, indicative rates, supplier-payment copy, map hubs."""

ORIGINS = [
    {"id": "china", "es": "China", "en": "China", "group": "asia"},
    {"id": "taiwan", "es": "Taiwán", "en": "Taiwan", "group": "asia"},
    {"id": "thailand", "es": "Tailandia", "en": "Thailand", "group": "asia"},
    {"id": "bangladesh", "es": "Bangladés", "en": "Bangladesh", "group": "asia"},
    {"id": "usa", "es": "Estados Unidos", "en": "United States", "group": "americas"},
]

DESTINATIONS = [
    {"id": "argentina", "es": "Argentina", "en": "Argentina", "flag": "🇦🇷"},
    {"id": "usa", "es": "Estados Unidos", "en": "United States", "flag": "🇺🇸"},
    {"id": "mexico", "es": "México", "en": "Mexico", "flag": "🇲🇽"},
    {"id": "colombia", "es": "Colombia", "en": "Colombia", "flag": "🇨🇴"},
    {"id": "ecuador", "es": "Ecuador", "en": "Ecuador", "flag": "🇪🇨"},
    {"id": "peru", "es": "Perú", "en": "Peru", "flag": "🇵🇪"},
    {"id": "bolivia", "es": "Bolivia", "en": "Bolivia", "flag": "🇧🇴"},
    {"id": "chile", "es": "Chile", "en": "Chile", "flag": "🇨🇱"},
    {"id": "brazil", "es": "Brasil", "en": "Brazil", "flag": "🇧🇷"},
    {"id": "paraguay", "es": "Paraguay", "en": "Paraguay", "flag": "🇵🇾"},
]

# Indicative "from" rates (client-facing estimates before formal quote).
# Keys: (origin_id, dest_id) → list of modes
def _modes(air_kg, air_days, sea_cbm=None, sea_weeks=None, extra=None):
    out = [
        {"mode": "air", "from_usd": air_kg, "unit": "kg",
         "transit_es": air_days, "transit_en": air_days},
    ]
    if sea_cbm is not None:
        out.append({
            "mode": "sea", "from_usd": sea_cbm, "unit": "cbm",
            "transit_es": sea_weeks, "transit_en": sea_weeks,
        })
    if extra:
        out.extend(extra)
    return out


_DEFAULT_LATAM = _modes(
    8, "Express 4–7 días · Estándar 12–15 días",
    95, "Marítimo aprox. 8–10 semanas",
)
_DEFAULT_LATAM_EN = _modes(
    8, "Express 4–7 days · Standard 12–15 days",
    95, "Ocean approx. 8–10 weeks",
)

# Per-destination card copy (services page) — Spanish primary
DEST_CARDS = [
    {
        "id": "argentina",
        "es": "Argentina", "en": "Argentina", "flag": "🇦🇷",
        "text_es": "Express (4 a 7 días hábiles), Estándar (12 a 15 días) y envío marítimo (aprox. 8 a 10 semanas). Despacho aduanero incluido, entrega a domicilio.",
        "text_en": "Express (4–7 business days), Standard (12–15 days) and ocean freight (approx. 8–10 weeks). Customs clearance included, home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "usa",
        "es": "Estados Unidos", "en": "United States", "flag": "🇺🇸",
        "text_es": "Courier aéreo y carga consolidada LCL. Gestionamos importación y despacho aduanero hasta la entrega en tu domicilio.",
        "text_en": "Air courier and consolidated LCL cargo. We handle import and customs clearance through delivery to your door.",
        "modes": ["air", "lcl"],
    },
    {
        "id": "mexico",
        "es": "México", "en": "Mexico", "flag": "🇲🇽",
        "text_es": "Courier aéreo, LCL y contenedor completo FCL. Gestionamos documentación, despacho aduanero y entrega a domicilio.",
        "text_en": "Air courier, LCL and full-container FCL. We handle documentation, customs clearance and home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "colombia",
        "es": "Colombia", "en": "Colombia", "flag": "🇨🇴",
        "text_es": "Courier aéreo, LCL y FCL. Gestionamos documentación y despacho aduanero con entrega a domicilio.",
        "text_en": "Air courier, LCL and FCL. We handle documentation and customs clearance with home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "ecuador",
        "es": "Ecuador", "en": "Ecuador", "flag": "🇪🇨",
        "text_es": "Courier aéreo, LCL y FCL. Gestionamos documentación y despacho aduanero con entrega a domicilio.",
        "text_en": "Air courier, LCL and FCL. We handle documentation and customs clearance with home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "peru",
        "es": "Perú", "en": "Peru", "flag": "🇵🇪",
        "text_es": "Courier aéreo, LCL y FCL. Gestionamos documentación y despacho aduanero con entrega a domicilio.",
        "text_en": "Air courier, LCL and FCL. We handle documentation and customs clearance with home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "bolivia",
        "es": "Bolivia", "en": "Bolivia", "flag": "🇧🇴",
        "text_es": "Courier aéreo y LCL; FCL vía puerto de tránsito. Gestionamos tránsito y despacho aduanero hasta tu ciudad.",
        "text_en": "Air courier and LCL; FCL via a transit port. We manage transit and customs clearance through to your city.",
        "modes": ["air", "lcl", "fcl-transit"],
    },
    {
        "id": "chile",
        "es": "Chile", "en": "Chile", "flag": "🇨🇱",
        "text_es": "Courier aéreo, LCL y FCL. Gestionamos documentación y despacho aduanero con entrega a domicilio.",
        "text_en": "Air courier, LCL and FCL. We handle documentation and customs clearance with home delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "brazil",
        "es": "Brasil", "en": "Brazil", "flag": "🇧🇷",
        "text_es": "Courier aéreo, LCL y FCL. La importación tiene requisitos específicos; te orientamos antes del embarque.",
        "text_en": "Air courier, LCL and FCL. Import has specific requirements; we guide you before shipment.",
        "modes": ["air", "lcl", "fcl"],
    },
    {
        "id": "paraguay",
        "es": "Paraguay", "en": "Paraguay", "flag": "🇵🇾",
        "text_es": "Courier aéreo, LCL y FCL, con presencia operativa propia en Paraguay. Gestionamos despacho aduanero y entrega.",
        "text_en": "Air courier, LCL and FCL, with our own operational presence in Paraguay. We handle customs clearance and delivery.",
        "modes": ["air", "lcl", "fcl"],
    },
]

# Starting rate table shown on Dónde comprar (before register CTA)
RATE_TABLE = {
    "air_from_usd_kg": 6,
    "sea_from_usd_cbm": 85,
    "note_es": "Valores orientativos «desde». El precio final depende de peso/volumen, origen, destino y tipo de carga. Pedí tu cotización gratuita.",
    "note_en": "Indicative «from» rates. Final price depends on weight/volume, origin, destination and cargo type. Request your free quote.",
    "samples": [
        {"dest": "argentina", "air": "desde USD 8/kg", "sea": "desde USD 95/CBM",
         "transit_es": "Aéreo 4–15 días · Marítimo 8–10 sem.",
         "transit_en": "Air 4–15 days · Ocean 8–10 wks"},
        {"dest": "usa", "air": "desde USD 6/kg", "sea": "desde USD 85/CBM",
         "transit_es": "Aéreo 3–10 días · Marítimo 4–6 sem.",
         "transit_en": "Air 3–10 days · Ocean 4–6 wks"},
        {"dest": "peru", "air": "desde USD 8/kg", "sea": "desde USD 95/CBM",
         "transit_es": "Aéreo 5–15 días · Marítimo 8–10 sem.",
         "transit_en": "Air 5–15 days · Ocean 8–10 wks"},
        {"dest": "mexico", "air": "desde USD 7/kg", "sea": "desde USD 90/CBM",
         "transit_es": "Aéreo 4–12 días · Marítimo 6–8 sem.",
         "transit_en": "Air 4–12 days · Ocean 6–8 wks"},
        {"dest": "paraguay", "air": "desde USD 7/kg", "sea": "desde USD 90/CBM",
         "transit_es": "Aéreo 4–12 días · Marítimo 7–9 sem.",
         "transit_en": "Air 4–12 days · Ocean 7–9 wks"},
    ],
}

ASIA_ENTITY = {
    "name": "World Wide Commerce Group, Limited (Hong Kong)",
    "statement_es": "Las operaciones en Asia se realizan bajo World Wide Commerce Group, Limited (Hong Kong).",
    "statement_en": "Asia operations are conducted under World Wide Commerce Group, Limited (Hong Kong).",
    "hk_label_es": "Oficina registrada en Hong Kong",
    "hk_label_en": "Hong Kong registered office",
    "hk_lines": [
        "Unit 2–16, 3/F, Join-In Hang Sing Centre",
        "71–75 Container Port Road, Kwai Chung",
        "New Territories, Hong Kong",
    ],
    "cn_label_es": "Almacén / oficina en China",
    "cn_label_en": "China warehouse / office",
    "cn_lines": [
        "Warehouse operativo en Guangzhou (recepción de carga)",
        "Hub Hong Kong para compras ecommerce (Shein, AliExpress, etc.)",
        "Centros de consolidación: Shenzhen · Shanghái · Guangzhou",
    ],
}

ASIA_EXTENDED = [
    {
        "id": "taiwan",
        "es": "Taiwán", "en": "Taiwan",
        "industries_es": "Electrónica, semiconductores, repuestos industriales y maquinaria.",
        "industries_en": "Electronics, semiconductors, industrial spare parts and machinery.",
    },
    {
        "id": "thailand",
        "es": "Tailandia", "en": "Thailand",
        "industries_es": "Autopartes, caucho, packaging, cosmética y productos de industria liviana.",
        "industries_en": "Auto parts, rubber, packaging, cosmetics and light-industry goods.",
    },
    {
        "id": "bangladesh",
        "es": "Bangladés", "en": "Bangladesh",
        "industries_es": "Textiles, indumentaria mayorista y confección de prendas.",
        "industries_en": "Textiles, wholesale apparel and garment manufacturing.",
    },
]

STORE_RETAIL = [
    ("AliExpress", "aliexpress.com"),
    ("Temu", "temu.com"),
    ("DHgate", "dhgate.com"),
    ("Shein", "shein.com"),
    ("Amazon", "amazon.com"),
    ("eBay", "ebay.com"),
    ("Walmart", "walmart.com"),
    ("Target", "target.com"),
]

STORE_WHOLESALE_CN = [
    ("Alibaba", "alibaba.com"),
    ("1688", "1688.com"),
    ("Made-in-China", "made-in-china.com"),
    ("Yiwu", "yiwugo.com"),
]

# Approximate SVG map coordinates (viewBox 0 0 1000 500)
MAP_POINTS = {
    "china": (780, 210),
    "taiwan": (810, 235),
    "thailand": (760, 265),
    "bangladesh": (735, 230),
    "miami": (265, 230),
    "argentina": (290, 420),
    "usa": (200, 195),
    "mexico": (195, 245),
    "colombia": (255, 295),
    "ecuador": (240, 320),
    "peru": (255, 345),
    "bolivia": (285, 370),
    "chile": (275, 400),
    "brazil": (330, 360),
    "paraguay": (305, 385),
}

ORIGIN_IDS = ["china", "taiwan", "thailand", "bangladesh"]
DEST_IDS = ["argentina", "usa", "mexico", "colombia", "ecuador",
            "peru", "bolivia", "chile", "brazil", "paraguay"]


def quote_matrix(locale="es"):
    """Build JS-friendly rate lookup for every origin→dest (except same id)."""
    en = locale == "en"
    matrix = {}
    for o in ORIGINS:
        for d in DESTINATIONS:
            if o["id"] == d["id"]:
                continue
            key = "%s|%s" % (o["id"], d["id"])
            # Asia origins slightly higher air base; USA origin lower
            air = 6 if o["id"] == "usa" else 8
            if d["id"] == "usa" and o["id"] != "usa":
                air = 7
            sea = 85 if o["id"] == "usa" else 95
            if en:
                air_t = "Express 4–7 days · Standard 12–15 days"
                sea_t = "Ocean approx. 8–10 weeks"
                if d["id"] == "usa":
                    air_t = "Air courier 3–10 days"
                    sea_t = "Ocean / LCL approx. 4–6 weeks"
            else:
                air_t = "Express 4–7 días · Estándar 12–15 días"
                sea_t = "Marítimo aprox. 8–10 semanas"
                if d["id"] == "usa":
                    air_t = "Courier aéreo 3–10 días"
                    sea_t = "Marítimo / LCL aprox. 4–6 semanas"
            modes = [
                {"mode": "air", "label": "Aéreo" if not en else "Air",
                 "from_usd": air, "unit": "kg", "transit": air_t},
                {"mode": "sea", "label": "Marítimo" if not en else "Ocean",
                 "from_usd": sea, "unit": "cbm", "transit": sea_t},
            ]
            matrix[key] = modes
    return matrix
