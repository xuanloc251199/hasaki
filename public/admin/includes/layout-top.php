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
  ['Thông báo',         'notifications.php',   'fa-bell'],
  ['Quản lý khách hàng','customers.php',       'fa-users'],
  ['Quản lý nhân viên', 'employees.php',       'fa-user-tie'],
  ['Quản lý tài khoản', 'accounts.php',        'fa-key'],
  ['Cấu hình Chatbot',  'chatbot.php',         'fa-robot'],
  ['Cài đặt website',   'settings.php',        'fa-sliders'],
  ['Quản lý menu',      'menus.php',           'fa-bars-staggered'],
  ['Thống kê',          'stats.php',           'fa-chart-line'],
];
$page_title = $page_title ?? 'Hasaki Admin';

// Thong bao admin (chuong tren topbar) - canh bao ton kho...
require_once __DIR__ . '/../../../src/Business/NotificationBLL.php';
$__notifBLL    = new NotificationBLL();
$__notifUnread = $__notifBLL->countUnread();
$__notifRecent = $__notifBLL->recent(8);
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

        <!-- Chuong thong bao -->
        <div style="position:relative" data-notif-wrap>
          <button type="button" data-notif-toggle aria-label="Thông báo"
                  style="position:relative;width:40px;height:40px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;color:#374151;font-size:16px">
            <i class="fa-solid fa-bell"></i>
            <?php if ($__notifUnread > 0): ?>
              <span style="position:absolute;top:-5px;right:-5px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;line-height:1"><?= $__notifUnread > 99 ? '99+' : (int)$__notifUnread ?></span>
            <?php endif; ?>
          </button>

          <div data-notif-panel class="hidden"
               style="position:absolute;right:0;top:50px;width:360px;max-width:88vw;background:#fff;border:1px solid #eee;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,.14);z-index:60;overflow:hidden">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid #f1f1f1">
              <strong style="font-size:14px;color:#111">Thông báo<?= $__notifUnread > 0 ? ' (' . (int)$__notifUnread . ')' : '' ?></strong>
              <?php if ($__notifUnread > 0): ?>
                <a href="<?= url('admin/notifications.php?action=read_all') ?>" style="font-size:12px;color:#DD2D4A;text-decoration:none;font-weight:600">Đánh dấu đã đọc</a>
              <?php endif; ?>
            </div>
            <div style="max-height:380px;overflow-y:auto">
              <?php if (empty($__notifRecent)): ?>
                <div style="padding:30px 16px;text-align:center;color:#9ca3af;font-size:13px">
                  <i class="fa-regular fa-bell" style="font-size:22px;display:block;margin-bottom:8px;opacity:.6"></i>
                  Chưa có thông báo nào
                </div>
              <?php else: foreach ($__notifRecent as $__n):
                $__unread = (int)$__n['DaDoc'] === 0;
                $__dot = match ($__n['MucDo']) { 'danger' => '#ef4444', 'warning' => '#f59e0b', default => '#3b82f6' };
                $__goto = !empty($__n['Link']) ? url($__n['Link']) : url('admin/notifications.php');
                $__href = url('admin/notifications.php?action=read&id=' . (int)$__n['MaThongBao'] . '&goto=' . urlencode($__goto));
              ?>
                <a href="<?= e($__href) ?>"
                   style="display:flex;gap:10px;padding:12px 16px;border-bottom:1px solid #f7f7f7;text-decoration:none;color:#333;<?= $__unread ? 'background:#fff7f8' : '' ?>">
                  <span style="width:9px;height:9px;border-radius:50%;background:<?= $__dot ?>;margin-top:5px;flex:0 0 auto"></span>
                  <span style="flex:1;min-width:0">
                    <span style="display:block;font-weight:600;font-size:13px;line-height:1.35;color:#111"><?= e($__n['TieuDe']) ?></span>
                    <span style="display:block;font-size:12px;color:#6b7280;line-height:1.45;margin-top:2px"><?= e($__n['NoiDung']) ?></span>
                    <span style="display:block;font-size:11px;color:#9ca3af;margin-top:4px"><?= format_datetime($__n['NgayTao']) ?></span>
                  </span>
                </a>
              <?php endforeach; endif; ?>
            </div>
            <a href="<?= url('admin/notifications.php') ?>"
               style="display:block;text-align:center;padding:11px;font-size:13px;font-weight:600;color:#DD2D4A;text-decoration:none;border-top:1px solid #f1f1f1">
              Xem tất cả thông báo
            </a>
          </div>
        </div>

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
