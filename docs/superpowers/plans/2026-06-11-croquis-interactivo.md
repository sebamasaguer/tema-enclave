# Croquis Interactivo Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Agregar un croquis interactivo (JPG) a la ficha de proyecto de WordPress, con puntos clickeables (lotes y sectores) administrables desde el panel, que al hacer clic abren un modal con imagen y nombre.

**Architecture:** Se agrega un metabox al CPT `eu_project` con un campo de imagen (JPG del croquis) y un repeater de hotspots (nombre, imagen, X%, Y%, tipo). En `single-eu_project.php` se renderiza la imagen con botones absolutamente posicionados usando porcentajes, y un modal por hotspot. El JS del modal usa event delegation sin librerías extra.

**Tech Stack:** PHP 7.4+, WordPress 6.0+, jQuery (admin), vanilla JS (frontend), CSS custom properties existentes del tema.

---

## Mapa de archivos

| Archivo | Acción | Responsabilidad |
|---|---|---|
| `inc/metaboxes.php` | Modificar | Registrar, renderizar y guardar el metabox del croquis |
| `assets/js/admin.js` | Modificar | Repeater JS (agregar/quitar filas de hotspots) |
| `single-eu_project.php` | Modificar | Renderizar la sección del croquis y los modales |
| `assets/css/main.css` | Modificar | Estilos del mapa, puntos y modal |
| `assets/js/main.js` | Modificar | Lógica JS del modal (abrir, cerrar, teclado) |

---

## Task 1: Metabox PHP — registro, render y save

**Files:**
- Modify: `inc/metaboxes.php`

- [ ] **Step 1: Registrar el metabox**

En `eu_register_metaboxes()` (línea 13), agregar la línea del croquis dentro del bloque existente:

```php
function eu_register_metaboxes() {
    add_meta_box('eu_project_data', __('Datos del proyecto', 'enclave-urbano'), 'eu_render_project_metabox', 'eu_project', 'normal', 'high');
    add_meta_box('eu_croquis_data', __('Croquis del barrio', 'enclave-urbano'), 'eu_render_croquis_metabox', 'eu_project', 'normal', 'default');
    add_meta_box('eu_team_data', __('Datos del miembro', 'enclave-urbano'), 'eu_render_team_metabox', 'eu_team', 'normal', 'high');
    add_meta_box('eu_value_data', __('Icono del valor', 'enclave-urbano'), 'eu_render_value_metabox', 'eu_value', 'side', 'default');
    add_meta_box('eu_alliance_data', __('Datos de la alianza', 'enclave-urbano'), 'eu_render_alliance_metabox', 'eu_alliance', 'normal', 'default');
    add_meta_box('eu_inquiry_data', __('Datos de la consulta', 'enclave-urbano'), 'eu_render_inquiry_metabox', 'eu_inquiry', 'normal', 'high');
}
```

- [ ] **Step 2: Agregar la función de render**

Agregar esta función completa después de `eu_render_project_metabox()` (después de la línea 50):

```php
function eu_render_croquis_metabox($post) {
    wp_nonce_field('eu_save_croquis_data', 'eu_croquis_nonce');
    $croquis_image = get_post_meta($post->ID, '_eu_croquis_image', true);
    $hotspots_raw  = get_post_meta($post->ID, '_eu_croquis_hotspots', true);
    $hotspots      = $hotspots_raw ? json_decode($hotspots_raw, true) : array();
    if (!is_array($hotspots)) {
        $hotspots = array();
    }
    ?>
    <div class="eu-meta-field">
        <label for="_eu_croquis_image"><?php esc_html_e('Imagen del croquis (JPG/PNG)', 'enclave-urbano'); ?></label>
        <div class="eu-meta-input-wrap">
            <input id="_eu_croquis_image" type="url" name="_eu_croquis_image" class="widefat eu-media-url" value="<?php echo esc_url($croquis_image); ?>">
            <button type="button" class="button eu-media-button" data-target="#_eu_croquis_image"><?php esc_html_e('Seleccionar', 'enclave-urbano'); ?></button>
            <button type="button" class="button eu-media-clear" data-target="#_eu_croquis_image"><?php esc_html_e('Quitar', 'enclave-urbano'); ?></button>
        </div>
        <?php if ($croquis_image) : ?>
            <img class="eu-media-preview" src="<?php echo esc_url($croquis_image); ?>" alt="">
        <?php endif; ?>
    </div>

    <hr style="margin:16px 0">

    <h4 style="margin:0 0 4px"><?php esc_html_e('Referencias (hotspots)', 'enclave-urbano'); ?></h4>
    <p class="description"><?php esc_html_e('X e Y son porcentajes de posición sobre el croquis (0–100). Los hotspots sin nombre no se muestran.', 'enclave-urbano'); ?></p>

    <table class="widefat" id="eu-croquis-hotspots" style="margin-top:8px">
        <thead>
            <tr>
                <th><?php esc_html_e('Nombre', 'enclave-urbano'); ?></th>
                <th><?php esc_html_e('Imagen', 'enclave-urbano'); ?></th>
                <th style="width:60px"><?php esc_html_e('X %', 'enclave-urbano'); ?></th>
                <th style="width:60px"><?php esc_html_e('Y %', 'enclave-urbano'); ?></th>
                <th style="width:90px"><?php esc_html_e('Tipo', 'enclave-urbano'); ?></th>
                <th style="width:40px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hotspots as $i => $hotspot) : ?>
            <tr data-row="<?php echo esc_attr($i); ?>">
                <td><input type="text" name="eu_croquis_hotspot[<?php echo esc_attr($i); ?>][name]" class="widefat" value="<?php echo esc_attr($hotspot['name']); ?>"></td>
                <td>
                    <input type="url" id="eu_croquis_img_<?php echo esc_attr($i); ?>" name="eu_croquis_hotspot[<?php echo esc_attr($i); ?>][image_url]" class="widefat eu-media-url" value="<?php echo esc_url(!empty($hotspot['image_url']) ? $hotspot['image_url'] : ''); ?>">
                    <button type="button" class="button eu-media-button" data-target="#eu_croquis_img_<?php echo esc_attr($i); ?>"><?php esc_html_e('Sel.', 'enclave-urbano'); ?></button>
                    <button type="button" class="button eu-media-clear" data-target="#eu_croquis_img_<?php echo esc_attr($i); ?>">×</button>
                    <?php if (!empty($hotspot['image_url'])) : ?>
                        <img class="eu-media-preview" src="<?php echo esc_url($hotspot['image_url']); ?>" alt="">
                    <?php endif; ?>
                </td>
                <td><input type="number" name="eu_croquis_hotspot[<?php echo esc_attr($i); ?>][x]" class="small-text" min="0" max="100" value="<?php echo esc_attr($hotspot['x']); ?>"></td>
                <td><input type="number" name="eu_croquis_hotspot[<?php echo esc_attr($i); ?>][y]" class="small-text" min="0" max="100" value="<?php echo esc_attr($hotspot['y']); ?>"></td>
                <td>
                    <select name="eu_croquis_hotspot[<?php echo esc_attr($i); ?>][type]">
                        <option value="lote" <?php selected(!empty($hotspot['type']) ? $hotspot['type'] : 'lote', 'lote'); ?>><?php esc_html_e('Lote', 'enclave-urbano'); ?></option>
                        <option value="sector" <?php selected(!empty($hotspot['type']) ? $hotspot['type'] : 'lote', 'sector'); ?>><?php esc_html_e('Sector', 'enclave-urbano'); ?></option>
                    </select>
                </td>
                <td><button type="button" class="button eu-croquis-remove-row">&#x2715;</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><button type="button" class="button" id="eu-croquis-add-row"><?php esc_html_e('+ Agregar referencia', 'enclave-urbano'); ?></button></p>
    <input type="hidden" id="eu-croquis-row-count" value="<?php echo esc_attr(count($hotspots)); ?>">
    <?php
}
```

- [ ] **Step 3: Agregar el save del croquis en `eu_save_metaboxes()`**

Dentro del bloque `if ('eu_project' === $post_type)` (después del `foreach` de campos, antes del cierre del `if`), agregar:

```php
    if (isset($_POST['eu_croquis_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_croquis_nonce'])), 'eu_save_croquis_data')) {
        $croquis_image = isset($_POST['_eu_croquis_image']) ? esc_url_raw(wp_unslash($_POST['_eu_croquis_image'])) : '';
        if ($croquis_image) {
            update_post_meta($post_id, '_eu_croquis_image', $croquis_image);
        } else {
            delete_post_meta($post_id, '_eu_croquis_image');
        }

        $hotspots = array();
        if (isset($_POST['eu_croquis_hotspot']) && is_array($_POST['eu_croquis_hotspot'])) {
            foreach ($_POST['eu_croquis_hotspot'] as $row) {
                $name = isset($row['name']) ? sanitize_text_field(wp_unslash($row['name'])) : '';
                if ('' === $name) {
                    continue;
                }
                $hotspots[] = array(
                    'name'      => $name,
                    'image_url' => isset($row['image_url']) ? esc_url_raw(wp_unslash($row['image_url'])) : '',
                    'x'         => isset($row['x']) ? max(0, min(100, (int) $row['x'])) : 50,
                    'y'         => isset($row['y']) ? max(0, min(100, (int) $row['y'])) : 50,
                    'type'      => (isset($row['type']) && in_array($row['type'], array('lote', 'sector'), true)) ? $row['type'] : 'lote',
                );
            }
        }
        update_post_meta($post_id, '_eu_croquis_hotspots', wp_json_encode($hotspots));
    }
```

El bloque `eu_project` completo resultante en `eu_save_metaboxes()` queda así:

```php
if ('eu_project' === $post_type) {
    if (!isset($_POST['eu_project_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_project_nonce'])), 'eu_save_project_data')) {
        return;
    }
    foreach (eu_project_fields() as $key => $field) {
        eu_save_meta_value($post_id, '_eu_project_' . $key, $field['type']);
    }

    if (isset($_POST['eu_croquis_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eu_croquis_nonce'])), 'eu_save_croquis_data')) {
        $croquis_image = isset($_POST['_eu_croquis_image']) ? esc_url_raw(wp_unslash($_POST['_eu_croquis_image'])) : '';
        if ($croquis_image) {
            update_post_meta($post_id, '_eu_croquis_image', $croquis_image);
        } else {
            delete_post_meta($post_id, '_eu_croquis_image');
        }

        $hotspots = array();
        if (isset($_POST['eu_croquis_hotspot']) && is_array($_POST['eu_croquis_hotspot'])) {
            foreach ($_POST['eu_croquis_hotspot'] as $row) {
                $name = isset($row['name']) ? sanitize_text_field(wp_unslash($row['name'])) : '';
                if ('' === $name) {
                    continue;
                }
                $hotspots[] = array(
                    'name'      => $name,
                    'image_url' => isset($row['image_url']) ? esc_url_raw(wp_unslash($row['image_url'])) : '',
                    'x'         => isset($row['x']) ? max(0, min(100, (int) $row['x'])) : 50,
                    'y'         => isset($row['y']) ? max(0, min(100, (int) $row['y'])) : 50,
                    'type'      => (isset($row['type']) && in_array($row['type'], array('lote', 'sector'), true)) ? $row['type'] : 'lote',
                );
            }
        }
        update_post_meta($post_id, '_eu_croquis_hotspots', wp_json_encode($hotspots));
    }
}
```

- [ ] **Step 4: Verificar en el navegador**

Abrir WordPress admin → Proyectos → editar un proyecto. Verificar que aparece el metabox "Croquis del barrio" con el campo de imagen y la tabla (vacía). No hay errores PHP en el admin.

- [ ] **Step 5: Commit**

```bash
git add inc/metaboxes.php
git commit -m "feat: add croquis metabox to eu_project CPT"
```

---

## Task 2: Admin JS — repeater de hotspots

**Files:**
- Modify: `assets/js/admin.js`

- [ ] **Step 1: Agregar el código del repeater al final del IIFE existente**

Reemplazar el contenido completo de `assets/js/admin.js` con el siguiente (agrega el bloque del repeater antes del cierre del IIFE):

```js
(function ($) {
  'use strict';

  $(document).on('click', '.eu-media-button', function (event) {
    event.preventDefault();

    const button = $(this);
    const target = $(button.data('target'));

    const frame = wp.media({
      title: 'Seleccionar archivo',
      button: { text: 'Usar este archivo' },
      multiple: false,
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      target.val(attachment.url).trigger('change');

      const preview = target.closest('.eu-field-row, .eu-meta-field, td').find('.eu-media-preview');
      if (preview.length) {
        preview.attr('src', attachment.url).show();
      } else {
        target.closest('.eu-field-row, .eu-meta-field, td').append('<img class="eu-media-preview" src="' + attachment.url + '" alt="" />');
      }
    });

    frame.open();
  });

  $(document).on('click', '.eu-media-clear', function (event) {
    event.preventDefault();
    const target = $($(this).data('target'));
    target.val('').trigger('change');
    target.closest('.eu-field-row, .eu-meta-field, td').find('.eu-media-preview').remove();
  });

  // Croquis hotspot repeater
  var euCroquisRowIdx = 0;

  $(document).ready(function () {
    euCroquisRowIdx = parseInt($('#eu-croquis-row-count').val(), 10) || 0;
  });

  $(document).on('click', '#eu-croquis-add-row', function () {
    var i = euCroquisRowIdx++;
    var imgInputId = 'eu_croquis_img_' + i;

    var row = $('<tr>').attr('data-row', i);

    row.append(
      $('<td>').append(
        $('<input>').attr({ type: 'text', name: 'eu_croquis_hotspot[' + i + '][name]', class: 'widefat', placeholder: 'Nombre' })
      )
    );

    var imgTd = $('<td>');
    imgTd.append($('<input>').attr({ type: 'url', id: imgInputId, name: 'eu_croquis_hotspot[' + i + '][image_url]', class: 'widefat eu-media-url', value: '', placeholder: 'URL imagen' }));
    imgTd.append(' ');
    imgTd.append($('<button>').attr({ type: 'button', class: 'button eu-media-button', 'data-target': '#' + imgInputId }).text('Sel.'));
    imgTd.append(' ');
    imgTd.append($('<button>').attr({ type: 'button', class: 'button eu-media-clear', 'data-target': '#' + imgInputId }).text('×'));
    row.append(imgTd);

    row.append(
      $('<td>').append(
        $('<input>').attr({ type: 'number', name: 'eu_croquis_hotspot[' + i + '][x]', class: 'small-text', min: 0, max: 100, value: 50 })
      )
    );

    row.append(
      $('<td>').append(
        $('<input>').attr({ type: 'number', name: 'eu_croquis_hotspot[' + i + '][y]', class: 'small-text', min: 0, max: 100, value: 50 })
      )
    );

    var select = $('<select>').attr('name', 'eu_croquis_hotspot[' + i + '][type]');
    select.append($('<option>').val('lote').text('Lote'));
    select.append($('<option>').val('sector').text('Sector'));
    row.append($('<td>').append(select));

    row.append(
      $('<td>').append(
        $('<button>').attr({ type: 'button', class: 'button eu-croquis-remove-row' }).text('✕')
      )
    );

    $('#eu-croquis-hotspots tbody').append(row);
  });

  $(document).on('click', '.eu-croquis-remove-row', function () {
    $(this).closest('tr').remove();
  });

}(jQuery));
```

- [ ] **Step 2: Verificar en el admin**

Abrir el admin → editar un proyecto → sección "Croquis del barrio":
- Hacer clic en "+ Agregar referencia" → debe aparecer una fila nueva con todos los campos
- Hacer clic en "✕" de la fila → debe desaparecer
- Agregar 2 filas, guardar el proyecto, recargar → las filas deben persistir con los datos guardados

- [ ] **Step 3: Verificar el media button en hotspots**

En una fila de hotspot, hacer clic en "Sel." → debe abrirse el media picker de WordPress. Seleccionar una imagen → la URL debe rellenarse en el campo y aparecer el preview.

- [ ] **Step 4: Commit**

```bash
git add assets/js/admin.js
git commit -m "feat: add croquis hotspot repeater to admin JS"
```

---

## Task 3: Frontend PHP — sección del croquis en la ficha del proyecto

**Files:**
- Modify: `single-eu_project.php`

- [ ] **Step 1: Agregar lectura de metas del croquis**

En el bloque PHP de variables al inicio del `while` (después de `$wa_link`, antes del `<header>`), agregar:

```php
$croquis_image    = get_post_meta(get_the_ID(), '_eu_croquis_image', true);
$croquis_raw      = get_post_meta(get_the_ID(), '_eu_croquis_hotspots', true);
$croquis_hotspots = ($croquis_raw) ? json_decode($croquis_raw, true) : array();
if (!is_array($croquis_hotspots)) {
    $croquis_hotspots = array();
}
$croquis_hotspots = array_values(array_filter($croquis_hotspots, function ($h) {
    return !empty($h['name']);
}));
```

- [ ] **Step 2: Agregar la sección del croquis dentro de `.eu-project-maincol`**

Después del bloque `<?php if ($kml || ($lat && $lng)) : ?>` (después de su `endif`), y antes del cierre de `</article>`, agregar:

```php
<?php if ($croquis_image) : ?>
    <section class="eu-project-block eu-croquis">
        <h2><?php esc_html_e('Croquis del barrio', 'enclave-urbano'); ?></h2>
        <div class="eu-croquis__map">
            <img
                src="<?php echo esc_url($croquis_image); ?>"
                alt="<?php esc_attr_e('Croquis del barrio', 'enclave-urbano'); ?>"
                class="eu-croquis__img"
            >
            <?php
            $lote_counter = 0;
            foreach ($croquis_hotspots as $i => $hotspot) :
                $is_lote = 'lote' === ($hotspot['type'] ?? 'lote');
                if ($is_lote) {
                    $lote_counter++;
                }
                $dot_label = $is_lote ? $lote_counter : '&#9733;';
            ?>
                <button
                    class="eu-croquis__dot eu-croquis__dot--<?php echo esc_attr($hotspot['type'] ?? 'lote'); ?>"
                    style="left:<?php echo esc_attr($hotspot['x'] ?? 50); ?>%;top:<?php echo esc_attr($hotspot['y'] ?? 50); ?>%"
                    data-modal="eu-modal-<?php echo esc_attr($i); ?>"
                    aria-label="<?php echo esc_attr($hotspot['name']); ?>"
                    type="button"
                ><?php echo $is_lote ? esc_html($lote_counter) : '&#9733;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($croquis_hotspots)) : ?>
        <div class="eu-croquis__legend">
            <span class="eu-croquis__legend-item eu-croquis__legend-item--lote"><?php esc_html_e('Lote', 'enclave-urbano'); ?></span>
            <span class="eu-croquis__legend-item eu-croquis__legend-item--sector"><?php esc_html_e('Sector', 'enclave-urbano'); ?></span>
        </div>
        <?php endif; ?>
    </section>
<?php endif; ?>
```

- [ ] **Step 3: Agregar los modales después del cierre de `.eu-project-layout`**

Después del cierre del `</div>` de `.eu-project-layout` y antes de la sección `#consulta-proyecto`, agregar:

```php
<?php foreach ($croquis_hotspots as $i => $hotspot) : ?>
<div
    id="eu-modal-<?php echo esc_attr($i); ?>"
    class="eu-modal"
    role="dialog"
    aria-modal="true"
    aria-label="<?php echo esc_attr($hotspot['name']); ?>"
    hidden
>
    <div class="eu-modal__overlay"></div>
    <div class="eu-modal__box">
        <button class="eu-modal__close" aria-label="<?php esc_attr_e('Cerrar', 'enclave-urbano'); ?>" type="button">&#x2715;</button>
        <?php if (!empty($hotspot['image_url'])) : ?>
            <img
                src="<?php echo esc_url($hotspot['image_url']); ?>"
                alt="<?php echo esc_attr($hotspot['name']); ?>"
                class="eu-modal__img"
            >
        <?php else : ?>
            <div class="eu-modal__img-placeholder"></div>
        <?php endif; ?>
        <div class="eu-modal__body">
            <h3 class="eu-modal__title"><?php echo esc_html($hotspot['name']); ?></h3>
            <span class="eu-modal__badge eu-modal__badge--<?php echo esc_attr($hotspot['type'] ?? 'lote'); ?>">
                <?php echo ('lote' === ($hotspot['type'] ?? 'lote')) ? esc_html__('Lote', 'enclave-urbano') : esc_html__('Sector', 'enclave-urbano'); ?>
            </span>
        </div>
    </div>
</div>
<?php endforeach; ?>
```

- [ ] **Step 4: Verificar render PHP**

Abrir un proyecto en el frontend. Si tiene `_eu_croquis_image` seteado, debe aparecer la sección "Croquis del barrio" con la imagen y los puntos. Sin datos, la sección no aparece. No hay errores PHP.

- [ ] **Step 5: Commit**

```bash
git add single-eu_project.php
git commit -m "feat: render croquis section and modals in single project template"
```

---

## Task 4: CSS — estilos del croquis y modal

**Files:**
- Modify: `assets/css/main.css`

- [ ] **Step 1: Agregar al final de `assets/css/main.css`**

```css
/* =========================================================
   CROQUIS INTERACTIVO
========================================================= */

.eu-croquis__map {
  position: relative;
  width: 100%;
}

.eu-croquis__img {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 6px;
}

.eu-croquis__dot {
  position: absolute;
  transform: translate(-50%, -50%);
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: 3px solid #fff;
  cursor: pointer;
  font-size: 0.75rem;
  font-weight: 700;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
  transition: transform 0.15s ease;
  padding: 0;
  line-height: 1;
  background: none;
}

.eu-croquis__dot:hover,
.eu-croquis__dot:focus {
  transform: translate(-50%, -50%) scale(1.2);
  outline: none;
}

.eu-croquis__dot--lote {
  background: var(--eu-green);
}

.eu-croquis__dot--sector {
  background: #a07d00;
  border-color: var(--eu-yellow);
}

.eu-croquis__dot::after {
  content: attr(aria-label);
  position: absolute;
  bottom: calc(100% + 8px);
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.85);
  color: #fff;
  font-size: 0.7rem;
  white-space: nowrap;
  padding: 3px 8px;
  border-radius: 4px;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.15s;
}

.eu-croquis__dot:hover::after,
.eu-croquis__dot:focus::after {
  opacity: 1;
}

.eu-croquis__legend {
  display: flex;
  gap: 1rem;
  margin-top: 0.75rem;
}

.eu-croquis__legend-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.82rem;
  color: var(--eu-gray);
}

.eu-croquis__legend-item::before {
  content: '';
  width: 14px;
  height: 14px;
  border-radius: 50%;
  flex-shrink: 0;
}

.eu-croquis__legend-item--lote::before {
  background: var(--eu-green);
}

.eu-croquis__legend-item--sector::before {
  background: #a07d00;
  border: 2px solid var(--eu-yellow);
  box-sizing: border-box;
}

@media (max-width: 767px) {
  .eu-croquis__dot {
    width: 26px;
    height: 26px;
    font-size: 0.65rem;
    border-width: 2px;
  }
}

/* =========================================================
   MODAL (croquis hotspots)
========================================================= */

.eu-modal {
  position: fixed;
  inset: 0;
  z-index: 9000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.eu-modal[hidden] {
  display: none;
}

.eu-modal__overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.75);
}

.eu-modal__box {
  position: relative;
  background: #fff;
  border-radius: 10px;
  max-width: 480px;
  width: 90%;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.eu-modal__close {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(0, 0, 0, 0.55);
  border: none;
  color: #fff;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
  transition: background 0.15s;
}

.eu-modal__close:hover {
  background: rgba(0, 0, 0, 0.8);
}

.eu-modal__img {
  width: 100%;
  height: auto;
  display: block;
  max-height: 320px;
  object-fit: cover;
}

.eu-modal__img-placeholder {
  width: 100%;
  height: 220px;
  background: #e2e8f0;
}

.eu-modal__body {
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.eu-modal__title {
  margin: 0;
  font-size: 1.1rem;
  color: var(--eu-dark);
  font-weight: 600;
}

.eu-modal__badge {
  flex-shrink: 0;
  font-size: 0.72rem;
  padding: 2px 10px;
  border-radius: 12px;
  font-weight: 600;
  color: #fff;
}

.eu-modal__badge--lote {
  background: var(--eu-green);
}

.eu-modal__badge--sector {
  background: #a07d00;
}
```

- [ ] **Step 2: Verificar visualmente**

Recargar la ficha de un proyecto con croquis cargado. Verificar:
- La imagen ocupa el ancho completo
- Los puntos verdes y amarillos aparecen correctamente posicionados
- El tooltip aparece en hover
- En mobile (< 768px) los puntos son más pequeños

- [ ] **Step 3: Commit**

```bash
git add assets/css/main.css
git commit -m "feat: add croquis map and modal CSS styles"
```

---

## Task 5: Frontend JS — lógica del modal

**Files:**
- Modify: `assets/js/main.js`

- [ ] **Step 1: Agregar el código del modal al final de `assets/js/main.js`**

El archivo actual cierra con `}());`. Agregar después de ese cierre:

```js
(function () {
  'use strict';

  var activeModal = null;
  var triggerEl = null;

  function openModal(modal, trigger) {
    if (activeModal) {
      closeModal();
    }
    modal.removeAttribute('hidden');
    activeModal = modal;
    triggerEl = trigger || null;
    var closeBtn = modal.querySelector('.eu-modal__close');
    if (closeBtn) {
      closeBtn.focus();
    }
  }

  function closeModal() {
    if (!activeModal) {
      return;
    }
    activeModal.setAttribute('hidden', '');
    if (triggerEl) {
      triggerEl.focus();
    }
    activeModal = null;
    triggerEl = null;
  }

  document.addEventListener('click', function (event) {
    var dot = event.target.closest('.eu-croquis__dot');
    if (dot) {
      var modalId = dot.getAttribute('data-modal');
      var modal = modalId ? document.getElementById(modalId) : null;
      if (modal) {
        openModal(modal, dot);
      }
      return;
    }

    if (event.target.closest('.eu-modal__overlay') || event.target.closest('.eu-modal__close')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && activeModal) {
      closeModal();
    }
  });
}());
```

- [ ] **Step 2: Verificar comportamiento del modal**

En el frontend, hacer clic en un punto del croquis:
- El modal aparece con la imagen y el nombre
- El foco pasa al botón ✕
- Hacer clic en el overlay → modal se cierra
- Hacer clic en ✕ → modal se cierra
- Presionar Escape → modal se cierra
- Abrir modal 1, luego clic en otro punto → modal 1 se cierra y abre modal 2
- El foco vuelve al punto que disparó el modal al cerrar

- [ ] **Step 3: Commit**

```bash
git add assets/js/main.js
git commit -m "feat: add modal open/close JS for croquis hotspots"
```

---

## Task 6: Test manual end-to-end

- [ ] **Step 1: Cargar datos de prueba en el admin**

1. Ir a WordPress Admin → Proyectos → editar "La Enriqueta" (o cualquier proyecto)
2. En "Croquis del barrio": subir una imagen JPG del croquis
3. Agregar 3 hotspots:
   - Nombre: "Lote 12", tipo: Lote, X: 30, Y: 45, imagen: cualquier foto
   - Nombre: "Lote 7", tipo: Lote, X: 55, Y: 60, sin imagen
   - Nombre: "Club House", tipo: Sector, X: 70, Y: 25, imagen: cualquier foto
4. Guardar el proyecto

- [ ] **Step 2: Verificar en el frontend**

Abrir la URL pública del proyecto. Comprobar:
- Aparece la sección "Croquis del barrio" con la imagen
- Se ven 2 círculos verdes numerados (1 y 2) y 1 círculo amarillo con ★
- Las posiciones coinciden aproximadamente con los porcentajes ingresados

- [ ] **Step 3: Verificar modal con imagen**

Hacer clic en "Lote 12":
- Se abre el modal con la foto, el nombre "Lote 12" y el badge verde "Lote"

- [ ] **Step 4: Verificar modal sin imagen**

Hacer clic en "Lote 7":
- Se abre el modal con el placeholder gris, el nombre "Lote 7" y el badge verde "Lote"

- [ ] **Step 5: Verificar sector**

Hacer clic en "Club House":
- Se abre el modal con la foto, el nombre "Club House" y el badge amarillo-oscuro "Sector"

- [ ] **Step 6: Verificar proyecto sin croquis**

Abrir otro proyecto que no tenga croquis cargado:
- La sección "Croquis del barrio" no debe aparecer

- [ ] **Step 7: Verificar responsive en mobile**

Reducir el navegador a < 768px:
- Los puntos se ven más pequeños (26px)
- El modal ocupa el 90% del ancho
- El croquis sigue siendo usable

- [ ] **Step 8: Commit final**

```bash
git add -A
git commit -m "feat: complete croquis interactivo feature"
```
