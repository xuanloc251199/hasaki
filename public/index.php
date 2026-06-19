<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/ProductBLL.php';
require_once __DIR__ . '/../src/Business/CategoryBLL.php';

$productBLL  = new ProductBLL();
$categoryBLL = new CategoryBLL();

$featured  = $productBLL->getFeatured(8);
$newest    = $productBLL->getNewest(8);
$categories = $categoryBLL->getAll();

$page_title = SITE_NAME . ' - Mỹ phẩm chính hãng, làm đẹp mỗi ngày';
require __DIR__ . '/includes/header.php';
?>

<!-- ========== HERO ========== -->
<section class="relative overflow-hidden bg-gradient-to-br from-brand-50 via-brand-100 to-mint-100">
  <!-- Decorative blobs -->
  <div class="absolute top-10 left-10 w-72 h-72 rounded-full bg-brand-300/30 blur-3xl"></div>
  <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-brand-300/30 blur-3xl"></div>

  <?php
    // Hero content from settings
    $heroBadge    = setting('hero_badge', 'Bộ sưu tập mới');
    $heroTitle    = setting('hero_title', 'Tỏa sáng <em>vẻ đẹp</em> tự nhiên của bạn');
    $heroSubtitle = setting('hero_subtitle', 'Khám phá hàng nghìn sản phẩm mỹ phẩm chính hãng.');
    $heroCTA1     = setting('hero_cta_primary',   'Mua ngay');
    $heroCTA2     = setting('hero_cta_secondary', 'Câu chuyện thương hiệu');
    // <em>...</em> in hero_title becomes a gradient span
    $heroTitleHtml = preg_replace(
      '/<em>(.+?)<\/em>/',
      '<span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-700 to-brand-500 drop-shadow-sm">$1</span>',
      $heroTitle
    );
  ?>
  <div class="relative max-w-7xl mx-auto px-4 lg:px-6 py-12 lg:py-20 grid lg:grid-cols-2 gap-12 items-center">
    <!-- Left: text -->
    <div class="relative z-10">
      <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/80 backdrop-blur-sm rounded-full text-xs font-semibold text-brand-600 shadow-sm mb-6">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
        </span>
        <?= e($heroBadge) ?>
      </div>

      <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] text-gray-900 mb-5">
        <?= $heroTitleHtml ?>
      </h1>

      <p class="text-base sm:text-lg text-gray-700 mb-8 max-w-lg leading-relaxed">
        <?= $heroSubtitle // allow basic HTML (strong, em) ?>
      </p>

      <div class="flex flex-wrap gap-3 mb-10">
        <a href="<?= url('products.php') ?>"
           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl hover:shadow-brand-500/40 transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
          <?= e($heroCTA1) ?> <i class="fa-solid fa-arrow-right"></i>
        </a>
        <a href="<?= url('about.php') ?>"
           class="inline-flex items-center gap-2 px-6 py-3.5 bg-white border border-gray-300 text-gray-900 font-semibold rounded-full hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
          <?= e($heroCTA2) ?>
        </a>
      </div>

      <!-- Stats -->
      <div class="flex flex-wrap gap-8">
        <?php for ($i = 1; $i <= 3; $i++):
          $num = setting("hero_stat{$i}_number");
          $lbl = setting("hero_stat{$i}_label");
          if (!$num) continue;
        ?>
          <div <?= $i > 1 ? 'class="border-l border-gray-300 pl-8"' : '' ?>>
            <div class="font-display text-3xl font-extrabold text-gray-900"><?= e($num) ?></div>
            <div class="text-xs text-gray-600 uppercase tracking-wider mt-1 font-semibold"><?= e($lbl) ?></div>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Right: image collage -->
    <div class="relative h-[420px] lg:h-[500px] hidden lg:block">
      <!-- Main product card floating -->
      <div class="absolute top-0 right-0 w-72 h-80 rounded-3xl bg-gradient-to-br from-brand-300 to-brand-400 overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500">
        <img src="<?= asset('images/categories/son-moi.jpg') ?>"
             alt="Mỹ phẩm Hasaki" class="w-full h-full object-cover"
             onerror="this.parentElement.innerHTML='<div class=&quot;w-full h-full flex items-center justify-center text-white text-6xl&quot;><i class=&quot;fa-solid fa-spa&quot;></i></div>'">
      </div>

      <!-- Secondary image -->
      <div class="absolute top-32 left-0 w-56 h-72 rounded-3xl bg-gradient-to-br from-mint-200 to-mint-300 overflow-hidden shadow-xl -rotate-6 hover:rotate-0 transition-transform duration-500">
        <img src="<?= asset('images/categories/duong-da.jpg') ?>"
             alt="Skincare" class="w-full h-full object-cover"
             onerror="this.parentElement.innerHTML='<div class=&quot;w-full h-full flex items-center justify-center text-white text-5xl&quot;><i class=&quot;fa-solid fa-droplet&quot;></i></div>'">
      </div>

      <!-- Floating cards -->
      <div class="absolute bottom-8 right-12 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3 max-w-[220px]">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
          <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div>
          <div class="font-bold text-sm">Giao 30 phút</div>
          <div class="text-xs text-gray-500">Nội thành TP.HCM</div>
        </div>
      </div>

      <div class="absolute top-8 left-44 bg-white rounded-2xl shadow-xl p-4 flex items-center gap-3 max-w-[220px]">
        <div class="w-12 h-12 rounded-xl bg-brand-100 text-brand-500 flex items-center justify-center text-xl shrink-0">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
          <div class="font-bold text-sm">100% chính hãng</div>
          <div class="text-xs text-gray-500">Cam kết chất lượng</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== CATEGORY GRID ========== -->
<section class="max-w-7xl mx-auto px-4 lg:px-6 py-14">
  <div class="flex items-end justify-between mb-8">
    <div>
      <div class="text-sm font-semibold text-brand-500 uppercase tracking-wider mb-1">Khám phá</div>
      <h2 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900">Danh mục nổi bật</h2>
    </div>
    <a href="<?= url('products.php') ?>" class="hidden sm:inline-flex items-center gap-1 text-sm font-semibold text-brand-500 hover:text-brand-600">
      Xem tất cả <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
    <?php
    // Style map keyed by category ID (stable across renames)
    $catStyles = [
      1 => ['fa-kiss',      'from-brand-300 to-brand-400',     'text-brand-500'],   // Son môi
      2 => ['fa-droplet',   'from-blue-100 to-cyan-200',       'text-blue-500'],    // Dưỡng da
      3 => ['fa-palette',   'from-purple-100 to-fuchsia-200',  'text-purple-500'],  // Trang điểm
      4 => ['fa-soap',      'from-teal-100 to-emerald-200',    'text-teal-500'],    // Làm sạch da
      5 => ['fa-spa',       'from-amber-100 to-orange-200',    'text-amber-600'],   // Chăm sóc cơ thể
      6 => ['fa-spray-can', 'from-violet-100 to-indigo-200',   'text-violet-500'],  // Nước hoa
    ];
    foreach ($categories as $cat):
      [$icon, $bg, $color] = $catStyles[(int)$cat['MaDanhMuc']] ?? ['fa-bag-shopping', 'from-gray-100 to-gray-200', 'text-gray-500'];
    ?>
      <a href="<?= url('products.php?cat=' . (int)$cat['MaDanhMuc']) ?>"
         class="group relative bg-gradient-to-br <?= $bg ?> rounded-2xl text-center hover:scale-105 hover:shadow-xl transition-all duration-300 overflow-hidden aspect-[4/5]">

        <!-- Category image background -->
        <?php if (!empty($cat['AnhDanhMuc'])): ?>
          <img src="<?= asset(e($cat['AnhDanhMuc'])) ?>" alt="<?= e($cat['TenDanhMuc']) ?>"
               class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 group-hover:scale-110 transition-all duration-500"
               onerror="this.style.display='none'">
        <?php endif; ?>

        <!-- Decorative big icon -->
        <div class="absolute -bottom-4 -right-4 text-7xl <?= $color ?> opacity-20 group-hover:opacity-30 group-hover:rotate-12 transition-all">
          <i class="fa-solid <?= $icon ?>"></i>
        </div>

        <!-- Foreground content -->
        <div class="absolute inset-0 flex flex-col items-center justify-end p-4 bg-gradient-to-t from-white/90 via-white/40 to-transparent">
          <div class="w-12 h-12 rounded-2xl bg-white/95 backdrop-blur-sm <?= $color ?> flex items-center justify-center text-xl mb-2 shadow-md">
            <i class="fa-solid <?= $icon ?>"></i>
          </div>
          <div class="font-bold text-gray-900 text-sm leading-tight"><?= e($cat['TenDanhMuc']) ?></div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ========== FEATURED PRODUCTS ========== -->
<section class="bg-gradient-to-br from-brand-50/40 to-mint-100/40 py-14">
  <div class="max-w-7xl mx-auto px-4 lg:px-6">
    <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
      <div>
        <div class="text-sm font-semibold text-brand-500 uppercase tracking-wider mb-1 flex items-center gap-2">
          <i class="fa-solid fa-fire"></i> Hot deals
        </div>
        <h2 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900">Sản phẩm khuyến mãi</h2>
        <p class="text-gray-500 mt-2">Giảm giá lên đến 30% các sản phẩm hot tháng này</p>
      </div>
      <a href="<?= url('products.php') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-800 font-semibold rounded-full hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-colors text-sm">
        Xem tất cả <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
      <?php foreach ($featured as $p): include __DIR__ . '/includes/product-card.php'; endforeach; ?>
    </div>
  </div>
</section>

<!-- ========== NEWEST PRODUCTS ========== -->
<section class="max-w-7xl mx-auto px-4 lg:px-6 py-14">
  <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
    <div>
      <div class="text-sm font-semibold text-emerald-500 uppercase tracking-wider mb-1 flex items-center gap-2">
        <i class="fa-solid fa-sparkles"></i> New arrivals
      </div>
      <h2 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900">Mới ra mắt</h2>
      <p class="text-gray-500 mt-2">Cập nhật xu hướng mỹ phẩm mới nhất</p>
    </div>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
    <?php foreach ($newest as $p): include __DIR__ . '/includes/product-card.php'; endforeach; ?>
  </div>
</section>

<!-- ========== FEATURES STRIP ========== -->
<section class="max-w-7xl mx-auto px-4 lg:px-6 pb-14">
  <div class="bg-gradient-to-br from-gray-900 to-slate-800 rounded-3xl p-8 lg:p-12 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-brand-500/30 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-orange-400/20 blur-3xl"></div>

    <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-8 text-white text-center">
      <div>
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-2xl text-brand-300 border border-white/10">
          <i class="fa-solid fa-truck-fast"></i>
        </div>
        <h3 class="font-bold text-base lg:text-lg mb-1.5 text-white">Giao hàng tận nơi</h3>
        <p class="text-sm text-gray-300 leading-relaxed">Nhanh chóng trong 30 phút khu vực nội thành</p>
      </div>
      <div>
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-2xl text-mint-200 border border-white/10">
          <i class="fa-solid fa-rotate-left"></i>
        </div>
        <h3 class="font-bold text-base lg:text-lg mb-1.5 text-white">Đổi trả 7 ngày</h3>
        <p class="text-sm text-gray-300 leading-relaxed">Hỗ trợ đổi trả miễn phí, không cần lý do</p>
      </div>
      <div>
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-2xl text-brand-400 border border-white/10">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h3 class="font-bold text-base lg:text-lg mb-1.5 text-white">100% chính hãng</h3>
        <p class="text-sm text-gray-300 leading-relaxed">Sản phẩm an toàn cho làn da của bạn</p>
      </div>
      <div>
        <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center text-2xl text-mint-300 border border-white/10">
          <i class="fa-solid fa-headset"></i>
        </div>
        <h3 class="font-bold text-base lg:text-lg mb-1.5 text-white">Tư vấn 24/7</h3>
        <p class="text-sm text-gray-300 leading-relaxed">Đội ngũ chuyên gia da liễu hỗ trợ tận tình</p>
      </div>
    </div>
  </div>
</section>

<!-- ========== CTA NEWSLETTER ========== -->
<section class="max-w-7xl mx-auto px-4 lg:px-6 pb-16">
  <div class="bg-gradient-to-r from-brand-700 via-brand-500 to-brand-400 rounded-3xl p-8 lg:p-14 text-center text-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 50%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative">
      <div class="text-5xl mb-4">💌</div>
      <h2 class="font-display text-3xl lg:text-4xl font-extrabold mb-3 text-white drop-shadow">Đăng ký nhận ưu đãi mới nhất</h2>
      <p class="text-base text-white/95 max-w-xl mx-auto mb-6 leading-relaxed">Nhập email để không bỏ lỡ những deal HOT từ Hasaki - Giảm ngay <strong class="text-white">50.000đ</strong> cho đơn hàng đầu tiên</p>
      <form class="flex max-w-md mx-auto gap-2 flex-wrap sm:flex-nowrap" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã đăng ký!');">
        <input type="email" required placeholder="Nhập email của bạn..."
               class="flex-1 min-w-[200px] !bg-white !text-gray-900 !border-0 !px-5 !py-3 !rounded-full shadow-lg">
        <button class="px-7 py-3 bg-gray-900 text-white font-semibold rounded-full hover:bg-black transition-colors shrink-0 shadow-lg whitespace-nowrap">
          Đăng ký ngay
        </button>
      </form>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
