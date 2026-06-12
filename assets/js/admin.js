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

      const preview = target.closest('.eu-field-row, .eu-meta-field').find('.eu-media-preview');
      if (preview.length) {
        preview.attr('src', attachment.url).show();
      } else {
        target.closest('.eu-field-row, .eu-meta-field').append('<img class="eu-media-preview" src="' + attachment.url + '" alt="" />');
      }
    });

    frame.open();
  });

  $(document).on('click', '.eu-media-clear', function (event) {
    event.preventDefault();
    const target = $($(this).data('target'));
    target.val('').trigger('change');
    target.closest('.eu-field-row, .eu-meta-field').find('.eu-media-preview').remove();
  });
}(jQuery));
