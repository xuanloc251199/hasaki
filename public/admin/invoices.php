<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/InvoiceBLL.php';

require_admin();

$page_title = 'Quản lý đơn hàng bán';
$bll = new InvoiceBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $bll->updateStatus((int)$_POST['id'], (int)$_POST['status']);
  set_flash_success('Đã cập nhật trạng thái');
  redirect(url('admin/invoices.php'));
}
if ($action === 'delete') {
  $bll->delete((int)$_GET['id']);
  set_flash_success('Đã xóa đơn hàng');
  redirect(url('admin/invoices.php'));
}

$view = $action === 'view' ? $bll->getById((int)$_GET['id']) : null;
$details = $view ? $bll->getDetails((int)$view['MaHoaDon']) : [];

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $bll->search($keyword) : $bll->getAll();

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Mã đơn / SĐT / Tên KH..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
</div>

<table class="table">
  <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>SĐT</th><th>Ngày đặt</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="8" class="text-center muted">Không có đơn hàng</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaHoaDon'] ?></td>
        <td><?= e($r['TenKhachHang'] ?? '—') ?></td>
        <td><?= e($r['SDT']) ?></td>
        <td><?= format_datetime($r['NgayBan']) ?></td>
        <td style="font-weight:700;color:#ff5e8e"><?= format_currency($r['ThanhTien']) ?></td>
        <td><?= e($r['HinhThucTT']) ?></td>
        <td><span class="badge badge-status-<?= (int)$r['TrangThai'] ?>"><?= e($bll->statusLabel((int)$r['TrangThai'])) ?></span></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=view&id=<?= (int)$r['MaHoaDon'] ?>"><i class="fa-solid fa-eye"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaHoaDon'] ?>" data-confirm="Xóa đơn hàng này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php if ($view): ?>
<div class="modal-bg" style="display:flex">
  <div class="modal" style="max-width:720px">
    <div class="modal-head"><h3>Đơn hàng #<?= (int)$view['MaHoaDon'] ?></h3><a class="close" href="<?= url('admin/invoices.php') ?>">&times;</a></div>
    <div class="modal-body">
      <div class="row">
        <div class="col"><strong>Khách hàng:</strong> <?= e($view['TenKhachHang'] ?? '—') ?></div>
        <div class="col"><strong>SĐT:</strong> <?= e($view['SDT']) ?></div>
      </div>
      <div class="row">
        <div class="col"><strong>Email:</strong> <?= e($view['Email']) ?></div>
        <div class="col"><strong>Ngày đặt:</strong> <?= format_datetime($view['NgayBan']) ?></div>
      </div>
      <div class="form-group mt-2"><strong>Địa chỉ giao hàng:</strong> <?= e($view['DiaChiGH']) ?></div>
      <?php if (!empty($view['GhiChu'])): ?>
        <div class="form-group"><strong>Ghi chú:</strong> <?= e($view['GhiChu']) ?></div>
      <?php endif; ?>
      <table class="table mt-2">
        <thead><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
        <tbody>
          <?php foreach ($details as $d): ?>
            <tr>
              <td><?= e($d['TenSanPham']) ?></td>
              <td><?= (int)$d['SoLuong'] ?></td>
              <td><?= format_currency($d['GiaBan']) ?></td>
              <td style="font-weight:700"><?= format_currency($d['GiaBan'] * $d['SoLuong']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr><td colspan="3" class="text-right"><strong>Tổng cộng</strong></td><td style="font-weight:800;color:#ff5e8e"><?= format_currency($view['ThanhTien']) ?></td></tr>
        </tfoot>
      </table>

      <form method="post" action="?action=update_status" class="mt-2 flex-row">
        <input type="hidden" name="id" value="<?= (int)$view['MaHoaDon'] ?>">
        <label style="margin:0">Cập nhật trạng thái:</label>
        <select name="status" style="max-width:200px">
          <?php for ($i = 0; $i <= 3; $i++): ?>
            <option value="<?= $i ?>" <?= (int)$view['TrangThai'] === $i ? 'selected' : '' ?>><?= e($bll->statusLabel($i)) ?></option>
          <?php endfor; ?>
        </select>
        <button class="btn">Cập nhật</button>
      </form>
    </div>
    <div class="modal-foot">
      <a class="btn-outline btn" href="<?= url('admin/invoices.php') ?>">Đóng</a>
      <a class="btn-outline btn" href="<?= url('invoice-print.php?id=' . (int)$view['MaHoaDon'] . '&download=1') ?>"><i class="fa-solid fa-download"></i> Tải đơn</a>
      <a class="btn" href="<?= url('invoice-print.php?id=' . (int)$view['MaHoaDon'] . '&print=1') ?>" target="_blank"><i class="fa-solid fa-print"></i> In hóa đơn</a>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
