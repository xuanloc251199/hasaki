<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/ChatbotBLL.php';

require_admin();

$page_title = 'Cấu hình AI Chatbot';
$bot = new ChatbotBLL();
$action = $_REQUEST['action'] ?? 'list';

// === Handle POST ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($action === 'create' || $action === 'update') {
    $data = [
      'TenIntent'    => trim($_POST['TenIntent'] ?? ''),
      'TuKhoa'       => trim($_POST['TuKhoa'] ?? ''),
      'CauTraLoi'    => trim($_POST['CauTraLoi'] ?? ''),
      'LoaiGoiY'     => $_POST['LoaiGoiY'] ?? 'none',
      'GiaTriGoiY'   => trim($_POST['GiaTriGoiY'] ?? '') ?: null,
      'Link'         => trim($_POST['Link'] ?? '') ?: null,
      'ThuTu'        => (int)($_POST['ThuTu'] ?? 0),
      'KichHoat'     => isset($_POST['KichHoat']) ? 1 : 0,
      'LaQuickReply' => isset($_POST['LaQuickReply']) ? 1 : 0,
    ];
    $res = $action === 'create'
      ? $bot->adminCreateIntent($data)
      : $bot->adminUpdateIntent((int)$_POST['id'], $data);
    isset($res['error'])
      ? set_flash_error($res['error'])
      : set_flash_success($action === 'create' ? 'Đã thêm intent' : 'Đã cập nhật');
    redirect(url('admin/chatbot.php'));
  }

  if ($action === 'save_config') {
    foreach (['greeting', 'fallback', 'bot_name', 'max_products', 'enable_fallback_search'] as $k) {
      $bot->adminSetConfig($k, trim((string)($_POST[$k] ?? '')));
    }
    set_flash_success('Đã lưu cấu hình');
    redirect(url('admin/chatbot.php#config'));
  }
}

if ($action === 'delete') {
  $bot->adminDeleteIntent((int)$_GET['id']);
  set_flash_success('Đã xóa intent');
  redirect(url('admin/chatbot.php'));
}
if ($action === 'toggle') {
  $bot->adminToggleIntent((int)$_GET['id']);
  redirect(url('admin/chatbot.php'));
}

// === Data ===
$keyword = trim($_GET['q'] ?? '');
$intents = $bot->adminListIntents($keyword);
$config  = $bot->adminGetConfig();
$edit    = $action === 'edit' ? $bot->adminGetIntent((int)$_GET['id']) : null;

$type_labels = [
  'none'     => ['Không gợi ý',          'bg-gray-100 text-gray-700'],
  'search'   => ['Tìm theo từ khóa',    'bg-blue-100 text-blue-700'],
  'featured' => ['Sản phẩm sale',        'bg-brand-100 text-brand-700'],
  'newest'   => ['Sản phẩm mới',         'bg-emerald-100 text-emerald-700'],
];

require __DIR__ . '/includes/layout-top.php';
?>

<!-- Page header -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-bold text-gray-900 m-0">AI Chatbot</h1>
    <p class="text-sm text-gray-500 mt-1">Quản lý câu trả lời, từ khóa và cấu hình của trợ lý ảo Hasaki Bot</p>
  </div>
  <div class="flex items-center gap-2">
    <button class="btn-outline btn" onclick="document.querySelector('#tab-config').scrollIntoView({behavior:'smooth'})">
      <i class="fa-solid fa-gear"></i> Cấu hình
    </button>
    <button class="btn" data-modal-open="modal-create">
      <i class="fa-solid fa-plus"></i> Thêm intent
    </button>
  </div>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-white rounded-xl p-5 shadow-card flex items-center gap-4">
    <div class="w-12 h-12 rounded-lg bg-brand-50 text-brand-500 flex items-center justify-center text-xl">
      <i class="fa-solid fa-list"></i>
    </div>
    <div>
      <div class="text-xs uppercase text-gray-500 tracking-wide">Tổng intent</div>
      <div class="text-2xl font-extrabold text-gray-900"><?= count($bot->adminListIntents()) ?></div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card flex items-center gap-4">
    <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
      <i class="fa-solid fa-circle-check"></i>
    </div>
    <div>
      <div class="text-xs uppercase text-gray-500 tracking-wide">Đang hoạt động</div>
      <div class="text-2xl font-extrabold text-gray-900"><?= count(array_filter($intents, fn($i) => $i['KichHoat'])) ?></div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card flex items-center gap-4">
    <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
      <i class="fa-solid fa-bolt"></i>
    </div>
    <div>
      <div class="text-xs uppercase text-gray-500 tracking-wide">Quick reply</div>
      <div class="text-2xl font-extrabold text-gray-900"><?= count(array_filter($intents, fn($i) => $i['LaQuickReply'])) ?></div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card flex items-center gap-4">
    <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
      <i class="fa-solid fa-arrows-spin"></i>
    </div>
    <div>
      <div class="text-xs uppercase text-gray-500 tracking-wide">Có gợi ý SP</div>
      <div class="text-2xl font-extrabold text-gray-900"><?= count(array_filter($intents, fn($i) => $i['LoaiGoiY'] !== 'none')) ?></div>
    </div>
  </div>
</div>

<!-- Search bar -->
<div class="bg-white rounded-xl shadow-card p-4 mb-4">
  <form method="get" class="flex gap-2 items-center">
    <div class="relative flex-1 max-w-md">
      <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
      <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Tìm theo tên intent hoặc từ khóa..."
             class="!pl-10 !pr-4">
    </div>
    <button class="btn">Tìm</button>
    <?php if ($keyword): ?>
      <a href="<?= url('admin/chatbot.php') ?>" class="btn-outline btn">Xóa lọc</a>
    <?php endif; ?>
  </form>
</div>

<!-- Intents list (cards) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
  <?php if (empty($intents)): ?>
    <div class="col-span-full bg-white rounded-xl shadow-card p-12 text-center text-gray-500">
      <i class="fa-regular fa-folder-open text-5xl mb-3"></i>
      <p>Không có intent nào</p>
    </div>
  <?php else: foreach ($intents as $it):
    [$typeLabel, $typeClass] = $type_labels[$it['LoaiGoiY']] ?? ['—', 'bg-gray-100 text-gray-700'];
  ?>
    <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100 hover:shadow-soft transition-shadow">
      <div class="p-5">
        <div class="flex items-start justify-between gap-2 mb-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <span class="font-bold text-gray-900 text-base truncate"><?= e($it['TenIntent']) ?></span>
              <?php if (!$it['KichHoat']): ?>
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-bold">TẮT</span>
              <?php endif; ?>
              <?php if ($it['LaQuickReply']): ?>
                <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 font-bold">QUICK</span>
              <?php endif; ?>
            </div>
            <span class="inline-block text-[11px] px-2 py-0.5 rounded-full font-semibold <?= $typeClass ?>">
              <?= $typeLabel ?><?= $it['GiaTriGoiY'] ? ' · ' . e($it['GiaTriGoiY']) : '' ?>
            </span>
          </div>
          <span class="text-xs text-gray-400 shrink-0">#<?= (int)$it['MaIntent'] ?> · ưu tiên <?= (int)$it['ThuTu'] ?></span>
        </div>

        <div class="flex flex-wrap gap-1 mb-3">
          <?php foreach (array_slice(explode('|', $it['TuKhoa']), 0, 6) as $kw): ?>
            <span class="text-[11px] bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md font-medium">
              <?= e(trim($kw)) ?>
            </span>
          <?php endforeach; ?>
          <?php $count = count(explode('|', $it['TuKhoa'])); if ($count > 6): ?>
            <span class="text-[11px] text-gray-400 px-2 py-0.5">+<?= $count - 6 ?> nữa</span>
          <?php endif; ?>
        </div>

        <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed"><?= nl2br(e(mb_substr($it['CauTraLoi'], 0, 220))) ?><?= mb_strlen($it['CauTraLoi']) > 220 ? '...' : '' ?></p>
      </div>
      <div class="bg-gray-50 px-5 py-3 flex items-center justify-between border-t border-gray-100">
        <div class="text-xs text-gray-400">
          <i class="fa-regular fa-clock"></i> <?= format_datetime($it['NgayCapNhat']) ?>
        </div>
        <div class="flex gap-1">
          <a href="?action=toggle&id=<?= (int)$it['MaIntent'] ?>"
             class="px-2.5 py-1 rounded-md text-xs font-semibold <?= $it['KichHoat'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-700 hover:bg-red-200' ?>"
             title="<?= $it['KichHoat'] ? 'Tắt' : 'Bật' ?>">
            <i class="fa-solid fa-<?= $it['KichHoat'] ? 'toggle-on' : 'toggle-off' ?>"></i>
          </a>
          <a href="?action=edit&id=<?= (int)$it['MaIntent'] ?>"
             class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200">
            <i class="fa-solid fa-pen"></i>
          </a>
          <a href="?action=delete&id=<?= (int)$it['MaIntent'] ?>" data-confirm="Xóa intent này?"
             class="px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 hover:bg-red-200">
            <i class="fa-solid fa-trash"></i>
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; endif; ?>
</div>

<!-- Config tab -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 mb-8" id="tab-config">
  <div class="px-6 py-5 border-b border-gray-100">
    <h2 class="text-lg font-bold m-0 flex items-center gap-2"><i class="fa-solid fa-sliders text-brand-500"></i> Cấu hình chung</h2>
    <p class="text-sm text-gray-500 mt-1">Tùy chỉnh tên hiển thị, lời chào và hành vi của Chatbot</p>
  </div>
  <form method="post" action="?action=save_config" class="p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="!text-sm !font-semibold !text-gray-700 !mb-2 block">Tên hiển thị của Bot</label>
        <input type="text" name="bot_name" value="<?= e($config['bot_name']['ConfigValue'] ?? 'Hasaki Bot') ?>">
      </div>
      <div>
        <label class="!text-sm !font-semibold !text-gray-700 !mb-2 block">Số sản phẩm tối đa / 1 reply</label>
        <input type="number" name="max_products" min="1" max="12"
               value="<?= e($config['max_products']['ConfigValue'] ?? '4') ?>">
      </div>
    </div>

    <div>
      <label class="!text-sm !font-semibold !text-gray-700 !mb-2 block">Lời chào mở đầu cuộc trò chuyện</label>
      <textarea name="greeting" rows="4"><?= e($config['greeting']['ConfigValue'] ?? '') ?></textarea>
      <p class="text-xs text-gray-500 mt-1">Hỗ trợ markdown: <code class="bg-gray-100 px-1 rounded">**chữ in đậm**</code> · xuống dòng <code class="bg-gray-100 px-1 rounded">\n</code></p>
    </div>

    <div>
      <label class="!text-sm !font-semibold !text-gray-700 !mb-2 block">Câu trả lời mặc định khi không hiểu (fallback)</label>
      <textarea name="fallback" rows="3"><?= e($config['fallback']['ConfigValue'] ?? '') ?></textarea>
    </div>

    <label class="flex items-start gap-3 p-4 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
      <input type="checkbox" name="enable_fallback_search" value="1"
             <?= ($config['enable_fallback_search']['ConfigValue'] ?? '1') === '1' ? 'checked' : '' ?>
             class="mt-1 w-5 h-5 accent-brand-500">
      <div>
        <div class="font-semibold text-gray-900">Bật fallback search</div>
        <div class="text-sm text-gray-500">Khi không match intent nào, Chatbot sẽ tự động search trong database sản phẩm và trả về kết quả liên quan.</div>
      </div>
    </label>

    <div class="flex justify-end pt-4 border-t border-gray-100">
      <button class="btn"><i class="fa-solid fa-floppy-disk"></i> Lưu cấu hình</button>
    </div>
  </form>
</div>

<!-- Live preview chat -->
<div class="bg-gradient-to-br from-brand-50 to-mint-100 rounded-xl p-6 border border-brand-100">
  <h2 class="text-lg font-bold m-0 flex items-center gap-2 mb-3"><i class="fa-solid fa-flask text-brand-500"></i> Test thử Chatbot</h2>
  <p class="text-sm text-gray-600 mb-4">Gõ câu hỏi bên dưới để xem chatbot phản hồi như thế nào.</p>
  <div class="flex gap-2 mb-4">
    <input type="text" id="bot-test-input" placeholder='Thử gõ "da khô" hoặc "son môi"...' class="!bg-white">
    <button id="bot-test-send" class="btn shrink-0"><i class="fa-solid fa-paper-plane"></i> Gửi</button>
  </div>
  <div id="bot-test-output" class="bg-white rounded-lg p-4 min-h-[80px] text-sm text-gray-700 hidden"></div>
</div>

<!-- ====== Modals ====== -->

<!-- Create modal (Tailwind) -->
<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal !max-w-2xl">
    <div class="modal-head"><h3>Thêm intent mới</h3>
      <button type="button" class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create">
      <div class="modal-body space-y-4">
        <?php require __DIR__ . '/includes/intent-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-outline btn" data-modal-close="modal-create">Hủy</button>
        <button class="btn">Thêm</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit modal -->
<?php if ($edit):
  $intent = $edit;
?>
<div class="modal-bg" style="display:flex">
  <div class="modal !max-w-2xl">
    <div class="modal-head"><h3>Sửa intent #<?= (int)$edit['MaIntent'] ?></h3>
      <a class="close" href="<?= url('admin/chatbot.php') ?>">&times;</a></div>
    <form method="post" action="?action=update">
      <input type="hidden" name="id" value="<?= (int)$edit['MaIntent'] ?>">
      <div class="modal-body space-y-4">
        <?php require __DIR__ . '/includes/intent-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/chatbot.php') ?>">Hủy</a>
        <button class="btn">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
// Live preview test
const testInput  = document.getElementById('bot-test-input');
const testBtn    = document.getElementById('bot-test-send');
const testOutput = document.getElementById('bot-test-output');

async function runTest() {
  const msg = testInput.value.trim();
  if (!msg) return;
  testOutput.classList.remove('hidden');
  testOutput.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-brand-500"></i> Đang xử lý...';
  try {
    const res = await fetch('<?= url('chatbot.php') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: msg })
    });
    const data = await res.json();
    let html = '<div class="font-semibold text-gray-500 text-xs mb-1">Bot trả lời:</div>';
    html += '<div class="whitespace-pre-line">' + (data.reply || '').replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>') + '</div>';
    if (data.products && data.products.length) {
      html += '<div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">';
      data.products.forEach(p => {
        html += `<a href="${p.url}" target="_blank" class="border rounded-lg p-2 bg-gray-50 hover:shadow-sm transition-shadow">
          <img src="${p.image}" class="w-full aspect-square object-cover rounded mb-1" onerror="this.src='https://placehold.co/80'">
          <div class="text-xs font-semibold line-clamp-2">${p.name}</div>
          <div class="text-xs text-brand-500 font-bold">${p.price}</div>
        </a>`;
      });
      html += '</div>';
    }
    if (data.link) html += '<div class="mt-2 text-xs text-brand-500">→ Link: ' + data.link + '</div>';
    testOutput.innerHTML = html;
  } catch (err) {
    testOutput.innerHTML = '<span class="text-red-500">Lỗi: không gọi được API chatbot</span>';
  }
}
testBtn.addEventListener('click', runTest);
testInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); runTest(); } });
</script>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
