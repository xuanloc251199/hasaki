<?php
/**
 * Trang in hóa đơn (dùng chung cho khách hàng & admin/nhân viên).
 *
 * Phân quyền:
 *   - Admin / nhân viên: in được mọi hóa đơn.
 *   - Khách hàng: chỉ in được hóa đơn của chính mình.
 *
 * Trang render HTML tối giản, có @media print và tự bung hộp thoại in.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/InvoiceBLL.php';
require_once __DIR__ . '/../src/Business/CustomerBLL.php';

require_login();

$id = (int)($_GET['id'] ?? 0);
$invoiceBLL = new InvoiceBLL();
$invoice = $invoiceBLL->getById($id);
if (!$invoice) {
  http_response_code(404);
  exit('Không tìm thấy hóa đơn.');
}

// Kiểm tra quyền: khách chỉ được xem hóa đơn của mình
if (!is_employee()) {
  $customer = (new CustomerBLL())->getByAccount((int)$_SESSION['user_id']);
  if (!$customer || (int)$customer['MaKhachHang'] !== (int)$invoice['MaKhachHang']) {
    http_response_code(403);
    exit('Bạn không có quyền xem hóa đơn này.');
  }
}

$details = $invoiceBLL->getDetails($id);

// Chế độ tải file: xuất hóa đơn dưới dạng file HTML đính kèm
$download = ($_GET['download'] ?? '') === '1';
if ($download) {
  header('Content-Type: text/html; charset=UTF-8');
  header('Content-Disposition: attachment; filename="hoa-don-' . (int)$invoice['MaHoaDon'] . '.html"');
}

// Thông tin cửa hàng (lấy từ Settings)
$shopName    = setting('site_name', SITE_NAME);
$shopAddress = setting('contact_address', '');
$shopPhone   = setting('contact_phone', '');
$shopEmail   = setting('contact_email', '');

$subtotal = 0;
foreach ($details as $d) {
  $subtotal += (float)$d['GiaBan'] * (int)$d['SoLuong'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hóa đơn #<?= (int)$invoice['MaHoaDon'] ?> - <?= e($shopName) ?></title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      color: #1f2937;
      background: #f3f4f6;
      font-size: 14px;
      line-height: 1.5;
      padding: 24px;
    }
    .sheet {
      max-width: 760px;
      margin: 0 auto;
      background: #fff;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0,0,0,.06);
    }
    .toolbar {
      max-width: 760px;
      margin: 0 auto 16px;
      display: flex;
      gap: 8px;
      justify-content: flex-end;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 18px;
      border-radius: 999px;
      border: 0;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
    }
    .btn-print { background: #ff5e8e; color: #fff; }
    .btn-download { background: #fff0f3; color: #be185d; }
    .btn-back  { background: #e5e7eb; color: #374151; }
    .head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px solid #ff5e8e;
      padding-bottom: 20px;
      margin-bottom: 24px;
    }
    .brand { font-size: 28px; font-weight: 800; color: #ff5e8e; letter-spacing: .5px; }
    .brand-sub { font-size: 12px; color: #6b7280; margin-top: 4px; max-width: 280px; }
    .doc-title { text-align: right; }
    .doc-title h1 { font-size: 22px; color: #111827; text-transform: uppercase; letter-spacing: 1px; }
    .doc-title .no { font-size: 14px; color: #6b7280; margin-top: 4px; }
    .meta {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }
    .meta h3 {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .5px;
      color: #9ca3af;
      margin-bottom: 6px;
    }
    .meta p { margin: 2px 0; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead th {
      background: #fff0f3;
      text-align: left;
      padding: 10px 12px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .3px;
      color: #be185d;
    }
    thead th.num, tbody td.num { text-align: right; }
    tbody td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; }
    tbody td .variant { font-size: 12px; color: #9ca3af; }
    .totals { margin-left: auto; width: 280px; }
    .totals .row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
    }
    .totals .grand {
      border-top: 2px solid #e5e7eb;
      margin-top: 6px;
      padding-top: 12px;
      font-size: 18px;
      font-weight: 800;
    }
    .totals .grand span:last-child { color: #ff5e8e; }
    .foot {
      margin-top: 32px;
      padding-top: 20px;
      border-top: 1px dashed #d1d5db;
      text-align: center;
      color: #6b7280;
      font-size: 13px;
    }
    .badge {
      display: inline-block;
      padding: 2px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      background: #f3f4f6;
      color: #374151;
    }
    @media print {
      body { background: #fff; padding: 0; font-size: 12px; }
      .sheet { box-shadow: none; max-width: none; padding: 0; border-radius: 0; }
      .toolbar { display: none; }
      thead th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
  </style>
</head>
<body>
  <?php if (!$download): ?>
  <div class="toolbar">
    <button class="btn btn-print" onclick="window.print()">🖨️ In hóa đơn</button>
    <a class="btn btn-download" href="<?= url('invoice-print.php?id=' . (int)$invoice['MaHoaDon'] . '&download=1') ?>">⬇️ Tải đơn</a>
    <a class="btn btn-back" href="<?= is_employee() ? url('admin/invoices.php') : url('account.php') ?>">← Quay lại</a>
  </div>
  <?php endif; ?>

  <div class="sheet">
    <div class="head">
      <div>
        <div class="brand"><?= e($shopName) ?></div>
        <?php if ($shopAddress): ?><div class="brand-sub"><?= e($shopAddress) ?></div><?php endif; ?>
        <?php if ($shopPhone): ?><div class="brand-sub">Hotline: <?= e($shopPhone) ?></div><?php endif; ?>
        <?php if ($shopEmail): ?><div class="brand-sub">Email: <?= e($shopEmail) ?></div><?php endif; ?>
      </div>
      <div class="doc-title">
        <h1>Hóa đơn</h1>
        <div class="no">Số: #<?= (int)$invoice['MaHoaDon'] ?></div>
        <div class="no"><?= format_datetime($invoice['NgayBan']) ?></div>
      </div>
    </div>

    <div class="meta">
      <div>
        <h3>Khách hàng</h3>
        <p><strong><?= e($invoice['TenKhachHang'] ?? '—') ?></strong></p>
        <?php if (!empty($invoice['SDT'])): ?><p>SĐT: <?= e($invoice['SDT']) ?></p><?php endif; ?>
        <?php if (!empty($invoice['Email'])): ?><p>Email: <?= e($invoice['Email']) ?></p><?php endif; ?>
        <p>Địa chỉ giao: <?= e($invoice['DiaChiGH']) ?></p>
      </div>
      <div>
        <h3>Thông tin đơn hàng</h3>
        <p>Thanh toán: <strong><?= e($invoice['HinhThucTT']) ?></strong></p>
        <p>Trạng thái: <span class="badge"><?= e($invoiceBLL->statusLabel((int)$invoice['TrangThai'])) ?></span></p>
        <?php if (!empty($invoice['GhiChu'])): ?><p>Ghi chú: <?= e($invoice['GhiChu']) ?></p><?php endif; ?>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th style="width:36px">#</th>
          <th>Sản phẩm</th>
          <th class="num">Đơn giá</th>
          <th class="num">SL</th>
          <th class="num">Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($details as $i => $d): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>
              <?= e($d['TenSanPham']) ?>
              <?php
                $variant = array_filter([$d['KichCo'] ?? null, $d['MauSac'] ?? null]);
                if ($variant): ?>
                <div class="variant"><?= e(implode(' · ', $variant)) ?></div>
              <?php endif; ?>
            </td>
            <td class="num"><?= format_currency($d['GiaBan']) ?></td>
            <td class="num"><?= (int)$d['SoLuong'] ?></td>
            <td class="num"><strong><?= format_currency($d['GiaBan'] * $d['SoLuong']) ?></strong></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="totals">
      <div class="row"><span>Tạm tính</span><span><?= format_currency($subtotal) ?></span></div>
      <div class="row"><span>Phí vận chuyển</span><span>Miễn phí</span></div>
      <div class="row grand"><span>Tổng cộng</span><span><?= format_currency($invoice['ThanhTien']) ?></span></div>
    </div>

    <div class="foot">
      Cảm ơn quý khách đã mua hàng tại <?= e($shopName) ?>!<br>
      Hóa đơn được tạo tự động từ hệ thống, có giá trị xác nhận đơn hàng.
    </div>
  </div>

  <script>
    // Tự bung hộp thoại in khi mở với ?print=1
    if (new URLSearchParams(location.search).get('print') === '1') {
      window.addEventListener('load', function () { window.print(); });
    }
  </script>
</body>
</html>
