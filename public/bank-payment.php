<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/InvoiceBLL.php';

$id = (int)($_GET['id'] ?? 0);
$invoiceBLL = new InvoiceBLL();
$invoice = $invoiceBLL->getById($id);

// Same access rule as order-success: members may view their orders; guests may
// only view an order they just placed in this session.
$isOwnGuestOrder = in_array($id, $_SESSION['guest_orders'] ?? [], true);
if (!$invoice || (!is_logged_in() && !$isOwnGuestOrder)) {
  redirect(url('index.php'));
}

// This page is only meaningful for bank-transfer orders.
if (($invoice['HinhThucTT'] ?? '') !== 'BANK') {
  redirect(url('order-success.php?id=' . $id));
}

// Handle the "Tôi đã chuyển khoản" confirmation.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'confirm') {
  $invoiceBLL->confirmBankTransfer($id);
  redirect(url('bank-payment.php?id=' . $id . '&done=1'));
}

$confirmed    = $invoiceBLL->isBankConfirmed($invoice) || !empty($_GET['done']);
$bankQr       = setting('bank_qr_image', '');
$bankName     = setting('bank_name', '');
$bankNo       = setting('bank_account_no', '');
$bankAcc      = setting('bank_account_name', '');
$transferNote = setting('bank_transfer_note', 'HASAKI DH') . (int)$invoice['MaHoaDon'];

$page_title = 'Thanh toán chuyển khoản - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>
<div class="container" style="padding:40px 16px;max-width:820px">

  <?php if ($confirmed): ?>
    <!-- Waiting-for-verification state -->
    <div style="background:#fff;border-radius:14px;padding:44px 32px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.05)">
      <div style="font-size:56px;color:#f59e0b"><i class="fa-solid fa-hourglass-half"></i></div>
      <h1 style="margin:12px 0 6px">Đã ghi nhận, đang chờ kiểm tra</h1>
      <p class="muted" style="max-width:520px;margin:0 auto">
        Cảm ơn bạn! Chúng tôi đã nhận được xác nhận chuyển khoản cho đơn
        <strong>#<?= (int)$invoice['MaHoaDon'] ?></strong>. Shop sẽ đối chiếu giao dịch và
        xử lý đơn hàng trong thời gian sớm nhất. Trạng thái hiện tại: <strong>Chờ xác nhận</strong>.
      </p>
      <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:14px 18px;margin:22px auto 0;max-width:460px;text-align:left;font-size:14px">
        <div style="display:flex;justify-content:space-between;padding:4px 0"><span class="muted">Mã đơn hàng</span><strong>#<?= (int)$invoice['MaHoaDon'] ?></strong></div>
        <div style="display:flex;justify-content:space-between;padding:4px 0"><span class="muted">Số tiền</span><strong style="color:#ff5e8e"><?= format_currency($invoice['ThanhTien']) ?></strong></div>
        <div style="display:flex;justify-content:space-between;padding:4px 0"><span class="muted">Nội dung CK</span><strong><?= e($transferNote) ?></strong></div>
      </div>
      <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:26px">
        <a href="<?= url('order-success.php?id=' . (int)$invoice['MaHoaDon']) ?>" class="btn-outline btn"><i class="fa-solid fa-receipt"></i> Chi tiết đơn hàng</a>
        <a href="<?= url('products.php') ?>" class="btn">Tiếp tục mua sắm</a>
      </div>
    </div>

  <?php else: ?>
    <!-- Pay-by-QR state -->
    <div style="background:#fff;border-radius:14px;padding:36px 32px;box-shadow:0 4px 20px rgba(0,0,0,.05)">
      <div style="text-align:center;margin-bottom:8px">
        <div style="font-size:44px;color:#0d9488"><i class="fa-solid fa-qrcode"></i></div>
        <h1 style="margin:8px 0 4px">Quét mã QR để thanh toán</h1>
        <p class="muted" style="margin:0">Đơn hàng <strong>#<?= (int)$invoice['MaHoaDon'] ?></strong> đã được tạo. Vui lòng chuyển khoản theo mã QR bên dưới.</p>
      </div>

      <div style="display:flex;gap:28px;align-items:center;flex-wrap:wrap;justify-content:center;margin:24px 0">
        <?php if ($bankQr): ?>
          <img src="<?= asset(e($bankQr)) ?>" alt="Mã QR chuyển khoản"
               style="width:240px;height:240px;object-fit:contain;background:#fff;border:1px solid #e5e7eb;border-radius:12px;flex-shrink:0"
               onerror="this.outerHTML='<div style=&quot;color:#dc2626;font-size:13px;width:240px&quot;>Chưa cấu hình ảnh mã QR. Vui lòng liên hệ shop.</div>'">
        <?php else: ?>
          <div style="width:240px;color:#dc2626;font-size:13px">Chưa cấu hình ảnh mã QR. Vui lòng liên hệ shop.</div>
        <?php endif; ?>
        <div style="flex:1;min-width:260px;font-size:14px">
          <?php if ($bankName): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:6px 0;border-bottom:1px solid #f3f4f6"><span class="muted">Ngân hàng</span><strong style="text-align:right"><?= e($bankName) ?></strong></div><?php endif; ?>
          <?php if ($bankNo): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:6px 0;border-bottom:1px solid #f3f4f6"><span class="muted">Số tài khoản</span><strong><?= e($bankNo) ?></strong></div><?php endif; ?>
          <?php if ($bankAcc): ?><div style="display:flex;justify-content:space-between;gap:12px;padding:6px 0;border-bottom:1px solid #f3f4f6"><span class="muted">Chủ tài khoản</span><strong><?= e($bankAcc) ?></strong></div><?php endif; ?>
          <div style="display:flex;justify-content:space-between;gap:12px;padding:6px 0;border-bottom:1px solid #f3f4f6"><span class="muted">Số tiền</span><strong style="color:#ff5e8e"><?= format_currency($invoice['ThanhTien']) ?></strong></div>
          <div style="display:flex;justify-content:space-between;gap:12px;padding:6px 0"><span class="muted">Nội dung CK</span><strong><?= e($transferNote) ?></strong></div>
        </div>
      </div>

      <div style="background:#f0fdfa;border:1px solid #99f6e4;border-radius:10px;padding:12px 16px;font-size:13px;color:#0f766e;margin-bottom:22px">
        <i class="fa-solid fa-circle-info"></i>
        Vui lòng chuyển <strong>đúng số tiền</strong> và ghi <strong>đúng nội dung "<?= e($transferNote) ?>"</strong> để shop đối chiếu nhanh.
        Sau khi chuyển khoản xong, bấm nút bên dưới để xác nhận.
      </div>

      <form method="post" style="text-align:center">
        <input type="hidden" name="action" value="confirm">
        <button type="submit" class="btn" style="min-width:280px;padding:14px 28px;font-size:15px">
          <i class="fa-solid fa-circle-check"></i> Tôi đã chuyển khoản
        </button>
        <p class="muted" style="margin:12px 0 0;font-size:12px">Đơn hàng chỉ được xử lý sau khi shop kiểm tra và nhận đủ tiền.</p>
      </form>
    </div>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
