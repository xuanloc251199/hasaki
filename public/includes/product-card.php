<?php
// Reusable product card. Expects $p (product row) in scope.
$detailUrl = url('product-detail.php?id=' . (int)$p['MaSanPham']);
$priceOld = !empty($p['Sale']) ? (float)$p['GiaBan'] / (1 - (float)$p['Sale'] / 100) : null;
$soldOut  = (int)($p['SoLuong'] ?? 0) <= 0;
?>
<div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-transparent hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-300 flex flex-col">

  <a href="<?= $detailUrl ?>" class="block relative aspect-square overflow-hidden">
    <!-- Decorative fallback (sits behind image) -->
    <div class="absolute inset-0 bg-gradient-to-br from-brand-100 via-brand-50 to-orange-100 flex items-center justify-center">
      <i class="fa-solid fa-spa text-6xl text-brand-300/60"></i>
    </div>
    <img src="<?= asset(e($p['HinhAnh'])) ?>"
         alt="<?= e($p['TenSanPham']) ?>"
         class="relative w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
         onerror="this.style.display='none'">

    <?php if (!empty($p['Sale']) && !$soldOut): ?>
      <div class="absolute top-3 left-3 bg-gradient-to-br from-brand-500 to-brand-400 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1">
        <i class="fa-solid fa-fire text-[10px]"></i> -<?= e($p['Sale']) ?>%
      </div>
    <?php endif; ?>

    <?php if ($soldOut): ?>
      <div class="absolute inset-0 bg-white/55 flex items-center justify-center pointer-events-none z-10">
        <span class="px-4 py-1.5 bg-gray-900/85 text-white text-sm font-bold rounded-full shadow-lg tracking-wide">Hết hàng</span>
      </div>
    <?php endif; ?>

    <!-- Wishlist (visual only) -->
    <button type="button"
            class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm shadow-md flex items-center justify-center text-gray-400 hover:text-brand-500 hover:scale-110 transition-all"
            onclick="event.preventDefault(); this.classList.toggle('!text-brand-500'); this.querySelector('i').classList.toggle('fa-regular'); this.querySelector('i').classList.toggle('fa-solid');">
      <i class="fa-regular fa-heart"></i>
    </button>

    <!-- Hover quick-add overlay -->
    <?php if (!$soldOut): ?>
    <div class="absolute inset-x-3 bottom-3 translate-y-16 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
      <form action="<?= url('cart-action.php') ?>" method="post" onsubmit="event.stopPropagation();">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?= (int)$p['MaSanPham'] ?>">
        <button type="submit" onclick="event.stopPropagation();"
                class="w-full py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-brand-500 transition-colors flex items-center justify-center gap-2 shadow-lg">
          <i class="fa-solid fa-bag-shopping text-xs"></i> Thêm vào giỏ
        </button>
      </form>
    </div>
    <?php endif; ?>
  </a>

  <div class="p-4 flex flex-col flex-1">
    <a href="<?= $detailUrl ?>" class="font-semibold text-gray-800 text-sm leading-snug hover:text-brand-500 transition-colors line-clamp-2 min-h-[40px] mb-1">
      <?= e($p['TenSanPham']) ?>
    </a>

    <div class="text-xs text-gray-400 mb-3"><?= e($p['KichCo'] ?? '') ?><?= !empty($p['MauSac']) ? ' · ' . e($p['MauSac']) : '' ?></div>

    <div class="mt-auto flex items-baseline gap-2 flex-wrap">
      <span class="text-lg font-extrabold text-brand-500"><?= format_currency($p['GiaBan']) ?></span>
      <?php if ($priceOld): ?>
        <span class="text-xs text-gray-400 line-through"><?= format_currency($priceOld) ?></span>
      <?php endif; ?>
    </div>

    <!-- Rating row (decorative) -->
    <div class="flex items-center gap-1 mt-2 text-xs">
      <span class="text-amber-400">
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star"></i>
        <i class="fa-solid fa-star-half-alt"></i>
      </span>
      <span class="text-gray-400 text-[11px]">(<?= rand(20, 200) ?>)</span>
    </div>
  </div>
</div>
