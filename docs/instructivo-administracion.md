# Instructivo de administración — Enclave Urbano

Este documento explica cómo editar cada área de texto y contenido del sitio desde el panel de WordPress.

---

## 1. Ajustes generales del tema

**Ruta:** Apariencia → Ajustes Enclave

Esta es la pantalla central de configuración. Agrupa todos los textos e imágenes globales del sitio.

### Identidad visual
| Campo | Qué controla |
|---|---|
| Logo grande | Logo que aparece en el header del sitio |
| Logo chico / isotipo | Logo reducido para fondos oscuros o móvil |
| Imagen de portada de la home | Foto de fondo del hero principal |
| QR de contacto | Código QR que se muestra en la sección de contacto |
| Verde principal / Amarillo | Colores de la marca |
| Frase institucional | Texto corto debajo del formulario de contacto |

### Datos de contacto
| Campo | Qué controla |
|---|---|
| Email receptor de formularios | Dirección a la que llegan los mensajes del formulario |
| Teléfono administración | Teléfono del área admin |
| Teléfono comercialización | Teléfono del área ventas |
| WhatsApp global | Número usado en el botón flotante de WhatsApp |
| Dirección / texto de ubicación | Texto libre con la dirección (admite formato enriquecido) |

### Redes sociales
Campos de URL para Instagram, Facebook, LinkedIn, YouTube y TikTok. Los íconos aparecen automáticamente en el footer si se completan.

### Home: Enclave Urbano
| Campo | Dónde se ve |
|---|---|
| Texto misión | Bloque introductorio de la portada junto al logo |
| Título bloque Genera | Encabezado de la sección "Genera" |
| Texto bloque Genera | Cuerpo de la sección "Genera" |
| Título bloque Alcance | Encabezado de la sección "Alcance" |
| Texto bloque Alcance | Cuerpo de la sección "Alcance" |

> Todos los campos de texto largo tienen **editor enriquecido**: negrita, cursiva, subrayado, listas y enlaces.

### Home: Equipo
| Campo | Dónde se ve |
|---|---|
| Título lateral | Encabezado de la columna izquierda en la sección equipo de la home |
| Texto lateral | Párrafo descriptivo de la columna izquierda |
| Caja inferior de equipo | Recuadro que aparece debajo del texto lateral |

### Footer
| Campo | Qué controla |
|---|---|
| Texto principal del footer | Línea de texto en el pie de página |
| Imagen banda Ciudad Abierta | Foto panorámica que se muestra en el footer |
| Altura de la banda en escritorio / móvil | Tamaño en píxeles de esa imagen |
| Posición horizontal / vertical | Encuadre de la imagen dentro del contenedor |
| Ajuste de la imagen | Cover (recorta) o Contain (muestra completa) |

---

## 2. Proyectos

**Ruta:** Proyectos → Todos los proyectos → (editar uno)

Cada proyecto tiene tres áreas editables:

### Datos básicos
- **Título:** nombre del proyecto (campo superior de la pantalla de edición).
- **Imagen destacada:** foto principal que aparece como fondo del header del proyecto. Se carga desde el panel lateral derecho.
- **Editor de contenido:** descripción larga del proyecto. Se muestra en la sección "Descripción".

### Logo del proyecto
Si el proyecto tiene su propio logo (ej: La Enriqueta), cargarlo en el campo **Logo del proyecto**. Cuando está cargado, reemplaza el nombre de texto en el header del proyecto.

### Ficha técnica (metadatos)
Estos campos aparecen en el cuadro lateral "Ficha técnica" de cada proyecto:

| Campo | Descripción |
|---|---|
| Ubicación | Ciudad o zona del proyecto |
| Superficie total | M² totales |
| Cantidad de unidades | Número de lotes o unidades |
| Inversión mínima | Monto desde |
| Fecha de entrega | Fecha estimada |
| Etapa actual | Ej: "En construcción" |
| Precio desde | Precio de entrada |
| Video URL 1 / 2 | Links de YouTube o Vimeo (se incrustan automáticamente) |
| Google Maps Embed URL | URL del iframe de Google Maps |
| WhatsApp de contacto | Número del proyecto (sin el + inicial) |
| Archivo KML URL | URL pública del archivo .kml para ver urbanización |
| Latitud / Longitud / Zoom | Coordenadas para centrar el mapa KML |

### Croquis del barrio
- **Título del croquis:** encabezado de la sección (ej: "Croquis del barrio").
- **Imagen del croquis:** plano o mapa del loteo (JPG/PNG).
- **Referencias (hotspots):** puntos interactivos sobre el croquis. Cada uno tiene nombre, imagen, posición X/Y (en porcentaje) y tipo (Lote, Área o Sector). Al hacer clic en un punto se abre un popup con la foto.

---

## 3. Equipo

**Ruta:** Equipo → Todos los miembros → (editar uno)

| Campo | Dónde se ve |
|---|---|
| Título | Nombre del arquitecto/a |
| Imagen destacada | Foto de perfil en las tarjetas y en la sección de perfiles |
| Editor de contenido | Biografía o descripción, visible en la sección de perfiles expandidos de la página Equipo |
| Cargo / especialidad | Texto debajo del nombre en la tarjeta |
| QR personal | Código QR individual (aparece en la esquina de la tarjeta) |
| Email | Visible en el perfil expandido |
| Teléfono | Visible en el perfil expandido |
| Link externo / matrícula / perfil | Enlace al pie del perfil expandido |

> El orden de los miembros se controla desde **Atributos de página → Orden** (número menor = aparece primero).

---

## 4. Valores

**Ruta:** Valores → Todos los valores → (editar uno)

| Campo | Dónde se ve |
|---|---|
| Título | Nombre del valor |
| Imagen destacada | Ícono o imagen representativa |
| Icono personalizado | URL alternativa de ícono (opcional) |
| Editor de contenido | Descripción del valor |

> El orden se controla por **Atributos de página → Orden**.

---

## 5. Alianzas

**Ruta:** Alianzas → Todas las alianzas → (editar una)

| Campo | Dónde se ve |
|---|---|
| Título | Nombre de la organización o profesional |
| Imagen destacada | Logo de la alianza |
| Editor de contenido | Descripción |
| Sitio web | URL del sitio de la alianza |
| Teléfono | Teléfono de contacto |
| Email | Email de contacto |

> Usar la taxonomía **Tipo de alianza** para clasificar en categorías (Organismos, Profesionales, Inmobiliarias, etc.).

---

## 6. Páginas estáticas

**Ruta:** Páginas → Todas las páginas → (editar una)

Las páginas del sitio (Filosofía, Comercialización, Comunidad, Contacto, etc.) se editan con el editor estándar de WordPress:

- **Título:** nombre de la página.
- **Editor de contenido:** cuerpo de la página, con soporte completo de bloques Gutenberg o editor clásico.

---

## 7. Consultas recibidas

**Ruta:** Consultas → Todas las consultas

Las consultas llegan automáticamente desde el formulario del sitio. Desde el listado se puede:

- Ver nombre, email, teléfono y estado de cada consulta.
- Abrir una consulta para ver todos sus datos y cambiar el **estado**: Nuevo → Leído → Respondido → Archivado.

---

## Consejos generales

- **Guardar siempre:** después de cualquier cambio, hacer clic en **Guardar ajustes** (en Ajustes Enclave) o **Actualizar** (en proyectos, equipo, etc.).
- **Imágenes:** usar el botón **Seleccionar** para elegir una imagen de la biblioteca de medios, o subir una nueva desde ahí.
- **Editor enriquecido:** en los campos de texto largo se puede usar la barra de herramientas para aplicar negrita, cursiva, subrayado, listas o insertar enlaces.
- **Vista previa:** antes de publicar cambios importantes, usar el botón **Vista previa** para ver cómo quedan en el sitio.
