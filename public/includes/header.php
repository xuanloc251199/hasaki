<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/CartBLL.php';
require_once __DIR__ . '/../../src/Business/CategoryBLL.php';

$current_path = basename($_SERVER['PHP_SELF'] ?? '');
$header_categories = (new CategoryBLL())->getAll();
$cart_count = (new CartBLL())->count();

$siteName     = setting('site_name',    'HASAKI');
$siteTagline  = setting('site_tagline', 'Beauty & Wellness');
$siteLogo     = setting('site_logo');
$siteFavicon  = setting('site_favicon');
$seoSuffix    = setting('seo_title_suffix', $siteName);
$seoKeywords  = setting('seo_keywords', '');
$siteDesc     = setting('site_description', '');

$page_title = $page_title ?? ($siteName . ' - ' . $siteTagline);

$bannerActive = setting('top_banner_active', '1') === '1';
$bannerText   = setting('top_banner_text');
$bannerLink   = setting('top_banner_link', 'products.php');
$bannerCTA    = setting('top_banner_cta',  'Mua ngay');

$headerMenu = menu('header');
?><!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($page_title) ?></title>
  <?php if ($siteDesc): ?><meta name="description" content="<?= e($siteDesc) ?>"><?php endif; ?>
  <?php if ($seoKeywords): ?><meta name="keywords" content="<?= e($seoKeywords) ?>"><?php endif; ?>
  <?php if ($siteFavicon): ?><link rel="icon" href="<?= asset(e($siteFavicon)) ?>"><?php endif; ?>
  <link rel="stylesheet" href="<?= asset('css/tailwind.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    body, html { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .font-display { font-family: 'Playfair Display', serif; }
  </style>
</head>
<body>

  <?php if ($bannerActive && $bannerText): ?>
  <!-- Top promo strip (configured via admin) -->
  <div class="bg-gradient-to-r from-gray-900 via-slate-800 to-gray-900 text-white text-center text-xs sm:text-sm py-2 px-4">
    <span class="opacity-90"><?= $bannerText // allow basic HTML from admin ?></span>
    <?php if ($bannerLink && $bannerCTA): ?>
      <a href="<?= e(strpos($bannerLink, '://') !== false ? $bannerLink : url($bannerLink)) ?>"
         class="ml-2 underline hover:text-brand-300 transition-colors font-semibold"><?= e($bannerCTA) ?> →</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- Header -->
  <header class="bg-white/95 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
      <div class="flex items-center gap-3 lg:gap-4 py-3 min-w-0">

        <!-- Logo (configurable from admin) -->
        <a href="<?= url('index.php') ?>" class="flex items-center gap-2 shrink-0 group">
          <?php if ($siteLogo): ?>
            <img src="<?= asset(e($siteLogo)) ?>" alt="<?= e($siteName) ?>"
                 class="h-10 w-auto max-w-[140px] object-contain"
                 onerror="this.style.display='none';this.nextElementSibling && this.nextElementSibling.classList.remove('hidden')">
            <div class="hidden">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-400 flex items-center justify-center text-white shadow-md">
                <i class="fa-solid fa-spa text-lg"></i>
              </div>
            </div>
          <?php else: ?>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-400 flex items-center justify-center text-white shadow-md group-hover:shadow-lg transition-shadow">
              <i class="fa-solid fa-spa text-lg"></i>
            </div>
            <div class="hidden sm:block leading-none">
              <div class="font-display text-xl xl:text-2xl font-extrabold text-gray-900 tracking-tight whitespace-nowrap"><?= e($siteName) ?></div>
              <?php if ($siteTagline): ?>
                <div class="text-[9px] uppercase tracking-widest text-gray-400 mt-1 whitespace-nowrap"><?= e($siteTagline) ?></div>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </a>

        <!-- Nav (managed via admin/menus.php) -->
        <nav class="hidden lg:flex items-center gap-0.5 flex-shrink min-w-0">
          <?php foreach ($headerMenu as $m):
            $href = strpos($m['DuongDan'], '://') !== false ? $m['DuongDan'] : url($m['DuongDan']);
            $isActive = $current_path === basename($m['DuongDan']) && $m['DuongDan'] !== 'products.php' || ($current_path === 'index.php' && $m['DuongDan'] === 'index.php');
          ?>
            <a href="<?= e($href) ?>" <?= $m['MoCuaSoMoi'] ? 'target="_blank"' : '' ?>
               class="whitespace-nowrap px-3 py-2 text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-1.5 <?= $isActive ? 'text-brand-500 bg-brand-50' : 'text-gray-700 hover:text-brand-500 hover:bg-gray-50' ?>">
              <?php if ($m['Icon']): ?>
                <i class="fa-solid <?= e($m['Icon']) ?> <?= str_contains($m['Icon'], 'fire') ? 'text-orange-500 text-xs' : 'text-xs' ?>"></i>
              <?php endif; ?>
              <?= e($m['TieuDe']) ?>
            </a>
          <?php endforeach; ?>

          <!-- Categories dropdown (auto from DB) -->
          <div class="relative group">
            <button type="button" class="whitespace-nowrap px-3 py-2 text-sm font-semibold rounded-lg text-gray-700 hover:text-brand-500 hover:bg-gray-50 inline-flex items-center gap-1 transition-colors">
              Danh mục <i class="fa-solid fa-chevron-down text-[9px] mt-0.5"></i>
            </button>
            <div class="absolute left-0 top-full pt-2 w-56 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 z-50">
              <div class="bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                <?php foreach ($header_categories as $cat): ?>
                  <a href="<?= url('products.php?cat=' . (int)$cat['MaDanhMuc']) ?>"
                     class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-brand-50 hover:text-brand-500 transition-colors whitespace-nowrap">
                    <i class="fa-solid fa-tag text-[10px] text-brand-300"></i>
                    <?= e($cat['TenDanhMuc']) ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </nav>

        <!-- Search -->
        <form action="<?= url('products.php') ?>" method="get" class="hidden md:block flex-1 min-w-0 max-w-xs ml-auto">
          <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" name="q" placeholder="Tìm sản phẩm..." value="<?= e($_GET['q'] ?? '') ?>"
                   class="!bg-gray-50 !border-transparent !pl-9 !pr-3 !py-2 !text-sm focus:!bg-white">
          </div>
        </form>

        <!-- Actions -->
        <div class="flex items-center gap-1 ml-auto md:ml-0 shrink-0">
          <?php if (is_logged_in()): ?>
            <a href="<?= url('account.php') ?>" title="<?= e($_SESSION['display_name'] ?? '') ?>"
               class="w-10 h-10 rounded-full hover:bg-brand-50 flex items-center justify-center text-gray-700 hover:text-brand-500 transition-colors">
              <i class="fa-regular fa-user"></i>
            </a>
            <a href="<?= url('logout.php') ?>" title="Đăng xuất"
               class="w-10 h-10 rounded-full hover:bg-red-50 flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors">
              <i class="fa-solid fa-right-from-bracket text-sm"></i>
            </a>
          <?php else: ?>
            <a href="<?= url('login.php') ?>" title="Đăng nhập"
               class="w-10 h-10 rounded-full hover:bg-brand-50 flex items-center justify-center text-gray-700 hover:text-brand-500 transition-colors">
              <i class="fa-regular fa-user"></i>
            </a>
          <?php endif; ?>

          <a href="<?= url('cart.php') ?>" title="Giỏ hàng"
             class="relative w-10 h-10 rounded-full hover:bg-brand-50 flex items-center justify-center text-gray-700 hover:text-brand-500 transition-colors">
            <i class="fa-solid fa-bag-shopping"></i>
            <?php if ($cart_count > 0): ?>
              <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-brand-500 text-white text-[10px] font-bold flex items-center justify-center shadow">
                <?= $cart_count ?>
              </span>
            <?php endif; ?>
          </a>
        </div>
      </div>
    </div>
  </header>

  <?php if ($msg = flash_success()): ?>
    <div class="max-w-7xl mx-auto px-4 pt-4">
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg p-3 text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> <?= e($msg) ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if ($msg = flash_error()): ?>
    <div class="max-w-7xl mx-auto px-4 pt-4">
      <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 text-sm flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i> <?= e($msg) ?>
      </div>
    </div>
  <?php endif; ?>
