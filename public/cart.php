<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/CartBLL.php';

$cart = new CartBLL();
$items = $cart->getItems();
$total = $cart->total();

$page_title = 'Giỏ hàng - ' . SITE_NAME;
require __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumb -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 py-4">
  <nav class="text-xs text-gray-500 flex items-center gap-1.5">
    <a href="<?= url('index.php') ?>" class="hover:text-brand-500">Trang chủ</a>
    <i class="fa-solid fa-chevron-right text-[8px] text-gray-300"></i>
    <span class="text-gray-700 font-medium">Giỏ hàng</span>
  </nav>
</div>

<!-- Header -->
<div class="max-w-7xl mx-auto px-4 lg:px-6 mb-6">
  <h1 class="font-display text-3xl lg:text-4xl font-extrabold text-gray-900 mb-1">
    Giỏ hàng của bạn
  </h1>
  <p class="text-gray-500 text-sm">
    <?= empty($items) ? 'Chưa có sản phẩm nào' : 'Bạn đang có <strong class="text-gray-900">' . array_sum(array_column($items, 'SoLuong')) . '</strong> sản phẩm trong giỏ' ?>
  </p>
</div>

<div class="max-w-7xl mx-auto px-4 lg:px-6 pb-12 grid <?= empty($items) ? '' : 'lg:grid-cols-[1fr_380px]' ?> gap-6">

  <!-- Items -->
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <?php if (empty($items)): ?>
      <div class="p-16 text-center">
        <div class="w-24 h-24 mx-auto mb-5 rounded-full bg-gray-50 flex items-center justify-center">
          <i class="fa-solid fa-bag-shopping text-4xl text-gray-300"></i>
        </div>
        <h3 class="font-bold text-xl text-gray-900 mb-2">Giỏ hàng đang trống</h3>
        <p class="text-gray-500 mb-6">Hãy chọn những sản phẩm bạn yêu thích!</p>
        <a href="<?= url('products.php') ?>"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-all">
          Bắt đầu mua sắm <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    <?php else: ?>
      <div class="divide-y divide-gray-100">
        <?php foreach ($items as $item): ?>
          <div class="p-4 lg:p-5 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
            <a href="<?= url('product-detail.php?id=' . (int)$item['MaSanPham']) ?>" class="shrink-0">
              <div class="relative w-20 h-20 lg:w-24 lg:h-24 rounded-xl overflow-hidden bg-gradient-to-br from-brand-100 via-brand-50 to-orange-100">
                <div class="absolute inset-0 flex items-center justify-center"><i class="fa-solid fa-spa text-3xl text-brand-300/60"></i></div>
                <img src="<?= asset(e($item['HinhAnh'])) ?>"
                     onerror="this.style.display='none'"
                     class="relative w-full h-full object-cover">
              </div>
            </a>

            <div class="flex-1 min-w-0">
              <a href="<?= url('product-detail.php?id=' . (int)$item['MaSanPham']) ?>"
                 class="font-semibold text-gray-900 hover:text-brand-500 line-clamp-2 mb-1">
                <?= e($item['TenSanPham']) ?>
              </a>
              <div class="text-xs text-gray-400">
                <?= e($item['KichCo'] ?? '') ?>
                <?php if (!empty($item['MauSac'])): ?> · <?= e($item['MauSac']) ?><?php endif; ?>
              </div>
              <div class="text-sm font-bold text-brand-500 mt-1 lg:hidden">
                <?= format_currency($item['GiaBan'] * $item['SoLuong']) ?>
              </div>
            </div>

            <form action="<?= url('cart-action.php') ?>" method="post" class="shrink-0">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= (int)$item['MaSanPham'] ?>">
              <div class="flex items-center bg-gray-100 rounded-full p-0.5">
                <button type="button" onclick="this.nextElementSibling.stepDown();this.nextElementSibling.dispatchEvent(new Event('change'))"
                        class="w-7 h-7 rounded-full hover:bg-white transition-colors flex items-center justify-center text-gray-700 text-sm">−</button>
                <input type="number" name="quantity" value="<?= (int)$item['SoLuong'] ?>" min="0"
                       data-cart-update
                       class="!w-10 !text-center !bg-transparent !border-0 !p-0 !text-sm !font-semibold focus:!shadow-none">
                <button type="button" onclick="this.previousElementSibling.stepUp();this.previousElementSibling.dispatchEvent(new Event('change'))"
                        class="w-7 h-7 rounded-full hover:bg-white transition-colors flex items-center justify-center text-gray-700 text-sm">+</button>
              </div>
            </form>

            <div class="hidden lg:block text-right shrink-0 min-w-[100px]">
              <div class="font-bold text-brand-500"><?= format_currency($item['GiaBan'] * $item['SoLuong']) ?></div>
              <div class="text-xs text-gray-400 mt-0.5"><?= format_currency($item['GiaBan']) ?> / cái</div>
            </div>

            <a href="<?= url('cart-action.php?action=remove&id=' . (int)$item['MaSanPham']) ?>"
               data-confirm="Xóa sản phẩm này khỏi giỏ?"
               class="shrink-0 w-9 h-9 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition-colors">
              <i class="fa-solid fa-trash text-sm"></i>
            </a>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="px-5 py-4 bg-gray-50 flex items-center justify-between">
        <a href="<?= url('products.php') ?>" class="text-sm font-semibold text-gray-700 hover:text-brand-500 inline-flex items-center gap-1">
          <i class="fa-solid fa-arrow-left text-xs"></i> Tiếp tục mua sắm
        </a>
        <a href="<?= url('cart-action.php?action=clear') ?>" data-confirm="Xóa toàn bộ giỏ hàng?"
           class="text-sm font-semibold text-red-500 hover:text-red-600 inline-flex items-center gap-1">
          <i class="fa-solid fa-trash text-xs"></i> Xóa tất cả
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- Summary -->
  <?php if (!empty($items)): ?>
    <aside class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:sticky lg:top-24 self-start">
      <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
        <i class="fa-solid fa-receipt text-brand-500"></i> Tóm tắt đơn hàng
      </h3>

      <div class="space-y-2.5 mb-4 text-sm">
        <div class="flex justify-between text-gray-600">
          <span>Tạm tính (<?= count($items) ?> sản phẩm)</span>
          <span class="font-medium text-gray-900"><?= format_currency($total) ?></span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Phí giao hàng</span>
          <span class="font-medium text-emerald-600">Miễn phí</span>
        </div>
        <div class="flex justify-between text-gray-600">
          <span>Giảm giá</span>
          <span class="font-medium text-gray-900">- 0đ</span>
        </div>
      </div>

      <!-- Coupon -->
      <div class="bg-gray-50 rounded-xl p-3 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-ticket text-brand-500"></i>
        <input type="text" placeholder="Nhập mã giảm giá" class="!flex-1 !bg-transparent !border-0 !p-0 !text-sm focus:!shadow-none">
        <button class="text-xs font-semibold text-brand-500 hover:text-brand-600">Áp dụng</button>
      </div>

      <div class="border-t border-dashed border-gray-200 pt-4 mb-5">
        <div class="flex justify-between items-baseline">
          <span class="text-base font-semibold text-gray-900">Tổng cộng</span>
          <span class="font-display text-2xl font-extrabold text-brand-500"><?= format_currency($total) ?></span>
        </div>
        <div class="text-xs text-gray-400 text-right mt-1">Đã bao gồm VAT</div>
      </div>

      <a href="<?= url('checkout.php') ?>"
         class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 bg-gradient-to-r from-brand-500 to-brand-400 text-white font-semibold rounded-full shadow-lg shadow-brand-500/30 hover:shadow-xl transition-shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2">
        <span>Tiến hành thanh toán</span>
        <i class="fa-solid fa-arrow-right"></i>
      </a>

      <!-- Trust badges -->
      <div class="grid grid-cols-3 gap-2 mt-5 pt-5 border-t border-gray-100 text-center">
        <div>
          <i class="fa-solid fa-shield text-emerald-500 text-lg mb-1"></i>
          <div class="text-[10px] font-semibold text-gray-600">Bảo mật</div>
        </div>
        <div>
          <i class="fa-solid fa-truck text-blue-500 text-lg mb-1"></i>
          <div class="text-[10px] font-semibold text-gray-600">Giao nhanh</div>
        </div>
        <div>
          <i class="fa-solid fa-rotate-left text-amber-500 text-lg mb-1"></i>
          <div class="text-[10px] font-semibold text-gray-600">Đổi trả 7d</div>
        </div>
      </div>
    </aside>
  <?php endif; ?>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
