# SEO: Meta Tags y Schema.org

**Fecha:** 2026-06-30  
**Estado:** Aprobado

## Objetivo

Implementar meta tags de Open Graph, Twitter Cards y datos estructurados Schema.org directamente en el tema WordPress de Enclave Urbano, usando los textos y valores ya configurados en las theme options existentes, sin plugins externos.

## Arquitectura

### Archivo nuevo: `inc/seo.php`

Archivo dedicado al SEO, siguiendo el patrón de inclusión del tema (`inc/helpers.php`, `inc/setup.php`, etc.). Se requiere desde `functions.php` con `require_once EU_THEME_DIR . '/inc/seo.php';`.

### Modificación: `functions.php`

Agregar el require de `inc/seo.php` al bloque de requires existentes (líneas 16–21).

## Componentes

### 1. Control del `<title>` (`document_title_parts` + `document_title_separator` filters)

Formato: `Nombre de la página | Enclave Urbano`

- Portada: `Enclave Urbano | {tagline}`
- Interior: `{post_title} | Enclave Urbano`

### 2. Meta description (`<meta name="description">`)

Fuente según contexto:
- **Portada:** `eu_get_option('tagline')`
- **Proyecto / Novedad / Página:** excerpt del post si existe; si no, primeros 160 caracteres del contenido sin HTML ni shortcodes

### 3. Open Graph tags

| Tag | Valor |
|-----|-------|
| `og:site_name` | `get_bloginfo('name')` |
| `og:title` | Mismo que `<title>` |
| `og:description` | Mismo que `<meta description>` |
| `og:url` | `home_url()` en portada, `get_permalink()` en el resto |
| `og:type` | `website` (portada y páginas), `article` (novedades) |
| `og:image` | Imagen destacada del post; fallback: `eu_get_option('home_hero_image')` |
| `og:image:width` / `og:image:height` | Dimensiones reales si están disponibles en la biblioteca de medios |

### 4. Twitter Card tags

| Tag | Valor |
|-----|-------|
| `twitter:card` | `summary_large_image` |
| `twitter:title` | Mismo que `og:title` |
| `twitter:description` | Mismo que `og:description` |
| `twitter:image` | Mismo que `og:image` |

### 5. Schema.org JSON-LD

#### Portada — `Organization` + `RealEstateAgent`

```json
{
  "@context": "https://schema.org",
  "@type": ["Organization", "RealEstateAgent"],
  "name": "{blogname}",
  "description": "{tagline}",
  "url": "{home_url}",
  "logo": "{eu_get_option('logo_large')}",
  "address": "{eu_get_option('address')} (solo si está cargado)",
  "telephone": "{eu_get_option('phone_admin')} (solo si está cargado)",
  "email": "{eu_get_option('contact_email')} (solo si está cargado)",
  "sameAs": ["{instagram_url}", "{facebook_url}", "{linkedin_url}", "{youtube_url}", "{tiktok_url}"]
}
```

Solo se incluyen en `sameAs` las URLs de redes sociales que no estén vacías.

#### Proyecto individual (`eu_project`) — `RealEstateListing`

```json
{
  "@context": "https://schema.org",
  "@type": "RealEstateListing",
  "name": "{post_title}",
  "description": "{excerpt o contenido truncado}",
  "url": "{permalink}",
  "image": "{imagen destacada}"
}
```

El campo `address` se agrega como texto plano si el metabox `location` del proyecto tiene valor.

#### Novedad individual (`eu_news`) — `NewsArticle`

```json
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{post_title}",
  "description": "{excerpt o contenido truncado}",
  "url": "{permalink}",
  "image": "{imagen destacada}",
  "datePublished": "{post_date ISO 8601}",
  "dateModified": "{post_modified ISO 8601}",
  "publisher": {
    "@type": "Organization",
    "name": "{blogname}",
    "logo": "{eu_get_option('logo_large')}"
  }
}
```

#### Cualquier otra página — `WebPage`

```json
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "{post_title}",
  "description": "{excerpt o contenido truncado}",
  "url": "{permalink}"
}
```

## Fuentes de datos

Todos los valores se toman de sources ya existentes en el tema:

| Dato | Fuente |
|------|--------|
| Nombre del sitio | `get_bloginfo('name')` |
| Tagline | `eu_get_option('tagline')` |
| Logo | `eu_get_option('logo_large')` |
| Imagen hero | `eu_get_option('home_hero_image')` |
| Dirección | `eu_get_option('address')` |
| Teléfono | `eu_get_option('phone_admin')` |
| Email | `eu_get_option('contact_email')` |
| Redes sociales | `eu_get_option('instagram_url')`, etc. |
| Imagen del post | `get_the_post_thumbnail_url()` |
| Ubicación del proyecto | `eu_project_meta(get_the_ID(), 'location')` |

## Archivos modificados

| Archivo | Cambio |
|---------|--------|
| `inc/seo.php` | Archivo nuevo con toda la lógica SEO |
| `functions.php` | `require_once` de `inc/seo.php` |

Nota: `add_theme_support('title-tag')` ya está presente en `inc/setup.php` (línea 16), no requiere cambios.

## Lo que queda fuera de scope

- Panel de administración para SEO (no requerido, los datos vienen de theme options existentes)
- Sitemap XML (se recomienda instalar un plugin para esto)
- robots.txt (se gestiona desde WordPress o el servidor)
- Integración con Google Search Console (paso manual del cliente)
