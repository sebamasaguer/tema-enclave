# Tema WordPress Enclave Urbano

Tema personalizado para una desarrolladora urbana con identidad verde `#336633` y amarillo `#ffff00`.

## Instalación

1. En WordPress, ir a **Apariencia > Temas > Añadir nuevo > Subir tema**.
2. Subir `enclave-urbano.zip`.
3. Activar el tema.
4. Ir a **Apariencia > Ajustes Enclave** y configurar logos, portada, email, teléfonos, redes, frase institucional y Google Maps API Key.
5. Ir a **Ajustes > Enlaces permanentes** y guardar una vez para refrescar URLs, si fuera necesario.

## Menú sugerido

Crear un menú en **Apariencia > Menús** y asignarlo a “Menú principal”:

- Enclave Urbano: `/#enclave-urbano`
- Equipo: `/#equipo`
- Valores: `/#valores`
- News: página de entradas o `/news/`
- Comercialización: página con plantilla “Comercialización”
- Alianzas: página con plantilla “Alianzas”
- Comunidad: página con plantilla “Comunidad”
- Filosofía: página con plantilla “Filosofía”
- Proyectos: archivo `/proyectos/`
  - La Enriqueta: proyecto individual dentro de Proyectos
- Contacto: página con plantilla “Contacto”

Si no se crea menú, el tema muestra un menú de respaldo con esta estructura.

## Contenido administrable

- **Proyectos**: custom post type con ficha técnica, video, Google Maps embed, WhatsApp y KML.
- **Valores**: custom post type para icono, título y texto.
- **Equipo**: custom post type para profesionales, foto, cargo, QR y link.
- **Alianzas**: custom post type agrupado por “Tipos de alianza”.
- **Consultas**: se guardan automáticamente desde formularios y también se envían al email configurado.
- **News**: usa las entradas nativas de WordPress.

## KML y Google Maps

Para ver urbanizaciones con KML:

1. Cargar la Google Maps API Key en **Apariencia > Ajustes Enclave**.
2. En cada proyecto, subir o pegar una URL pública de archivo `.kml`.
3. Completar latitud, longitud y zoom inicial como respaldo.

El tema habilita subida de archivos `.kml` y `.kmz` en la biblioteca de medios.

## Formularios

El formulario guarda:

- Nombre y apellido
- Email
- Teléfono
- Mensaje
- Contexto
- Proyecto relacionado, cuando corresponde
- Estado: nuevo, leído, respondido o archivado

Email por defecto: `info@enclaveurbano.com.ar`.

## Plantillas incluidas

- Home personalizada (`front-page.php`)
- Comunidad
- Alianzas
- Filosofía
- Contacto
- Comercialización
- Archivo de proyectos
- Proyecto individual
- News / blog


## Footer - banda Ciudad Abierta editable

Desde Apariencia > Ajustes Enclave > Footer se puede cambiar la imagen de la banda del footer, definir la altura en escritorio y mobile, elegir la posición horizontal/vertical y el modo de ajuste de la imagen.
