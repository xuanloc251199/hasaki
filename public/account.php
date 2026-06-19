<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/CustomerBLL.php';
require_once __DIR__ . '/../src/Business/InvoiceBLL.php';
require_once __DIR__ . '/../src/Business/AuthBLL.php';

require_login();

$customerBLL = new CustomerBLL();
$invoiceBLL  = new InvoiceBLL();
$authBLL     = new AuthBLL();

$customer = $customerBLL->getByAccount((int)$_SESSION['user_id']);
$orders = $customer ? $invoiceBLL->getByCustomer((int)$customer['MaKhachHang']) : [];

$err = null; $ok = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (($_POST['form'] ?? '') === 'profile' && $customer) {
    $res = $customerBLL->update((int)$customer['MaKhachHang'], [
      'TenKhachHang' => trim($_POST['fullname']),
      'DiaChi'       => trim($_POST['address']),
      'SDT'          => trim($_POST['phone']),
      'Email'        => trim($_POST['email']),
      'GioiTinh'     => $_POST['gender'] ?? 'Nu',
    ]);
    isset($res['error']) ? ($err = $res['error']) : ($ok = 'Cập nhật thông tin thành công!');
    $customer = $customerBLL->getByAccount((int)$_SESSION['user_id']);
  } elseif (($_POST['form'] ?? '') === 'password') {
    $res = $authBLL->changePassword((int)$_SESSION['user_id'], $_POST['old_password'] ?? '', $_POST['new_password'] ?? '');
    isset($res['error']) ? ($err = $res['error']) : ($ok = 'Đổi mật khẩu thành công!');
  }
}

$page_title = 'Tài khoản - ' . SITE_NAME;
$total_orders = count($orders);
$total_spent  = array_sum(array_column($orders, 'ThanhTien'));

require __DIR__ . '/includes/header.php';
?>

<!-- Profile hero -->
<section class="bg-gradient-to-br from-brand-50 via-brand-100 to-mint-100 border-b border-brand-100">
  <div class="max-w-7xl mx-auto px-4 lg:px-6 py-10">
    <nav class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
      <a href="<?= url('index.php') ?>" class="hover:text-brand-500">Trang chủ</a>
      <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
      <span class="text-gray-700 font-medium">Tài khoản</span>
    </nav>
    <div class="flex items-center gap-5 flex-wrap">
      <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-brand-400 text-white text-3xl font-extrabold flex items-center justify-center shadow-lg">
        <?= e(mb_strtoupper(mb_substr($customer['TenKhachHang'] ?? 'U', 0, 1))) ?>
      </div>
      <div>
        <h1 class="font-display text-3xl font-extrabold text-gray-900 leading-tight">
          Xin chào, <?= e($customer['TenKhachHang'] ?? $_SESSION['username']) ?> 👋
        </h1>
        <p class="text-sm text-gray-600 mt-1">
          <span class="font-semibold"><?= $total_orders ?></span> đơn hàng ·
          <span class="font-semibold text-brand-500"><?= format_currency($total_spent) ?></span> tổng chi tiêu
        </p>
      </div>
    </div>
  </div>
</section>

<div class="max-w-7xl mx-auto px-4 lg:px-6 py-10 grid lg:grid-cols-[260px_1fr] gap-8">

  <!-- Sidebar -->
  <aside class="lg:sticky lg:top-24 self-start">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <nav class="p-2">
        <a href="#profile" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-50 hover:text-brand-500 transition-colors text-sm font-semibold">
          <i class="fa-solid fa-user w-5"></i> Thông tin cá nhân
        </a>
        <a href="#orders" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-50 hover:text-brand-500 transition-colors text-sm font-semibold">
          <i class="fa-solid fa-receipt w-5"></i> Đơn hàng của tôi
          <span class="ml-auto bg-brand-100 text-brand-600 text-xs px-2 py-0.5 rounded-full"><?= $total_orders ?></span>
        </a>
        <a href="#password" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-50 hover:text-brand-500 transition-colors text-sm font-semibold">
          <i class="fa-solid fa-lock w-5"></i> Đổi mật khẩu
        </a>
        <hr class="my-2 border-gray-100">
        <a href="<?= url('logout.php') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 hover:text-red-500 transition-colors text-sm font-semibold text-gray-700">
          <i class="fa-solid fa-right-from-bracket w-5"></i> Đăng xuất
        </a>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="space-y-6">

    <?php if ($err): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> <?= e($err) ?>
      </div>
    <?php endif; ?>
    <?php if ($ok): ?>
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl p-3 flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> <?= e($ok) ?>
      </div>
    <?php endif; ?>

    <!-- Profile -->
    <section id="profile" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8 scroll-mt-24">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-bold flex items-center gap-2">
          <i class="fa-solid fa-user text-brand-500"></i> Thông tin cá nhân
        </h2>
      </div>
      <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" name="form" value="profile">
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Họ và tên</label>
          <input type="text" name="fullname" value="<?= e($customer['TenKhachHang'] ?? '') ?>">
        </div>
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Số điện thoại</label>
          <input type="tel" name="phone" value="<?= e($customer['SDT'] ?? '') ?>">
        </div>
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Email</label>
          <input type="email" name="email" value="<?= e($customer['Email'] ?? '') ?>">
        </div>
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Giới tính</label>
          <select name="gender">
            <option value="Nu" <?= ($customer['GioiTinh'] ?? '') === 'Nu' ? 'selected' : '' ?>>Nữ</option>
            <option value="Nam" <?= ($customer['GioiTinh'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Địa chỉ</label>
          <input type="text" name="address" value="<?= e($customer['DiaChi'] ?? '') ?>">
        </div>
        <div class="md:col-span-2">
          <button class="px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
          </button>
        </div>
      </form>
    </section>

    <!-- Orders -->
    <section id="orders" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden scroll-mt-24">
      <div class="px-6 lg:px-8 py-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-bold flex items-center gap-2">
          <i class="fa-solid fa-receipt text-brand-500"></i> Đơn hàng của tôi
        </h2>
        <span class="text-sm text-gray-500"><?= $total_orders ?> đơn hàng</span>
      </div>
      <?php if (empty($orders)): ?>
        <div class="p-12 text-center">
          <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-50 flex items-center justify-center">
            <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
          </div>
          <p class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào</p>
          <a href="<?= url('products.php') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white font-semibold rounded-full hover:bg-brand-600 text-sm">
            Khám phá sản phẩm <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      <?php else: ?>
        <div class="divide-y divide-gray-100">
          <?php foreach ($orders as $o):
            $statusClass = match ((int)$o['TrangThai']) {
              0 => 'bg-amber-100 text-amber-700',
              1 => 'bg-blue-100 text-blue-700',
              2 => 'bg-emerald-100 text-emerald-700',
              3 => 'bg-red-100 text-red-700',
              default => 'bg-gray-100 text-gray-700',
            };
          ?>
            <div class="p-5 flex items-center justify-between flex-wrap gap-3 hover:bg-gray-50/50 transition-colors">
              <div>
                <div class="font-bold text-gray-900">Đơn hàng #<?= (int)$o['MaHoaDon'] ?></div>
                <div class="text-xs text-gray-500 mt-1">
                  <i class="fa-regular fa-clock"></i> <?= format_datetime($o['NgayBan']) ?>
                </div>
              </div>
              <div class="font-display text-xl font-extrabold text-brand-500">
                <?= format_currency($o['ThanhTien']) ?>
              </div>
              <div class="flex items-center gap-3">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                  <?= e($invoiceBLL->statusLabel((int)$o['TrangThai'])) ?>
                </span>
                <a href="<?= url('invoice-print.php?id=' . (int)$o['MaHoaDon']) ?>" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition-colors">
                  <i class="fa-solid fa-print"></i> In hóa đơn
                </a>
                <a href="<?= url('invoice-print.php?id=' . (int)$o['MaHoaDon'] . '&download=1') ?>"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                  <i class="fa-solid fa-download"></i> Tải đơn
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- Change password -->
    <section id="password" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8 scroll-mt-24">
      <h2 class="text-lg font-bold flex items-center gap-2 mb-5">
        <i class="fa-solid fa-lock text-brand-500"></i> Đổi mật khẩu
      </h2>
      <form method="post" class="space-y-4 max-w-md">
        <input type="hidden" name="form" value="password">
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Mật khẩu hiện tại</label>
          <div class="pw-field" data-password-wrap>
            <input type="password" name="old_password" required>
            <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Mật khẩu mới</label>
          <div class="pw-field" data-password-wrap>
            <input type="password" name="new_password" required minlength="6">
            <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>
        </div>
        <button class="px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
          <i class="fa-solid fa-shield-halved"></i> Đổi mật khẩu
        </button>
      </form>
    </section>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
