<?php
require_once __DIR__ . '/../../../config/config.php';
require_admin();

$current = basename($_SERVER['PHP_SELF']);
$menu = [
  ['Tổng quan',         'index.php',           'fa-gauge'],
  ['Quản lý sản phẩm',  'products.php',        'fa-box'],
  ['Quản lý danh mục',  'categories.php',      'fa-list'],
  ['Quản lý đơn hàng',  'invoices.php',        'fa-receipt'],
  ['Quản lý nhập kho',  'import-invoices.php', 'fa-truck-ramp-box'],
  ['Quản lý kho hàng',  'warehouse.php',       'fa-warehouse'],
  ['Quản lý khách hàng','customers.php',       'fa-users'],
  ['Quản lý nhân viên', 'employees.php',       'fa-user-tie'],
  ['Quản lý tài khoản', 'accounts.php',        'fa-key'],
  ['Cấu hình Chatbot',  'chatbot.php',         'fa-robot'],
  ['Cài đặt website',   'settings.php',        'fa-sliders'],
  ['Quản lý menu',      'menus.php',           'fa-bars-staggered'],
  ['Thống kê',          'stats.php',           'fa-chart-line'],
];
$page_title = $page_title ?? 'Hasaki Admin';
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?> - Hasaki Admin</title>
  <link rel="stylesheet" href="<?= asset('css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="brand">
      <div class="logo">H</div>
      <div class="name">HASAKI</div>
    </div>
    <div class="nav-group-title">Tổng quan</div>
    <nav class="nav">
      <?php foreach ($menu as [$label, $file, $icon]): ?>
        <a href="<?= url('admin/' . $file) ?>" class="nav-item <?= $current === $file ? 'active' : '' ?>">
          <span class="icon"><i class="fa-solid <?= $icon ?>"></i></span>
          <?= e($label) ?>
        </a>
      <?php endforeach; ?>
      <a href="<?= url('logout.php') ?>" class="nav-item">
        <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
        Đăng xuất
      </a>
    </nav>
  </aside>
  <main class="admin-main">
    <header class="admin-topbar">
      <div class="page-title"><?= e($page_title) ?></div>
      <div class="topbar-right">
        <a href="<?= url('index.php') ?>" target="_blank" class="btn-outline btn btn-sm"><i class="fa-solid fa-arrow-up-right-from-square"></i> Xem website</a>
        <div class="user-chip">
          <div class="avatar"><?= mb_strtoupper(mb_substr($_SESSION['display_name'] ?? 'A', 0, 1)) ?></div>
          <div>
            <div style="font-weight:600;font-size:13px"><?= e($_SESSION['display_name'] ?? '') ?></div>
            <div style="font-size:11px;color:#999"><?= e($_SESSION['role_name'] ?? '') ?></div>
          </div>
        </div>
      </div>
    </header>
    <div class="admin-content">
      <?php if ($msg = flash_success()): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
      <?php if ($msg = flash_error()): ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>
