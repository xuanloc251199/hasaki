<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/DataAccess/WarehouseDAL.php';

require_admin();

$page_title = 'Quản lý kho hàng';
$dal = new WarehouseDAL();
$action = $_REQUEST['action'] ?? 'list';

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $dal->update((int)$_POST['id'], [
    'TenSanPham'  => trim($_POST['TenSanPham']),
    'SoLuongCon'  => (int)$_POST['SoLuongCon'],
    'NgayNhap'    => $_POST['NgayNhap'] ?: date('Y-m-d'),
    'LoaiSanPham' => trim($_POST['LoaiSanPham'] ?? ''),
  ]);
  set_flash_success('Đã cập nhật kho');
  redirect(url('admin/warehouse.php'));
}
if ($action === 'delete') {
  $dal->delete((int)$_GET['id']);
  set_flash_success('Đã xóa');
  redirect(url('admin/warehouse.php'));
}

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $dal->search($keyword) : $dal->getAll();
$edit = $action === 'edit' ? $dal->getById((int)$_GET['id']) : null;

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm kho hàng..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <p class="muted" style="margin:0">Tổng: <strong><?= count($rows) ?></strong> mục</p>
</div>

<table class="table">
  <thead><tr><th>#</th><th>Ảnh</th><th>Tên sản phẩm</th><th>Số lượng còn</th><th>Loại</th><th>Ngày nhập</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="7" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaKho'] ?></td>
        <td><img class="thumb" src="<?= asset(e($r['HinhAnh'])) ?>" onerror="this.src='https://placehold.co/60'"></td>
        <td><strong><?= e($r['TenSanPham']) ?></strong></td>
        <td><span class="badge"><?= (int)$r['SoLuongCon'] ?></span></td>
        <td><?= e($r['LoaiSanPham']) ?></td>
        <td><?= format_date($r['NgayNhap']) ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$r['MaKho'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaKho'] ?>" data-confirm="Xóa khỏi kho?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php if ($edit): ?>
<div class="modal-bg" style="display:flex">
  <div class="modal">
    <div class="modal-head"><h3>Sửa kho hàng</h3><a class="close" href="<?= url('admin/warehouse.php') ?>">&times;</a></div>
    <form method="post" action="?action=update">
      <input type="hidden" name="id" value="<?= (int)$edit['MaKho'] ?>">
      <div class="modal-body">
        <div class="form-group"><label>Tên sản phẩm</label><input name="TenSanPham" value="<?= e($edit['TenSanPham']) ?>"></div>
        <div class="row">
          <div class="col form-group"><label>Số lượng còn</label><input name="SoLuongCon" type="number" value="<?= (int)$edit['SoLuongCon'] ?>"></div>
          <div class="col form-group"><label>Ngày nhập</label><input name="NgayNhap" type="date" value="<?= e($edit['NgayNhap']) ?>"></div>
        </div>
        <div class="form-group"><label>Loại sản phẩm</label><input name="LoaiSanPham" value="<?= e($edit['LoaiSanPham']) ?>"></div>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/warehouse.php') ?>">Hủy</a>
        <button class="btn">Lưu</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
