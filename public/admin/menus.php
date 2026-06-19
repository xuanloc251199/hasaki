<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/MenuBLL.php';

require_admin();

$page_title = 'Quản lý menu';
$bll = new MenuBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'update'])) {
  $data = [
    'ViTri'      => $_POST['ViTri'] ?? 'header',
    'TieuDe'     => trim($_POST['TieuDe'] ?? ''),
    'DuongDan'   => trim($_POST['DuongDan'] ?? ''),
    'Icon'       => trim($_POST['Icon'] ?? '') ?: null,
    'ThuTu'      => (int)($_POST['ThuTu'] ?? 0),
    'KichHoat'   => isset($_POST['KichHoat']) ? 1 : 0,
    'MoCuaSoMoi' => isset($_POST['MoCuaSoMoi']) ? 1 : 0,
  ];
  $res = $action === 'create'
    ? $bll->create($data)
    : $bll->update((int)$_POST['id'], $data);
  isset($res['error'])
    ? set_flash_error($res['error'])
    : set_flash_success($action === 'create' ? 'Đã thêm menu' : 'Đã cập nhật');
  redirect(url('admin/menus.php'));
}

if ($action === 'delete')  { $bll->delete((int)$_GET['id']); set_flash_success('Đã xóa'); redirect(url('admin/menus.php')); }
if ($action === 'toggle')  { $bll->toggle((int)$_GET['id']); redirect(url('admin/menus.php')); }

$all   = $bll->getAll();
$byPos = [];
foreach ($all as $m) $byPos[$m['ViTri']][] = $m;
$edit = $action === 'edit' ? $bll->getById((int)$_GET['id']) : null;

require __DIR__ . '/includes/layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-bold text-gray-900 m-0">Quản lý menu</h1>
    <p class="text-sm text-gray-500 mt-1">Tùy chỉnh các mục menu trên header và footer</p>
  </div>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm mục menu</button>
</div>

<div class="grid lg:grid-cols-3 gap-5">
  <?php foreach (MenuBLL::POSITIONS as $pos => $label):
    $items = $byPos[$pos] ?? [];
    $iconClass = match ($pos) {
      'header'         => 'fa-house',
      'footer_support' => 'fa-headset',
      'footer_account' => 'fa-user',
      default          => 'fa-bars',
    };
  ?>
    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 m-0 flex items-center gap-2">
          <i class="fa-solid <?= $iconClass ?> text-brand-500"></i> <?= e($label) ?>
        </h3>
        <span class="text-xs text-gray-400"><?= count($items) ?> mục</span>
      </div>
      <div class="divide-y divide-gray-100">
        <?php if (empty($items)): ?>
          <div class="p-5 text-center text-sm text-gray-400">Chưa có mục nào</div>
        <?php else: foreach ($items as $m): ?>
          <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50/50 transition-colors group">
            <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold shrink-0">
              <?= (int)$m['ThuTu'] ?>
            </span>
            <?php if ($m['Icon']): ?>
              <i class="fa-solid <?= e($m['Icon']) ?> text-brand-400 text-sm w-4"></i>
            <?php endif; ?>
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-sm text-gray-900 flex items-center gap-1.5">
                <?= e($m['TieuDe']) ?>
                <?php if (!$m['KichHoat']): ?>
                  <span class="text-[9px] px-1.5 py-0.5 rounded bg-red-100 text-red-700 font-bold">TẮT</span>
                <?php endif; ?>
                <?php if ($m['MoCuaSoMoi']): ?>
                  <i class="fa-solid fa-arrow-up-right-from-square text-gray-300 text-[10px]" title="Mở tab mới"></i>
                <?php endif; ?>
              </div>
              <div class="text-xs text-gray-500 truncate">→ <?= e($m['DuongDan']) ?></div>
            </div>
            <div class="flex gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
              <a href="?action=toggle&id=<?= (int)$m['MaMenu'] ?>"
                 class="w-8 h-8 rounded-lg <?= $m['KichHoat'] ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-700 hover:bg-red-200' ?> flex items-center justify-center"
                 title="<?= $m['KichHoat'] ? 'Tắt' : 'Bật' ?>">
                <i class="fa-solid fa-<?= $m['KichHoat'] ? 'toggle-on' : 'toggle-off' ?> text-xs"></i>
              </a>
              <a href="?action=edit&id=<?= (int)$m['MaMenu'] ?>"
                 class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 flex items-center justify-center" title="Sửa">
                <i class="fa-solid fa-pen text-xs"></i>
              </a>
              <a href="?action=delete&id=<?= (int)$m['MaMenu'] ?>" data-confirm="Xóa mục menu này?"
                 class="w-8 h-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 flex items-center justify-center" title="Xoá">
                <i class="fa-solid fa-trash text-xs"></i>
              </a>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Create modal -->
<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal">
    <div class="modal-head"><h3>Thêm mục menu</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create">
      <div class="modal-body">
        <?php $item = []; require __DIR__ . '/includes/menu-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-outline btn" data-modal-close="modal-create">Hủy</button>
        <button class="btn">Thêm</button>
      </div>
    </form>
  </div>
</div>

<?php if ($edit): ?>
<div class="modal-bg" style="display:flex">
  <div class="modal">
    <div class="modal-head">
      <h3>Sửa mục menu #<?= (int)$edit['MaMenu'] ?></h3>
      <a class="close" href="<?= url('admin/menus.php') ?>">&times;</a>
    </div>
    <form method="post" action="?action=update">
      <input type="hidden" name="id" value="<?= (int)$edit['MaMenu'] ?>">
      <div class="modal-body">
        <?php $item = $edit; require __DIR__ . '/includes/menu-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/menus.php') ?>">Hủy</a>
        <button class="btn">Lưu</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
