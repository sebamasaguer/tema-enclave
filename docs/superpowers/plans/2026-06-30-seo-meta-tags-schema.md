# SEO Meta Tags y Schema.org — Plan de Implementación

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Agregar meta tags de Open Graph, Twitter Cards y datos estructurados Schema.org al tema WordPress Enclave Urbano, usando los valores ya configurados en las theme options.

**Architecture:** Se crea `inc/seo.php` con funciones helper y hooks `wp_head` / `document_title_parts`. El archivo se incluye desde `functions.php` siguiendo el patrón existente del tema. No se agrega ningún panel de administración; todos los valores se toman de `eu_get_option()` y de las APIs de WordPress.

**Tech Stack:** PHP 7.4+, WordPress hooks (`wp_head`, `document_title_parts`, `document_title_separator`), JSON-LD, schema.org.

## Global Constraints

- No agregar dependencias externas ni plugins.
- Todos los valores dinámicos deben escaparse con las funciones de WordPress (`esc_attr`, `esc_url`, `wp_json_encode`, etc.).
- Seguir el patrón de archivos del tema: cada `inc/*.php` tiene su propia responsabilidad y se incluye desde `functions.php`.
- `add_theme_support('title-tag')` ya existe en `inc/setup.php` — no duplicar.
- Las redes sociales vacías (`''`) no deben aparecer en el schema.

---

## Mapa de archivos

| Archivo | Acción | Responsabilidad |
|---------|--------|-----------------|
| `inc/seo.php` | Crear | Toda la lógica SEO: título, helpers, OG/Twitter, JSON-LD |
| `functions.php` | Modificar | Agregar `require_once` de `inc/seo.php` |

---

### Task 1: Scaffold de `inc/seo.php` + control del `<title>`

**Files:**
- Create: `inc/seo.php`
- Modify: `functions.php`

**Interfaces:**
- Consumes: `eu_get_option()` de `inc/helpers.php` (ya disponible al momento del hook)
- Produces:
  - `eu_seo_title(): string` — título SEO según contexto
  - `eu_seo_url(): string` — URL canónica de la página actual
  - Hook `document_title_parts` activo
  - Hook `document_title_separator` activo

- [ ] **Step 1: Crear `inc/seo.php` con scaffold y filtros de título**

```php
<?php
/**
 * SEO: título, Open Graph, Twitter Cards y Schema.org.
 *
 * @package Enclave_Urbano
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Título SEO según contexto de página.
 */
function eu_seo_title() {
    if (is_front_page()) {
        return get_bloginfo('name');
    }
    if (is_singular()) {
        return get_the_title();
    }
    if (is_post_type_archive()) {
        return post_type_archive_title('', false);
    }
    $obj = get_queried_object();
    if ($obj && isset($obj->name)) {
        return $obj->name;
    }
    return get_bloginfo('name');
}

/**
 * URL canónica de la página actual.
 */
function eu_seo_url() {
    if (is_front_page()) {
        return home_url('/');
    }
    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
}

/**
 * Formato del separador en <title>.
 */
add_filter('document_title_separator', function () {
    return '|';
});

/**
 * Partes del <title> por tipo de página.
 */
add_filter('document_title_parts', function ($parts) {
    if (is_front_page()) {
        $tagline = eu_get_option('tagline');
        return array_filter(array(
            'title'   => get_bloginfo('name'),
            'tagline' => $tagline ?: null,
        ));
    }
    $parts['site'] = get_bloginfo('name');
    unset($parts['tagline']);
    return $parts;
});
```

- [ ] **Step 2: Registrar `inc/seo.php` en `functions.php`**

Abrir `functions.php`. Después de la línea `require_once EU_THEME_DIR . '/inc/template-tags.php';` (línea 22), agregar:

```php
require_once EU_THEME_DIR . '/inc/seo.php';
```

- [ ] **Step 3: Verificar sintaxis PHP**

```bash
php -l inc/seo.php
```

Resultado esperado: `No syntax errors detected in inc/seo.php`

- [ ] **Step 4: Verificar el `<title>` en el navegador**

Cargar la portada del sitio y abrir "Ver código fuente". Buscar la etiqueta `<title>`.

Portada esperada: `<title>Enclave Urbano | Buenos resultados, entre creatividad e innovación.</title>`

En una página interior (ej. un proyecto): `<title>Nombre del proyecto | Enclave Urbano</title>`

- [ ] **Step 5: Commit**

```bash
git add inc/seo.php functions.php
git commit -m "feat(seo): scaffold inc/seo.php con control del <title>"
```

---

### Task 2: Helper functions + meta description + Open Graph + Twitter Cards

**Files:**
- Modify: `inc/seo.php`

**Interfaces:**
- Consumes:
  - `eu_seo_title(): string` (Task 1)
  - `eu_seo_url(): string` (Task 1)
  - `eu_get_option(string $key): string` (helpers.php)
- Produces:
  - `eu_seo_description(int $length = 160): string`
  - `eu_seo_image_data(): array{url: string, width: int|null, height: int|null}`
  - Hook `wp_head` activo con meta description, OG tags y Twitter Card tags

- [ ] **Step 1: Agregar helpers de descripción e imagen al final de `inc/seo.php`**

```php
/**
 * Descripción SEO según contexto.
 */
function eu_seo_description($length = 160) {
    if (is_front_page()) {
        return eu_get_option('tagline');
    }
    if (!is_singular()) {
        return '';
    }
    $excerpt = get_the_excerpt();
    if ($excerpt) {
        return wp_strip_all_tags($excerpt);
    }
    $content = wp_strip_all_tags(strip_shortcodes((string) get_the_content()));
    return mb_substr(trim($content), 0, $length);
}

/**
 * Imagen OG: imagen destacada del post o imagen hero del sitio.
 * Devuelve url, width, height (width/height son null si no están disponibles).
 */
function eu_seo_image_data() {
    if (is_singular()) {
        $thumb_id = get_post_thumbnail_id();
        if ($thumb_id) {
            $img = wp_get_attachment_image_src($thumb_id, 'large');
            if ($img) {
                return array('url' => $img[0], 'width' => (int) $img[1], 'height' => (int) $img[2]);
            }
        }
    }
    return array('url' => eu_get_option('home_hero_image'), 'width' => null, 'height' => null);
}
```

- [ ] **Step 2: Agregar el hook `wp_head` con meta description, OG y Twitter Cards**

Agregar al final de `inc/seo.php`:

```php
/**
 * Inyecta meta tags SEO en <head>.
 */
add_action('wp_head', 'eu_seo_head', 1);
function eu_seo_head() {
    $title       = eu_seo_title();
    $description = eu_seo_description();
    $url         = eu_seo_url();
    $site_name   = get_bloginfo('name');
    $image       = eu_seo_image_data();
    $og_type     = is_singular('eu_news') ? 'article' : 'website';

    ?>
    <?php if ($description) : ?>
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <?php if ($description) : ?>
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>">
    <?php if ($image['url']) : ?>
    <meta property="og:image" content="<?php echo esc_url($image['url']); ?>">
    <?php if ($image['width']) : ?>
    <meta property="og:image:width" content="<?php echo esc_attr($image['width']); ?>">
    <meta property="og:image:height" content="<?php echo esc_attr($image['height']); ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <?php if ($description) : ?>
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <?php endif; ?>
    <?php if ($image['url']) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image['url']); ?>">
    <?php endif; ?>
    <?php
}
```

- [ ] **Step 3: Verificar sintaxis PHP**

```bash
php -l inc/seo.php
```

Resultado esperado: `No syntax errors detected in inc/seo.php`

- [ ] **Step 4: Verificar las tags en el código fuente**

Cargar la portada y buscar en el código fuente:

```html
<meta name="description" content="Buenos resultados, entre creatividad e innovación.">
<meta property="og:site_name" content="Enclave Urbano">
<meta property="og:title" content="Enclave Urbano">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
```

Cargar la página de una novedad y verificar que `og:type` sea `article` y que la imagen destacada aparezca en `og:image`.

- [ ] **Step 5: Commit**

```bash
git add inc/seo.php
git commit -m "feat(seo): meta description, Open Graph y Twitter Cards"
```

---

### Task 3: JSON-LD Schema.org

**Files:**
- Modify: `inc/seo.php`

**Interfaces:**
- Consumes:
  - `eu_seo_title(): string` (Task 1)
  - `eu_seo_url(): string` (Task 1)
  - `eu_seo_description(int $length): string` (Task 2)
  - `eu_seo_image_data(): array` (Task 2)
  - `eu_get_option(string $key): string` (helpers.php)
  - `eu_project_meta(int $post_id, string $key): string` (template-tags.php)
- Produces: bloque `<script type="application/ld+json">` en `<head>` para cada tipo de página

- [ ] **Step 1: Agregar función `eu_seo_schema()` en `inc/seo.php`**

Agregar antes de la función `eu_seo_head()`:

```php
/**
 * Genera el array Schema.org según el tipo de página.
 * Devuelve null si no aplica ningún schema.
 */
function eu_seo_schema() {
    if (is_front_page()) {
        $same_as = array_values(array_filter(array(
            eu_get_option('instagram_url'),
            eu_get_option('facebook_url'),
            eu_get_option('linkedin_url'),
            eu_get_option('youtube_url'),
            eu_get_option('tiktok_url'),
        )));

        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => array('Organization', 'RealEstateAgent'),
            'name'     => get_bloginfo('name'),
            'url'      => home_url('/'),
        );

        $tagline = eu_get_option('tagline');
        if ($tagline) {
            $schema['description'] = $tagline;
        }

        $logo = eu_get_option('logo_large');
        if ($logo) {
            $schema['logo'] = $logo;
        }

        $address = eu_get_option('address');
        if ($address) {
            $schema['address'] = wp_strip_all_tags($address);
        }

        $phone = eu_get_option('phone_admin');
        if ($phone) {
            $schema['telephone'] = $phone;
        }

        $email = eu_get_option('contact_email');
        if ($email) {
            $schema['email'] = $email;
        }

        if (!empty($same_as)) {
            $schema['sameAs'] = $same_as;
        }

        return $schema;
    }

    if (is_singular('eu_project')) {
        $image  = eu_seo_image_data();
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'RealEstateListing',
            'name'        => get_the_title(),
            'description' => eu_seo_description(),
            'url'         => get_permalink(),
        );

        if ($image['url']) {
            $schema['image'] = $image['url'];
        }

        $location = eu_project_meta(get_the_ID(), 'location');
        if ($location) {
            $schema['address'] = $location;
        }

        return $schema;
    }

    if (is_singular('eu_news')) {
        $image  = eu_seo_image_data();
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'NewsArticle',
            'headline'      => get_the_title(),
            'description'   => eu_seo_description(),
            'url'           => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified'  => get_the_modified_date('c'),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo('name'),
                'logo'  => eu_get_option('logo_large'),
            ),
        );

        if ($image['url']) {
            $schema['image'] = $image['url'];
        }

        return $schema;
    }

    // Páginas genéricas y archivos
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'WebPage',
        'name'     => eu_seo_title(),
        'url'      => eu_seo_url(),
    );

    $desc = eu_seo_description();
    if ($desc) {
        $schema['description'] = $desc;
    }

    return $schema;
}
```

- [ ] **Step 2: Agregar la salida del JSON-LD dentro de `eu_seo_head()`**

La función `eu_seo_head()` definida en Task 2 termina con este patrón:

```php
    <?php if ($image['url']) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image['url']); ?>">
    <?php endif; ?>
    <?php
}
```

Reemplazar el bloque final `<?php\n}` por:

```php
    <?php if ($image['url']) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image['url']); ?>">
    <?php endif; ?>
    <?php
    // JSON-LD Schema.org
    $schema = eu_seo_schema();
    if ($schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
```

- [ ] **Step 3: Verificar sintaxis PHP**

```bash
php -l inc/seo.php
```

Resultado esperado: `No syntax errors detected in inc/seo.php`

- [ ] **Step 4: Verificar JSON-LD en el código fuente de la portada**

Cargar la portada, ver código fuente y buscar `application/ld+json`. Debe aparecer algo como:

```json
{
    "@context": "https://schema.org",
    "@type": [
        "Organization",
        "RealEstateAgent"
    ],
    "name": "Enclave Urbano",
    "url": "https://...",
    "description": "Buenos resultados, entre creatividad e innovación.",
    ...
}
```

- [ ] **Step 5: Validar el JSON-LD**

Copiar el bloque JSON del código fuente y pegarlo en [https://validator.schema.org](https://validator.schema.org) para verificar que no haya errores de estructura.

En una novedad individual, verificar que el schema sea `NewsArticle` con `datePublished` en formato ISO 8601 (ej. `2024-03-15T10:00:00+00:00`).

En un proyecto individual, verificar que el schema sea `RealEstateListing`.

- [ ] **Step 6: Commit final**

```bash
git add inc/seo.php
git commit -m "feat(seo): JSON-LD Schema.org (Organization, RealEstateListing, NewsArticle, WebPage)"
```
