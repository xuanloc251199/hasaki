<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/CategoryBLL.php';

require_admin();

$page_title = 'Quản lý danh mục';
$bll = new CategoryBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $existingImage = trim($_POST['AnhDanhMuc_existing'] ?? '');
  $upload = handle_image_upload('AnhDanhMucFile', $existingImage ?: null);
  if (isset($upload['error'])) {
    set_flash_error($upload['error']);
    redirect(url('admin/categories.php'));
  }
  $imagePath = $upload['path'] ?? trim($_POST['AnhDanhMuc'] ?? '');

  $data = [
    'TenDanhMuc' => trim($_POST['TenDanhMuc'] ?? ''),
    'MoTa'       => trim($_POST['MoTa'] ?? ''),
    'AnhDanhMuc' => $imagePath ?: null,
  ];
  if ($action === 'create') {
    $res = $bll->create($data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã thêm danh mục');
  } elseif ($action === 'update') {
    $res = $bll->update((int)$_POST['id'], $data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã cập nhật');
  }
  redirect(url('admin/categories.php'));
}
if ($action === 'delete') {
  try {
    $bll->delete((int)$_GET['id']);
    set_flash_success('Đã xóa danh mục');
  } catch (\Throwable $e) {
    set_flash_error('Không thể xóa: danh mục đang chứa sản phẩm');
  }
  redirect(url('admin/categories.php'));
}

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $bll->search($keyword) : $bll->getAll();
$pg = paginate($rows, current_page(), 10);
$rows = $pg['items'];
$edit = $action === 'edit' ? $bll->getById((int)$_GET['id']) : null;

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm danh mục..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm danh mục</button>
</div>

<table class="table">
  <thead><tr><th>#</th><th>Ảnh</th><th>Tên danh mục</th><th>Mô tả</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="5" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaDanhMuc'] ?></td>
        <td>
          <?php if (!empty($r['AnhDanhMuc'])): ?>
            <img class="thumb" src="<?= asset(e($r['AnhDanhMuc'])) ?>" onerror="this.src='https://placehold.co/60'">
          <?php else: ?>
            <div class="thumb" style="display:flex;align-items:center;justify-content:center;color:#bbb"><i class="fa-regular fa-image"></i></div>
          <?php endif; ?>
        </td>
        <td><strong><?= e($r['TenDanhMuc']) ?></strong></td>
        <td class="muted"><?= e($r['MoTa']) ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$r['MaDanhMuc'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaDanhMuc'] ?>" data-confirm="Xóa danh mục này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?= render_pagination($pg['page'], $pg['total_pages'], $keyword !== '' ? ['q' => $keyword] : []) ?>

<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal">
    <div class="modal-head"><h3>Thêm danh mục</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="form-group"><label>Tên danh mục *</label><input type="text" name="TenDanhMuc" required></div>
        <div class="form-group"><label>Mô tả</label><textarea name="MoTa"></textarea></div>
        <div class="form-group">
          <label>Ảnh danh mục</label>
          <div class="image-uploader">
            <div class="preview" data-preview><i class="fa-regular fa-image"></i></div>
            <div class="uploader-fields">
              <input type="file" name="AnhDanhMucFile" accept="image/*" data-image-input>
              <small class="muted" style="display:block;margin-top:4px">JPG / PNG / WEBP - tối đa 5MB</small>
            </div>
          </div>
        </div>
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
    <div class="modal-head"><h3>Sửa danh mục</h3><a class="close" href="<?= url('admin/categories.php') ?>">&times;</a></div>
    <form method="post" action="?action=update" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= (int)$edit['MaDanhMuc'] ?>">
      <input type="hidden" name="AnhDanhMuc_existing" value="<?= e($edit['AnhDanhMuc']) ?>">
      <div class="modal-body">
        <div class="form-group"><label>Tên danh mục *</label><input type="text" name="TenDanhMuc" required value="<?= e($edit['TenDanhMuc']) ?>"></div>
        <div class="form-group"><label>Mô tả</label><textarea name="MoTa"><?= e($edit['MoTa']) ?></textarea></div>
        <div class="form-group">
          <label>Ảnh danh mục</label>
          <div class="image-uploader">
            <div class="preview" data-preview>
              <?php if (!empty($edit['AnhDanhMuc'])): ?>
                <img src="<?= asset(e($edit['AnhDanhMuc'])) ?>" onerror="this.style.display='none';this.parentNode.innerHTML='<i class=&quot;fa-regular fa-image&quot;></i>'">
              <?php else: ?>
                <i class="fa-regular fa-image"></i>
              <?php endif; ?>
            </div>
            <div class="uploader-fields">
              <input type="file" name="AnhDanhMucFile" accept="image/*" data-image-input>
              <small class="muted" style="display:block;margin-top:6px">Để trống nếu giữ nguyên ảnh hiện tại</small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/categories.php') ?>">Hủy</a>
        <button class="btn">Lưu</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
document.querySelectorAll('[data-image-input]').forEach(input => {
  input.addEventListener('change', e => {
    const file = e.target.files[0];
    const preview = input.closest('.image-uploader').querySelector('[data-preview]');
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => { preview.innerHTML = '<img src="' + ev.target.result + '">'; };
      reader.readAsDataURL(file);
    }
  });
});
</script>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
