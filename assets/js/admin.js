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
        target.closest('.eu-field-row, .eu-meta-field, td').append(
          $('<img>').attr({ class: 'eu-media-preview', src: attachment.url, alt: '' })
        );
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
    select.append($('<option>').val('area').text('Área'));
    select.append($('<option>').val('sector').text('Sector'));
    row.append($('<td>').append(select));

    row.append(
      $('<td>').append(
        $('<button>').attr({ type: 'button', class: 'button eu-croquis-remove-row' }).text('✕')
      )
    );

    var tbody = $('#eu-croquis-hotspots tbody');
    if (tbody.length) {
      tbody.append(row);
    }
  });

  $(document).on('click', '.eu-croquis-remove-row', function () {
    $(this).closest('tr').remove();
  });

}(jQuery));
