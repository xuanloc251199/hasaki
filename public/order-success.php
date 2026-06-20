<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/InvoiceBLL.php';

$id = (int)($_GET['id'] ?? 0);
$invoiceBLL = new InvoiceBLL();
$invoice = $invoiceBLL->getById($id);

// Members may view orders (as before); guests may only view an order they just
// placed in this session, not arbitrary order ids.
$isOwnGuestOrder = in_array($id, $_SESSION['guest_orders'] ?? [], true);
if (!$invoice || (!is_logged_in() && !$isOwnGuestOrder)) {
  redirect(url('index.php'));
}
$details = $invoiceBLL->getDetails($id);

$page_title = 'Đặt hàng thành công - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>
<div class="container" style="padding:48px 16px;max-width:780px">
  <div style="background:#fff;border-radius:14px;padding:48px 32px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.05)">
    <div style="font-size:60px;color:#2ecc71"><i class="fa-solid fa-circle-check"></i></div>
    <h1>Đặt hàng thành công!</h1>
    <p class="muted">Cảm ơn bạn đã mua hàng tại Hasaki. Đơn hàng của bạn đang được xử lý.</p>
    <div style="background:#fff0f3;border-radius:8px;padding:16px;margin:24px 0;text-align:left">
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Mã đơn hàng</span><strong>#<?= (int)$invoice['MaHoaDon'] ?></strong></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Ngày đặt</span><span><?= format_datetime($invoice['NgayBan']) ?></span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Phương thức thanh toán</span><span><?= e($invoice['HinhThucTT']) ?></span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Địa chỉ giao hàng</span><span><?= e($invoice['DiaChiGH']) ?></span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;border-top:1px dashed #ddd;margin-top:6px;font-weight:700;font-size:16px"><span>Tổng tiền</span><span style="color:#ff5e8e"><?= format_currency($invoice['ThanhTien']) ?></span></div>
    </div>
    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
      <a href="<?= url('invoice-print.php?id=' . (int)$invoice['MaHoaDon'] . '&print=1') ?>" target="_blank" class="btn-outline btn"><i class="fa-solid fa-print"></i> In hóa đơn</a>
      <a href="<?= url('invoice-print.php?id=' . (int)$invoice['MaHoaDon'] . '&download=1') ?>" class="btn-outline btn"><i class="fa-solid fa-download"></i> Tải đơn</a>
      <?php if (is_logged_in()): ?><a href="<?= url('account.php') ?>" class="btn-outline btn">Xem đơn hàng</a><?php endif; ?>
      <a href="<?= url('products.php') ?>" class="btn">Tiếp tục mua sắm</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
