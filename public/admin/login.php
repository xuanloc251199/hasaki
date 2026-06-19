<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/AuthBLL.php';

if (is_employee()) redirect(url('admin/index.php'));

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $auth = new AuthBLL();
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $result = $auth->login($username, $password);
  if (is_array($result) && isset($result['error'])) {
    $err = $result['error'];
  } elseif (!$result) {
    $err = 'Sai tài khoản hoặc mật khẩu';
  } elseif (!is_employee()) {
    $err = 'Tài khoản này không có quyền truy cập trang quản trị';
    (new AuthBLL())->logout();
    session_start();
  } else {
    redirect(url('admin/index.php'));
  }
}
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hasaki Admin - Đăng nhập</title>
  <link rel="stylesheet" href="<?= asset('css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="!bg-gradient-to-br !from-slate-900 !via-purple-900 !to-slate-900 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-br from-brand-500 to-brand-400 p-8 text-center text-white">
        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl font-extrabold shadow-lg">
          H
        </div>
        <h1 class="text-2xl font-bold m-0">Hasaki Admin</h1>
        <p class="text-sm opacity-90 mt-1">Hệ thống quản trị nội dung</p>
      </div>

      <!-- Form -->
      <div class="p-8">
        <?php if ($err): ?>
          <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-4 flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <span><?= e($err) ?></span>
          </div>
        <?php endif; ?>

        <form method="post" class="space-y-4">
          <div>
            <label class="!text-sm !font-semibold !text-gray-700 !mb-1.5 block">
              <i class="fa-solid fa-user text-brand-500"></i> Tên đăng nhập
            </label>
            <input type="text" name="username" required autofocus
                   placeholder="admin / nhanvien1"
                   class="!py-3">
          </div>

          <div>
            <label class="!text-sm !font-semibold !text-gray-700 !mb-1.5 block">
              <i class="fa-solid fa-lock text-brand-500"></i> Mật khẩu
            </label>
            <div class="pw-field" data-password-wrap>
              <input type="password" name="password" required placeholder="••••••••">
              <button type="button" class="pw-toggle" data-password-toggle aria-label="Hiện mật khẩu">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>

          <button class="!w-full !py-3 !text-base !font-semibold btn">
            <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
          </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100 text-center text-xs text-gray-500">
          Demo: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">admin</code> / <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">123456</code>
        </div>
      </div>
    </div>

    <p class="text-center text-white/70 text-sm mt-6">
      <a href="<?= url('index.php') ?>" class="hover:text-white transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Quay lại trang chính
      </a>
    </p>
  </div>

  <script>
    document.querySelectorAll('[data-password-toggle]').forEach(btn => {
      btn.addEventListener('click', () => {
        const wrap = btn.closest('[data-password-wrap]');
        const input = wrap.querySelector('input');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        const icon = btn.querySelector('i');
        icon.classList.toggle('fa-eye',       !isHidden);
        icon.classList.toggle('fa-eye-slash',  isHidden);
        btn.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
      });
    });
  </script>

</body>
</html>
