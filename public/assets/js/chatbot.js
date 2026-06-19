// Hasaki AI Chatbot widget
(function () {
  const endpoint = window.HASAKI_CHATBOT_URL || 'chatbot.php';
  let opened = false;
  let initialized = false;

  // Build DOM
  const root = document.createElement('div');
  root.className = 'chatbot';
  root.innerHTML = `
    <div class="chatbot-window" id="cb-window">
      <div class="chatbot-header">
        <div class="avatar"><i class="fa-solid fa-robot"></i></div>
        <div class="info">
          <div class="name">Hasaki Bot</div>
          <div class="status">Đang trực tuyến</div>
        </div>
        <button type="button" class="close-btn" id="cb-close" aria-label="Đóng">&times;</button>
      </div>
      <div class="chatbot-messages" id="cb-messages"></div>
      <div class="chatbot-quick" id="cb-quick"></div>
      <form class="chatbot-input" id="cb-form">
        <input id="cb-input" type="text" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="300">
        <button type="submit" aria-label="Gửi"><i class="fa-solid fa-paper-plane"></i></button>
      </form>
    </div>
    <button type="button" class="chatbot-toggle" id="cb-toggle" aria-label="Mở chatbot">
      <i class="fa-regular fa-comments"></i>
      <span class="pulse"></span>
    </button>
  `;
  document.body.appendChild(root);

  const win   = document.getElementById('cb-window');
  const list  = document.getElementById('cb-messages');
  const form  = document.getElementById('cb-form');
  const input = document.getElementById('cb-input');
  const quick = document.getElementById('cb-quick');

  document.getElementById('cb-toggle').addEventListener('click', toggle);
  document.getElementById('cb-close').addEventListener('click', toggle);
  form.addEventListener('submit', e => { e.preventDefault(); send(input.value); });

  function toggle() {
    opened = !opened;
    win.classList.toggle('open', opened);
    if (opened && !initialized) {
      initialized = true;
      init();
    } else if (opened) {
      input.focus();
    }
  }

  function init() {
    fetch(endpoint + '?action=init', { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => {
        addBot(data.reply);
        renderQuick(data.quick || []);
        input.focus();
      })
      .catch(() => addBot('Xin chào! Mình là Hasaki Bot. Bạn cần hỗ trợ gì ạ?'));
  }

  function renderQuick(replies) {
    quick.innerHTML = '';
    replies.forEach(text => {
      const chip = document.createElement('button');
      chip.type = 'button';
      chip.className = 'chip';
      chip.textContent = text;
      chip.addEventListener('click', () => send(text));
      quick.appendChild(chip);
    });
  }

  function send(text) {
    text = (text || '').trim();
    if (!text) return;
    addUser(text);
    input.value = '';
    quick.innerHTML = '';
    showTyping();

    fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: text })
    })
      .then(r => r.json())
      .then(data => {
        hideTyping();
        if (data.error) {
          addBot('Xin lỗi, có lỗi xảy ra. Bạn thử lại nhé!');
          return;
        }
        addBot(data.reply, data);
      })
      .catch(() => {
        hideTyping();
        addBot('Hiện không kết nối được tới máy chủ. Bạn thử lại sau nhé!');
      });
  }

  function addUser(text) {
    const el = document.createElement('div');
    el.className = 'msg user';
    el.innerHTML = `<div class="bubble">${escapeHtml(text)}</div>`;
    list.appendChild(el);
    scrollBottom();
  }

  function addBot(text, extra) {
    const el = document.createElement('div');
    el.className = 'msg bot';
    el.innerHTML = `
      <div class="avatar"><i class="fa-solid fa-robot"></i></div>
      <div class="bubble">${formatText(text || '')}</div>
    `;
    list.appendChild(el);

    if (extra && extra.products && extra.products.length) {
      const wrap = document.createElement('div');
      wrap.className = 'msg bot';
      wrap.innerHTML = `<div class="avatar"></div><div class="msg-products" style="background:transparent"></div>`;
      const grid = wrap.querySelector('.msg-products');
      extra.products.forEach(p => {
        const a = document.createElement('a');
        a.className = 'product-mini';
        a.href = p.url;
        a.target = '_self';
        a.innerHTML = `
          <div class="relative w-full aspect-square rounded mb-1 overflow-hidden bg-gradient-to-br from-pink-100 to-orange-100 flex items-center justify-center">
            <i class="fa-solid fa-spa absolute text-2xl text-rose-300/60"></i>
            <img src="${escapeAttr(p.image)}" onerror="this.style.display='none'" class="relative w-full h-full object-cover">
          </div>
          <div class="name">${escapeHtml(p.name)}</div>
          <div class="price">${escapeHtml(p.price)}</div>
        `;
        grid.appendChild(a);
      });
      list.appendChild(wrap);
    }

    if (extra && extra.link) {
      const wrap = document.createElement('div');
      wrap.className = 'msg bot';
      wrap.innerHTML = `<div class="avatar"></div><div class="bubble"><a href="${escapeAttr(extra.link)}" style="color:#ff5e8e;font-weight:600">→ Đến trang đó</a></div>`;
      list.appendChild(wrap);
    }

    scrollBottom();
  }

  let typingEl = null;
  function showTyping() {
    typingEl = document.createElement('div');
    typingEl.className = 'msg bot typing';
    typingEl.innerHTML = `<div class="avatar"><i class="fa-solid fa-robot"></i></div><div class="bubble"><span></span><span></span><span></span></div>`;
    list.appendChild(typingEl);
    scrollBottom();
  }
  function hideTyping() {
    if (typingEl) { typingEl.remove(); typingEl = null; }
  }

  function scrollBottom() { list.scrollTop = list.scrollHeight; }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[c]);
  }
  function escapeAttr(s) { return escapeHtml(s); }

  // Markdown-lite: **bold**, line breaks
  function formatText(s) {
    return escapeHtml(s)
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/\n/g, '<br>');
  }
})();
