<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/AccountBLL.php';

require_admin();

$page_title = 'Quản lý tài khoản';
$bll = new AccountBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($action === 'create') {
    $res = $bll->create(trim($_POST['TenTaiKhoan']), $_POST['MatKhau'], (int)$_POST['LoaiTaiKhoan']);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã thêm tài khoản');
  } elseif ($action === 'reset') {
    $res = $bll->resetPassword((int)$_POST['id'], $_POST['MatKhau']);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã đặt lại mật khẩu');
  }
  redirect(url('admin/accounts.php'));
}
if ($action === 'lock')   { $bll->lock((int)$_GET['id']); set_flash_success('Đã khóa'); redirect(url('admin/accounts.php')); }
if ($action === 'unlock') { $bll->unlock((int)$_GET['id']); set_flash_success('Đã mở khóa'); redirect(url('admin/accounts.php')); }
if ($action === 'delete') { $bll->delete((int)$_GET['id']); set_flash_success('Đã xóa'); redirect(url('admin/accounts.php')); }

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $bll->search($keyword) : $bll->getAll();

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm tài khoản..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm tài khoản</button>
</div>

<table class="table">
  <thead><tr><th>#</th><th>Tên đăng nhập</th><th>Loại</th><th>Trạng thái</th><th>Ngày tạo</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="6" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaTaiKhoan'] ?></td>
        <td><strong><?= e($r['TenTaiKhoan']) ?></strong></td>
        <td><?= e($r['TenLoaiTaiKhoan']) ?></td>
        <td>
          <?php if ((int)$r['TrangThai'] === 1): ?>
            <span class="badge badge-status-2">Hoạt động</span>
          <?php else: ?>
            <span class="badge badge-status-3">Đã khóa</span>
          <?php endif; ?>
        </td>
        <td><?= format_datetime($r['NgayTao']) ?></td>
        <td class="actions">
          <?php if ((int)$r['TrangThai'] === 1): ?>
            <a class="btn-outline btn btn-sm" href="?action=lock&id=<?= (int)$r['MaTaiKhoan'] ?>" data-confirm="Khóa tài khoản này?"><i class="fa-solid fa-lock"></i></a>
          <?php else: ?>
            <a class="btn-outline btn btn-sm" href="?action=unlock&id=<?= (int)$r['MaTaiKhoan'] ?>"><i class="fa-solid fa-unlock"></i></a>
          <?php endif; ?>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaTaiKhoan'] ?>" data-confirm="Xóa tài khoản này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal">
    <div class="modal-head"><h3>Thêm tài khoản</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create">
      <div class="modal-body">
        <div class="form-group"><label>Tên đăng nhập *</label><input name="TenTaiKhoan" required minlength="4"></div>
        <div class="form-group"><label>Mật khẩu *</label><input name="MatKhau" type="password" required minlength="6"></div>
        <div class="form-group">
          <label>Loại tài khoản</label>
          <select name="LoaiTaiKhoan">
            <option value="2">Nhân viên</option>
            <option value="1">Admin</option>
            <option value="3">Khách hàng</option>
          </select>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-outline btn" data-modal-close="modal-create">Hủy</button>
        <button class="btn">Thêm</button>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
