<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/CustomerBLL.php';

require_admin();

$page_title = 'Quản lý khách hàng';
$bll = new CustomerBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = [
    'TenKhachHang' => trim($_POST['TenKhachHang'] ?? ''),
    'DiaChi'       => trim($_POST['DiaChi'] ?? ''),
    'SDT'          => trim($_POST['SDT'] ?? ''),
    'Email'        => trim($_POST['Email'] ?? ''),
    'GioiTinh'     => $_POST['GioiTinh'] ?? 'Nu',
  ];
  if ($action === 'create') {
    $res = $bll->create($data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã thêm khách hàng');
  } elseif ($action === 'update') {
    $res = $bll->update((int)$_POST['id'], $data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã cập nhật');
  }
  redirect(url('admin/customers.php'));
}
if ($action === 'delete') {
  try {
    $bll->delete((int)$_GET['id']);
    set_flash_success('Đã xóa');
  } catch (\Throwable $e) {
    set_flash_error('Không thể xóa: khách hàng có đơn hàng');
  }
  redirect(url('admin/customers.php'));
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
    <input type="text" name="q" placeholder="Tìm khách hàng..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm khách hàng</button>
</div>

<table class="table">
  <thead><tr><th>#</th><th>Họ tên</th><th>SĐT</th><th>Email</th><th>Địa chỉ</th><th>Giới tính</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="7" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaKhachHang'] ?></td>
        <td><strong><?= e($r['TenKhachHang']) ?></strong></td>
        <td><?= e($r['SDT']) ?></td>
        <td><?= e($r['Email']) ?></td>
        <td class="muted"><?= e($r['DiaChi']) ?></td>
        <td><?= e($r['GioiTinh']) ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$r['MaKhachHang'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaKhachHang'] ?>" data-confirm="Xóa khách hàng này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?= render_pagination($pg['page'], $pg['total_pages'], $keyword !== '' ? ['q' => $keyword] : []) ?>

<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal">
    <div class="modal-head"><h3>Thêm khách hàng</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create">
      <div class="modal-body">
        <div class="form-group"><label>Họ tên *</label><input name="TenKhachHang" required></div>
        <div class="row">
          <div class="col form-group"><label>SĐT</label><input name="SDT"></div>
          <div class="col form-group"><label>Email</label><input name="Email" type="email"></div>
        </div>
        <div class="form-group"><label>Địa chỉ</label><input name="DiaChi"></div>
        <div class="form-group">
          <label>Giới tính</label>
          <select name="GioiTinh"><option value="Nu">Nữ</option><option value="Nam">Nam</option></select>
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
    <div class="modal-head"><h3>Sửa khách hàng</h3><a class="close" href="<?= url('admin/customers.php') ?>">&times;</a></div>
    <form method="post" action="?action=update">
      <input type="hidden" name="id" value="<?= (int)$edit['MaKhachHang'] ?>">
      <div class="modal-body">
        <div class="form-group"><label>Họ tên *</label><input name="TenKhachHang" required value="<?= e($edit['TenKhachHang']) ?>"></div>
        <div class="row">
          <div class="col form-group"><label>SĐT</label><input name="SDT" value="<?= e($edit['SDT']) ?>"></div>
          <div class="col form-group"><label>Email</label><input name="Email" value="<?= e($edit['Email']) ?>"></div>
        </div>
        <div class="form-group"><label>Địa chỉ</label><input name="DiaChi" value="<?= e($edit['DiaChi']) ?>"></div>
        <div class="form-group">
          <label>Giới tính</label>
          <select name="GioiTinh">
            <option value="Nu" <?= $edit['GioiTinh'] === 'Nu' ? 'selected' : '' ?>>Nữ</option>
            <option value="Nam" <?= $edit['GioiTinh'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
          </select>
        </div>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/customers.php') ?>">Hủy</a>
        <button class="btn">Lưu</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
