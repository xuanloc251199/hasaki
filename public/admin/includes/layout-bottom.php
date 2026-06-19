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
</script>
</body>
</html>
