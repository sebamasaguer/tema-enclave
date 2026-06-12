(function () {
  'use strict';

  const toggles = document.querySelectorAll('.eu-menu-toggle');

  toggles.forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      const isOpen = document.body.classList.toggle('eu-menu-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && document.body.classList.contains('eu-menu-open')) {
      document.body.classList.remove('eu-menu-open');
      toggles.forEach(function (toggle) {
        toggle.setAttribute('aria-expanded', 'false');
      });
    }
  });

  document.querySelectorAll('.eu-primary-nav a').forEach(function (link) {
    link.addEventListener('click', function () {
      document.body.classList.remove('eu-menu-open');
      toggles.forEach(function (toggle) {
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  });
}());
