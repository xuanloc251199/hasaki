<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/ProductBLL.php';
require_once __DIR__ . '/../../src/Business/CategoryBLL.php';

require_admin();

$page_title = 'Quản lý sản phẩm';
$productBLL  = new ProductBLL();
$categoryBLL = new CategoryBLL();

$action = $_REQUEST['action'] ?? 'list';

// POST handlers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $existingImage = trim($_POST['HinhAnh_existing'] ?? '');
  $upload = handle_image_upload('HinhAnhFile', $existingImage ?: null);
  if (isset($upload['error'])) {
    set_flash_error($upload['error']);
    redirect(url('admin/products.php'));
  }
  $imagePath = $upload['path'] ?? trim($_POST['HinhAnh'] ?? '');
  if (!$imagePath) $imagePath = 'images/products/default.jpg';

  $data = [
    'MaDanhMuc'      => (int)($_POST['MaDanhMuc'] ?? 0),
    'TenSanPham'     => trim($_POST['TenSanPham'] ?? ''),
    'ThuongHieu'     => trim($_POST['ThuongHieu'] ?? '') ?: null,
    'XuatXu'         => trim($_POST['XuatXu'] ?? '') ?: null,
    'DungTich'       => trim($_POST['DungTich'] ?? '') ?: null,
    'LoaiDa'         => trim($_POST['LoaiDa'] ?? '') ?: null,
    'SoLuong'        => (int)($_POST['SoLuong'] ?? 0),
    'GiaBan'         => (float)($_POST['GiaBan'] ?? 0),
    'MauSac'         => trim($_POST['MauSac'] ?? '') ?: null,
    'KichCo'         => trim($_POST['KichCo'] ?? '') ?: null,
    'MoTa'           => trim($_POST['MoTa'] ?? '') ?: null,
    'ThanhPhan'      => trim($_POST['ThanhPhan'] ?? '') ?: null,
    'HuongDanSuDung' => trim($_POST['HuongDanSuDung'] ?? '') ?: null,
    'BaoQuan'        => trim($_POST['BaoQuan'] ?? '') ?: null,
    'HanSuDung'      => trim($_POST['HanSuDung'] ?? '') ?: null,
    'HinhAnh'        => $imagePath,
    'Sale'           => trim($_POST['Sale'] ?? '') ?: null,
  ];
  if ($action === 'create') {
    $res = $productBLL->create($data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã thêm sản phẩm');
  } elseif ($action === 'update') {
    $id = (int)$_POST['id'];
    $res = $productBLL->update($id, $data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã cập nhật sản phẩm');
  }
  redirect(url('admin/products.php'));
}

// Delete
if ($action === 'delete') {
  $productBLL->delete((int)$_GET['id']);
  set_flash_success('Đã xóa sản phẩm');
  redirect(url('admin/products.php'));
}

// Edit form
$editProduct = null;
if ($action === 'edit') {
  $editProduct = $productBLL->getById((int)$_GET['id']);
}

$keyword = trim($_GET['q'] ?? '');
$products = $keyword !== '' ? $productBLL->search($keyword) : $productBLL->getAll();
$categories = $categoryBLL->getAll();

require __DIR__ . '/includes/layout-top.php';
?>

<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm sản phẩm..." value="<?= e($keyword) ?>">
    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm sản phẩm</button>
</div>

<table class="table">
  <thead>
    <tr><th>#</th><th>Ảnh</th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Tồn</th><th>Sale</th><th></th></tr>
  </thead>
  <tbody>
    <?php if (empty($products)): ?>
      <tr><td colspan="8" class="text-center muted">Không có sản phẩm</td></tr>
    <?php else: foreach ($products as $p): ?>
      <tr>
        <td>#<?= (int)$p['MaSanPham'] ?></td>
        <td><img class="thumb" src="<?= asset(e($p['HinhAnh'])) ?>" onerror="this.src='https://placehold.co/60'"></td>
        <td><strong><?= e($p['TenSanPham']) ?></strong><div class="muted" style="font-size:12px"><?= e($p['KichCo'] ?? '') ?></div></td>
        <td><?= e($p['TenDanhMuc']) ?></td>
        <td style="font-weight:700;color:#ff5e8e"><?= format_currency($p['GiaBan']) ?></td>
        <td><?= (int)$p['SoLuong'] ?></td>
        <td><?php if (!empty($p['Sale'])): ?><span class="badge" style="background:#ff5e8e;color:#fff">-<?= e($p['Sale']) ?>%</span><?php endif; ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$p['MaSanPham'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$p['MaSanPham'] ?>" data-confirm="Xóa sản phẩm này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<!-- Create modal -->
<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal !max-w-3xl">
    <div class="modal-head"><h3>Thêm sản phẩm mới</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create" enctype="multipart/form-data">
      <div class="modal-body">
        <?php $product = []; require __DIR__ . '/includes/product-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-outline btn" data-modal-close="modal-create">Hủy</button>
        <button class="btn">Thêm sản phẩm</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit modal -->
<?php if ($editProduct): ?>
<div class="modal-bg" id="modal-edit" style="display:flex">
  <div class="modal !max-w-3xl">
    <div class="modal-head">
      <h3>Sửa sản phẩm #<?= (int)$editProduct['MaSanPham'] ?></h3>
      <a class="close" href="<?= url('admin/products.php') ?>">&times;</a>
    </div>
    <form method="post" action="?action=update" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= (int)$editProduct['MaSanPham'] ?>">
      <div class="modal-body">
        <?php $product = $editProduct; require __DIR__ . '/includes/product-form-fields.php'; ?>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/products.php') ?>">Hủy</a>
        <button class="btn">Lưu thay đổi</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
// Image preview
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
