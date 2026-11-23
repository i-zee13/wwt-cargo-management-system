# World Wide Commerce — Sitio web

Sitio estático, original y optimizado para SEO, construido con el contenido real de
**worldwidecommerce.us**. Sin frameworks, sin dependencias, sin build step obligatorio.

> **Nota sobre el origen del diseño.** Este sitio NO es una copia del template Logixpress
> (Designesia / ThemeForest) ni de ningún otro template comercial. Todo el CSS, el JS,
> los SVG y la estructura se escribieron desde cero para esta marca. Podés usarlo,
> modificarlo y venderlo sin ningún riesgo de licencia.

---

## 1. Arrancar

No hay nada que instalar. Para verlo:

```bash
cd wwc-site
python3 -m http.server 8000
# abrir http://localhost:8000
```

Para publicarlo: subí **todo el contenido de la carpeta** a cualquier hosting estático
(Netlify, Vercel, Cloudflare Pages, GitHub Pages, o un hosting cPanel común).
No necesita Node, PHP ni base de datos.

---

## 2. Estructura

```
wwc-site/
├── index.html                    Home
├── servicios.html                Índice de los 14 servicios
├── servicio-*.html               14 páginas de detalle (una por servicio)
├── nosotros.html                 Institucional
├── donde-comprar.html            Tiendas sugeridas por categoría
├── rastreo.html                  Rastreo de envíos
├── preguntas-frecuentes.html     FAQ (con schema FAQPage)
├── contacto.html                 Formulario + oficinas
├── gracias.html                  Post-envío del formulario (noindex)
├── terminos.html                 Términos — BORRADOR, ver §6
├── privacidad.html               Privacidad — BORRADOR, ver §6
├── 404.html                      Página de error
├── sitemap.xml  robots.txt  site.webmanifest  favicon.ico
│
├── assets/
│   ├── css/style.css             Design system completo (~720 líneas, comentado)
│   ├── js/main.js                Vanilla JS (~280 líneas, sin jQuery)
│   └── img/
│       ├── brand/                Logo, favicon, iconos PWA
│       ├── graphics/             Ilustraciones SVG originales
│       ├── photos/               ← VACÍA. Poné acá tus fotos (ver IMAGES.md)
│       └── og-image.png          Imagen para compartir en redes
│
└── _build/                       Generador (opcional, ver §3)
    ├── config.py                 Datos de la empresa, nav, FAQ, iconos
    ├── services.py               Los 14 servicios con todo su contenido
    └── build.py                  Genera todos los .html
```

---

## 3. Cómo editar el contenido

Tenés dos caminos. Elegí uno.

### Opción A — Editar el HTML directamente (recomendado para Cursor)
Los `.html` son archivos planos y legibles. Abrilos y editalos. Funciona perfecto.
**Si elegís este camino, borrá la carpeta `_build/`** para no confundirte después.

### Opción B — Usar el generador
Si vas a cambiar algo que se repite en las 25 páginas (el teléfono, el menú, el footer),
es mucho más rápido editar los datos y regenerar:

```bash
python3 _build/build.py     # reescribe los 25 .html + sitemap
```

- Teléfono, email, direcciones, redes → `_build/config.py` → `SITE` y `OFFICES`
- Menú de navegación → `_build/config.py` → `NAV`
- Preguntas frecuentes → `_build/config.py` → `FAQS`
- Los 14 servicios (títulos, textos, SEO) → `_build/services.py`

⚠️ El generador **sobrescribe** los `.html`. Si editaste HTML a mano, no lo corras.

---

## 4. Lo que FALTA conectar (importante)

| Qué | Dónde | Estado |
|---|---|---|
| **Formulario de contacto** | `contacto.html`, atributo `action` | ❌ No envía nada. Ver abajo. |
| **Fotos reales** | `assets/img/photos/` | ❌ Vacío. Ver `IMAGES.md`. |
| **Reseñas de clientes** | `nosotros.html` | ❌ Sección vacía a propósito. Ver §5. |
| **Selector de idioma** | `href="#"` en el topbar | ❌ Apunta a `#`. Ver §7. |
| **Analytics** | — | ❌ Sin GA4 / Tag Manager. |
| **URL de rastreo** | `assets/js/main.js` → `TRACK_BASE` | ⚠️ Verificar el formato del parámetro. |

### Formulario de contacto
El formulario valida en el navegador pero no manda nada. La forma más rápida de activarlo:

```html
<!-- en contacto.html, línea del <form> -->
<form class="form-grid" data-contact-form
      action="https://formspree.io/f/TU_ID" method="POST">
```

Y agregá un campo oculto para que te redirija a la página de gracias:
```html
<input type="hidden" name="_next" value="https://worldwidecommerce.us/gracias.html">
```

Alternativas: Netlify Forms (agregá el atributo `netlify` al `<form>`), Web3Forms,
o un endpoint propio en PHP/Node.

### Rastreo
`assets/js/main.js` abre `https://wwcsys.worldwidecommerce.us/?tracking=CODIGO`.
Verificá cuál es el parámetro real que espera tu sistema y ajustá `TRACK_BASE`
y la query en la función `initTrackForm()`.

---

## 5. Reseñas de clientes — leer esto

La sección de testimonios en `nosotros.html` está **intencionalmente vacía**.
No se incluyeron reseñas inventadas: publicar testimonios falsos es ilegal en
Estados Unidos (FTC) y en la mayoría de los países, y además Google penaliza
el schema de reviews falsas.

Tenés 76 reseñas reales con 5,0 de promedio. Usalas:

1. **Lo más fácil:** insertar el widget de Trustindex o el de Google Reviews,
   que traen las reseñas verificadas automáticamente y se actualizan solas.
2. **A mano:** copiar el texto literal de reseñas reales en el bloque HTML
   comentado que dejé en `nosotros.html`, con el nombre real del cliente.

El `aggregateRating` en el schema (5.0 / 76) refleja tus datos reales de Google,
así que es correcto dejarlo.

---

## 6. Términos y Privacidad — BORRADORES

`terminos.html` y `privacidad.html` son **estructuras base, no documentos legales**.
Tienen marcadores `[COMPLETAR...]` donde falta contenido real.
Hacelos revisar por un abogado antes de publicar, sobre todo:

- Límites de responsabilidad por pérdida o daño de mercadería
- Jurisdicción y legislación aplicable (operás en EE. UU., Paraguay y China)
- Cumplimiento de Ley 25.326 (Argentina), GDPR si tenés tráfico europeo

---

## 7. Multi-idioma (ES / EN / PT)

El selector está en el topbar pero apunta a `#`. Tu sitio actual ofrece tres idiomas.
Cuando los tengas, la estructura recomendada es por carpeta:

```
/              → español (actual)
/en/           → inglés
/pt/           → portugués
```

Y agregá en el `<head>` de cada página:
```html
<link rel="alternate" hreflang="es" href="https://worldwidecommerce.us/index.html">
<link rel="alternate" hreflang="en" href="https://worldwidecommerce.us/en/index.html">
<link rel="alternate" hreflang="pt" href="https://worldwidecommerce.us/pt/index.html">
<link rel="alternate" hreflang="x-default" href="https://worldwidecommerce.us/index.html">
```

---

## 8. SEO — qué ya está hecho

✅ `<title>` y `meta description` únicos y optimizados en las 25 páginas
✅ Un solo `<h1>` por página, jerarquía H2/H3 correcta
✅ Canonical en todas las páginas
✅ Open Graph + Twitter Card + imagen 1200×630
✅ Schema.org JSON-LD: `Organization`, `LocalBusiness`, `Service` (×14),
   `FAQPage`, `BreadcrumbList`, `ItemList`, `WebSite`
✅ `sitemap.xml` con prioridades y `robots.txt`
✅ URLs descriptivas con keywords (`servicio-importacion-y-exportacion.html`)
✅ `alt` en todas las imágenes
✅ Breadcrumbs visibles + marcados
✅ Enlazado interno denso entre servicios relacionados
✅ Mobile-first y responsive
✅ `prefers-reduced-motion` respetado
✅ Sin render-blocking JS (`defer`), CSS único
✅ Funciona sin JavaScript (contenido visible igual)

### Pendientes de SEO que dependen de vos
- [ ] Cambiar `SITE["domain"]` en `_build/config.py` si el dominio final es otro
- [ ] Dar de alta el sitio en Google Search Console y subir el sitemap
- [ ] Completar el perfil de Google Business (potencia mucho el `LocalBusiness`)
- [ ] Reemplazar las ilustraciones SVG por fotos reales (ver `IMAGES.md`)
- [ ] Instalar GA4
- [ ] Comprimir las fotos que agregues a WebP

---

## 9. Design system

Todo el diseño sale de variables CSS en `:root` (arriba de `style.css`).
Para cambiar la identidad visual completa, tocá estas:

```css
--midnight:#0A1B2A;   /* azul marino profundo */
--brand:#F0591F;      /* acento primario (naranja carga) */
--amber:#F2A33C;      /* acento cálido secundario */
--ocean:#2E7FA8;      /* azul de apoyo */
--font-display:'Sora';
--font-body:'Inter';
```

### Cambiar el fondo del hero por una foto real

El hero usa hoy la ilustración `assets/img/graphics/hero-port.svg`.
Para poner una foto, editá el `<img>` dentro de `.hero-bg` en `index.html`
(o en `_build/build.py` si usás el generador):

```html
<div class="hero-bg" aria-hidden="true">
  <img src="assets/img/photos/hero-puerto.jpg" alt="" width="1920" height="1080">
</div>
```

Medidas: **1920 × 1080 px, menos de 300 KB**, WebP o JPG.
El degradado oscuro que mantiene el texto legible ya está en el CSS
(`.hero-bg::after`), así que no hay que tocar nada más.
Lo mismo aplica a `.page-hero-bg` en las páginas internas.

Las tipografías se cargan desde Google Fonts en el `<head>`.
Para producción conviene auto-hospedarlas (mejora LCP y evita el pedido externo).

---

## 10. Checklist antes de publicar

- [ ] Conectar el formulario de contacto y probarlo de verdad
- [ ] Reemplazar ilustraciones por fotos reales
- [ ] Insertar reseñas reales de Google
- [ ] Revisión legal de Términos y Privacidad
- [ ] Verificar el link de rastreo contra el sistema real
- [ ] Instalar GA4 / Tag Manager
- [ ] Confirmar que el dominio en `sitemap.xml` y los canonical sea el correcto
- [ ] Probar en móvil real (no solo en el inspector)
- [ ] Pasar PageSpeed Insights
- [ ] Subir el sitemap a Search Console
