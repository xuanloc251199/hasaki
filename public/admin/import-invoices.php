<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/ImportInvoiceBLL.php';
require_once __DIR__ . '/../../src/Business/ProductBLL.php';
require_once __DIR__ . '/../../src/DataAccess/EmployeeDAL.php';

require_admin();

$page_title = 'Quản lý đơn hàng nhập';
$bll = new ImportInvoiceBLL();
$action = $_REQUEST['action'] ?? 'list';

if (($action === 'create' || $action === 'update') && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $items = [];
  $maSP = $_POST['MaSanPham'] ?? [];
  $sl   = $_POST['SoLuong'] ?? [];
  $gia  = $_POST['GiaNhap'] ?? [];
  foreach ($maSP as $i => $id) {
    $items[] = [
      'MaSanPham' => $id,
      'SoLuong'   => $sl[$i] ?? 0,
      'GiaNhap'   => $gia[$i] ?? 0,
    ];
  }
  $info = [
    'TenNCC'        => $_POST['TenNCC'] ?? '',
    'SDT'           => $_POST['SDT'] ?? null,
    'Email'         => $_POST['Email'] ?? null,
    'DiaChiLayHang' => $_POST['DiaChiLayHang'] ?? null,
    'MaNV'          => $_POST['MaNV'] ?? null,
  ];

  if ($action === 'update') {
    $editId = (int)($_POST['id'] ?? 0);
    $res = $bll->updateImport($editId, $info, $items);
    if (isset($res['error'])) {
      set_flash_error($res['error']);
      redirect(url('admin/import-invoices.php?action=edit&id=' . $editId));
    }
    set_flash_success('Đã cập nhật phiếu nhập kho #' . $editId . ' và tồn kho');
  } else {
    $res = $bll->createImport($info, $items);
    if (isset($res['error'])) {
      set_flash_error($res['error']);
      redirect(url('admin/import-invoices.php?action=new'));
    }
    set_flash_success('Đã tạo phiếu nhập kho #' . $res['invoice_id'] . ' và cập nhật tồn kho');
  }
  redirect(url('admin/import-invoices.php'));
}

if ($action === 'delete') {
  $bll->delete((int)$_GET['id']);
  set_flash_success('Đã xóa hóa đơn nhập');
  redirect(url('admin/import-invoices.php'));
}

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $bll->search($keyword) : $bll->getAll();
$pg = paginate($rows, current_page(), 10);
$rows = $pg['items'];
$view = $action === 'view' ? $bll->getById((int)$_GET['id']) : null;
$details = $view ? $bll->getDetails((int)$view['MaHoaDon']) : [];

// Dữ liệu cho form thêm/sửa phiếu nhập
$isNew  = $action === 'new';
$editing = $action === 'edit' ? $bll->getById((int)$_GET['id']) : null;
$showForm = $isNew || $editing;
$editItems = $editing ? $bll->getDetails((int)$editing['MaHoaDon']) : [];
$products  = $showForm ? (new ProductBLL())->getAll() : [];
$employees = $showForm ? (new EmployeeDAL())->getAll() : [];
$currentEmp = $isNew ? (new EmployeeDAL())->getByAccount((int)($_SESSION['user_id'] ?? 0)) : null;
$selectedNV = $editing ? (int)($editing['MaNV'] ?? 0) : (int)($currentEmp['MaNV'] ?? 0);

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm nhà cung cấp / SĐT..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <div style="display:flex;align-items:center;gap:12px">
    <p class="muted" style="margin:0">Tổng: <strong><?= $pg['total'] ?></strong> hóa đơn</p>
    <a class="btn" href="?action=new"><i class="fa-solid fa-plus"></i> Tạo phiếu nhập</a>
  </div>
</div>

<table class="table">
  <thead><tr><th>Mã</th><th>Nhà cung cấp</th><th>SĐT</th><th>NV nhập</th><th>Ngày nhập</th><th>Thành tiền</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="7" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaHoaDon'] ?></td>
        <td><strong><?= e($r['TenNCC']) ?></strong></td>
        <td><?= e($r['SDT']) ?></td>
        <td><?= e($r['TenNV'] ?? '—') ?></td>
        <td><?= format_datetime($r['NgayNhap']) ?></td>
        <td style="font-weight:700;color:#ff5e8e"><?= format_currency($r['ThanhTien']) ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=view&id=<?= (int)$r['MaHoaDon'] ?>"><i class="fa-solid fa-eye"></i></a>
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$r['MaHoaDon'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaHoaDon'] ?>" data-confirm="Xóa hóa đơn này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?= render_pagination($pg['page'], $pg['total_pages'], $keyword !== '' ? ['q' => $keyword] : []) ?>

<?php if ($showForm):
  // Các dòng sản phẩm cần render: phiếu đang sửa dùng chi tiết cũ, phiếu mới dùng 1 dòng trống
  $formRows = $editing ? $editItems : [['MaSanPham' => 0, 'SoLuong' => 1, 'GiaNhap' => 0]];
?>
<div class="modal-bg" style="display:flex">
  <div class="modal" style="max-width:860px">
    <div class="modal-head"><h3><?= $editing ? 'Sửa phiếu nhập kho #' . (int)$editing['MaHoaDon'] : 'Tạo phiếu nhập kho' ?></h3><a class="close" href="<?= url('admin/import-invoices.php') ?>">&times;</a></div>
    <form method="post" action="?action=<?= $editing ? 'update' : 'create' ?>" id="import-form">
      <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['MaHoaDon'] ?>"><?php endif; ?>
      <div class="modal-body">
        <div class="row">
          <div class="col form-group"><label>Nhà cung cấp *</label><input name="TenNCC" required placeholder="Tên nhà cung cấp" value="<?= e($editing['TenNCC'] ?? '') ?>"></div>
          <div class="col form-group"><label>Số điện thoại</label><input name="SDT" placeholder="SĐT NCC" value="<?= e($editing['SDT'] ?? '') ?>"></div>
        </div>
        <div class="row">
          <div class="col form-group"><label>Email</label><input name="Email" type="email" placeholder="Email NCC" value="<?= e($editing['Email'] ?? '') ?>"></div>
          <div class="col form-group">
            <label>Nhân viên nhập</label>
            <select name="MaNV">
              <option value="">— Không chọn —</option>
              <?php foreach ($employees as $emp): ?>
                <option value="<?= (int)$emp['MaNV'] ?>" <?= $selectedNV === (int)$emp['MaNV'] ? 'selected' : '' ?>>
                  <?= e($emp['TenNV']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group"><label>Địa chỉ lấy hàng</label><input name="DiaChiLayHang" placeholder="Địa chỉ kho NCC" value="<?= e($editing['DiaChiLayHang'] ?? '') ?>"></div>

        <h4 style="margin:16px 0 8px">Chi tiết sản phẩm nhập</h4>
        <table class="table" id="items-table">
          <thead><tr><th style="width:45%">Sản phẩm</th><th>Số lượng</th><th>Giá nhập (đ)</th><th>Thành tiền</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($formRows as $fr): ?>
            <tr class="item-row">
              <td>
                <select name="MaSanPham[]" class="sp-select" required>
                  <option value="">— Chọn sản phẩm —</option>
                  <?php foreach ($products as $p): ?>
                    <option value="<?= (int)$p['MaSanPham'] ?>" <?= (int)$fr['MaSanPham'] === (int)$p['MaSanPham'] ? 'selected' : '' ?>><?= e($p['TenSanPham']) ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td><input name="SoLuong[]" type="number" min="1" value="<?= (int)($fr['SoLuong'] ?? 1) ?>" class="sp-qty" style="width:90px"></td>
              <td><input name="GiaNhap[]" type="number" min="0" step="1000" value="<?= (int)($fr['GiaNhap'] ?? 0) ?>" class="sp-price" style="width:130px"></td>
              <td class="sp-line muted">0đ</td>
              <td><button type="button" class="btn-danger btn btn-sm row-del"><i class="fa-solid fa-xmark"></i></button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <button type="button" class="btn-outline btn btn-sm" id="add-row"><i class="fa-solid fa-plus"></i> Thêm dòng</button>

        <div style="text-align:right;margin-top:16px;font-size:18px;font-weight:800">
          Tổng cộng: <span id="grand-total" style="color:#ff5e8e">0đ</span>
        </div>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/import-invoices.php') ?>">Hủy</a>
        <button class="btn"><i class="fa-solid fa-floppy-disk"></i> Lưu phiếu nhập</button>
      </div>
    </form>
  </div>
</div>

<script>
(function () {
  var table = document.querySelector('#items-table tbody');
  var fmt = function (n) { return n.toLocaleString('vi-VN') + 'đ'; };

  function recalc() {
    var total = 0;
    table.querySelectorAll('.item-row').forEach(function (row) {
      var qty = parseFloat(row.querySelector('.sp-qty').value) || 0;
      var price = parseFloat(row.querySelector('.sp-price').value) || 0;
      var line = qty * price;
      total += line;
      row.querySelector('.sp-line').textContent = fmt(line);
    });
    document.getElementById('grand-total').textContent = fmt(total);
  }

  table.addEventListener('input', recalc);
  table.addEventListener('click', function (e) {
    var btn = e.target.closest('.row-del');
    if (btn && table.querySelectorAll('.item-row').length > 1) {
      btn.closest('.item-row').remove();
      recalc();
    }
  });

  document.getElementById('add-row').addEventListener('click', function () {
    var first = table.querySelector('.item-row');
    var clone = first.cloneNode(true);
    clone.querySelector('.sp-select').value = '';
    clone.querySelector('.sp-qty').value = 1;
    clone.querySelector('.sp-price').value = 0;
    clone.querySelector('.sp-line').textContent = '0đ';
    table.appendChild(clone);
  });

  recalc();
})();
</script>
<?php endif; ?>

<?php if ($view): ?>
<div class="modal-bg" style="display:flex">
  <div class="modal" style="max-width:680px">
    <div class="modal-head"><h3>Hóa đơn nhập #<?= (int)$view['MaHoaDon'] ?></h3><a class="close" href="<?= url('admin/import-invoices.php') ?>">&times;</a></div>
    <div class="modal-body">
      <div class="row">
        <div class="col"><strong>Nhà cung cấp:</strong> <?= e($view['TenNCC']) ?></div>
        <div class="col"><strong>SĐT:</strong> <?= e($view['SDT']) ?></div>
      </div>
      <div class="row">
        <div class="col"><strong>Email:</strong> <?= e($view['Email']) ?></div>
        <div class="col"><strong>Ngày nhập:</strong> <?= format_datetime($view['NgayNhap']) ?></div>
      </div>
      <table class="table mt-2">
        <thead><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>
        <tbody>
          <?php foreach ($details as $d): ?>
            <tr>
              <td><?= e($d['TenSanPham']) ?></td>
              <td><?= (int)$d['SoLuong'] ?></td>
              <td><?= format_currency($d['GiaNhap']) ?></td>
              <td style="font-weight:700"><?= format_currency($d['GiaNhap'] * $d['SoLuong']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($details)): ?>
            <tr><td colspan="4" class="text-center muted">Không có chi tiết</td></tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr><td colspan="3" class="text-right"><strong>Tổng cộng</strong></td><td style="font-weight:800;color:#ff5e8e"><?= format_currency($view['ThanhTien']) ?></td></tr>
        </tfoot>
      </table>
    </div>
    <div class="modal-foot"><a class="btn-outline btn" href="<?= url('admin/import-invoices.php') ?>">Đóng</a></div>
  </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
