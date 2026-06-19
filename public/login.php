<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/AuthBLL.php';

if (is_logged_in()) {
  redirect(url(is_employee() ? 'admin/index.php' : 'account.php'));
}

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $auth = new AuthBLL();
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $result = $auth->login($username, $password);
  if (is_array($result) && isset($result['error'])) {
    $err = $result['error'];
  } elseif (!$result) {
    $err = 'Tên đăng nhập hoặc mật khẩu không đúng';
  } else {
    set_flash_success('Đăng nhập thành công!');
    redirect(url(is_employee() ? 'admin/index.php' : 'account.php'));
  }
}

$page_title = 'Đăng nhập - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<div class="min-h-[80vh] py-12 px-4 grid lg:grid-cols-2 max-w-7xl mx-auto items-center gap-12">

  <!-- Left: marketing -->
  <div class="hidden lg:block relative">
    <div class="bg-gradient-to-br from-brand-600 via-brand-500 to-brand-400 rounded-3xl p-12 text-white relative overflow-hidden h-[600px] flex flex-col justify-between">
      <div class="absolute top-0 right-0 w-96 h-96 rounded-full bg-white/10 blur-3xl -translate-y-1/2 translate-x-1/2"></div>
      <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full bg-white/10 blur-3xl translate-y-1/2 -translate-x-1/2"></div>

      <div class="relative">
        <div class="font-display text-3xl font-extrabold mb-2">HASAKI</div>
        <div class="text-sm uppercase tracking-widest opacity-80">Beauty &amp; Wellness</div>
      </div>

      <div class="relative">
        <div class="text-6xl mb-6">💄</div>
        <h2 class="font-display text-4xl font-extrabold mb-4 leading-tight">Chào mừng bạn trở lại!</h2>
        <p class="text-lg opacity-90 leading-relaxed">Đăng nhập để xem ưu đãi dành riêng cho bạn, theo dõi đơn hàng và mua sắm dễ dàng hơn.</p>
      </div>

      <div class="relative grid grid-cols-3 gap-4 pt-6 border-t border-white/20">
        <div>
          <div class="font-display text-2xl font-extrabold">10K+</div>
          <div class="text-xs opacity-80 uppercase tracking-wider">Khách hàng</div>
        </div>
        <div>
          <div class="font-display text-2xl font-extrabold">1K+</div>
          <div class="text-xs opacity-80 uppercase tracking-wider">Sản phẩm</div>
        </div>
        <div>
          <div class="font-display text-2xl font-extrabold">4.9★</div>
          <div class="text-xs opacity-80 uppercase tracking-wider">Đánh giá</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: form -->
  <div class="bg-white rounded-3xl shadow-soft border border-gray-100 p-8 lg:p-10 max-w-md mx-auto w-full">
    <div class="text-center mb-7">
      <h1 class="font-display text-3xl font-extrabold text-gray-900 mb-2">Đăng nhập</h1>
      <p class="text-sm text-gray-500">Sử dụng tài khoản Hasaki của bạn để tiếp tục</p>
    </div>

    <?php if ($err): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-3 mb-5 flex items-start gap-2">
        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
        <span><?= e($err) ?></span>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">
          Email / SĐT / Tài khoản
        </label>
        <div class="relative">
          <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="username" required autofocus value="<?= e($_POST['username'] ?? '') ?>"
                 placeholder="Nhập tài khoản của bạn"
                 class="!pl-11 !py-3">
        </div>
      </div>

      <div>
        <div class="flex justify-between items-center !mb-1.5">
          <label class="!text-xs !font-semibold !text-gray-700 uppercase tracking-wider !mb-0">Mật khẩu</label>
          <a href="#" class="text-xs text-brand-500 hover:text-brand-600 font-semibold">Quên mật khẩu?</a>
        </div>
        <div class="pw-field" data-password-wrap>
          <span class="pw-icon"><i class="fa-solid fa-lock"></i></span>
          <input type="password" name="password" required placeholder="••••••••">
          <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
            <i class="fa-regular fa-eye"></i>
          </button>
        </div>
      </div>

      <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
        <input type="checkbox" class="w-4 h-4 accent-brand-500"> Ghi nhớ tài khoản
      </label>

      <button type="submit"
              class="w-full inline-flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
        <span>Đăng nhập</span>
        <i class="fa-solid fa-arrow-right"></i>
      </button>
    </form>

    <div class="relative my-6">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-200"></div>
      </div>
      <div class="relative flex justify-center text-xs">
        <span class="bg-white px-4 text-gray-400 uppercase tracking-wider">Hoặc</span>
      </div>
    </div>

    <div class="space-y-2">
      <button type="button"
              class="w-full py-3 bg-[#1877f2] text-white font-semibold rounded-full hover:bg-[#0e63d2] transition-colors flex items-center justify-center gap-2 text-sm">
        <i class="fa-brands fa-facebook-f"></i> Đăng nhập với Facebook
      </button>
      <button type="button"
              class="w-full py-3 bg-white border border-gray-200 text-gray-800 font-semibold rounded-full hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-sm">
        <i class="fa-brands fa-google text-red-500"></i> Đăng nhập với Google
      </button>
    </div>

    <p class="text-center text-sm text-gray-600 mt-6">
      Chưa có tài khoản?
      <a href="<?= url('register.php') ?>" class="text-brand-500 font-semibold hover:text-brand-600">Đăng ký ngay</a>
    </p>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
