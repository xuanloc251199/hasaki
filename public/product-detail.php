<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/ProductBLL.php';
require_once __DIR__ . '/../src/Business/ReviewBLL.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$productBLL = new ProductBLL();
$reviewBLL  = new ReviewBLL();
$product = $productBLL->getById($id);

if (!$product) {
  set_flash_error('Sản phẩm không tồn tại');
  redirect(url('products.php'));
}

$related   = $productBLL->getRelated($id, (int)$product['MaDanhMuc']);
$priceOld  = !empty($product['Sale']) ? (float)$product['GiaBan'] / (1 - (float)$product['Sale'] / 100) : null;
$soldOut   = (int)($product['SoLuong'] ?? 0) <= 0;
$lowStock  = !$soldOut && (int)$product['SoLuong'] <= (int)setting('low_stock_threshold', '10');
$reviews   = $reviewBLL->getByProduct($id);
$summary   = $reviewBLL->summary($id);
$avgRating = $summary['average'];
$reviewCount = $summary['count'];
$distribution = $summary['distribution'];

function render_stars(float $score, int $size = 14): string {
  $out = '';
  for ($i = 1; $i <= 5; $i++) {
    if ($score >= $i)         $out .= '<i class="fa-solid fa-star" style="font-size:' . $size . 'px"></i>';
    elseif ($score >= $i - 0.5) $out .= '<i class="fa-solid fa-star-half-alt" style="font-size:' . $size . 'px"></i>';
    else                       $out .= '<i class="fa-regular fa-star" style="font-size:' . $size . 'px"></i>';
  }
  return $out;
}

$page_title = $product['TenSanPham'] . ' - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 py-4">
  <nav class="text-xs text-gray-500 flex items-center gap-1.5 flex-wrap">
    <a href="<?= url('index.php') ?>" class="hover:text-brand-500">Trang chủ</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
    <a href="<?= url('products.php') ?>" class="hover:text-brand-500">Sản phẩm</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
    <a href="<?= url('products.php?cat=' . (int)$product['MaDanhMuc']) ?>" class="hover:text-brand-500"><?= e($product['TenDanhMuc']) ?></a>
    <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
    <span class="text-gray-700 font-medium truncate max-w-xs"><?= e($product['TenSanPham']) ?></span>
  </nav>
</div>

<!-- Product detail card -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 pb-12">
  <div class="bg-white rounded-3xl shadow-sm overflow-hidden grid lg:grid-cols-2 gap-8 lg:gap-12 p-6 lg:p-10">

    <!-- Image side -->
    <div>
      <div class="relative aspect-square rounded-2xl bg-gradient-to-br from-brand-100 via-brand-50 to-mint-100 overflow-hidden group">
        <div class="absolute inset-0 flex items-center justify-center">
          <i class="fa-solid fa-spa text-9xl text-brand-300/50"></i>
        </div>
        <img src="<?= asset(e($product['HinhAnh'])) ?>" data-main-image
             class="relative w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
             onerror="this.style.display='none'">
        <?php if (!empty($product['Sale'])): ?>
          <div class="absolute top-4 left-4 bg-gradient-to-br from-brand-500 to-brand-400 text-white text-sm font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5">
            <i class="fa-solid fa-fire"></i> Giảm <?= e($product['Sale']) ?>%
          </div>
        <?php endif; ?>
      </div>

      <!-- Thumbnails -->
      <div class="flex gap-2 mt-4">
        <?php
        $thumbs = array_filter([$product['HinhAnh'], $product['AnhHover1'] ?? null, $product['AnhHover2'] ?? null]);
        foreach ($thumbs as $i => $thumb):
        ?>
          <img src="<?= asset(e($thumb)) ?>" data-thumb
               class="w-20 h-20 rounded-xl object-cover cursor-pointer border-2 transition-all <?= $i === 0 ? 'border-brand-500 active' : 'border-transparent hover:border-brand-300 opacity-60 hover:opacity-100' ?>"
               onerror="this.style.display='none'">
        <?php endforeach; ?>
      </div>

      <!-- Quick info pills (desktop only) -->
      <div class="hidden lg:grid grid-cols-2 gap-2 mt-5">
        <?php if ($product['ThuongHieu']): ?>
          <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-2">
            <i class="fa-solid fa-award text-brand-500"></i>
            <div>
              <div class="text-[10px] uppercase text-gray-500 tracking-wider">Thương hiệu</div>
              <div class="text-sm font-semibold text-gray-800"><?= e($product['ThuongHieu']) ?></div>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($product['XuatXu']): ?>
          <div class="bg-gray-50 rounded-xl p-3 flex items-center gap-2">
            <i class="fa-solid fa-globe text-brand-500"></i>
            <div>
              <div class="text-[10px] uppercase text-gray-500 tracking-wider">Xuất xứ</div>
              <div class="text-sm font-semibold text-gray-800"><?= e($product['XuatXu']) ?></div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Info side -->
    <div>
      <div class="flex items-center gap-2 text-xs text-gray-500 mb-2 flex-wrap">
        <span class="px-2 py-1 bg-brand-50 text-brand-600 rounded-md font-semibold"><?= e($product['TenDanhMuc']) ?></span>
        <?php if ($product['ThuongHieu']): ?>
          <span class="px-2 py-1 bg-mint-100 text-mint-700 rounded-md font-semibold">
            <i class="fa-solid fa-check-circle"></i> <?= e($product['ThuongHieu']) ?>
          </span>
        <?php endif; ?>
        <span>·</span>
        <span>Mã SP: #<?= (int)$product['MaSanPham'] ?></span>
      </div>

      <h1 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900 mb-3 leading-tight">
        <?= e($product['TenSanPham']) ?>
      </h1>

      <!-- Rating -->
      <div class="flex items-center gap-3 mb-5 pb-5 border-b border-gray-100 flex-wrap">
        <div class="text-amber-400">
          <?= render_stars($avgRating, 16) ?>
        </div>
        <span class="text-sm font-semibold text-gray-700"><?= number_format($avgRating, 1) ?></span>
        <a href="#reviews" class="text-sm text-brand-500 hover:text-brand-600 font-medium underline-offset-2 hover:underline">
          (<?= $reviewCount ?> đánh giá)
        </a>
        <span class="text-gray-300">|</span>
        <?php if ($soldOut): ?>
          <span class="text-sm text-red-600 font-semibold">
            <i class="fa-solid fa-circle-xmark"></i> Hết hàng
          </span>
        <?php elseif ($lowStock): ?>
          <span class="text-sm text-amber-600 font-semibold">
            <i class="fa-solid fa-triangle-exclamation"></i> Sắp hết - chỉ còn <?= (int)$product['SoLuong'] ?> sản phẩm
          </span>
        <?php else: ?>
          <span class="text-sm text-emerald-600 font-medium">
            <i class="fa-solid fa-check"></i> Còn <?= (int)$product['SoLuong'] ?> sản phẩm
          </span>
        <?php endif; ?>
      </div>

      <!-- Price -->
      <div class="flex items-baseline gap-3 mb-6 flex-wrap">
        <span class="font-display text-4xl font-extrabold text-brand-500"><?= format_currency($product['GiaBan']) ?></span>
        <?php if ($priceOld): ?>
          <span class="text-lg text-gray-400 line-through"><?= format_currency($priceOld) ?></span>
          <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-md">-<?= e($product['Sale']) ?>%</span>
        <?php endif; ?>
      </div>

      <!-- Short description -->
      <?php if (!empty($product['MoTa'])): ?>
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-sm text-gray-700 leading-relaxed line-clamp-4">
          <?= nl2br(e($product['MoTa'])) ?>
        </div>
      <?php endif; ?>

      <!-- Options -->
      <div class="space-y-4 mb-6">
        <?php if (!empty($product['MauSac'])): ?>
          <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Màu sắc</div>
            <span class="inline-block px-4 py-2 bg-brand-50 border-2 border-brand-500 text-brand-700 font-semibold text-sm rounded-xl">
              <?= e($product['MauSac']) ?>
            </span>
          </div>
        <?php endif; ?>
        <?php if (!empty($product['DungTich']) || !empty($product['KichCo'])): ?>
          <div>
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dung tích / Kích cỡ</div>
            <span class="inline-block px-4 py-2 bg-gray-50 border-2 border-gray-200 text-gray-700 font-semibold text-sm rounded-xl">
              <?= e($product['DungTich'] ?? $product['KichCo']) ?>
            </span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Add to cart -->
      <?php if ($soldOut): ?>
      <div class="border-t border-gray-100 pt-6">
        <button type="button" disabled
                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-200 text-gray-500 font-semibold rounded-full cursor-not-allowed">
          <i class="fa-solid fa-ban"></i> Hết hàng
        </button>
        <p class="text-center text-sm text-gray-500 mt-3">Sản phẩm tạm thời hết hàng. Vui lòng quay lại sau.</p>
      </div>
      <?php else: ?>
      <form action="<?= url('cart-action.php') ?>" method="post" class="border-t border-gray-100 pt-6">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?= (int)$product['MaSanPham'] ?>">

        <div class="flex items-center gap-4 mb-5">
          <span class="text-sm font-semibold text-gray-700">Số lượng:</span>
          <div class="flex items-center bg-gray-50 rounded-full p-1" data-qty-stepper>
            <button type="button" data-action="minus" class="w-9 h-9 rounded-full hover:bg-white transition-colors flex items-center justify-center text-gray-700">−</button>
            <input type="number" name="quantity" value="1" min="1" class="!w-12 !text-center !bg-transparent !border-0 !p-0 !font-semibold focus:!shadow-none">
            <button type="button" data-action="plus" class="w-9 h-9 rounded-full hover:bg-white transition-colors flex items-center justify-center text-gray-700">+</button>
          </div>
        </div>

        <div class="flex flex-wrap gap-3">
          <button type="submit"
                  class="flex-1 min-w-[180px] inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white border-2 border-brand-500 text-brand-500 font-semibold rounded-full hover:bg-brand-50 transition-colors">
            <i class="fa-solid fa-bag-shopping"></i> Thêm vào giỏ
          </button>
          <button type="submit" name="buy_now" value="1"
                  class="flex-1 min-w-[180px] inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
            <i class="fa-solid fa-bolt"></i> Mua ngay
          </button>
        </div>
      </form>
      <?php endif; ?>

      <!-- Trust badges -->
      <div class="grid grid-cols-3 gap-2 mt-6 pt-6 border-t border-gray-100">
        <div class="text-center px-2">
          <i class="fa-solid fa-truck-fast text-2xl text-emerald-500 mb-1"></i>
          <div class="text-[11px] font-semibold text-gray-700">Giao 30 phút</div>
        </div>
        <div class="text-center px-2 border-x border-gray-100">
          <i class="fa-solid fa-shield-halved text-2xl text-brand-500 mb-1"></i>
          <div class="text-[11px] font-semibold text-gray-700">Chính hãng 100%</div>
        </div>
        <div class="text-center px-2">
          <i class="fa-solid fa-rotate-left text-2xl text-amber-500 mb-1"></i>
          <div class="text-[11px] font-semibold text-gray-700">Đổi trả 7 ngày</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ============= TABS ============= -->
  <div class="mt-8 bg-white rounded-3xl shadow-sm overflow-hidden">

    <!-- Tab nav -->
    <div class="flex flex-wrap border-b border-gray-100 px-2 lg:px-4 bg-white" data-tab-nav>
      <button type="button" data-tab="info"
              class="tab-btn relative px-5 py-4 text-sm font-semibold text-gray-700 hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-circle-info"></i> Thông tin sản phẩm
      </button>
      <button type="button" data-tab="ingredients"
              class="tab-btn relative px-5 py-4 text-sm font-semibold text-gray-700 hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-flask"></i> Thành phần
      </button>
      <button type="button" data-tab="usage"
              class="tab-btn relative px-5 py-4 text-sm font-semibold text-gray-700 hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-book-open"></i> Hướng dẫn sử dụng
      </button>
      <button type="button" data-tab="reviews" id="reviews"
              class="tab-btn relative px-5 py-4 text-sm font-semibold text-gray-700 hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-star"></i> Đánh giá (<?= $reviewCount ?>)
      </button>
      <button type="button" data-tab="policy"
              class="tab-btn relative px-5 py-4 text-sm font-semibold text-gray-700 hover:text-brand-500 transition-colors">
        <i class="fa-solid fa-shield"></i> Chính sách
      </button>
    </div>

    <div class="p-6 lg:p-10">

      <!-- TAB: Thông tin sản phẩm -->
      <div data-tab-panel="info" class="tab-panel">
        <div class="grid lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2">
            <h3 class="font-display text-xl font-extrabold text-gray-900 mb-4">Mô tả chi tiết</h3>
            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
              <?= nl2br(e($product['MoTa'] ?? 'Chưa có mô tả chi tiết.')) ?>
            </div>

            <?php if (!empty($product['BaoQuan'])): ?>
              <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl p-4">
                <div class="flex items-start gap-3">
                  <i class="fa-solid fa-circle-info text-amber-500 mt-1"></i>
                  <div>
                    <div class="font-bold text-gray-900 mb-1">Hướng dẫn bảo quản</div>
                    <p class="text-sm text-gray-700"><?= nl2br(e($product['BaoQuan'])) ?></p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Spec table -->
          <div>
            <div class="bg-gradient-to-br from-brand-50 to-mint-100 rounded-2xl p-5">
              <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-brand-500"></i> Thông số kỹ thuật
              </h3>
              <dl class="space-y-3 text-sm">
                <?php
                $specs = [
                  'Thương hiệu'   => $product['ThuongHieu'] ?? null,
                  'Xuất xứ'       => $product['XuatXu']     ?? null,
                  'Dung tích'     => $product['DungTich']   ?? $product['KichCo'] ?? null,
                  'Loại da phù hợp' => $product['LoaiDa']   ?? null,
                  'Màu sắc'       => $product['MauSac']     ?? null,
                  'Hạn sử dụng'   => $product['HanSuDung']  ?? null,
                  'Danh mục'      => $product['TenDanhMuc'] ?? null,
                ];
                foreach ($specs as $k => $v): if (!$v) continue; ?>
                  <div class="flex justify-between gap-3 pb-2 border-b border-white/60 last:border-0">
                    <dt class="text-gray-500 shrink-0"><?= e($k) ?></dt>
                    <dd class="text-gray-900 font-semibold text-right"><?= e($v) ?></dd>
                  </div>
                <?php endforeach; ?>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB: Thành phần -->
      <div data-tab-panel="ingredients" class="tab-panel hidden">
        <div class="max-w-3xl">
          <h3 class="font-display text-xl font-extrabold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-flask text-brand-500"></i> Thành phần (Ingredients)
          </h3>
          <p class="text-sm text-gray-500 mb-4">Danh sách thành phần đầy đủ theo quy chuẩn INCI</p>
          <?php if (!empty($product['ThanhPhan'])): ?>
            <div class="bg-gray-50 rounded-xl p-5 text-sm text-gray-700 leading-relaxed">
              <?= nl2br(e($product['ThanhPhan'])) ?>
            </div>
            <div class="mt-5 grid sm:grid-cols-3 gap-3">
              <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-center">
                <i class="fa-solid fa-leaf text-emerald-500 text-xl mb-1"></i>
                <div class="text-xs font-bold text-emerald-700">An toàn</div>
                <div class="text-[11px] text-emerald-600 mt-0.5">Đã kiểm nghiệm da liễu</div>
              </div>
              <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-center">
                <i class="fa-solid fa-paw text-blue-500 text-xl mb-1"></i>
                <div class="text-xs font-bold text-blue-700">Cruelty-free</div>
                <div class="text-[11px] text-blue-600 mt-0.5">Không thử nghiệm trên động vật</div>
              </div>
              <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-center">
                <i class="fa-solid fa-heart text-amber-500 text-xl mb-1"></i>
                <div class="text-xs font-bold text-amber-700">Dermatest</div>
                <div class="text-[11px] text-amber-600 mt-0.5">Phù hợp da nhạy cảm</div>
              </div>
            </div>
          <?php else: ?>
            <p class="text-sm text-gray-400 italic">Chưa có thông tin thành phần.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- TAB: Hướng dẫn sử dụng -->
      <div data-tab-panel="usage" class="tab-panel hidden">
        <div class="max-w-3xl">
          <h3 class="font-display text-xl font-extrabold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-book-open text-brand-500"></i> Cách sử dụng
          </h3>
          <p class="text-sm text-gray-500 mb-5">Theo hướng dẫn từ nhà sản xuất để đạt hiệu quả tốt nhất</p>
          <?php if (!empty($product['HuongDanSuDung'])): ?>
            <div class="bg-gradient-to-br from-mint-100 to-mint-200/50 rounded-xl p-6 text-sm text-gray-800 leading-relaxed whitespace-pre-line border border-mint-200">
              <?= nl2br(e($product['HuongDanSuDung'])) ?>
            </div>
          <?php else: ?>
            <p class="text-sm text-gray-400 italic">Chưa có hướng dẫn sử dụng.</p>
          <?php endif; ?>

          <!-- Tip box -->
          <div class="mt-6 grid sm:grid-cols-2 gap-3">
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-start gap-3">
              <i class="fa-solid fa-lightbulb text-amber-500 text-xl mt-0.5"></i>
              <div>
                <div class="font-bold text-sm text-gray-900 mb-1">Mẹo hay</div>
                <p class="text-xs text-gray-600">Sử dụng đều đặn vào cùng một thời điểm để đạt hiệu quả tối ưu.</p>
              </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-start gap-3">
              <i class="fa-solid fa-circle-exclamation text-red-500 text-xl mt-0.5"></i>
              <div>
                <div class="font-bold text-sm text-gray-900 mb-1">Lưu ý</div>
                <p class="text-xs text-gray-600">Test thử ở vùng da nhỏ (cổ tay) trước khi dùng nếu bạn có làn da nhạy cảm.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB: Đánh giá -->
      <div data-tab-panel="reviews" class="tab-panel hidden">
        <div class="grid lg:grid-cols-[280px_1fr] gap-8">
          <!-- Rating summary -->
          <div class="bg-gradient-to-br from-brand-50 to-mint-100/50 rounded-2xl p-5 lg:p-6 self-start border border-brand-100">
            <div class="text-center pb-4 mb-4 border-b border-white/80">
              <div class="font-display text-5xl font-extrabold text-brand-500 leading-none mb-1"><?= number_format($avgRating, 1) ?></div>
              <div class="text-amber-400 text-base mb-1"><?= render_stars($avgRating, 18) ?></div>
              <div class="text-xs text-gray-600"><?= $reviewCount ?> đánh giá</div>
            </div>
            <div class="space-y-1.5 text-xs">
              <?php for ($s = 5; $s >= 1; $s--):
                $pct = $reviewCount ? ($distribution[$s] / $reviewCount) * 100 : 0;
              ?>
                <div class="flex items-center gap-2">
                  <span class="w-4 text-gray-700 font-bold"><?= $s ?></span>
                  <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                  <div class="flex-1 h-2 bg-white rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: <?= $pct ?>%"></div>
                  </div>
                  <span class="w-8 text-right text-gray-500 text-[10px]"><?= $distribution[$s] ?></span>
                </div>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Review list -->
          <div>
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
              <h3 class="font-display text-xl font-extrabold text-gray-900">Đánh giá từ khách hàng</h3>
              <?php if (is_logged_in()): ?>
                <button type="button" onclick="document.getElementById('review-form').classList.toggle('hidden');document.getElementById('review-form').scrollIntoView({behavior:'smooth'});"
                        class="px-4 py-2 bg-gradient-to-r from-brand-500 to-brand-400 text-white text-sm font-semibold rounded-full shadow hover:shadow-lg transition-shadow">
                  <i class="fa-solid fa-pen"></i> Viết đánh giá
                </button>
              <?php else: ?>
                <a href="<?= url('login.php') ?>" class="px-4 py-2 bg-white border border-brand-500 text-brand-500 text-sm font-semibold rounded-full hover:bg-brand-50 transition-colors">
                  <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập để đánh giá
                </a>
              <?php endif; ?>
            </div>

            <!-- Review form -->
            <?php if (is_logged_in()): ?>
              <form id="review-form" method="post" action="<?= url('review-action.php') ?>"
                    class="hidden bg-gray-50 rounded-2xl p-5 mb-6 border border-gray-200">
                <input type="hidden" name="MaSanPham" value="<?= (int)$product['MaSanPham'] ?>">
                <h4 class="font-bold text-gray-900 mb-3">Chia sẻ trải nghiệm của bạn</h4>

                <div class="mb-4">
                  <label class="!text-xs !font-semibold !text-gray-700 !mb-2 block uppercase tracking-wider">Đánh giá *</label>
                  <div class="flex gap-1 text-2xl text-gray-300" data-rating-stars>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <button type="button" data-star="<?= $i ?>" class="hover:text-amber-400 transition-colors">
                        <i class="fa-solid fa-star"></i>
                      </button>
                    <?php endfor; ?>
                    <input type="hidden" name="SoSao" value="5" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Tiêu đề (tùy chọn)</label>
                  <input type="text" name="TieuDe" placeholder="VD: Sản phẩm rất tốt!">
                </div>

                <div class="mb-4">
                  <label class="!text-xs !font-semibold !text-gray-700 !mb-1.5 block uppercase tracking-wider">Nhận xét chi tiết *</label>
                  <textarea name="NoiDung" rows="4" required minlength="10" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm: chất lượng, giao hàng, dịch vụ..."></textarea>
                </div>

                <div class="flex gap-2 justify-end">
                  <button type="button" onclick="document.getElementById('review-form').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-full transition-colors">Huỷ</button>
                  <button class="px-5 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-full transition-colors">
                    <i class="fa-solid fa-paper-plane"></i> Gửi đánh giá
                  </button>
                </div>
              </form>
            <?php endif; ?>

            <!-- List -->
            <?php if (empty($reviews)): ?>
              <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-200">
                <i class="fa-regular fa-comment-dots text-4xl text-gray-300 mb-3"></i>
                <h4 class="font-bold text-gray-900 mb-1">Chưa có đánh giá nào</h4>
                <p class="text-sm text-gray-500">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
              </div>
            <?php else: ?>
              <div class="space-y-4">
                <?php foreach ($reviews as $r): ?>
                  <div class="border-b border-gray-100 pb-5 last:border-0">
                    <div class="flex items-start gap-3">
                      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-300 to-brand-500 text-white flex items-center justify-center font-bold shrink-0">
                        <?= e(mb_strtoupper(mb_substr($r['TenNguoiDanhGia'], 0, 1))) ?>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                          <span class="font-semibold text-gray-900 text-sm"><?= e($r['TenNguoiDanhGia']) ?></span>
                          <?php if ($r['DaXacThucMua']): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md">
                              <i class="fa-solid fa-circle-check"></i> Đã mua hàng
                            </span>
                          <?php endif; ?>
                          <span class="text-xs text-gray-400">·</span>
                          <span class="text-xs text-gray-400"><?= format_datetime($r['NgayDanhGia']) ?></span>
                        </div>
                        <div class="text-amber-400 mb-2 text-sm">
                          <?= render_stars((float)$r['SoSao'], 13) ?>
                        </div>
                        <?php if (!empty($r['TieuDe'])): ?>
                          <div class="font-bold text-gray-900 mb-1"><?= e($r['TieuDe']) ?></div>
                        <?php endif; ?>
                        <p class="text-sm text-gray-700 leading-relaxed mb-2"><?= nl2br(e($r['NoiDung'])) ?></p>
                        <div class="flex items-center gap-3 text-xs">
                          <a href="<?= url('review-action.php?action=helpful&id=' . (int)$r['MaDanhGia'] . '&pid=' . (int)$product['MaSanPham']) ?>"
                             class="inline-flex items-center gap-1 text-gray-500 hover:text-brand-500 transition-colors">
                            <i class="fa-regular fa-thumbs-up"></i> Hữu ích (<?= (int)$r['HuuIch'] ?>)
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- TAB: Chính sách -->
      <div data-tab-panel="policy" class="tab-panel hidden">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-emerald-500 mb-3">
              <i class="fa-solid fa-truck-fast"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Giao hàng nhanh</h4>
            <p class="text-sm text-gray-600 leading-relaxed">Giao trong 30 phút - 2 giờ tại nội thành TP.HCM. Tỉnh khác 1-3 ngày làm việc.</p>
          </div>
          <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-blue-500 mb-3">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Cam kết chính hãng</h4>
            <p class="text-sm text-gray-600 leading-relaxed">100% chính hãng có tem nhập khẩu. Đền x10 nếu phát hiện hàng giả.</p>
          </div>
          <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-amber-500 mb-3">
              <i class="fa-solid fa-rotate-left"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Đổi trả 7 ngày</h4>
            <p class="text-sm text-gray-600 leading-relaxed">Đổi trả miễn phí trong 7 ngày nếu sản phẩm còn nguyên seal, chưa sử dụng.</p>
          </div>
          <div class="bg-violet-50 border border-violet-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-violet-500 mb-3">
              <i class="fa-solid fa-headset"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Hỗ trợ 24/7</h4>
            <p class="text-sm text-gray-600 leading-relaxed">Đội ngũ tư vấn viên và chuyên gia da liễu luôn sẵn sàng hỗ trợ. Hotline 1900 1234.</p>
          </div>
          <div class="bg-brand-50 border border-brand-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-brand-500 mb-3">
              <i class="fa-solid fa-credit-card"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Thanh toán an toàn</h4>
            <p class="text-sm text-gray-600 leading-relaxed">COD, ATM, Visa, Mastercard, VNPay-QR, MoMo - bảo mật theo tiêu chuẩn quốc tế.</p>
          </div>
          <div class="bg-mint-100 border border-mint-200 rounded-2xl p-5">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl text-mint-600 mb-3">
              <i class="fa-solid fa-gift"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Ưu đãi thành viên</h4>
            <p class="text-sm text-gray-600 leading-relaxed">Tích điểm cho mỗi đơn hàng, giảm giá riêng cho thành viên VIP, sinh nhật tặng quà.</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Related products -->
  <?php if (!empty($related)): ?>
    <section class="mt-12">
      <div class="flex items-end justify-between mb-6">
        <div>
          <div class="text-sm font-semibold text-brand-500 uppercase tracking-wider mb-1">Có thể bạn thích</div>
          <h2 class="font-display text-2xl lg:text-3xl font-extrabold text-gray-900">Sản phẩm liên quan</h2>
        </div>
      </div>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($related as $p): include __DIR__ . '/includes/product-card.php'; endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</div>

<style>
  .tab-btn.active { color: #DD2D4A; }
  .tab-btn.active::after {
    content: '';
    position: absolute;
    left: 1.25rem;
    right: 1.25rem;
    bottom: -1px;
    height: 2px;
    background: linear-gradient(to right, #DD2D4A, #F26A8D);
    border-radius: 2px;
  }
</style>

<script>
const tabBtns = document.querySelectorAll('[data-tab]');
const tabPanels = document.querySelectorAll('[data-tab-panel]');

function activateTab(name, scroll = false) {
  tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === name));
  tabPanels.forEach(p => p.classList.toggle('hidden', p.dataset.tabPanel !== name));

  // If user is currently scrolled past the tab nav, bring tab nav back into view
  // so the new panel's content is fully visible
  if (scroll) {
    const tabNav = document.querySelector('[data-tab-nav]');
    if (tabNav) {
      const rect = tabNav.getBoundingClientRect();
      const headerOffset = 80; // sticky site header height
      if (rect.top < headerOffset) {
        const top = window.scrollY + rect.top - headerOffset - 12;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    }
  }
}
tabBtns.forEach(b => b.addEventListener('click', () => activateTab(b.dataset.tab, true)));

const hash = location.hash.replace('#', '');
activateTab(['info','ingredients','usage','reviews','policy'].includes(hash) ? hash : 'info', false);

// Star rating picker
const starGroup = document.querySelector('[data-rating-stars]');
if (starGroup) {
  const hidden = starGroup.querySelector('input[type=hidden]');
  const stars  = starGroup.querySelectorAll('button[data-star]');
  function paint(n) {
    stars.forEach(s => {
      s.classList.toggle('text-amber-400', parseInt(s.dataset.star) <= n);
      s.classList.toggle('text-gray-300',  parseInt(s.dataset.star) > n);
    });
  }
  paint(5);
  stars.forEach(s => {
    s.addEventListener('click', () => { hidden.value = s.dataset.star; paint(parseInt(s.dataset.star)); });
    s.addEventListener('mouseenter', () => paint(parseInt(s.dataset.star)));
    s.addEventListener('mouseleave', () => paint(parseInt(hidden.value)));
  });
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
