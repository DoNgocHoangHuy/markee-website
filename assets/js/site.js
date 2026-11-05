/** Menu di động (nút hamburger). */
(function () {
  'use strict';
  var btn = document.querySelector('.nav-toggle');
  var header = document.getElementById('site-header');
  if (!btn || !header) return;

  function setOpen(open) {
    header.classList.toggle('nav-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.setAttribute('aria-label', open ? 'Đóng menu' : 'Mở menu');
  }

  btn.addEventListener('click', function () {
    setOpen(!header.classList.contains('nav-open'));
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });
  // Bấm vào link trong menu thì đóng lại; quay về desktop thì reset
  header.addEventListener('click', function (e) {
    if (e.target.closest('.main-nav a')) setOpen(false);
  });
  window.matchMedia('(min-width: 981px)').addEventListener('change', function () { setOpen(false); });
})();
