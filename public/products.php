<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/ProductBLL.php';
require_once __DIR__ . '/../src/Business/CategoryBLL.php';

$productBLL  = new ProductBLL();
$categoryBLL = new CategoryBLL();

$catId   = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
  $products = $productBLL->search($keyword);
  $headTitle = "Tìm kiếm: " . $keyword;
  $headDesc  = "Có " . count($products) . " sản phẩm phù hợp với từ khóa của bạn.";
} elseif ($catId > 0) {
  $products = $productBLL->getByCategory($catId);
  $cat = $categoryBLL->getById($catId);
  $headTitle = $cat ? $cat['TenDanhMuc'] : 'Danh mục sản phẩm';
  $headDesc  = $cat['MoTa'] ?? 'Khám phá các sản phẩm thuộc danh mục này';
} else {
  $products = $productBLL->getAll();
  $headTitle = 'Tất cả sản phẩm';
  $headDesc  = 'Khám phá toàn bộ bộ sưu tập mỹ phẩm chính hãng tại Hasaki';
}
$categories = $categoryBLL->getAll();

$page_title = $headTitle . ' - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<!-- Page hero -->
<section class="bg-gradient-to-br from-brand-50 via-brand-100 to-mint-100 border-b border-brand-100">
  <div class="max-w-7xl mx-auto px-4 lg:px-6 py-10">
    <nav class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
      <a href="<?= url('index.php') ?>" class="hover:text-brand-500">Trang chủ</a>
      <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
      <a href="<?= url('products.php') ?>" class="hover:text-brand-500">Sản phẩm</a>
      <?php if ($catId && isset($cat)): ?>
        <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
        <span class="text-gray-700 font-medium"><?= e($cat['TenDanhMuc']) ?></span>
      <?php endif; ?>
    </nav>
    <h1 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900 mb-2"><?= e($headTitle) ?></h1>
    <p class="text-gray-600 text-sm max-w-xl"><?= e($headDesc) ?></p>
  </div>
</section>

<!-- Body -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 py-10 grid lg:grid-cols-[260px_1fr] gap-8">

  <!-- Sidebar -->
  <aside class="lg:sticky lg:top-24 self-start space-y-6">
    <!-- Categories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-list text-brand-500"></i> Danh mục
      </h3>
      <ul class="space-y-1">
        <li>
          <a href="<?= url('products.php') ?>"
             class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors <?= $catId === 0 && $keyword === '' ? 'bg-brand-50 text-brand-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' ?>">
            <span>Tất cả sản phẩm</span>
            <span class="text-xs text-gray-400"><?= $productBLL->countAll() ?></span>
          </a>
        </li>
        <?php foreach ($categories as $c):
          $count = count($productBLL->getByCategory((int)$c['MaDanhMuc']));
        ?>
          <li>
            <a href="<?= url('products.php?cat=' . (int)$c['MaDanhMuc']) ?>"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors <?= $catId === (int)$c['MaDanhMuc'] ? 'bg-brand-50 text-brand-600 font-semibold' : 'text-gray-700 hover:bg-gray-50' ?>">
              <span><?= e($c['TenDanhMuc']) ?></span>
              <span class="text-xs <?= $catId === (int)$c['MaDanhMuc'] ? 'text-brand-400' : 'text-gray-400' ?>"><?= $count ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Filter price (decorative for now) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-tag text-brand-500"></i> Khoảng giá
      </h3>
      <div class="space-y-2">
        <?php foreach ([
          ['Dưới 200.000đ',     'lt-200'],
          ['200.000đ - 500.000đ', '200-500'],
          ['500.000đ - 1.000.000đ','500-1m'],
          ['Trên 1.000.000đ',     'gt-1m'],
        ] as [$label, $val]): ?>
          <label class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" class="w-4 h-4 accent-brand-500">
            <?= $label ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Promo card -->
    <div class="bg-gradient-to-br from-brand-500 to-brand-400 rounded-2xl p-6 text-white relative overflow-hidden">
      <div class="absolute -bottom-4 -right-4 text-7xl opacity-20"><i class="fa-solid fa-gift"></i></div>
      <div class="relative">
        <div class="text-xs uppercase tracking-wider opacity-90 mb-1">Ưu đãi đặc biệt</div>
        <div class="font-display text-2xl font-extrabold mb-2">Giảm 50K</div>
        <p class="text-xs opacity-90 mb-4">Cho đơn hàng đầu tiên trên 200k</p>
        <a href="<?= url('register.php') ?>" class="inline-block px-4 py-2 bg-white text-brand-500 font-semibold text-sm rounded-full hover:bg-gray-100 transition-colors">
          Đăng ký ngay
        </a>
      </div>
    </div>
  </aside>

  <!-- Products -->
  <div>
    <!-- Toolbar -->
    <div class="flex items-center justify-between flex-wrap gap-3 mb-5 bg-white rounded-2xl px-5 py-3 border border-gray-100 shadow-sm">
      <div class="text-sm text-gray-600">
        Tìm thấy <strong class="text-gray-900"><?= count($products) ?></strong> sản phẩm
      </div>
      <div class="flex items-center gap-2">
        <span class="text-xs text-gray-500">Sắp xếp:</span>
        <select class="!w-auto !py-1.5 !px-3 !text-sm !bg-gray-50 !border-0">
          <option>Mới nhất</option>
          <option>Giá: Thấp → Cao</option>
          <option>Giá: Cao → Thấp</option>
          <option>Bán chạy</option>
        </select>
      </div>
    </div>

    <?php if (empty($products)): ?>
      <div class="bg-white rounded-2xl p-16 text-center border border-gray-100">
        <div class="text-6xl text-gray-300 mb-4"><i class="fa-regular fa-face-frown"></i></div>
        <h3 class="font-bold text-xl text-gray-900 mb-2">Không tìm thấy sản phẩm phù hợp</h3>
        <p class="text-gray-500 mb-6">Hãy thử với từ khóa khác hoặc xem tất cả sản phẩm</p>
        <a href="<?= url('products.php') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white font-semibold rounded-full hover:bg-brand-600 transition-colors">
          Xem tất cả sản phẩm <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php foreach ($products as $p): include __DIR__ . '/includes/product-card.php'; endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
