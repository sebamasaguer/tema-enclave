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

// Croquis interactivo — modales
(function () {
  'use strict';

  function openModal(id) {
    var modal = document.getElementById(id);
    if (!modal) return;
    modal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
    var close = modal.querySelector('.eu-modal__close');
    if (close) close.focus();
  }

  function closeModal(modal) {
    modal.setAttribute('hidden', '');
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.eu-croquis__dot').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openModal(btn.dataset.modal);
    });
  });

  document.querySelectorAll('.eu-modal').forEach(function (modal) {
    modal.querySelector('.eu-modal__overlay').addEventListener('click', function () {
      closeModal(modal);
    });
    modal.querySelector('.eu-modal__close').addEventListener('click', function () {
      closeModal(modal);
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.eu-modal:not([hidden])').forEach(closeModal);
    }
  });
}());
