/**
 * Form liên hệ — kiểm tra nhanh phía trình duyệt (server vẫn kiểm tra lại).
 */
(function () {
  'use strict';

  var form = document.getElementById('cf-form');
  var alertBox = document.getElementById('cf-alert');

  // Có lỗi từ server: đưa người dùng tới thông báo lỗi
  if (alertBox) {
    alertBox.focus();
    alertBox.scrollIntoView({ block: 'center', behavior: 'smooth' });
  }
  if (!form) return;

  var PHONE_RE = /^(0[0-9]{9}|\+84[0-9]{9})$/;
  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  var rules = {
    ho_ten: function (v) { return v ? '' : 'Vui lòng nhập họ và tên.'; },
    sdt: function (v) {
      if (!v) return 'Vui lòng nhập số điện thoại.';
      return PHONE_RE.test(v.replace(/[\s.\-]+/g, ''))
        ? '' : 'Số điện thoại chưa đúng. Ví dụ: 0906 123 456 hoặc +84 906 123 456.';
    },
    email: function (v) {
      if (!v) return 'Vui lòng nhập email.';
      return EMAIL_RE.test(v) ? '' : 'Email chưa đúng định dạng. Ví dụ: ban@congty.com';
    },
    noi_dung: function (v) {
      if (!v) return 'Vui lòng mô tả ngắn gọn nhu cầu của bạn.';
      return v.length >= 10 ? '' : 'Nội dung hơi ngắn. Hãy mô tả thêm để chúng tôi tư vấn đúng hơn.';
    }
  };

  function setError(input, msg) {
    var wrap = input.closest('.cf-field');
    var out = document.getElementById('err-' + input.name);
    if (!wrap || !out) return;
    out.textContent = msg;
    wrap.classList.toggle('has-error', !!msg);
    if (msg) {
      input.setAttribute('aria-invalid', 'true');
      input.setAttribute('aria-describedby', out.id);
    } else {
      input.removeAttribute('aria-invalid');
      input.removeAttribute('aria-describedby');
    }
  }

  function check(input) {
    var rule = rules[input.name];
    if (!rule) return true;
    var msg = rule(input.value.trim());
    setError(input, msg);
    return !msg;
  }

  Object.keys(rules).forEach(function (name) {
    var input = form.elements[name];
    if (!input) return;
    input.addEventListener('blur', function () { if (input.value.trim() !== '' || input.getAttribute('aria-invalid')) check(input); });
    input.addEventListener('input', function () { if (input.getAttribute('aria-invalid')) check(input); });
  });

  form.addEventListener('submit', function (e) {
    var firstBad = null;
    Object.keys(rules).forEach(function (name) {
      var input = form.elements[name];
      if (input && !check(input) && !firstBad) firstBad = input;
    });
    if (firstBad) {
      e.preventDefault();
      firstBad.focus();
      return;
    }
    // Hợp lệ: khoá nút để tránh bấm 2 lần
    var btn = form.querySelector('.cf-submit');
    if (btn) {
      btn.disabled = true;
      btn.classList.add('is-loading');
      btn.setAttribute('aria-busy', 'true');
      btn.querySelector('.cf-submit-label').textContent = 'Đang gửi';
    }
  });

  // Người dùng quay lại bằng nút Back (bfcache): mở khoá nút
  window.addEventListener('pageshow', function (ev) {
    if (!ev.persisted) return;
    var btn = form.querySelector('.cf-submit');
    if (btn) {
      btn.disabled = false;
      btn.classList.remove('is-loading');
      btn.removeAttribute('aria-busy');
      btn.querySelector('.cf-submit-label').textContent = 'Gửi yêu cầu tư vấn';
    }
  });
})();
