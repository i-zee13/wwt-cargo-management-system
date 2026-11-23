# -*- coding: utf-8 -*-
"""
All service pages for World Wide Commerce.

Each entry:
  slug      -> output filename (servicio-<slug>.html)
  nav       -> short label used in menus
  h1        -> page headline
  seo_title -> <title> (aim for 50-60 chars + brand)
  meta      -> meta description (150-160 chars)
  kw        -> focus keywords
  icon      -> key from config.ICONS
  blurb     -> card text on home/servicios (1-2 sentences)
  intro     -> opening paragraph on the detail page
  benefits  -> bulleted value points
  sections  -> [(heading, [paragraph or <ul> html, ...]), ...]
  cta       -> closing call-to-action line
  new       -> badge flag
"""

SERVICES = [
# ─────────────────────────────────────────────────────────────── 1
{
 "slug":"realizamos-tus-compras",
 "nav":"Realizamos tus compras",
 "h1":"Realizamos tus compras en Estados Unidos y China",
 "seo_title":"Compras Asistidas en USA y China | World Wide Commerce",
 "meta":"Compramos por vos en tiendas de EE. UU. y China cuando tu tarjeta no es aceptada. Pagamos con medios locales y enviamos a tu puerta en Argentina.",
 "kw":"comprar en estados unidos desde argentina, asistente de compras usa, comprar en china, tarjeta rechazada tienda usa",
 "icon":"cart",
 "blurb":"Facilitamos tus compras en grandes tiendas de Estados Unidos y China: nos encargamos de todo el proceso, desde la búsqueda del producto hasta su envío a Argentina.",
 "intro":"Este servicio está diseñado para facilitar las compras en grandes tiendas de Estados Unidos y China a pedido del cliente. Nos encargamos de todo el proceso de adquisición, desde la búsqueda del producto hasta su compra y envío a Argentina, asegurando la mejor relación calidad-precio y el cumplimiento de las especificaciones solicitadas.",
 "benefits":[
   "Ahorro de tiempo y esfuerzo",
   "Acceso a productos exclusivos y de alta demanda",
   "Asesoría en la selección de productos",
   "Gestión de pagos internacionales segura",
   "Verificación de la autenticidad y calidad de los productos",
   "Consolidación de compras para reducir costos de envío",
   "Tramitación de devoluciones y reembolsos",
   "Transparencia en la gestión y seguimiento de compras",
   "Atención personalizada y soporte continuo",
   "Entrega rápida y eficiente en Argentina",
 ],
 "sections":[
  ("Cómo funciona el servicio",[
    "El proceso es deliberadamente simple. Nos enviás el enlace del producto que querés, con talle, color y cantidad. Preparamos una cotización que incluye el valor del artículo, los impuestos locales de la tienda, nuestra comisión de gestión y el flete estimado hasta destino.",
    "Una vez que aprobás el presupuesto y se acredita el pago, realizamos la compra el mismo día hábil. El paquete llega a nuestro almacén de Miami o de China, lo inspeccionamos, lo fotografiamos y queda listo para despachar o para consolidar con otras compras tuyas.",
  ]),
  ("Qué podemos comprar por vos",[
    "<ul><li>Indumentaria, calzado y accesorios de marca</li><li>Electrónica, computación, celulares y fotografía</li><li>Repuestos y autopartes</li><li>Artículos para bebés y juguetería</li><li>Herramientas, equipamiento y maquinaria liviana</li><li>Insumos y mercadería al por mayor desde proveedores chinos</li></ul>",
    "Si tenés dudas sobre si un artículo puede transportarse, consultanos antes de comprarlo. Es mucho más barato preguntar que descubrir una restricción cuando la mercadería ya está pagada.",
  ]),
  ("Por qué conviene delegar la compra",[
    "Cuando una compra se gestiona desde el exterior aparecen fricciones que no son obvias: validaciones de dirección, bloqueos antifraude, códigos de verificación que llegan a teléfonos locales y devoluciones que exigen una dirección en el país. Con más de 20 años operando en Estados Unidos y China, esas fricciones las resolvemos como parte del trabajo diario.",
  ]),
 ],
 "cta":"Mandanos el enlace del producto y te cotizamos la compra completa, puesta en tu puerta.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 2
{
 "slug":"alquiler-de-almacenamiento",
 "nav":"Alquiler de almacenamiento",
 "h1":"Alquiler de almacenamiento en nuestro depósito de Miami",
 "seo_title":"Alquiler de Almacenamiento en Miami | World Wide Commerce",
 "meta":"Casillero seguro en nuestro depósito de Miami: Un solo uso $4.50 por paquete, Nivel Básico $8.50/mes y Nivel Premium $17.50/mes, con seguro incluido.",
 "kw":"alquiler almacenamiento miami, deposito miami, bodega miami, casillero miami, storage logistico",
 "icon":"warehouse",
 "blurb":"Casillero seguro y confiable en nuestro depósito de Miami, con planes desde $4.50 por paquete o suscripción mensual desde $8.50.",
 "intro":"Con este servicio podés disfrutar de un casillero en nuestro depósito en forma segura y confiable. Elegí el plan que mejor se adapte a vos: Un solo uso ($4.50 por paquete), Nivel Básico ($8.50 mensual) o Nivel Premium ($17.50 mensual).",
 "benefits":[
   "Un solo uso: $4.50 por paquete, más $0.50 por un seguro de $1,000",
   "Nivel Básico: $8.50 mensual, 5 paquetes por mes y seguro gratuito de $1,000",
   "Nivel Premium: $17.50 mensual, 15 paquetes por mes y seguro gratuito de $1,000",
   "Soporte por correo electrónico y chat (prioritario en los planes mensuales)",
   "Ventana de recogida de 7 días",
   "Recargo de $1 por paquete por encima del límite mensual",
   "Cancelá en cualquier momento en los planes mensuales",
 ],
 "sections":[
  ("Planes y precios",[
    "<ul><li><strong>Un solo uso — $4.50/paquete:</strong> $4.50 por paquete, $0.50 por un seguro de $1,000, soporte por correo electrónico y chat, ventana de recogida de 7 días y $2.50 por cargo por recogida tardía.</li><li><strong>Nivel Básico — $8.50/mensual:</strong> 5 paquetes por mes, seguro gratuito de $1,000, soporte prioritario por correo electrónico y chat, ventana de recogida de 7 días, $2.50 por cargo por recogida tardía, recargo de $1 por paquete por encima del límite y cancelación en cualquier momento.</li><li><strong>Nivel Premium — $17.50/mensual:</strong> 15 paquetes por mes, seguro gratuito de $1,000, soporte prioritario por correo electrónico y chat, ventana de recogida de 7 días, $2.50 por cargo por recogida tardía, recargo de $1 por paquete por encima del límite y cancelación en cualquier momento.</li></ul>",
  ]),
  ("Paquetes de gran tamaño",[
    "Si tenés paquetes de gran tamaño, comunicate con clientes@worldwidecommerce.us y haremos todo lo posible para acomodarlo.",
  ]),
 ],
 "cta":"Contanos qué volumen necesitás guardar y te armamos una propuesta de almacenamiento.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 3
{
 "slug":"servicio-puerta-a-puerta",
 "nav":"Servicio puerta a puerta",
 "h1":"Servicio puerta a puerta: de la tienda a tu domicilio",
 "seo_title":"Envío Puerta a Puerta a Argentina | World Wide Commerce",
 "meta":"Servicio puerta a puerta desde EE. UU. y China hasta tu domicilio en Argentina. Gestionamos compra, flete, aduana y entrega final sin sorpresas.",
 "kw":"envio puerta a puerta argentina, courier usa argentina, envio door to door, importar sin tramites",
 "icon":"door",
 "blurb":"Comprás en grandes tiendas de USA o China y nosotros nos encargamos del resto, hasta tu domicilio en Argentina.",
 "intro":"Con nuestro servicio puerta a puerta, el cliente puede realizar compras en grandes tiendas de USA o China y nosotros nos encargamos del resto. Recibimos los productos en nuestro depósito, los controlamos, reempaquetamos si es necesario, gestionamos devoluciones y los enviamos directamente al domicilio del cliente en Argentina.",
 "benefits":[
   "Comodidad y simplicidad para el cliente",
   "Control de calidad y verificación de productos",
   "Reempaque profesional para mayor seguridad",
   "Gestión eficiente de devoluciones y cambios",
   "Reducción de costos aduaneros",
   "Entrega rápida y segura a domicilio",
   "Rastreo en tiempo real del envío",
   "Atención personalizada en cada etapa del proceso",
   "Servicio de seguros para mayor tranquilidad",
   "Adaptabilidad a diferentes tipos de productos y volúmenes",
 ],
 "sections":[
  ("El recorrido completo",[
    "<ul><li><strong>Origen:</strong> recibimos o compramos la mercadería en Estados Unidos o China</li><li><strong>Almacén:</strong> verificamos, fotografiamos, reempacamos y consolidamos</li><li><strong>Documentación:</strong> preparamos factura, packing list y lo que exija la aduana de destino</li><li><strong>Flete:</strong> despachamos por vía aérea o marítima según urgencia y volumen</li><li><strong>Destino:</strong> gestionamos el despacho aduanero y coordinamos la entrega final</li></ul>",
  ]),
  ("Aéreo o marítimo: cómo elegir",[
    "La vía aérea conviene cuando el tiempo importa más que el costo y el volumen es moderado: es considerablemente más rápida, pero el flete se calcula de forma más sensible al peso. La vía marítima es la opción natural para volúmenes grandes o mercadería no urgente, donde el costo por kilo baja de manera notable a cambio de un tránsito más largo.",
    "No hace falta que decidas solo. Con los datos de tu carga te mostramos las dos alternativas con su costo y su plazo estimado, y elegís con los números a la vista.",
  ]),
  ("Sin impuestos ocultos",[
    "Es la parte en la que más clientes se han sentido engañados por otros operadores, así que la decimos claro: te presentamos el presupuesto con los conceptos separados antes de despachar. Si durante el proceso aparece un costo que no estaba previsto, te lo comunicamos y lo autorizás antes de que se genere.",
  ]),
 ],
 "cta":"Pedí tu cotización puerta a puerta y recibí el desglose completo antes de decidir.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 4
{
 "slug":"importacion-y-exportacion",
 "nav":"Importación y exportación",
 "h1":"Importación y exportación entre América y Asia",
 "seo_title":"Importación y Exportación Comercial | World Wide Commerce",
 "meta":"Importación y exportación comercial entre EE. UU., China y Latinoamérica. Documentación, aduana, flete aéreo y marítimo con más de 20 años de oficio.",
 "kw":"importacion exportacion argentina, importar desde china, comercio exterior, despacho aduanero, flete internacional",
 "icon":"import-export",
 "blurb":"Servicio integral de importación y exportación desde Estados Unidos y China hacia Argentina, desde la recolección en origen hasta la entrega final.",
 "intro":"Ofrecemos un servicio integral de importación y exportación de productos desde Estados Unidos y China hacia Argentina. Nos encargamos de todo el proceso logístico, desde la recolección de mercancías en origen hasta la entrega final en el destino. Nuestro objetivo es garantizar la seguridad, rapidez y eficiencia en cada envío, adaptándonos a las necesidades específicas de cada cliente.",
 "benefits":[
   "Amplia experiencia en gestión de aduanas",
   "Seguimiento en tiempo real de la mercancía",
   "Reducción de costos logísticos",
   "Manejo seguro y profesional de la carga",
   "Asesoría personalizada durante todo el proceso",
   "Optimización de tiempos de entrega",
   "Soluciones logísticas adaptadas a diferentes industrias",
   "Trámites aduaneros simplificados",
   "Seguro de transporte de mercancías",
   "Alianzas estratégicas con agentes internacionales",
 ],
 "sections":[
  ("Qué resolvemos",[
    "<ul><li><strong>Análisis previo:</strong> si el producto puede ingresar, con qué requisitos y a qué costo total estimado</li><li><strong>Contacto con proveedores:</strong> especialmente en China, donde la verificación antes de pagar evita el problema más caro de todos</li><li><strong>Consolidación:</strong> juntamos carga de varios proveedores en un solo embarque</li><li><strong>Documentación y aduana:</strong> preparación y control de todo el legajo</li><li><strong>Seguro de carga:</strong> cobertura sobre el valor de la mercadería en tránsito</li></ul>",
  ]),
  ("Importar desde China sin sorpresas",[
    "Importar desde China tiene dos riesgos distintos. El primero es logístico y se resuelve con buena coordinación. El segundo es comercial: pagar por adelantado a un proveedor que entrega mercadería distinta a la acordada, o que no entrega nada.",
    "Nuestros almacenes en Guangzhou y Hong Kong permiten que alguien revise físicamente la mercadería antes de que salga del país. Es la diferencia entre descubrir un problema a tiempo y descubrirlo cuando el contenedor ya está en destino.",
  ]),
  ("Exportar desde Latinoamérica",[
    "También trabajamos en el sentido inverso, acompañando a empresas latinoamericanas que colocan su producto en el mercado estadounidense. Eso incluye la logística de salida, el ingreso a Estados Unidos, el almacenamiento en Miami y la distribución o el fulfillment posterior hacia el cliente final.",
  ]),
 ],
 "cta":"Contanos qué querés importar o exportar y armamos el esquema completo de la operación.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 5
{
 "slug":"desarrollo-de-negocios-internacionales",
 "nav":"Desarrollo de negocios",
 "h1":"Desarrollo de negocios internacionales",
 "seo_title":"Desarrollo de Negocios Internacionales | World Wide Commerce",
 "meta":"Acompañamos a empresas latinoamericanas que quieren operar en EE. UU. y China: proveedores, estructura, logística y entrada al mercado.",
 "kw":"expandir empresa a estados unidos, hacer negocios en china, consultoria comercio internacional, proveedores china",
 "icon":"briefcase",
 "blurb":"Con más de 20 años de experiencia, acompañamos a empresas argentinas en su expansión hacia los mercados de Estados Unidos y China.",
 "intro":"Con más de 20 años de experiencia, acompañamos a empresas argentinas en su expansión hacia los mercados de Estados Unidos y China. Brindamos asesoramiento estratégico y operativo para que tu negocio se desarrolle exitosamente en estos mercados internacionales.",
 "benefits":[
   "Amplia experiencia y conocimiento del mercado internacional",
   "Red de contactos y alianzas estratégicas",
   "Asesoría legal y regulatoria",
   "Estudios de mercado y análisis competitivo",
   "Estrategias de entrada al mercado personalizadas",
   "Gestión de relaciones comerciales y negociación",
   "Apoyo logístico integral",
   "Acceso a oportunidades de inversión y financiamiento",
   "Formación y capacitación en comercio internacional",
   "Seguimiento continuo y soporte post-entrada al mercado",
 ],
 "sections":[
  ("Para empresas que buscan proveedores",[
    "Encontrar un proveedor en Alibaba lleva una tarde. Encontrar uno que entregue de forma consistente, al precio acordado y con la calidad pactada lleva bastante más, y normalmente requiere que alguien esté físicamente en el lugar.",
    "Hacemos la búsqueda, pedimos y comparamos muestras, visitamos la planta cuando el volumen lo justifica, verificamos que la empresa exista y tenga capacidad real de producción, y negociamos condiciones. Después, la misma estructura logística que usamos a diario se encarga del embarque.",
  ]),
  ("Para empresas que quieren vender en Estados Unidos",[
    "<ul><li>Análisis del canal: retail, mayorista, marketplace o venta directa</li><li>Requisitos de etiquetado, normativa y certificaciones del producto</li><li>Estructura de importación y almacenamiento en Miami</li><li>Fulfillment y distribución hacia el cliente final</li><li>Estructura de costos completa, del puerto a la góndola</li></ul>",
  ]),
  ("Cómo trabajamos",[
    "Empezamos con una conversación honesta sobre lo que querés lograr y en qué plazo. De ahí sale un plan con etapas, costos y responsables. No vendemos informes de cien páginas: trabajamos sobre la operación, y como además somos el operador logístico, lo que recomendamos es lo que después ejecutamos.",
  ]),
 ],
 "cta":"Agendá una conversación y evaluamos juntos la oportunidad concreta de tu empresa.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 6
{
 "slug":"mudanzas-internacionales",
 "nav":"Mudanza internacional",
 "h1":"Mudanza internacional de Estados Unidos a Argentina",
 "seo_title":"Mudanza Internacional EE. UU. a Argentina | World Wide Commerce",
 "meta":"Mudanza internacional puerta a puerta de USA a Argentina: presupuesto gratuito, embalaje, recolección, envío marítimo, aduana y 3 meses de storage sin costo.",
 "kw":"mudanza internacional estados unidos argentina, mudanza miami argentina, menaje de casa, trasladar muebles al exterior",
 "icon":"moving",
 "blurb":"Tu mudanza puerta a puerta, sin estrés ni sorpresas: del embalaje en USA a la entrega en tu nueva casa en Argentina.",
 "intro":"Mudarte de Estados Unidos a Argentina no es solo trasladar tus pertenencias: es un proceso emocional, complejo y lleno de trámites. Por eso, diseñamos un servicio 100% personalizado y sin intermediarios, que se adapta a tus tiempos, tus necesidades y tu presupuesto. Nos ocupamos de todo: desde el embalaje y la recolección en tu domicilio en USA hasta la entrega final en tu casa en Argentina, incluyendo gestiones aduaneras, documentación y seguimiento continuo.",
 "benefits":[
   "Años de experiencia en logística internacional y transporte puerta a puerta",
   "Atención personalizada y en español, con seguimiento constante",
   "Sin límites de volumen o cantidad de ítems: te mudás como quieras",
   "3 meses de almacenamiento gratuito en Miami si necesitás más tiempo para organizar",
   "Trato humano y cercano: entendemos el proceso y te acompañamos en cada paso",
 ],
 "sections":[
  ("¿Qué incluye nuestra mudanza puerta a puerta?",[
    "<ul><li><strong>Presupuesto gratuito y a medida:</strong> recibís una cotización clara, sin cargos ocultos ni sorpresas al llegar.</li><li><strong>Embalaje profesional en USA:</strong> protegemos tus muebles, objetos frágiles y pertenencias con materiales de alta calidad y personal capacitado.</li><li><strong>Recolección en domicilio y transporte local:</strong> pasamos por tu casa o depósito en USA y trasladamos tus bienes a nuestro centro logístico en Miami.</li><li><strong>Consolidado, carga y envío marítimo:</strong> organizamos toda la carga de forma segura y optimizada en contenedores hacia Argentina.</li><li><strong>Tramitación aduanera completa:</strong> nos ocupamos de todos los formularios, permisos y trámites de importación.</li><li><strong>Entrega en tu nuevo hogar en Argentina:</strong> llevamos tus pertenencias directamente a tu dirección en el país. Podés elegir si querés también el desembalaje y armado.</li></ul>",
  ]),
  ("También trasladamos tu vehículo",[
    "¿Querés llevar tu auto o moto a Argentina? Te asistimos con la baja del título en USA, el embalaje y fijación en contenedor, los trámites de aduana y la gestión del patentamiento en Argentina.",
  ]),
  ("3 meses de almacenamiento gratuito en Miami",[
    "Si necesitás más tiempo para organizar tu mudanza, incluimos 3 meses de storage sin costo en Miami para que puedas avanzar por etapas.",
  ]),
  ("Lo decimos claro",[
    "Mudarse no tiene por qué ser un caos. Confiá en quienes realmente hacen el trabajo. Mostramos la mercadería, los depósitos, los despachos: lo que ves, es real.",
  ]),
 ],
 "cta":"Contanos desde dónde y hasta dónde te mudás, y preparamos el plan y el presupuesto.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 7
{
 "slug":"e-commerce-fulfillment",
 "nav":"E-commerce fulfillment",
 "h1":"E-commerce fulfillment desde Miami (3PL)",
 "seo_title":"E-commerce Fulfillment 3PL en Miami | World Wide Commerce",
 "meta":"Servicio 3PL en Miami: guardamos tu inventario, preparamos cada pedido y lo despachamos por vos. Integrable con tu tienda online.",
 "kw":"fulfillment miami, 3pl miami, logistica ecommerce, pick and pack miami, dropshipping desde usa",
 "icon":"fulfillment",
 "blurb":"Servicio 3PL: almacenamos tu mercadería en Miami y gestionamos cada envío al cliente final cada vez que realizás una venta.",
 "intro":"Tu stock en Miami, tu negocio en todo el mundo: sin estrés ni complicaciones. Escalar tu tienda online no es solo enviar paquetes: exige eficiencia, orden y confianza. Por eso diseñamos un servicio 3PL (Third Party Logistics) integral y personalizado que se convierte en tu brazo operativo en Estados Unidos, adaptándose a tu volumen de ventas y tus metas de expansión. Nos ocupamos de todo el ciclo logístico: desde el resguardo seguro de tus productos en nuestros almacenes de Miami hasta el envío al cliente final, para que tu única preocupación sea seguir vendiendo.",
 "benefits":[
   "Gestión inteligente (WMS): visualizás tu inventario en tiempo real",
   "Almacenamiento profesional: espacio seguro, organizado y optimizado exclusivamente para tus productos",
   "Control de existencias: mantenimiento detallado y actualización constante de tus niveles de stock",
   "Logística global: envíos eficientes con entregas rápidas en USA, Latinoamérica y el resto del mundo",
   "Gestión de devoluciones: logística inversa profesional para manejar tus retornos sin fricciones",
 ],
 "sections":[
  ("Nuestros servicios incluyen",[
    "<ul><li><strong>Gestión inteligente (WMS):</strong> implementamos un sistema de control avanzado para que visualices tu inventario en tiempo real.</li><li><strong>Almacenamiento profesional:</strong> espacio seguro, organizado y optimizado exclusivamente para tus productos.</li><li><strong>Control de existencias:</strong> mantenimiento detallado y actualización constante de tus niveles de stock.</li><li><strong>Logística global:</strong> cobertura de envíos eficientes con entregas rápidas en USA, Latinoamérica y el resto del mundo.</li><li><strong>Gestión de devoluciones:</strong> un sistema de logística inversa profesional para manejar tus retornos sin fricciones.</li></ul>",
  ]),
 ],
 "cta":"Contanos cuántos pedidos despachás por mes y te armamos un esquema de fulfillment.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 8
{
 "slug":"logistica-personalizada",
 "nav":"Logística personalizada",
 "h1":"Logística personalizada para tu operación",
 "seo_title":"Logística Personalizada a Medida | World Wide Commerce",
 "meta":"Diseñamos el circuito logístico que tu operación necesita: del almacén al despacho final, con la flexibilidad que un servicio estándar no da.",
 "kw":"logistica personalizada, logistica a medida, solucion logistica empresas, cadena de suministro",
 "icon":"custom-logistics",
 "blurb":"Soluciones logísticas flexibles y adaptadas a cada cliente, con control total sobre tu carga, desde el almacén hasta el despacho final.",
 "intro":"Soluciones adaptadas a cada uno de nuestros clientes. Cuando tu carga o paquetería llega a nuestro depósito, ponemos a tu disposición diversos servicios logísticos pensados para ofrecerte mayor control y flexibilidad. Ajustamos cada paso del proceso para que se integre perfectamente a tu forma de trabajar.",
 "benefits":[
   "Opción de Hold por documentación",
   "Recibo de Warehouse individual por paquete",
   "Fotografías de alta calidad del contenido (opcional)",
   "Notificación por correo electrónico al cliente final",
   "Consolidación de carga automática por cliente o destino",
   "Reempaque optimizado",
   "Etiquetado personalizado",
   "Control de calidad básico",
   "Inventario digital con acceso en línea",
   "Gestión de devoluciones o reenvíos",
   "Revisión documental",
   "Despacho programado",
   "Recepción en casillero privado",
   "Reporte mensual de operaciones",
 ],
 "sections":[
  ("Servicios disponibles",[
    "<ul><li><strong>Opción de Hold por documentación:</strong> retención de paquetes hasta contar con valor FOB y factura comercial.</li><li><strong>Recibo de Warehouse individual por paquete:</strong> identificación, apertura, registro detallado (descripción, tracking, peso y dimensiones).</li><li><strong>Fotografías de alta calidad del contenido (opcional):</strong> para control interno o envío al cliente final.</li><li><strong>Notificación por correo electrónico al cliente final:</strong> envío automático y personalizado al recibir su paquete en almacén.</li><li><strong>Consolidación de carga automática por cliente o destino:</strong> agrupamos múltiples paquetes bajo una misma guía para reducir costos de envío.</li><li><strong>Reempaque optimizado:</strong> eliminamos embalaje innecesario para reducir volumen y peso, o mejorar presentación.</li><li><strong>Etiquetado personalizado:</strong> aplicación de etiquetas con códigos de barras.</li><li><strong>Control de calidad básico:</strong> verificación visual del estado del producto recibido.</li><li><strong>Inventario digital con acceso en línea:</strong> el cliente puede visualizar en tiempo real el estado de sus paquetes.</li><li><strong>Gestión de devoluciones o reenvíos:</strong> servicio de logística inversa hacia proveedores o terceros.</li><li><strong>Revisión documental:</strong> verificación de facturas comerciales, valores FOB, descripciones y requisitos aduaneros.</li><li><strong>Despacho programado:</strong> posibilidad de indicar una frecuencia o día específico para consolidar y despachar la carga.</li><li><strong>Recepción en casillero privado:</strong> casillero con número exclusivo o nombre del cliente para identificar su carga.</li><li><strong>Reporte mensual de operaciones:</strong> informe en formato PDF o Excel con resumen de movimientos, costos y tracking.</li></ul>",
  ]),
 ],
 "cta":"Contanos cómo funciona hoy tu operación y te proponemos un circuito mejor.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 9
{
 "slug":"preparacion-de-la-carga",
 "nav":"Preparación de la carga",
 "h1":"Preparación y acondicionamiento de la carga",
 "seo_title":"Preparación de Carga para Exportación | World Wide Commerce",
 "meta":"Acondicionamos tu carga para el despacho: reempaque, paletizado, flejado, etiquetado y documentación. Menos volumen, menos flete, menos roturas.",
 "kw":"preparacion de carga, paletizado, reempaque exportacion, embalaje para exportacion, acondicionamiento carga",
 "icon":"cargo-prep",
 "blurb":"Despacho y conectividad global: carga aérea o marítima, entrega a tu agente o retiro en nuestro depósito.",
 "intro":"Despacho y conectividad global. Una vez que la carga se encuentra lista para su despacho, ofrecemos distintos servicios de envío, adaptados a las necesidades de cada cliente.",
 "benefits":[
   "Envío de carga aérea con tarifa preferencial",
   "Envío de carga marítima (LCL o FCL)",
   "Entrega de carga a tu agente",
   "Retiro en depósito en Miami o Guangzhou",
 ],
 "sections":[
  ("Envío de carga aérea con tarifa preferencial",[
    "Brindamos documentación de embarque y gestión de espacio aéreo con tarifas competitivas. Ofrecemos envío a los principales aeropuertos de Latinoamérica, trabajando con aerolíneas reconocidas y las rutas más óptimas para tu envío.",
  ]),
  ("Envío de carga marítima (LCL o FCL)",[
    "Ofrecemos servicios de consolidación de carga (LCL) o contenedor completo (FCL) desde Miami hacia los principales puertos de Latinoamérica. Gestionamos documentación, booking y entrega en destino, con tarifas competitivas y seguimiento continuo.",
  ]),
  ("Entrega de carga a tu agente",[
    "Si contás con tu propio agente de cargas, podemos entregar la carga ya lista para volar o embarcar, cumpliendo con tus instrucciones específicas.",
  ]),
  ("Retiro en depósito",[
    "Preparamos la carga según tus indicaciones y la dejamos disponible para retiro en nuestro depósito en Miami o Guangzhou.",
  ]),
 ],
 "cta":"Contanos qué tipo de carga manejás y te decimos cómo acondicionarla para gastar menos.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 10
{
 "slug":"software-web-a-medida",
 "nav":"Software web a medida",
 "h1":"Software web a medida para logística",
 "seo_title":"Software Logístico a Medida | World Wide Commerce",
 "meta":"Desarrollamos plataformas web para centralizar el seguimiento y la gestión logística: casilleros, inventario, tracking y portal de clientes.",
 "kw":"software logistico a medida, sistema de tracking, desarrollo web logistica, plataforma gestion envios, wms",
 "icon":"code",
 "blurb":"Plataforma integral para empresas con operaciones logísticas propias: control, seguimiento y gestión de cada etapa en un solo sistema.",
 "intro":"Desarrollamos una plataforma integral para empresas con operaciones logísticas propias, centralizando el control, seguimiento y gestión de cada etapa del proceso en un solo sistema. Ideal para empresas con procesos logísticos propios que requieren implementar una plataforma integral dedicada.",
 "benefits":[
   "Panel de control del cliente final que permite prealertar",
   "Email automático por paquete arribado",
   "Solicitud de fotos de cada paquete recibido",
   "Consolidación de paquetes y seguimiento de paquetería",
   "Módulo de sistemas para el agente: visualiza todo el proceso de logística, el manifiesto de carga, y cuenta con facturación y cobranza a cliente final",
   "Herramientas de atención al cliente y mucho más",
 ],
 "sections":[
  ("Qué incluye la plataforma",[
    "<ul><li>Panel de control del cliente final que permite PREALERTAR</li><li>Email automático por paquete arribado</li><li>Solicitud de fotos de cada paquete recibido</li><li>Consolidación de paquetes y seguimiento de paquetería</li><li>Módulo de sistemas para el AGENTE, que permite visualizar todo el proceso de logística, ver manifiesto de carga y gestionar la facturación y cobranza a cliente final</li><li>Herramientas para atención al cliente y mucho más</li></ul>",
  ]),
  ("La ventaja de que lo construya un operador logístico",[
    "Un equipo de desarrollo que no conoce el negocio te va a pedir que le expliques qué es el peso volumétrico, por qué un bulto se desconsolida o cómo cambia un estado cuando la aduana retiene la carga. Nosotros ya lo sabemos, porque lo operamos todos los días. Eso acorta el proyecto y, sobre todo, evita construir algo que en la práctica no sirve.",
  ]),
 ],
 "cta":"Contanos qué parte de tu operación querés digitalizar y te proponemos un alcance.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 11
{
 "slug":"infraestructura-logistica-en-miami",
 "nav":"Infraestructura en Miami",
 "h1":"Nuestra infraestructura logística en Miami",
 "seo_title":"Almacén y Operación Logística en Miami | World Wide Commerce",
 "meta":"Almacén en Miami TAX FREE (ahorrás 7 % de sales tax): recepción, consolidación y salidas aéreas y marítimas a Latinoamérica con plataforma online.",
 "kw":"almacen miami, deposito logistico miami, casillero en miami, operador logistico miami, consolidacion miami",
 "icon":"miami",
 "blurb":"Almacenes estratégicos en Miami, TAX FREE, para recibir, gestionar y preparar paquetería y carga comercial hacia Latinoamérica.",
 "intro":"Contamos con almacenes estratégicamente ubicados en Miami para la recepción, gestión y preparación de paquetería y carga comercial, optimizando costos y facilitando tus operaciones hacia Latinoamérica. Nuestro warehouse es TAX FREE: tus clientes ahorrarán 7 % de sales tax de USA.",
 "benefits":[
   "Warehouse TAX FREE: tus clientes ahorrarán 7 % de sales tax de USA",
   "Recepción de paquetería y carga comercial en nuestras bodegas en Miami (aérea y marítima)",
   "Retiro de carga en todo USA (In-Land)",
   "Identificación, apertura, registro de descripción, tracking, peso y medidas",
   "Fotos opcionales del contenido",
   "Envío automático de notificaciones al cliente final",
   "Preparación de carga y documentación para exportación",
   "Salidas aéreas y marítimas a Latinoamérica",
   "Posibilidad de entregar la carga a tu propio agente",
   "Plataforma online con prealertas, consolidaciones y seguimiento",
   "Integración con sistemas de facturación, manifiesto y atención al cliente",
   "Fulfillment para e-commerce: 3PL, inventario, envío por unidad y devoluciones",
 ],
 "sections":[
  ("Warehouse TAX FREE",[
    "Al recibir tus compras en nuestro depósito de Miami, tus clientes ahorran el 7 % de sales tax de USA.",
  ]),
  ("Nuestra dirección en Miami",[
    "<p><strong>2355 NW 70th Ave, Unit D-06<br>Miami, FL 33122, Estados Unidos</strong></p>",
    "Importante: al comprar en una tienda online tenés que usar la dirección exacta junto con tu número de casillero, que recibís al registrarte. Ese número es lo que nos permite identificar que el paquete es tuyo, así que un envío sin casillero demora bastante más en procesarse.",
  ]),
 ],
 "cta":"Registrate gratis y obtené tu dirección y tu casillero en Miami hoy mismo.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 12
{
 "slug":"infraestructura-logistica-en-china",
 "nav":"Infraestructura en China",
 "h1":"Nuestra infraestructura logística en China",
 "seo_title":"Almacenes y Consolidación en China | World Wide Commerce",
 "meta":"Centro de operaciones en China: warehouse en Guangzhou y Hong Kong, verificación, consolidación multi-proveedor y envíos a Latinoamérica o Miami.",
 "kw":"almacen en china, consolidacion carga china, importar desde china, casillero china, guangzhou hong kong",
 "icon":"china",
 "blurb":"Warehouses en Guangzhou y Hong Kong para recibir, verificar, consolidar y preparar carga de múltiples proveedores hacia Latinoamérica.",
 "intro":"Operamos con centros estratégicos en China para recibir, consolidar y preparar carga de múltiples proveedores, facilitando tus compras y optimizando su envío hacia Latinoamérica o nuestros hubs internacionales. Nuestro centro de operaciones cuenta con warehouse en Guangzhou y en Hong Kong.",
 "benefits":[
   "Warehouse en Guangzhou para recepción de carga",
   "Warehouse en Hong Kong para recepción de compras que no se envían dentro de China Mainland (Shein, AliExpress, etc.)",
   "Coordinación directa con proveedores (hablamos inglés, chino y español)",
   "Verificación de contenido (por unidad o bulto)",
   "Registro fotográfico y control de calidad básico",
   "Consolidación multi-proveedor",
   "Preparación de documentación para exportación",
   "Envíos directos a Latinoamérica o a nuestro centro en Miami (depósito bonded para carga en tránsito)",
   "Plataforma compartida con la misma funcionalidad que en USA",
 ],
 "sections":[
  ("Nuestro centro de operaciones en China",[
    "<ul><li><strong>Guangzhou:</strong> warehouse para recepción de carga.</li><li><strong>Hong Kong:</strong> warehouse para recepción de compras que no se envían dentro de China Mainland (Shein, AliExpress, etc.).</li></ul>",
  ]),
  ("Verificación y consolidación",[
    "Coordinamos directamente con tus proveedores (hablamos inglés, chino y español), verificamos el contenido por unidad o bulto, hacemos registro fotográfico y control de calidad básico, y consolidamos compras de múltiples proveedores en un solo envío.",
  ]),
  ("Envíos y plataforma",[
    "Enviamos directo a Latinoamérica o a nuestro centro en Miami (depósito bonded para carga en tránsito). Operás con la misma plataforma y funcionalidad que en USA.",
  ]),
 ],
 "cta":"Contanos a qué proveedores le comprás y coordinamos la recepción en nuestro almacén de China.",
 "new":True,
},
# ─────────────────────────────────────────────────────────────── 13
{
 "slug":"tercerizacion-de-servicios-logisticos",
 "nav":"Tercerización logística",
 "h1":"Tercerización de servicios logísticos",
 "seo_title":"Tercerización Logística para Empresas | World Wide Commerce",
 "meta":"Tercerizá tu logística internacional con un operador que tiene almacenes propios en Miami y China. Menos costo fijo, más capacidad.",
 "kw":"tercerizacion logistica, outsourcing logistico, operador logistico 3pl, externalizar logistica",
 "icon":"outsourcing",
 "blurb":"Con depósitos propios en USA y una logística armada, nos encargamos de la gestión de almacenes y envíos para que tu empresa se enfoque en su negocio.",
 "intro":"Ofrecemos soluciones de tercerización de servicios logísticos para empresas. Con depósitos propios en USA y una logística armada, nos encargamos de toda la gestión de almacenes y envíos, permitiendo a tu empresa enfocarse en su core business mientras nosotros manejamos la logística.",
 "benefits":[
   "Reducción de costos operativos",
   "Acceso a infraestructura logística avanzada",
   "Gestión integral de almacenes y depósitos",
   "Optimización de tiempos de entrega",
   "Mayor flexibilidad y escalabilidad en operaciones logísticas",
   "Tecnología de punta para el seguimiento y control de inventarios",
   "Servicio personalizado y adaptado a las necesidades del cliente",
   "Cumplimiento de normativas internacionales de transporte",
   "Soluciones logísticas a medida para diferentes industrias",
   "Soporte y asesoría continua en gestión logística",
 ],
 "sections":[
  ("Qué podés tercerizar",[
    "<ul><li><strong>Almacenamiento:</strong> inventario en Miami o en China</li><li><strong>Recepción y control:</strong> ingreso de mercadería de tus proveedores</li><li><strong>Preparación:</strong> reempaque, paletizado y etiquetado</li><li><strong>Fulfillment:</strong> pick &amp; pack y despacho de pedidos online</li><li><strong>Comercio exterior:</strong> documentación, aduana y flete internacional</li><li><strong>Última milla:</strong> coordinación de la entrega final</li></ul>",
    "No hace falta tercerizar todo. Muchos clientes empiezan por el tramo que más les duele y van sumando a medida que el esquema demuestra funcionar.",
  ]),
  ("Cómo medimos el servicio",[
    "Un acuerdo de tercerización sin indicadores es una apuesta. Definimos desde el inicio qué se mide: tiempo de procesamiento de un ingreso, plazo de preparación de un pedido, exactitud del inventario, cumplimiento de las fechas de despacho. Esos números se reportan con la periodicidad que acordemos, y son la base de la conversación cuando algo hay que ajustar.",
  ]),
  ("La transición",[
    "El cambio de operador es el momento de mayor riesgo, así que lo planificamos en etapas y con solapamiento. Primero relevamos tu operación actual, después migramos un flujo acotado, verificamos que funcione con datos reales y recién entonces movemos el resto. Nunca al revés.",
  ]),
 ],
 "cta":"Contanos qué parte de tu logística te está consumiendo más tiempo y lo analizamos juntos.",
 "new":False,
},
# ─────────────────────────────────────────────────────────────── 14
{
 "slug":"transporte-internacional-de-mascotas",
 "nav":"Transporte de mascotas",
 "h1":"Transporte internacional de mascotas",
 "seo_title":"Transporte Internacional de Mascotas | World Wide Commerce",
 "meta":"Coordinamos el transporte internacional de mascotas desde y hacia Estados Unidos con proveedores especializados y operadores autorizados en Miami.",
 "kw":"transporte internacional de mascotas, viajar con mascota al exterior, enviar perro a argentina, jaula iata, certificado veterinario",
 "icon":"pet",
 "blurb":"Coordinamos el traslado internacional de mascotas y animales pequeños desde y hacia Estados Unidos con proveedores especializados y operadores autorizados.",
 "intro":"Coordinamos el transporte internacional de mascotas y animales pequeños desde y hacia Estados Unidos mediante proveedores especializados y operadores autorizados. Para familias que necesitan trasladar a sus animales de manera segura, organizada y cumpliendo los requisitos sanitarios y logísticos exigidos por cada país, trabajamos junto a proveedores especializados y centros autorizados ubicados en el área de carga del Aeropuerto Internacional de Miami, brindando acompañamiento durante todo el proceso.",
 "benefits":[
   "Evaluación del traslado: analizamos origen, destino y requisitos del viaje",
   "Coordinación documental: asistencia con certificados y documentación requerida",
   "Gestión logística especializada con operadores autorizados",
   "Seguimiento personalizado con información actualizada durante todo el proceso",
   "Transporte terrestre complementario entre aeropuertos y destinos finales",
   "Importación y exportación: orientación sobre requisitos e inspecciones",
 ],
 "sections":[
  ("¿Cómo funciona?",[
    "<ul><li>Solicitá tu cotización</li><li>Enviános la información de tu mascota</li><li>Revisamos los requisitos sanitarios y logísticos</li><li>Coordinamos el traslado con operadores autorizados</li><li>Realizamos seguimiento durante todo el proceso</li><li>Coordinamos la entrega al propietario o representante autorizado</li></ul>",
  ]),
  ("Importación a Estados Unidos",[
    "<ul><li>Recepción en el Aeropuerto Internacional de Miami</li><li>Coordinación documental</li><li>Asistencia con requisitos regulatorios</li><li>Coordinación de inspecciones</li><li>Transporte terrestre al destino final</li><li>Seguimiento personalizado</li></ul>",
  ]),
  ("Exportación desde Estados Unidos",[
    "<ul><li>Asesoramiento previo al viaje</li><li>Revisión de requisitos del país de destino</li><li>Coordinación de documentación sanitaria</li><li>Gestión logística con aerolíneas</li><li>Entrega en terminal de carga</li><li>Seguimiento del embarque</li></ul>",
  ]),
 ],
 "cta":"Contanos la ruta y los datos de tu mascota, y armamos el plan de viaje completo.",
 "new":False,
},
]


# ═══════════════════════════════════════════════════════════════ ENGLISH FIELDS
# Merged into SERVICES below: nav_en, h1_en, seo_title_en, meta_en, blurb_en,
# intro_en, cta_en, benefits_en (sections stay Spanish-only).
SERVICES_EN = {
"realizamos-tus-compras": {
 "nav_en":"We Shop For You",
 "h1_en":"We shop for you in the USA and China",
 "seo_title_en":"Personal Shopping in the USA & China | World Wide Commerce",
 "meta_en":"We buy for you from US and Chinese stores when your card is declined. We pay with local methods and ship to your door in Latin America.",
 "blurb_en":"We make shopping at major US and Chinese stores easy: we handle the entire process, from finding the product to shipping it to Argentina.",
 "intro_en":"This service is designed to make buying from major US and Chinese stores easy, on the customer's request. We handle the entire purchasing process, from finding the product to buying it and shipping it to Argentina, ensuring the best value for money and compliance with the requested specifications.",
 "benefits_en":[
   "Saves you time and effort",
   "Access to exclusive and high-demand products",
   "Advice on choosing products",
   "Secure international payment handling",
   "Verification of product authenticity and quality",
   "Purchase consolidation to reduce shipping costs",
   "Handling of returns and refunds",
   "Transparent purchase management and tracking",
   "Personalized attention and ongoing support",
   "Fast and efficient delivery in Argentina",
 ],
 "cta_en":"Send us the product link and we'll quote the full purchase, delivered to your door.",
},
"alquiler-de-almacenamiento": {
 "nav_en":"Storage Rental",
 "h1_en":"Storage rental at our Miami warehouse",
 "seo_title_en":"Storage Rental in Miami | World Wide Commerce",
 "meta_en":"Secure mailbox at our Miami warehouse: Single use $4.50 per package, Basic tier $8.50/month and Premium tier $17.50/month, with insurance included.",
 "blurb_en":"A secure, reliable mailbox at our Miami warehouse, with plans from $4.50 per package or a monthly subscription from $8.50.",
 "intro_en":"With this service you can enjoy a mailbox at our warehouse, safe and reliable. Choose the plan that suits you best: Single use ($4.50 per package), Basic tier ($8.50 per month) or Premium tier ($17.50 per month).",
 "benefits_en":[
   "Single use: $4.50 per package, plus $0.50 for $1,000 of insurance",
   "Basic tier: $8.50 per month, 5 packages per month and free $1,000 insurance",
   "Premium tier: $17.50 per month, 15 packages per month and free $1,000 insurance",
   "Email and chat support (priority support on monthly plans)",
   "7-day pickup window",
   "$1 surcharge per package above the monthly limit",
   "Cancel anytime on monthly plans",
 ],
 "sections_en":[
  ("Plans and pricing",[
    "<ul><li><strong>Single use — $4.50/package:</strong> $4.50 per package, $0.50 for $1,000 of insurance, email and chat support, 7-day pickup window and a $2.50 late pickup fee.</li><li><strong>Basic tier — $8.50/month:</strong> 5 packages per month, free $1,000 insurance, priority email and chat support, 7-day pickup window, $2.50 late pickup fee, $1 surcharge per package above the limit and cancel anytime.</li><li><strong>Premium tier — $17.50/month:</strong> 15 packages per month, free $1,000 insurance, priority email and chat support, 7-day pickup window, $2.50 late pickup fee, $1 surcharge per package above the limit and cancel anytime.</li></ul>",
  ]),
  ("Oversized packages",[
    "If you have oversized packages, contact clientes@worldwidecommerce.us and we'll do our best to accommodate them.",
  ]),
 ],
 "cta_en":"Tell us how much volume you need to store and we'll put together a storage proposal.",
},
"servicio-puerta-a-puerta": {
 "nav_en":"Door-to-Door Service",
 "h1_en":"Door-to-door service: from the store to your home",
 "seo_title_en":"Door-to-Door Shipping to Latin America | World Wide Commerce",
 "meta_en":"Door-to-door service from the USA and China to your address in Latin America. We handle purchase, freight, customs and final delivery with no surprises.",
 "blurb_en":"You shop at major US or Chinese stores and we take care of the rest, all the way to your address in Argentina.",
 "intro_en":"With our door-to-door service, customers can shop at major US or Chinese stores and we take care of the rest. We receive the products at our warehouse, inspect them, repack them if necessary, handle returns and ship them directly to the customer's address in Argentina.",
 "benefits_en":[
   "Convenience and simplicity for the customer",
   "Quality control and product verification",
   "Professional repacking for added safety",
   "Efficient handling of returns and exchanges",
   "Lower customs costs",
   "Fast, safe home delivery",
   "Real-time shipment tracking",
   "Personalized attention at every stage",
   "Insurance service for peace of mind",
   "Adaptable to different product types and volumes",
 ],
 "cta_en":"Request your door-to-door quote and get the full breakdown before you decide.",
},
"importacion-y-exportacion": {
 "nav_en":"Import & Export",
 "h1_en":"Import and export between the Americas and Asia",
 "seo_title_en":"Commercial Import & Export | World Wide Commerce",
 "meta_en":"Commercial import and export between the USA, China and Latin America. Documentation, customs, air and sea freight with over 20 years of experience.",
 "blurb_en":"A complete import and export service from the United States and China to Argentina, from pickup at origin to final delivery.",
 "intro_en":"We offer a complete product import and export service from the United States and China to Argentina. We handle the entire logistics process, from collecting the goods at origin to final delivery at destination. Our goal is to guarantee safety, speed and efficiency in every shipment, adapting to each customer's specific needs.",
 "benefits_en":[
   "Extensive customs management experience",
   "Real-time cargo tracking",
   "Lower logistics costs",
   "Safe, professional cargo handling",
   "Personalized advice throughout the process",
   "Optimized delivery times",
   "Logistics solutions adapted to different industries",
   "Simplified customs procedures",
   "Cargo transport insurance",
   "Strategic alliances with international agents",
 ],
 "cta_en":"Tell us what you want to import or export and we'll design the full operation.",
},
"desarrollo-de-negocios-internacionales": {
 "nav_en":"Business Development",
 "h1_en":"International business development",
 "seo_title_en":"International Business Development | World Wide Commerce",
 "meta_en":"We help Latin American companies operate in the USA and China: suppliers, structure, logistics and market entry.",
 "blurb_en":"With over 20 years of experience, we support Argentine companies expanding into the US and Chinese markets.",
 "intro_en":"With over 20 years of experience, we support Argentine companies expanding into the US and Chinese markets. We provide strategic and operational advice so your business grows successfully in these international markets.",
 "benefits_en":[
   "Extensive experience and knowledge of the international market",
   "Network of contacts and strategic alliances",
   "Legal and regulatory advice",
   "Market research and competitive analysis",
   "Tailored market-entry strategies",
   "Business relationship management and negotiation",
   "Comprehensive logistics support",
   "Access to investment and financing opportunities",
   "International trade training",
   "Ongoing follow-up and post-market-entry support",
 ],
 "cta_en":"Schedule a conversation and we'll assess your company's specific opportunity together.",
},
"mudanzas-internacionales": {
 "nav_en":"International Moving",
 "h1_en":"International moving from the United States to Argentina",
 "seo_title_en":"International Moving USA to Argentina | World Wide Commerce",
 "meta_en":"Door-to-door international moving from the USA to Argentina: free quote, packing, pickup, sea freight, customs and 3 months of free storage.",
 "blurb_en":"Your door-to-door move, with no stress or surprises: from packing in the USA to delivery at your new home in Argentina.",
 "intro_en":"Moving from the United States to Argentina isn't just transporting your belongings: it's an emotional, complex process full of paperwork. That's why we designed a 100% personalized service with no middlemen, tailored to your timing, needs and budget. We take care of everything: from packing and pickup at your home in the USA to final delivery at your home in Argentina, including customs procedures, documentation and continuous tracking.",
 "benefits_en":[
   "Years of experience in international logistics and door-to-door transport",
   "Personalized, Spanish-language support with constant follow-up",
   "No limits on volume or number of items: move the way you want",
   "3 months of free storage in Miami if you need more time to get organized",
   "Human, close service: we understand the process and support you every step of the way",
 ],
 "sections_en":[
  ("What does our door-to-door move include?",[
    "<ul><li><strong>Free, tailored quote:</strong> you get a clear quotation with no hidden charges or surprises on arrival.</li><li><strong>Professional packing in the USA:</strong> we protect your furniture, fragile items and belongings with high-quality materials and trained staff.</li><li><strong>Home pickup and local transport:</strong> we come to your home or storage in the USA and take your goods to our logistics center in Miami.</li><li><strong>Consolidation, loading and sea freight:</strong> we organize all your cargo safely and efficiently in containers to Argentina.</li><li><strong>Full customs processing:</strong> we handle all forms, permits and import procedures.</li><li><strong>Delivery to your new home in Argentina:</strong> we take your belongings directly to your address in the country. You can also choose unpacking and assembly.</li></ul>",
  ]),
  ("We also move your vehicle",[
    "Want to take your car or motorcycle to Argentina? We help with title cancellation in the USA, packing and securing it in the container, customs procedures and registration in Argentina.",
  ]),
  ("3 months of free storage in Miami",[
    "If you need more time to organize your move, we include 3 months of free storage in Miami so you can move forward in stages.",
  ]),
  ("Let's be clear",[
    "Moving doesn't have to be chaos. Trust the people who actually do the work. We show the goods, the warehouses, the shipments: what you see is real.",
  ]),
 ],
 "cta_en":"Tell us where you're moving from and to, and we'll prepare the plan and quote.",
},
"e-commerce-fulfillment": {
 "nav_en":"E-commerce Fulfillment",
 "h1_en":"E-commerce fulfillment from Miami (3PL)",
 "seo_title_en":"E-commerce 3PL Fulfillment in Miami | World Wide Commerce",
 "meta_en":"3PL service in Miami: we store your inventory, pick and pack every order and ship it for you. Integrates with your online store.",
 "blurb_en":"3PL service: we store your goods in Miami and handle every shipment to your end customer each time you make a sale.",
 "intro_en":"Your stock in Miami, your business worldwide: no stress, no complications. Scaling your online store isn't just shipping packages: it demands efficiency, order and trust. That's why we designed a comprehensive, personalized 3PL (Third Party Logistics) service that becomes your operational arm in the United States, adapting to your sales volume and expansion goals. We handle the entire logistics cycle: from safe storage of your products in our Miami warehouses to shipping to the end customer, so your only concern is to keep selling.",
 "benefits_en":[
   "Smart management (WMS): see your inventory in real time",
   "Professional storage: safe, organized space optimized exclusively for your products",
   "Stock control: detailed upkeep and constant updating of your stock levels",
   "Global logistics: efficient shipping with fast deliveries in the USA, Latin America and the rest of the world",
   "Returns management: a professional reverse-logistics system to handle your returns without friction",
 ],
 "sections_en":[
  ("Our services include",[
    "<ul><li><strong>Smart management (WMS):</strong> we implement an advanced control system so you can see your inventory in real time.</li><li><strong>Professional storage:</strong> safe, organized space optimized exclusively for your products.</li><li><strong>Stock control:</strong> detailed upkeep and constant updating of your stock levels.</li><li><strong>Global logistics:</strong> efficient shipping coverage with fast deliveries in the USA, Latin America and the rest of the world.</li><li><strong>Returns management:</strong> a professional reverse-logistics system to handle your returns without friction.</li></ul>",
  ]),
 ],
 "cta_en":"Tell us how many orders you ship per month and we'll build a fulfillment plan.",
},
"logistica-personalizada": {
 "nav_en":"Custom Logistics",
 "h1_en":"Custom logistics for your operation",
 "seo_title_en":"Custom Logistics Solutions | World Wide Commerce",
 "meta_en":"We design the logistics flow your operation needs, from warehouse to final dispatch, with the flexibility a standard service can't offer.",
 "blurb_en":"Flexible logistics solutions tailored to each customer, with full control over your cargo, from warehouse to final dispatch.",
 "intro_en":"Solutions tailored to each of our customers. When your cargo or parcels arrive at our warehouse, we offer a range of logistics services designed to give you more control and flexibility. We adjust every step of the process so it fits seamlessly into the way you work.",
 "benefits_en":[
   "Hold option for documentation",
   "Individual warehouse receipt per package",
   "High-quality photos of the contents (optional)",
   "Email notification to the end customer",
   "Automatic cargo consolidation by customer or destination",
   "Optimized repacking",
   "Custom labeling",
   "Basic quality control",
   "Digital inventory with online access",
   "Returns or reshipment management",
   "Document review",
   "Scheduled dispatch",
   "Private mailbox receiving",
   "Monthly operations report",
 ],
 "sections_en":[
  ("Available services",[
    "<ul><li><strong>Hold option for documentation:</strong> packages are held until the FOB value and commercial invoice are available.</li><li><strong>Individual warehouse receipt per package:</strong> identification, opening and detailed logging (description, tracking, weight and dimensions).</li><li><strong>High-quality photos of the contents (optional):</strong> for internal control or to send to the end customer.</li><li><strong>Email notification to the end customer:</strong> automatic, personalized message when their package arrives at the warehouse.</li><li><strong>Automatic cargo consolidation by customer or destination:</strong> we group multiple packages under a single waybill to reduce shipping costs.</li><li><strong>Optimized repacking:</strong> we remove unnecessary packaging to reduce volume and weight, or improve presentation.</li><li><strong>Custom labeling:</strong> application of barcode labels.</li><li><strong>Basic quality control:</strong> visual check of the condition of the received product.</li><li><strong>Digital inventory with online access:</strong> customers can see the status of their packages in real time.</li><li><strong>Returns or reshipment management:</strong> reverse-logistics service to suppliers or third parties.</li><li><strong>Document review:</strong> verification of commercial invoices, FOB values, descriptions and customs requirements.</li><li><strong>Scheduled dispatch:</strong> option to set a specific frequency or day to consolidate and dispatch cargo.</li><li><strong>Private mailbox receiving:</strong> a mailbox with an exclusive number or the customer's name to identify their cargo.</li><li><strong>Monthly operations report:</strong> PDF or Excel report summarizing movements, costs and tracking.</li></ul>",
  ]),
 ],
 "cta_en":"Tell us how your operation works today and we'll propose a better flow.",
},
"preparacion-de-la-carga": {
 "nav_en":"Cargo Preparation",
 "h1_en":"Cargo preparation and conditioning",
 "seo_title_en":"Export Cargo Preparation | World Wide Commerce",
 "meta_en":"We prepare your cargo for dispatch: repacking, palletizing, strapping, labeling and documentation. Less volume, lower freight, fewer breakages.",
 "blurb_en":"Global dispatch and connectivity: air or sea freight, hand-off to your agent or pickup at our warehouse.",
 "intro_en":"Dispatch and global connectivity. Once your cargo is ready to ship, we offer a range of shipping services adapted to each customer's needs.",
 "benefits_en":[
   "Air cargo shipping at preferential rates",
   "Sea freight (LCL or FCL)",
   "Cargo hand-off to your agent",
   "Pickup at our warehouse in Miami or Guangzhou",
 ],
 "sections_en":[
  ("Air cargo at preferential rates",[
    "We provide shipping documentation and air space management at competitive rates. We ship to the main airports in Latin America, working with reputable airlines and the best routes for your shipment.",
  ]),
  ("Sea freight (LCL or FCL)",[
    "We offer cargo consolidation (LCL) or full container (FCL) services from Miami to the main ports in Latin America. We handle documentation, booking and delivery at destination, with competitive rates and continuous tracking.",
  ]),
  ("Cargo hand-off to your agent",[
    "If you have your own freight agent, we can hand over the cargo ready to fly or ship, following your specific instructions.",
  ]),
  ("Warehouse pickup",[
    "We prepare the cargo according to your instructions and leave it available for pickup at our warehouse in Miami or Guangzhou.",
  ]),
 ],
 "cta_en":"Tell us what kind of cargo you handle and we'll show you how to prepare it to spend less.",
},
"software-web-a-medida": {
 "nav_en":"Custom Web Software",
 "h1_en":"Custom web software for logistics",
 "seo_title_en":"Custom Logistics Software | World Wide Commerce",
 "meta_en":"We build web platforms to centralize logistics tracking and management: mailboxes, inventory, tracking and customer portal.",
 "blurb_en":"A comprehensive platform for companies with their own logistics operations: control, tracking and management of every stage in one system.",
 "intro_en":"We develop a comprehensive platform for companies with their own logistics operations, centralizing control, tracking and management of every stage of the process in a single system. Ideal for companies with their own logistics processes that need to implement a dedicated, comprehensive platform.",
 "benefits_en":[
   "End-customer dashboard that lets them pre-alert shipments",
   "Automatic email for every package that arrives",
   "Photo requests for each package received",
   "Package consolidation and parcel tracking",
   "Agent system module: see the entire logistics process and cargo manifest, with billing and collection from the end customer",
   "Customer service tools and much more",
 ],
 "sections_en":[
  ("What the platform includes",[
    "<ul><li>End-customer dashboard that lets them PRE-ALERT shipments</li><li>Automatic email for every package that arrives</li><li>Photo requests for each package received</li><li>Package consolidation and parcel tracking</li><li>AGENT system module to see the entire logistics process, view the cargo manifest and manage billing and collection from the end customer</li><li>Customer service tools and much more</li></ul>",
  ]),
  ("The advantage of being built by a logistics operator",[
    "A development team that doesn't know the business will ask you to explain what volumetric weight is, why a package gets deconsolidated, or how a status changes when customs holds the cargo. We already know, because we operate it every day. That shortens the project and, above all, avoids building something that doesn't work in practice.",
  ]),
 ],
 "cta_en":"Tell us which part of your operation you want to digitize and we'll propose a scope.",
},
"infraestructura-logistica-en-miami": {
 "nav_en":"Miami Infrastructure",
 "h1_en":"Our logistics infrastructure in Miami",
 "seo_title_en":"Warehouse & Logistics Operation in Miami | World Wide Commerce",
 "meta_en":"TAX FREE Miami warehouse (save the 7% sales tax): receiving, consolidation and air and sea departures to Latin America with an online platform.",
 "blurb_en":"Strategic TAX FREE warehouses in Miami to receive, manage and prepare parcels and commercial cargo to Latin America.",
 "intro_en":"We have strategically located warehouses in Miami for receiving, managing and preparing parcels and commercial cargo, optimizing costs and simplifying your operations to Latin America. Our warehouse is TAX FREE: your customers save the 7% US sales tax.",
 "benefits_en":[
   "TAX FREE warehouse: your customers save the 7% US sales tax",
   "Receiving of parcels and commercial cargo at our Miami warehouses (air and sea)",
   "In-land cargo pickup anywhere in the USA",
   "Identification, opening, and logging of description, tracking, weight and measurements",
   "Optional photos of the contents",
   "Automatic notifications to the end customer",
   "Cargo preparation and export documentation",
   "Air and sea departures to Latin America",
   "Option to hand the cargo to your own agent",
   "Online platform with pre-alerts, consolidations and tracking",
   "Integration with billing, manifest and customer service systems",
   "E-commerce fulfillment: 3PL, inventory, per-unit shipping and returns",
 ],
 "sections_en":[
  ("TAX FREE warehouse",[
    "When your purchases are received at our Miami warehouse, your customers save the 7% US sales tax.",
  ]),
  ("Our address in Miami",[
    "<p><strong>2355 NW 70th Ave, Unit D-06<br>Miami, FL 33122, United States</strong></p>",
    "Important: when buying from an online store you must use the exact address together with your mailbox number, which you receive when you sign up. That number is what lets us identify the package as yours, so a shipment without a mailbox number takes much longer to process.",
  ]),
 ],
 "cta_en":"Sign up free and get your Miami address and mailbox today.",
},
"infraestructura-logistica-en-china": {
 "nav_en":"China Infrastructure",
 "h1_en":"Our logistics infrastructure in China",
 "seo_title_en":"Warehouses & Consolidation in China | World Wide Commerce",
 "meta_en":"Our China operations center: warehouses in Guangzhou and Hong Kong, verification, multi-supplier consolidation and shipping to Latin America or Miami.",
 "blurb_en":"Warehouses in Guangzhou and Hong Kong to receive, inspect, consolidate and prepare cargo from multiple suppliers for Latin America.",
 "intro_en":"We operate strategic centers in China to receive, consolidate and prepare cargo from multiple suppliers, simplifying your purchases and optimizing shipping to Latin America or our international hubs. Our operations center has warehouses in Guangzhou and Hong Kong.",
 "benefits_en":[
   "Warehouse in Guangzhou for cargo receiving",
   "Warehouse in Hong Kong for purchases that are not shipped within Mainland China (Shein, AliExpress, etc.)",
   "Direct coordination with suppliers (we speak English, Chinese and Spanish)",
   "Content verification (per unit or package)",
   "Photo record and basic quality control",
   "Multi-supplier consolidation",
   "Export documentation preparation",
   "Direct shipments to Latin America or to our Miami center (bonded warehouse for in-transit cargo)",
   "Shared platform with the same functionality as in the USA",
 ],
 "sections_en":[
  ("Our operations center in China",[
    "<ul><li><strong>Guangzhou:</strong> warehouse for cargo receiving.</li><li><strong>Hong Kong:</strong> warehouse for purchases that are not shipped within Mainland China (Shein, AliExpress, etc.).</li></ul>",
  ]),
  ("Verification and consolidation",[
    "We coordinate directly with your suppliers (we speak English, Chinese and Spanish), verify contents per unit or package, keep a photo record and run basic quality control, and consolidate purchases from multiple suppliers into a single shipment.",
  ]),
  ("Shipping and platform",[
    "We ship directly to Latin America or to our Miami center (bonded warehouse for in-transit cargo). You use the same platform and functionality as in the USA.",
  ]),
 ],
 "cta_en":"Tell us which suppliers you buy from and we'll coordinate receiving at our China warehouse.",
},
"tercerizacion-de-servicios-logisticos": {
 "nav_en":"Logistics Outsourcing",
 "h1_en":"Logistics services outsourcing",
 "seo_title_en":"Logistics Outsourcing for Businesses | World Wide Commerce",
 "meta_en":"Outsource your international logistics to an operator with its own warehouses in Miami and China. Lower fixed costs, more capacity.",
 "blurb_en":"With our own warehouses in the USA and a ready-made logistics setup, we handle warehouse and shipping management so your company can focus on its business.",
 "intro_en":"We offer logistics outsourcing solutions for companies. With our own warehouses in the USA and a ready-made logistics setup, we handle all warehouse and shipping management, letting your company focus on its core business while we run the logistics.",
 "benefits_en":[
   "Lower operating costs",
   "Access to advanced logistics infrastructure",
   "Comprehensive warehouse and depot management",
   "Optimized delivery times",
   "Greater flexibility and scalability in logistics operations",
   "State-of-the-art technology for inventory tracking and control",
   "Personalized service adapted to the customer's needs",
   "Compliance with international transport regulations",
   "Tailored logistics solutions for different industries",
   "Ongoing logistics management support and advice",
 ],
 "cta_en":"Tell us which part of your logistics is eating most of your time and we'll look at it together.",
},
"transporte-internacional-de-mascotas": {
 "nav_en":"Pet Transport",
 "h1_en":"International pet transport",
 "seo_title_en":"International Pet Transport | World Wide Commerce",
 "meta_en":"We coordinate international pet transport to and from the United States with specialized providers and authorized operators in Miami.",
 "blurb_en":"We coordinate international transport of pets and small animals to and from the United States with specialized providers and authorized operators.",
 "intro_en":"We coordinate international transport of pets and small animals to and from the United States through specialized providers and authorized operators. For families who need to move their animals safely, in an organized way and in line with the health and logistics requirements of each country, we work with specialized providers and authorized centers located in the cargo area of Miami International Airport, supporting you throughout the process.",
 "benefits_en":[
   "Trip assessment: we analyze origin, destination and travel requirements",
   "Documentation coordination: help with certificates and required paperwork",
   "Specialized logistics management with authorized operators",
   "Personalized tracking with updated information throughout the process",
   "Complementary ground transport between airports and final destinations",
   "Import and export: guidance on requirements and inspections",
 ],
 "sections_en":[
  ("How does it work?",[
    "<ul><li>Request your quote</li><li>Send us your pet's information</li><li>We review the health and logistics requirements</li><li>We coordinate the trip with authorized operators</li><li>We follow up throughout the process</li><li>We coordinate delivery to the owner or authorized representative</li></ul>",
  ]),
  ("Import into the United States",[
    "<ul><li>Reception at Miami International Airport</li><li>Documentation coordination</li><li>Help with regulatory requirements</li><li>Inspection coordination</li><li>Ground transport to the final destination</li><li>Personalized tracking</li></ul>",
  ]),
  ("Export from the United States",[
    "<ul><li>Pre-travel advice</li><li>Review of destination-country requirements</li><li>Health documentation coordination</li><li>Logistics management with airlines</li><li>Delivery at the cargo terminal</li><li>Shipment tracking</li></ul>",
  ]),
 ],
 "cta_en":"Tell us the route and your pet's details and we'll build the full travel plan.",
},
}

_by_slug = {s["slug"]: s for s in SERVICES}
for _slug, _fields in SERVICES_EN.items():
    _by_slug[_slug].update(_fields)
for _s in SERVICES:
    assert "nav_en" in _s, _s["slug"]
