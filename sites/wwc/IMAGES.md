# Guía de imágenes

El sitio funciona hoy con **ilustraciones SVG originales** que hice para esta marca.
Se ven bien y pesan poco, pero las fotos reales de tu operación van a convertir mucho
mejor: la gente quiere ver tu almacén, tu equipo y tus cajas de verdad.

Esta guía dice exactamente qué foto va en qué lugar y con qué medidas.

---

## Por qué no incluí fotos de stock

Las imágenes del template que mandaste son fotos con licencia comercial.
Descargarlas y reutilizarlas en un sitio de producción es infracción de copyright,
y las agencias (Getty, Shutterstock, Adobe) rastrean activamente sus archivos.
No vale la pena el riesgo.

**Fuentes gratuitas y seguras**, con licencia para uso comercial sin atribución:

| Sitio | Para qué sirve |
|---|---|
| [Unsplash](https://unsplash.com) | Logística, almacenes, puertos, camiones |
| [Pexels](https://pexels.com) | Oficinas, equipos de trabajo, retratos |
| [Pixabay](https://pixabay.com) | Cargo, contenedores, aviones |
| [Burst](https://burst.shopify.com) | E-commerce, packaging, pequeños negocios |

Búsquedas que funcionan bien: `warehouse logistics`, `cargo container port`,
`delivery truck`, `fulfillment center`, `shipping boxes`, `air freight`.

**Lo ideal igual son fotos propias.** Una tarde con un celular moderno en el almacén
de Miami te da mejor material que cualquier banco de imágenes, y además es honesto:
la foto muestra tu operación real.

---

## Dónde va cada imagen

### 1. Home — sección "No subcontratamos la parte que importa"
- **Archivo actual:** `assets/img/graphics/warehouse.svg`
- **Reemplazar por:** `assets/img/photos/almacen-miami.jpg`
- **Medidas:** 1200 × 900 px (4:3)
- **Qué mostrar:** tu almacén de Miami — racks, cajas, montacargas, dársena
- **Dónde tocar:** `index.html`, buscá `graphics/warehouse.svg`

```html
<img src="assets/img/photos/almacen-miami.jpg"
     alt="Almacén de World Wide Commerce en Miami con estanterías de carga y personal operando"
     width="1200" height="900" loading="lazy">
```

### 2. Nosotros — sección institucional
- **Reemplazar por:** `assets/img/photos/equipo-wwc.jpg`
- **Medidas:** 1200 × 900 px
- **Qué mostrar:** tu equipo trabajando, o una vista amplia de la operación
- **Dónde tocar:** `nosotros.html`

### 3. Hero de la home (opcional)
El globo SVG animado del hero funciona muy bien y es único del sitio.
Si lo querés cambiar por una foto, usá uno **muy oscuro** (puerto de noche, carga aérea)
a 1920 × 1080, y subí la opacidad del degradado en `.hero-bg::after` para que el
texto siga legible. Mi recomendación: dejá el SVG.

### 4. Páginas de servicio (opcional pero recomendado)
Cada una de las 14 páginas gana mucho con una foto propia bajo el primer `<h2>`.
- **Medidas:** 1000 × 600 px (5:3)
- **Nombres sugeridos:** `assets/img/photos/servicio-<slug>.jpg`

Ejemplos de qué mostrar:
| Servicio | Foto |
|---|---|
| Realizamos tus compras | Paquetes de tiendas apilados, etiquetas de envío |
| Alquiler de almacenamiento | Racks con posiciones numeradas |
| Puerta a puerta | Entrega en domicilio, repartidor con caja |
| Importación y exportación | Contenedores, grúa de puerto |
| Mudanza internacional | Embalaje de muebles, cajas rotuladas |
| E-commerce fulfillment | Mesa de pick & pack, etiquetas |
| Preparación de la carga | Pallet con film y flejes |
| Infraestructura Miami | Fachada y dársena del almacén |
| Infraestructura China | Contenedores, puerto de Shenzhen |
| Transporte de mascotas | Jaula IATA, perro tranquilo |

Para insertarla, dentro del `<div class="prose">`:
```html
<figure class="split-media" style="margin:2rem 0">
  <img src="assets/img/photos/servicio-xxx.jpg" alt="Descripción real y específica"
       width="1000" height="600" loading="lazy">
</figure>
```

### 5. Logo
`assets/img/brand/logo.svg` es un logo original que diseñé para el sitio.
**Si ya tenés un logo oficial, reemplazalo** — es tu identidad, no la mía.
Mantené el nombre del archivo y el sitio lo toma solo. Archivos a cambiar:
- `logo.svg` (fondos claros) · `logo-light.svg` (fondos oscuros)
- `favicon.svg` · `icon-192.png` · `icon-512.png` · `apple-touch-icon.png` · `favicon.ico`

### 6. Imagen para compartir en redes
`assets/img/og-image.png` (1200 × 630) ya está hecha y se ve bien.
Si cambiás el logo o el eslogan, regenerala para que coincida.

---

## Optimización — no te la saltees

Una foto de celular sin comprimir pesa 4–8 MB y te destruye el PageSpeed.

```bash
# Redimensionar y comprimir a JPG
convert original.jpg -resize 1200x900^ -gravity center -extent 1200x900 \
        -quality 82 -strip almacen-miami.jpg

# Generar WebP (30-50% más liviano, soportado por todos los navegadores actuales)
cwebp -q 80 almacen-miami.jpg -o almacen-miami.webp
```

Objetivo: **menos de 200 KB por foto**. Si pesa más, comprimila otra vez.

Para servir WebP con respaldo:
```html
<picture>
  <source srcset="assets/img/photos/almacen-miami.webp" type="image/webp">
  <img src="assets/img/photos/almacen-miami.jpg" alt="..." width="1200" height="900" loading="lazy">
</picture>
```

---

## Reglas que el sitio ya cumple y conviene no romper

1. **Siempre `alt`** descriptivo y real. No `alt="imagen"` ni `alt="foto1"`.
   El `alt` es SEO y es accesibilidad.
2. **Siempre `width` y `height`** en el HTML. Evita que la página salte mientras carga
   (Cumulative Layout Shift, que Google mide).
3. **`loading="lazy"`** en todas las imágenes salvo la primera visible.
4. **Nombres de archivo con keywords**: `almacen-logistico-miami.jpg`,
   no `IMG_20241103_184522.jpg`.
