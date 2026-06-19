<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/AuthBLL.php';

if (is_logged_in()) redirect(url('account.php'));

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $auth = new AuthBLL();
  $data = [
    'TenTaiKhoan'  => trim($_POST['username'] ?? ''),
    'MatKhau'      => $_POST['password'] ?? '',
    'TenKhachHang' => trim($_POST['fullname'] ?? ''),
    'SDT'          => trim($_POST['phone'] ?? ''),
    'Email'        => trim($_POST['email'] ?? ''),
    'DiaChi'       => trim($_POST['address'] ?? ''),
    'GioiTinh'     => $_POST['gender'] ?? 'Nu',
  ];
  if ($_POST['password'] !== ($_POST['password_confirm'] ?? '')) {
    $err = 'Mật khẩu xác nhận không khớp';
  } else {
    $res = $auth->register($data);
    if (isset($res['error'])) $err = $res['error'];
    else {
      set_flash_success('Đăng ký thành công! Vui lòng đăng nhập.');
      redirect(url('login.php'));
    }
  }
}

$page_title = 'Đăng ký - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<div class="min-h-[80vh] py-12 px-4 max-w-2xl mx-auto">
  <div class="bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden">

    <!-- Header gradient -->
    <div class="bg-gradient-to-br from-brand-500 to-brand-400 p-8 lg:p-10 text-white text-center relative overflow-hidden">
      <div class="absolute top-0 right-0 text-9xl opacity-10">🌸</div>
      <div class="relative">
        <h1 class="font-display text-3xl lg:text-4xl font-extrabold mb-2">Tham gia Hasaki</h1>
        <p class="text-sm opacity-90">Tạo tài khoản miễn phí - Nhận ngay <strong>50K</strong> cho đơn hàng đầu tiên</p>
      </div>
    </div>

    <div class="p-8 lg:p-10">
      <?php if ($err): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3 mb-5 flex items-start gap-2">
          <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
          <span><?= e($err) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-4">
        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Họ và tên *</label>
          <input type="text" name="fullname" required value="<?= e($_POST['fullname'] ?? '') ?>" placeholder="Nguyễn Văn A">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Tên đăng nhập *</label>
            <input type="text" name="username" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="username" minlength="4">
          </div>
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Email</label>
            <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="email@example.com">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Số điện thoại</label>
            <input type="tel" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="0901234567">
          </div>
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Giới tính</label>
            <select name="gender">
              <option value="Nu">Nữ</option>
              <option value="Nam">Nam</option>
            </select>
          </div>
        </div>

        <div>
          <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Địa chỉ</label>
          <input type="text" name="address" value="<?= e($_POST['address'] ?? '') ?>" placeholder="123 Nguyễn Huệ, Q.1, TP.HCM">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Mật khẩu *</label>
            <div class="pw-field" data-password-wrap>
              <input type="password" name="password" required minlength="6" placeholder="••••••">
              <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>
          <div>
            <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Xác nhận MK *</label>
            <div class="pw-field" data-password-wrap>
              <input type="password" name="password_confirm" required minlength="6" placeholder="••••••">
              <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <label class="flex items-start gap-2 text-sm text-gray-600 cursor-pointer pt-2">
          <input type="checkbox" required class="w-4 h-4 mt-0.5 accent-brand-500">
          <span>Tôi đồng ý với <a href="#" class="text-brand-500 font-semibold hover:underline">Điều khoản sử dụng</a> và <a href="#" class="text-brand-500 font-semibold hover:underline">Chính sách bảo mật</a> của Hasaki</span>
        </label>

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-all mt-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
          <span>Tạo tài khoản</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-6">
        Đã có tài khoản?
        <a href="<?= url('login.php') ?>" class="text-brand-500 font-semibold hover:text-brand-600">Đăng nhập</a>
      </p>
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
