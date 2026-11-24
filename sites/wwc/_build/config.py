# -*- coding: utf-8 -*-
"""
Site-wide configuration and shared content for World Wide Commerce.
Edit values here and re-run build.py to regenerate every page.
"""

SITE = {
    "name": "World Wide Commerce",
    "short": "WWC",
    "legal": "WORLDWIDE COMMERCE GROUP LLC",
    "domain": "https://worldwidecommerce.us",
    "lang": "es",
    "tagline": "Tu puerta al mundo",
    "phone_display": "+1 (305) 497-6783",
    "phone_raw": "+13054976783",
    "whatsapp": "13054976783",
    "whatsapp_display": "+1 (305) 497-6783",
    "whatsapp_china": "8619854566405",
    "whatsapp_china_display": "+86 198 5456 6405",
    "email": "clientes@worldwidecommerce.us",
    "tracking_url": "https://wwcsys.worldwidecommerce.us",
    "register_url": "https://worldwidecommerce.us/registro-wwc/",
    "login_url": "https://worldwidecommerce.us/ingreso",
    "facebook": "https://www.facebook.com/worldwidecommerce",
    "instagram": "https://www.instagram.com/worldwidecommerce",
    "founded": "2004",
    "years": "20",
    "google_rating": "5.0",
    "google_reviews": "76",
}

OFFICES = [
    {
        "city": "Miami, Estados Unidos",
        "label": "Casa central y almacén",
        "lines": ["2355 NW 70th Ave, Unit D-06", "Miami, FL 33122", "Estados Unidos"],
        "country": "US",
        "region": "FL",
        "postal": "33122",
        "street": "2355 NW 70th Ave, Unit D-06",
        "locality": "Miami",
    },
    {
        "city": "Asunción, Paraguay",
        "label": "Oficina regional",
        "lines": ["General Máximo Santos 2285, esq. San Rafael",
                  "Barrio San Vicente, Asunción", "CP 1615, Paraguay"],
        "country": "PY",
        "region": "Asunción",
        "postal": "1615",
        "street": "General Máximo Santos 2285, esq. San Rafael",
        "locality": "Asunción",
    },
    {
        "city": "Shenzhen · Shanghái · Guangzhou",
        "label": "Centros de consolidación en China",
        "lines": ["Almacenes de consolidación", "Shenzhen, Shanghái y Guangzhou", "República Popular China"],
        "country": "CN",
        "region": "Guangdong",
        "postal": "",
        "street": "",
        "locality": "Shenzhen",
    },
]

# ---------------------------------------------------------------- navigation
NAV = [
    {"label": "Inicio", "href": "index.html"},
    {"label": "Servicios", "href": "servicios.html", "children": "SERVICES"},
    {"label": "Nosotros", "href": "nosotros.html"},
    {"label": "Presencia", "href": "presencia.html"},
    {"label": "Dónde comprar", "href": "donde-comprar.html"},
    {"label": "Rastreo", "href": "rastreo.html"},
    {"label": "Preguntas", "href": "preguntas-frecuentes.html"},
    {"label": "Contacto", "href": "contacto.html"},
]

# ---------------------------------------------------------------- 4-step flow
PROCESS = [
    {
        "title": "Registrate y obtené tu dirección en Miami o China",
        "text": "Abrís tu cuenta gratis y recibís tu dirección exclusiva en WWC Miami y en China.",
    },
    {
        "title": "Comprá online donde quieras",
        "text": "Usás tu dirección WWC como domicilio de entrega en tus tiendas favoritas de USA o China.",
    },
    {
        "title": "Nosotros recibimos, controlamos y reempaquetamos",
        "text": "Verificamos tu mercadería, la reempaquetamos si hace falta y consolidamos tus compras para ahorrar en el envío.",
    },
    {
        "title": "Te lo entregamos en tu domicilio",
        "text": "Despachamos con seguimiento y entrega puerta a puerta en Argentina, sin impuestos ocultos ni sorpresas.",
    },
]

# ---------------------------------------------------------------- stats band
STATS = [
    {"num": "20", "suffix": "+", "label": "Años de experiencia en comercio internacional"},
    {"num": "3", "suffix": "", "label": "Países con operación propia: EE. UU., China y Paraguay"},
    {"num": "5.0", "suffix": "★", "label": "Calificación promedio en Google sobre 76 reseñas"},
    {"num": "4", "suffix": "", "label": "Almacenes entre Miami y China"},
]

# ---------------------------------------------------------------- trust strip
TRUST = [
    {"icon": "shield", "html": "<b>Sin impuestos ocultos</b> — la cotización incluye envío, seguro, aduana e impuestos"},
    {"icon": "clock", "html": "<b>Express 4-7 días</b> · Estándar 12-15 días · marítimo 8-10 semanas"},
    {"icon": "star", "html": "<b>5,0 en Google</b> sobre 76 reseñas verificadas"},
    {"icon": "globe", "html": "<b>+20 años</b> en logística internacional, con depósito propio"},
]

# ---------------------------------------------------------------- retailers (name, domain for logo)
STORES = [
    ("Amazon", "amazon.com"),
    ("eBay", "ebay.com"),
    ("Walmart", "walmart.com"),
    ("AliExpress", "aliexpress.com"),
    ("B&H Photo", "bhphotovideo.com"),
    ("Carter's", "carters.com"),
    ("Disney Store", "shopdisney.com"),
    ("Victoria's Secret", "victoriassecret.com"),
    ("Zappos", "zappos.com"),
    ("Zara", "zara.com"),
    ("Apple", "apple.com"),
    ("Sephora", "sephora.com"),
    ("The Home Depot", "homedepot.com"),
    ("Target", "target.com"),
    ("Shein", "shein.com"),
    ("Uniqlo", "uniqlo.com"),
    ("LEGO", "lego.com"),
    ("Nike", "nike.com"),
    ("Best Buy", "bestbuy.com"),
    ("Costco", "costco.com"),
    ("Alibaba", "alibaba.com"),
    ("Temu", "temu.com"),
    ("Ulta Beauty", "ulta.com"),
    ("iHerb", "iherb.com"),
]

# ---------------------------------------------------------------- FAQ
FAQS = [
    {
        "q": "¿Cómo funciona el servicio de compras online y envío a Argentina?",
        "a": "Realizás tu compra online en cualquier tienda de USA, la enviás a nuestra dirección en Miami y nosotros la recibimos, verificamos, reempaquetamos (si es necesario) y te la mandamos directo a tu casa en Argentina.",
    },
    {
        "q": "¿Puedo comprar en varias tiendas y que lo unifiquen en un solo envío?",
        "a": "Sí. Podemos consolidar tus paquetes de distintas tiendas y hacer un solo envío para ahorrar en costos.",
    },
    {
        "q": "¿Qué productos puedo comprar y enviar a Argentina?",
        "a": "Podés enviar ropa, tecnología, suplementos, herramientas, repuestos, juguetes, calzado, entre otros. Si tenés dudas sobre productos restringidos, podés consultarnos.",
    },
    {
        "q": "¿Hay límite de valor o peso para enviar?",
        "a": "No hay límite de compra. Sin embargo, te ayudamos a optimizar tus envíos según peso, valor y tipo de producto para evitar demoras o cargos innecesarios.",
    },
    {
        "q": "¿Tengo que pagar impuestos o tasas adicionales?",
        "a": "No. Nuestro servicio es sin sorpresas. El valor que te cotizamos incluye todo: envío, seguro, gestión aduanera e impuestos.",
    },
    {
        "q": "¿Cómo sé que mi mercadería llegó a su destino?",
        "a": "Te compartimos actualizaciones con fotos y seguimiento del envío desde que llega a nuestro depósito hasta que lo recibís en tu casa.",
    },
    {
        "q": "¿Ofrecen seguro para los envíos?",
        "a": "Sí. Todos nuestros envíos incluyen seguro opcional para cubrir el valor declarado de tu compra.",
    },
    {
        "q": "¿Cuánto tarda en llegar mi envío desde Miami a Argentina?",
        "a": "Tenemos opciones Express (4 a 7 días hábiles) y Estándar (12 a 15 días). También ofrecemos marítimo para cargas grandes (8 a 10 semanas aprox.).",
    },
    {
        "q": "¿Puedo usar su dirección en USA aunque todavía no haya comprado?",
        "a": "Claro. Te registrás gratis y te damos tu dirección personalizada en Miami para que la uses cuando lo necesites.",
    },
    {
        "q": "¿Cómo hago el seguimiento de mi envío?",
        "a": "Desde tu panel de cliente podés seguir el estado de tus paquetes, ver cuándo llegan al depósito, su preparación y su envío a Argentina.",
    },
    {
        "q": "¿Ofrecen mudanzas completas desde USA a Argentina?",
        "a": "Sí. Nos encargamos de todo: embalaje, retiro, consolidado, trámites aduaneros y entrega en tu nueva casa en Argentina.",
    },
    {
        "q": "¿Puedo enviar muebles y objetos personales?",
        "a": "Sí. Podés enviar todo tipo de objetos personales, muebles, electrodomésticos y más. Te asistimos en el inventario y empaque.",
    },
    {
        "q": "¿También puedo enviar mi auto o moto a Argentina?",
        "a": "Sí. Ofrecemos transporte especializado para vehículos, incluyendo baja de títulos, consolidado en contenedor y asistencia para patentamiento.",
    },
    {
        "q": "¿Cómo se calcula el presupuesto de mi mudanza?",
        "a": "Te brindamos una cotización gratuita y personalizada según el volumen, tipo de carga y destino final en Argentina.",
    },
    {
        "q": "¿Puedo almacenar mis cosas antes del envío?",
        "a": "Sí. Incluimos 3 meses de storage sin costo para que puedas organizar tu mudanza por etapas.",
    },
    {
        "q": "¿Qué documentación necesito para la mudanza internacional?",
        "a": "Te guiamos paso a paso. Incluye certificado de residencia, pasaporte, lista de empaque, títulos de vehículos, etc.",
    },
    {
        "q": "¿Qué zonas de Argentina cubren?",
        "a": "Realizamos entregas puerta a puerta en todo el país. También podés retirar desde nuestros depósitos locales.",
    },
    {
        "q": "¿Me acompañan en los trámites de aduana en Argentina?",
        "a": "Sí. Nuestro equipo gestiona todos los trámites, turnos, traducciones, pagos de aranceles y más.",
    },
    {
        "q": "¿Puedo contratar solo algunos servicios y no toda la mudanza completa?",
        "a": "Sí. Nuestros servicios son modulares. Podés contratar solo el asesoramiento, el embalaje, el traslado o el despacho según lo que necesites.",
    },
    {
        "q": "¿Qué diferencia a Worldwide Commerce de otras empresas?",
        "a": "Somos un equipo real, con depósito propio en Miami, China y Europa. Atención personalizada, sin intermediarios y con más de 20 años de experiencia en logística internacional.",
    },
]

# ---------------------------------------------------------------- WHY US
WHY_US = [
    {
        "icon": "shield",
        "title": "Precio cerrado, sin impuestos ocultos",
        "text": "El valor que te cotizamos incluye todo: envío, seguro, gestión aduanera e impuestos. Sin sorpresas al llegar.",
    },
    {
        "icon": "clock",
        "title": "Express, Estándar o marítimo",
        "text": "Express en 4 a 7 días hábiles, Estándar en 12 a 15 días y marítimo para cargas grandes en 8 a 10 semanas aprox.",
    },
    {
        "icon": "box",
        "title": "Consolidación de compras",
        "text": "Juntamos paquetes de distintas tiendas en un solo envío para ahorrar en costos, desde un paquete hasta un contenedor.",
    },
    {
        "icon": "globe",
        "title": "Depósito propio en Miami y China",
        "text": "No subcontratamos el eslabón crítico: recibimos, verificamos con fotos y reempaquetamos en nuestros propios depósitos.",
    },
    {
        "icon": "users",
        "title": "Más de 20 años y 76 reseñas",
        "text": "Un equipo real, sin intermediarios, con más de 20 años en logística internacional y 5,0 en Google sobre 76 reseñas.",
    },
    {
        "icon": "moving",
        "title": "Mudanzas con 3 meses de storage",
        "text": "Mudanza puerta a puerta desde USA a Argentina, con presupuesto gratuito y 3 meses de almacenamiento sin costo en Miami.",
    },
]

# ---------------------------------------------------------------- SVG icons
# Single source of truth for all inline icons (24x24 stroke-based).
ICONS = {
"cart": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h2.2l2.6 12.2a1.5 1.5 0 0 0 1.5 1.2h9.4a1.5 1.5 0 0 0 1.5-1.2L21 8H5.3"/></svg>',
"warehouse": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21V9l10-5 10 5v12"/><path d="M6 21v-7h5v7M13 21v-4h5v4"/></svg>',
"door": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17"/><path d="M2 21h20M12.5 12h.01"/><path d="M19 21v-8l3 2v6"/></svg>',
"import-export": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17V5m0 0L3.5 8.5M7 5l3.5 3.5"/><path d="M17 7v12m0 0l3.5-3.5M17 19l-3.5-3.5"/></svg>',
"briefcase": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M2 13h20"/></svg>',
"moving": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 17V7h11v10"/><path d="M13 10h4.5l3.5 4v3h-8"/><circle cx="6" cy="18.5" r="1.6"/><circle cx="17" cy="18.5" r="1.6"/></svg>',
"fulfillment": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>',
"custom-logistics": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3m0 14v3M4.2 4.2l2.1 2.1m11.4 11.4l2.1 2.1M2 12h3m14 0h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>',
"cargo-prep": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M3 11h18M9 7V4h6v3M12 11v9"/></svg>',
"code": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M8 18l-5-6 5-6M16 6l5 6-5 6M13.5 4l-3 16"/></svg>',
"miami": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>',
"china": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><path d="M2.5 12h19M12 2.5c2.6 2.6 4 5.9 4 9.5s-1.4 6.9-4 9.5c-2.6-2.6-4-5.9-4-9.5s1.4-6.9 4-9.5z"/></svg>',
"outsourcing": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3.2a4 4 0 0 1 0 7.6M22 21v-1.8a4 4 0 0 0-3-3.8"/><circle cx="9.5" cy="7" r="4"/><path d="M2 21v-1.8a4 4 0 0 1 4-4h7a4 4 0 0 1 4 4V21"/></svg>',
"pet": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="5.5" cy="9" r="2.1"/><circle cx="9.8" cy="5.4" r="2.1"/><circle cx="14.6" cy="5.4" r="2.1"/><circle cx="18.6" cy="9" r="2.1"/><path d="M8 15.5c0-2.2 1.8-3.6 4.1-3.6s4.1 1.4 4.1 3.6c0 2.6-1.8 4.6-4.1 4.6S8 18.1 8 15.5z"/></svg>',
"shield": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-3.5 8-10V5.2l-8-3.1-8 3.1V12c0 6.5 8 10 8 10z"/><path d="M9 12l2.2 2.2L15.5 10"/></svg>',
"globe": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><path d="M2.5 12h19M12 2.5c2.6 2.6 4 5.9 4 9.5s-1.4 6.9-4 9.5c-2.6-2.6-4-5.9-4-9.5s1.4-6.9 4-9.5z"/></svg>',
"clock": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><path d="M12 7v5.3l3.6 2.1"/></svg>',
"star": '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.4l2.95 5.98 6.6.96-4.77 4.65 1.12 6.57L12 17.47l-5.9 3.09 1.12-6.57L2.45 9.34l6.6-.96z"/></svg>',
"users": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13A4 4 0 0 1 16 11"/></svg>',
"box": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8l-9-5-9 5v8l9 5 9-5z"/><path d="M3.3 8.2L12 13l8.7-4.8M12 13v8"/></svg>',
"phone": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 16.9v2.8a1.9 1.9 0 0 1-2.1 1.9 18.8 18.8 0 0 1-8.2-2.9 18.5 18.5 0 0 1-5.7-5.7A18.8 18.8 0 0 1 2.6 4.7 1.9 1.9 0 0 1 4.5 2.6h2.8a1.9 1.9 0 0 1 1.9 1.6c.1 1 .4 1.9.7 2.8a1.9 1.9 0 0 1-.4 2L8.3 10.2a15.2 15.2 0 0 0 5.5 5.5l1.2-1.2a1.9 1.9 0 0 1 2-.4c.9.3 1.8.6 2.8.7a1.9 1.9 0 0 1 1.7 2.1z"/></svg>',
"mail": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="M2.5 6.5l9.5 6.6 9.5-6.6"/></svg>',
"pin": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.5s7-6.4 7-11.3A7 7 0 1 0 5 10.2c0 4.9 7 11.3 7 11.3z"/><circle cx="12" cy="10" r="2.5"/></svg>',
"whatsapp": '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.1-.7.1l-.9 1.1c-.2.2-.4.2-.6.1a8.6 8.6 0 0 1-4-3.9c-.1-.2-.1-.4.1-.6l1-1c.2-.2.2-.4.1-.7l-.9-2.2c-.1-.3-.4-.5-.7-.4-.9.1-1.8.6-2.2 1.4-.5 1-.4 2.3.1 3.6a14.6 14.6 0 0 0 7.9 7.9c1.3.5 2.6.6 3.6.1.8-.4 1.3-1.3 1.4-2.2 0-.3-.1-.6-.4-.7z"/><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22.5l5.5-1.4A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2z"/></svg>',
"facebook": '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3V5.5h-3c-2.3 0-4 1.8-4 4.1V12H7.5v3.5H10V22h3.5v-6.5H16l.6-3.5h-3.1v-2c0-.6.4-1 .5-1z"/></svg>',
"instagram": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><rect x="2.5" y="2.5" width="19" height="19" rx="5.2"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.6" cy="6.4" r="1.2" fill="currentColor" stroke="none"/></svg>',
"chevron-right": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>',
"chevron-down": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>',
"arrow-right": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h15M13 6l6 6-6 6"/></svg>',
"arrow-up": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M6 11l6-6 6 6"/></svg>',
"plus": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>',
"check": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5" opacity=".28"/><path d="M8 12.2l2.6 2.6L16 9.4"/></svg>',
"search": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><circle cx="11" cy="11" r="7.5"/><path d="M16.5 16.5L21 21"/></svg>',
"truck": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 17V5h11v12"/><path d="M13 8h4.2l3.8 4.2V17h-8"/><circle cx="6" cy="18.6" r="1.7"/><circle cx="17" cy="18.6" r="1.7"/></svg>',
"plane": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.2 14.1L3 11.4l18-7.8-4.6 17.8-3.3-6.1-2.9 3.4z"/></svg>',
"ship": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18l1.6-6.4h14.8L21 18a12 12 0 0 1-18 0z"/><path d="M7 11.6V7h10v4.6M12 3v4"/></svg>',
"file": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2.5H6.5a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V8z"/><path d="M14 2.5V8h5.5M8.5 13h7M8.5 17h5"/></svg>',
}
