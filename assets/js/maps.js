(function () {
  'use strict';

  window.euInitKmlMaps = function () {
    const maps = document.querySelectorAll('.eu-kml-map');

    maps.forEach(function (el) {
      const lat = parseFloat(el.getAttribute('data-lat')) || -34.6037;
      const lng = parseFloat(el.getAttribute('data-lng')) || -58.3816;
      const zoom = parseInt(el.getAttribute('data-zoom'), 10) || 13;
      const kml = el.getAttribute('data-kml');

      const map = new google.maps.Map(el, {
        center: { lat: lat, lng: lng },
        zoom: zoom,
        mapTypeId: 'hybrid',
        streetViewControl: false,
        mapTypeControl: true,
      });

      if (kml) {
        new google.maps.KmlLayer({
          url: kml,
          map: map,
          preserveViewport: false,
          suppressInfoWindows: false,
        });
      } else {
        new google.maps.Marker({
          position: { lat: lat, lng: lng },
          map: map,
        });
      }
    });
  };
}());
