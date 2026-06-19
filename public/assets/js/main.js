// Hasaki - main user JavaScript

// Quantity stepper for product detail
document.querySelectorAll('[data-qty-stepper]').forEach(group => {
  const input = group.querySelector('input');
  const minus = group.querySelector('[data-action="minus"]');
  const plus  = group.querySelector('[data-action="plus"]');
  if (minus) minus.addEventListener('click', () => {
    input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
  });
  if (plus) plus.addEventListener('click', () => {
    input.value = (parseInt(input.value, 10) || 1) + 1;
  });
});

// Cart - update qty on change
document.querySelectorAll('[data-cart-update]').forEach(input => {
  input.addEventListener('change', () => {
    input.closest('form').submit();
  });
});

// Confirm dialogs
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.getAttribute('data-confirm'))) {
      e.preventDefault();
    }
  });
});

// Password show/hide toggle (works on any [data-password-toggle] button inside a .pw-field / [data-password-wrap])
document.querySelectorAll('[data-password-toggle]').forEach(btn => {
  btn.addEventListener('click', () => {
    const wrap = btn.closest('[data-password-wrap]') || btn.parentElement;
    // Match any input regardless of current type — type flips between 'password' and 'text'
    const input = wrap.querySelector('input');
    if (!input) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
      icon.classList.toggle('fa-eye',       !isHidden);  // showing → use closed-eye look
      icon.classList.toggle('fa-eye-slash',  isHidden);  // password hidden → use open eye
    }
    btn.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
  });
});

// Payment option selector
document.querySelectorAll('.payment-option').forEach(opt => {
  opt.addEventListener('click', () => {
    document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('active'));
    opt.classList.add('active');
    const radio = opt.querySelector('input[type=radio]');
    if (radio) radio.checked = true;
  });
});

// Thumbnail swap on product detail
document.querySelectorAll('[data-thumb]').forEach(thumb => {
  thumb.addEventListener('click', () => {
    const main = document.querySelector('[data-main-image]');
    const src = thumb.getAttribute('src');
    if (main) main.setAttribute('src', src);
    document.querySelectorAll('[data-thumb]').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
  });
});
