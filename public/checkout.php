<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/CartBLL.php';
require_once __DIR__ . '/../src/Business/InvoiceBLL.php';
require_once __DIR__ . '/../src/Business/CustomerBLL.php';

$cart = new CartBLL();
$items = $cart->getItems();
$total = $cart->total();

if (empty($items)) {
  set_flash_error('Giỏ hàng đang trống');
  redirect(url('cart.php'));
}

$customerBLL = new CustomerBLL();
$isGuest  = !is_logged_in();
$customer = $isGuest ? null : $customerBLL->getByAccount((int)$_SESSION['user_id']);
$err = null;

// Guests can order (a customer profile is created from the form below). But a
// logged-in admin/staff account has no customer profile and no valid
// MaKhachHang, so it is still blocked instead of crashing on the FK constraint.
if (!$isGuest && !$customer) {
  set_flash_error('Tài khoản quản trị/nhân viên không thể đặt hàng. Vui lòng dùng tài khoản khách hàng để mua hàng.');
  redirect(url('cart.php'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $invoiceBLL = new InvoiceBLL();
  $info = [
    'SDT'        => trim($_POST['sdt'] ?? ''),
    'Email'      => trim($_POST['email'] ?? ''),
    'DiaChiGH'   => trim($_POST['address'] ?? ''),
    'GhiChu'     => trim($_POST['note'] ?? ''),
    'HinhThucTT' => $_POST['payment'] ?? 'COD',
  ];

  // Resolve the customer id: existing profile for members, or a freshly created
  // guest profile (MaTaiKhoan = NULL) built from the checkout form.
  $maKhachHang = 0;
  if ($isGuest) {
    $fullname = trim($_POST['fullname'] ?? '');
    if ($fullname === '')             $err = 'Vui lòng nhập họ và tên';
    elseif ($info['SDT'] === '')      $err = 'Vui lòng nhập số điện thoại';
    elseif ($info['DiaChiGH'] === '') $err = 'Vui lòng nhập địa chỉ giao hàng';
    else {
      $created = $customerBLL->create([
        'TenKhachHang' => $fullname,
        'DiaChi'       => $info['DiaChiGH'],
        'SDT'          => $info['SDT'],
        'Email'        => $info['Email'],
        'GioiTinh'     => null,
        'MaTaiKhoan'   => null,
      ]);
      isset($created['error']) ? ($err = $created['error']) : ($maKhachHang = (int)$created['id']);
    }
  } else {
    $maKhachHang = (int)$customer['MaKhachHang'];
  }

  if (!$err) {
    $res = $invoiceBLL->checkout($maKhachHang, $info, $items);
    if (isset($res['error'])) {
      $err = $res['error'];
    } else {
      if ($isGuest) $_SESSION['guest_orders'][] = (int)$res['invoice_id'];
      $cart->clear();
      set_flash_success('Đặt hàng thành công! Mã đơn hàng: #' . $res['invoice_id']);
      redirect(url('order-success.php?id=' . $res['invoice_id']));
    }
  }
}

$page_title = 'Thanh toán - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<!-- Step indicator -->
<div class="bg-gray-50 border-b border-gray-100 py-5">
  <div class="max-w-3xl mx-auto px-4 flex items-center justify-between">
    <div class="flex items-center gap-3 flex-1">
      <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md">
        <i class="fa-solid fa-check"></i>
      </div>
      <div class="hidden sm:block">
        <div class="text-xs text-gray-500">Bước 1</div>
        <div class="font-semibold text-sm">Giỏ hàng</div>
      </div>
    </div>
    <div class="flex-1 h-0.5 bg-gradient-to-r from-emerald-500 to-brand-500 mx-2"></div>
    <div class="flex items-center gap-3 flex-1">
      <div class="w-10 h-10 rounded-full bg-brand-500 text-white flex items-center justify-center font-bold shadow-md">2</div>
      <div class="hidden sm:block">
        <div class="text-xs text-gray-500">Bước 2</div>
        <div class="font-semibold text-sm text-brand-500">Thanh toán</div>
      </div>
    </div>
    <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
    <div class="flex items-center gap-3 flex-1 justify-end">
      <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold">3</div>
      <div class="hidden sm:block">
        <div class="text-xs text-gray-400">Bước 3</div>
        <div class="font-semibold text-sm text-gray-400">Hoàn tất</div>
      </div>
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 lg:px-6 py-8">
  <h1 class="font-display text-3xl font-extrabold text-gray-900 mb-6">Thanh toán</h1>

  <form method="post" class="grid lg:grid-cols-[1.4fr_1fr] gap-6">
    <div class="space-y-5">
      <?php if ($err): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3 flex items-center gap-2">
          <i class="fa-solid fa-circle-exclamation"></i> <?= e($err) ?>
        </div>
      <?php endif; ?>

      <?php if ($isGuest): ?>
        <div class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-xl p-3 flex items-center gap-2">
          <i class="fa-solid fa-circle-info"></i>
          <span>Bạn đang đặt hàng với tư cách khách. Đã có tài khoản?
            <a href="<?= url('login.php') ?>" class="font-semibold underline">Đăng nhập</a> để theo dõi đơn hàng dễ hơn.</span>
        </div>
      <?php endif; ?>

      <!-- Shipping info -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-bold flex items-center gap-2 mb-4">
          <i class="fa-solid fa-location-dot text-brand-500"></i> Thông tin giao hàng
        </h3>
        <div class="space-y-4">
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Họ và tên <?= $isGuest ? '*' : '' ?></label>
            <?php if ($isGuest): ?>
              <input type="text" name="fullname" required value="<?= e($_POST['fullname'] ?? '') ?>" placeholder="Nhập họ và tên người nhận">
            <?php else: ?>
              <input type="text" value="<?= e($customer['TenKhachHang'] ?? '') ?>" disabled>
            <?php endif; ?>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Số điện thoại *</label>
              <input type="tel" name="sdt" required value="<?= e($_POST['sdt'] ?? $customer['SDT'] ?? '') ?>">
            </div>
            <div>
              <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Email</label>
              <input type="email" name="email" value="<?= e($_POST['email'] ?? $customer['Email'] ?? '') ?>">
            </div>
          </div>
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Địa chỉ giao hàng *</label>
            <input type="text" name="address" required value="<?= e($_POST['address'] ?? $customer['DiaChi'] ?? '') ?>" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành">
          </div>
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Ghi chú</label>
            <textarea name="note" rows="3" placeholder="Lưu ý cho người giao hàng (nếu có)"><?= e($_POST['note'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Payment method -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-bold flex items-center gap-2 mb-4">
          <i class="fa-solid fa-credit-card text-brand-500"></i> Hình thức thanh toán
        </h3>
        <div class="space-y-2">
          <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-brand-300 payment-option">
            <input type="radio" name="payment" value="COD" checked class="w-5 h-5 accent-brand-500">
            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-brand-500 text-xl shrink-0">
              <i class="fa-solid fa-truck"></i>
            </div>
            <div class="flex-1">
              <div class="font-semibold">Thanh toán khi nhận hàng (COD)</div>
              <div class="text-xs text-gray-500">Bạn sẽ trả tiền cho shipper khi nhận hàng</div>
            </div>
          </label>
          <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-brand-300 payment-option">
            <input type="radio" name="payment" value="VNPAY" class="w-5 h-5 accent-brand-500">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl shrink-0">
              <i class="fa-solid fa-credit-card"></i>
            </div>
            <div class="flex-1">
              <div class="font-semibold">Thẻ ATM / Internet Banking / VNPay QR</div>
              <div class="text-xs text-gray-500">Visa, Mastercard, JCB, các thẻ ATM nội địa</div>
            </div>
          </label>
          <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer hover:border-brand-300 payment-option">
            <input type="radio" name="payment" value="MOMO" class="w-5 h-5 accent-brand-500">
            <div class="w-12 h-12 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 text-xl shrink-0">
              <i class="fa-solid fa-mobile-screen"></i>
            </div>
            <div class="flex-1">
              <div class="font-semibold">Ví điện tử MoMo</div>
              <div class="text-xs text-gray-500">Thanh toán nhanh chóng qua ví MoMo</div>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- Order summary -->
    <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:sticky lg:top-24 self-start h-fit">
      <h3 class="text-base font-bold flex items-center gap-2 mb-4">
        <i class="fa-solid fa-bag-shopping text-brand-500"></i> Đơn hàng (<?= count($items) ?>)
      </h3>
      <div class="space-y-3 mb-4 max-h-72 overflow-y-auto pr-1">
        <?php foreach ($items as $it): ?>
          <div class="flex items-center gap-3 pb-3 border-b border-gray-100 last:border-0">
            <div class="relative shrink-0">
              <img src="<?= asset(e($it['HinhAnh'])) ?>"
                   onerror="this.src='https://placehold.co/60'"
                   class="w-14 h-14 rounded-xl object-cover bg-gray-100">
              <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-brand-500 text-white text-[10px] font-bold flex items-center justify-center">
                <?= (int)$it['SoLuong'] ?>
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-semibold text-gray-800 truncate"><?= e($it['TenSanPham']) ?></div>
              <div class="text-xs text-gray-400"><?= e($it['KichCo'] ?? '') ?></div>
            </div>
            <div class="font-semibold text-sm shrink-0"><?= format_currency($it['GiaBan'] * $it['SoLuong']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="space-y-2 pb-4 border-b border-dashed border-gray-200 mb-4 text-sm">
        <div class="flex justify-between text-gray-600">
          <span>Tạm tính</span>
          <span class="font-medium text-gray-900"><?= format_currency($total) ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Phí giao hàng</span>
          <span class="font-medium text-emerald-600">Miễn phí</span>
        </div>
      </div>

      <div class="flex justify-between items-baseline mb-5">
        <span class="text-base font-semibold text-gray-900">Tổng cộng</span>
        <span class="font-display text-2xl font-extrabold text-brand-500"><?= format_currency($total) ?></span>
      </div>

      <button type="submit"
              class="w-full inline-flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
        <i class="fa-solid fa-circle-check"></i>
        <span>Đặt hàng ngay</span>
      </button>

      <p class="text-xs text-gray-400 text-center mt-3">
        Bằng việc đặt hàng, bạn đồng ý với <a href="#" class="text-brand-500 hover:underline">Điều khoản</a> của Hasaki
      </p>
    </aside>
  </form>
</div>

<style>
  /* Highlight the selected payment option (driven by the checked radio) */
  .payment-option.active { border-color: #DD2D4A; background: #FDF2F4; }
  .payment-option.active .font-semibold { color: #DD2D4A; }
</style>
<script>
// Reflect the checked payment radio as the highlighted option (only one at a time)
(function () {
  var options = document.querySelectorAll('.payment-option');
  function sync() {
    var checked = document.querySelector('input[name="payment"]:checked');
    options.forEach(function (o) {
      o.classList.toggle('active', !!checked && o.contains(checked));
    });
  }
  document.querySelectorAll('input[name="payment"]').forEach(function (r) {
    r.addEventListener('change', sync);
  });
  sync();
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
