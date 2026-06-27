    </div>
  </main>
</div>
<script>
// Modal helpers
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
document.querySelectorAll('[data-modal-open]').forEach(el => {
  el.addEventListener('click', () => openModal(el.getAttribute('data-modal-open')));
});
document.querySelectorAll('[data-modal-close]').forEach(el => {
  el.addEventListener('click', () => closeModal(el.getAttribute('data-modal-close')));
});
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
  });
});

// Notification bell dropdown
(function () {
  const wrap = document.querySelector('[data-notif-wrap]');
  if (!wrap) return;
  const toggle = wrap.querySelector('[data-notif-toggle]');
  const panel = wrap.querySelector('[data-notif-panel]');
  toggle.addEventListener('click', e => {
    e.stopPropagation();
    panel.classList.toggle('hidden');
  });
  document.addEventListener('click', e => {
    if (!wrap.contains(e.target)) panel.classList.add('hidden');
  });
})();
</script>
</body>
</html>
