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
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Phương thức thanh toán</span><span><?= e(payment_method_label($invoice['HinhThucTT'])) ?></span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0"><span class="muted">Địa chỉ giao hàng</span><span><?= e($invoice['DiaChiGH']) ?></span></div>
      <div style="display:flex;justify-content:space-between;padding:6px 0;border-top:1px dashed #ddd;margin-top:6px;font-weight:700;font-size:16px"><span>Tổng tiền</span><span style="color:#ff5e8e"><?= format_currency($invoice['ThanhTien']) ?></span></div>
    </div>

    <?php if (($invoice['HinhThucTT'] ?? '') === 'BANK'):
      $bankQr       = setting('bank_qr_image', '');
      $bankName     = setting('bank_name', '');
      $bankNo       = setting('bank_account_no', '');
      $bankAcc      = setting('bank_account_name', '');
      $transferNote = setting('bank_transfer_note', 'HASAKI DH') . (int)$invoice['MaHoaDon']; ?>
      <div style="background:#f0fdfa;border:2px dashed #5eead4;border-radius:12px;padding:24px;margin:0 0 24px;text-align:left">
        <h3 style="margin:0 0 4px;font-size:16px"><i class="fa-solid fa-building-columns" style="color:#0d9488"></i> Thông tin chuyển khoản</h3>
        <p class="muted" style="margin:0 0 16px;font-size:13px">Đơn thanh toán bằng chuyển khoản ngân hàng. Nếu chưa chuyển, bạn có thể quét lại mã QR bên dưới. Shop sẽ xử lý đơn sau khi nhận đủ tiền.</p>
        <div style="display:flex;gap:24px;align-items:center;flex-wrap:wrap;justify-content:center">
          <?php if ($bankQr): ?>
          <img src="<?= asset(e($bankQr)) ?>"
               alt="Mã QR chuyển khoản"
               style="width:220px;height:220px;object-fit:contain;background:#fff;border:1px solid #e5e7eb;border-radius:12px;flex-shrink:0"
               onerror="this.style.display='none'">
          <?php endif; ?>
          <div style="flex:1;min-width:240px;font-size:14px">
            <?php if ($bankName): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:5px 0"><span class="muted">Ngân hàng</span><strong style="text-align:right"><?= e($bankName) ?></strong></div><?php endif; ?>
            <?php if ($bankNo): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:5px 0"><span class="muted">Số tài khoản</span><strong><?= e($bankNo) ?></strong></div><?php endif; ?>
            <?php if ($bankAcc): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:5px 0"><span class="muted">Chủ tài khoản</span><strong><?= e($bankAcc) ?></strong></div><?php endif; ?>
            <div style="display:flex;justify-content:space-between;gap:12px;padding:5px 0"><span class="muted">Số tiền</span><strong style="color:#ff5e8e"><?= format_currency($invoice['ThanhTien']) ?></strong></div>
            <div style="display:flex;justify-content:space-between;gap:12px;padding:5px 0"><span class="muted">Nội dung CK</span><strong><?= e($transferNote) ?></strong></div>
            <p class="muted" style="margin:10px 0 0;font-size:12px;border-top:1px solid #ccfbf1;padding-top:10px">
              <i class="fa-solid fa-circle-info"></i> Nếu chưa chuyển, vui lòng chuyển đúng nội dung <strong><?= e($transferNote) ?></strong> để shop đối chiếu.
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
      <a href="<?= url('invoice-print.php?id=' . (int)$invoice['MaHoaDon'] . '&print=1') ?>" target="_blank" class="btn-outline btn"><i class="fa-solid fa-print"></i> In hóa đơn</a>
      <a href="<?= url('invoice-print.php?id=' . (int)$invoice['MaHoaDon'] . '&download=1') ?>" class="btn-outline btn"><i class="fa-solid fa-download"></i> Tải đơn</a>
      <?php if (is_logged_in()): ?><a href="<?= url('account.php') ?>" class="btn-outline btn">Xem đơn hàng</a><?php endif; ?>
      <a href="<?= url('products.php') ?>" class="btn">Tiếp tục mua sắm</a>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
