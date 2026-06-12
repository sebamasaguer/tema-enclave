# Croquis interactivo del barrio

**Fecha:** 2026-06-11  
**Proyecto:** Tema WordPress Enclave Urbano  
**Feature:** Mapa interactivo con hotspots clickeables sobre la ficha de proyecto

---

## Objetivo

Agregar un croquis interactivo (imagen JPG) en la ficha individual de cada proyecto (`single-eu_project.php`). El administrador define referencias clickeables (lotes y sectores) desde el panel de WordPress. Al hacer clic en una referencia, se abre un modal con la imagen y el nombre de esa referencia.

---

## Arquitectura

### Archivos modificados

| Archivo | Cambio |
|---|---|
| `inc/metaboxes.php` | Nuevo metabox "Croquis del barrio" para el CPT `eu_project` |
| `single-eu_project.php` | Nueva sección con el mapa, los hotspots y el HTML del modal |
| `assets/css/main.css` | Estilos del mapa, puntos y modal |
| `assets/js/main.js` | Lógica JS del modal (abrir, cerrar, teclado) |

### Flujo de datos

1. Admin sube el JPG del croquis → se guarda en post meta `eu_croquis_image` (attachment ID)
2. Admin agrega hotspots (nombre, imagen, X%, Y%, tipo) → se guardan en `eu_croquis_hotspots` (JSON via `wp_json_encode`)
3. `single-eu_project.php` lee ambos metas y renderiza la sección
4. El usuario hace clic en un punto → JS abre el modal correspondiente
5. El usuario cierra el modal (clic fuera, botón ✕, o tecla Escape)

---

## Metabox en el admin

**Ubicación:** Panel de edición del CPT `eu_project`, debajo de los campos existentes.  
**Título:** "Croquis del barrio"

### Campos

- **Imagen del croquis** (`eu_croquis_image`): botón de media uploader de WordPress, guarda el attachment ID. Se muestra preview de la imagen seleccionada.
- **Hotspots** (`eu_croquis_hotspots`): repeater dinámico (JS) con filas. Se guarda como JSON con `wp_json_encode()` y se lee con `json_decode()`. Cada fila tiene:
  - **Nombre** (text, requerido — si está vacío, el hotspot no se renderiza)
  - **Imagen** (media uploader, attachment ID, opcional)
  - **X%** (number 0–100, posición horizontal sobre el mapa)
  - **Y%** (number 0–100, posición vertical sobre el mapa)
  - **Tipo** (select: `lote` | `sector`)
  - Botón eliminar fila

El repeater permite agregar/eliminar filas con JS. El orden es el orden visual de numeración en el mapa.

---

## Renderizado en la ficha del proyecto

### Condición de visibilidad

La sección completa solo se renderiza si `eu_croquis_image` tiene un valor válido. Si no hay imagen, no aparece nada.

### Estructura HTML

```html
<section class="eu-croquis">
  <div class="eu-croquis__map">
    <img src="{url_jpg}" alt="Croquis del barrio" class="eu-croquis__img">
    <!-- por cada hotspot con nombre -->
    <button class="eu-croquis__dot eu-croquis__dot--{tipo}"
            style="left:{x}%;top:{y}%"
            data-modal="eu-modal-{index}"
            aria-label="{nombre}">
      {número o ★}
    </button>
  </div>
  <div class="eu-croquis__legend">
    <span class="eu-croquis__legend-lote">Lote</span>
    <span class="eu-croquis__legend-sector">Sector</span>
  </div>
</section>

<!-- modales (uno por hotspot) -->
<div id="eu-modal-{index}" class="eu-modal" role="dialog" aria-modal="true" hidden>
  <div class="eu-modal__overlay"></div>
  <div class="eu-modal__box">
    <button class="eu-modal__close" aria-label="Cerrar">✕</button>
    <img src="{url_imagen}" alt="{nombre}" class="eu-modal__img">  <!-- o placeholder si no hay imagen -->
    <div class="eu-modal__body">
      <h3 class="eu-modal__title">{nombre}</h3>
      <span class="eu-modal__badge eu-modal__badge--{tipo}">{Lote | Sector}</span>
    </div>
  </div>
</div>
```

### Visual de los puntos

- **Lotes:** círculo verde `#336633`, borde blanco, numerados (1, 2, 3…)
- **Sectores:** círculo amarillo oscuro `#a07d00` con borde amarillo `#ffff00`, ícono `★`
- Tamaño: 32px escritorio, 26px mobile
- Tooltip con el nombre aparece en hover (`:hover` CSS)

---

## Modal

- Overlay semitransparente oscuro sobre toda la pantalla
- Caja centrada con imagen arriba y nombre + badge abajo
- Si el hotspot no tiene imagen, se muestra un placeholder gris
- Se cierra con:
  - Clic en el overlay
  - Botón ✕
  - Tecla `Escape`
- Solo un modal puede estar abierto a la vez

---

## Casos borde

| Situación | Comportamiento |
|---|---|
| Sin imagen de croquis | La sección no se renderiza |
| Croquis cargado sin hotspots | Se muestra el mapa estático sin puntos |
| Hotspot sin nombre | Se omite del render (no aparece en el mapa) |
| Hotspot sin imagen | El modal muestra un placeholder gris |
| Hotspot con X%/Y% fuera de 0–100 | Se sanitiza al guardar (min 0, max 100) |

---

## Responsive

- Posiciones en `%` → los puntos siguen a la imagen en cualquier tamaño de pantalla
- El contenedor del mapa usa `position: relative` con `width: 100%`
- La imagen usa `width: 100%; height: auto`
- Mobile (< 768px): puntos de 26px, modal al 90% del ancho
- El modal tiene `max-width: 480px` en escritorio

---

## Accesibilidad

- Los hotspots son `<button>` con `aria-label`
- El modal tiene `role="dialog"` y `aria-modal="true"`
- El foco se mueve al modal al abrirse y vuelve al botón que lo abrió al cerrarse
- Tecla `Escape` cierra el modal

---

## No incluido en este alcance

- Edición de la posición X/Y con drag & drop visual (se ingresa manualmente)
- Galería de múltiples imágenes por hotspot
- Estado del lote (disponible / reservado / vendido)
- Filtros por tipo de referencia
