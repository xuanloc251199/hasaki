<?php
// Shared intent form fields. Expects optional $intent (existing row) in scope.
$intent = $intent ?? [];
$tenIntent    = $intent['TenIntent']    ?? '';
$tuKhoa       = $intent['TuKhoa']       ?? '';
$cauTraLoi    = $intent['CauTraLoi']    ?? '';
$loaiGoiY     = $intent['LoaiGoiY']     ?? 'none';
$giaTriGoiY   = $intent['GiaTriGoiY']   ?? '';
$link         = $intent['Link']         ?? '';
$thuTu        = $intent['ThuTu']        ?? 0;
$kichHoat     = $intent['KichHoat']     ?? 1;
$laQuickReply = $intent['LaQuickReply'] ?? 0;
?>
<div>
  <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Tên intent <span class="text-red-500">*</span></label>
  <input type="text" name="TenIntent" required value="<?= e($tenIntent) ?>"
         placeholder="VD: Tư vấn da khô">
</div>

<div>
  <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Từ khóa <span class="text-red-500">*</span></label>
  <input type="text" name="TuKhoa" required value="<?= e($tuKhoa) ?>"
         placeholder="da kho|skin kho|cap am">
  <p class="text-xs text-gray-500 mt-1">Ngăn cách bằng dấu <code class="bg-gray-100 px-1 rounded">|</code> · không cần dấu tiếng Việt (hệ thống tự khử dấu)</p>
</div>

<div>
  <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Câu trả lời <span class="text-red-500">*</span></label>
  <textarea name="CauTraLoi" rows="5" required placeholder="Mô tả ngắn gọn lời tư vấn..."><?= e($cauTraLoi) ?></textarea>
  <p class="text-xs text-gray-500 mt-1">Hỗ trợ <code class="bg-gray-100 px-1 rounded">**bold**</code>, xuống dòng và emoji</p>
</div>

<div class="grid grid-cols-2 gap-4">
  <div>
    <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Loại gợi ý sản phẩm</label>
    <select name="LoaiGoiY" data-intent-type>
      <option value="none"     <?= $loaiGoiY === 'none' ? 'selected' : '' ?>>Không gợi ý</option>
      <option value="search"   <?= $loaiGoiY === 'search' ? 'selected' : '' ?>>Tìm theo từ khóa</option>
      <option value="featured" <?= $loaiGoiY === 'featured' ? 'selected' : '' ?>>Sản phẩm sale</option>
      <option value="newest"   <?= $loaiGoiY === 'newest' ? 'selected' : '' ?>>Sản phẩm mới</option>
    </select>
  </div>
  <div data-search-only style="display:<?= $loaiGoiY === 'search' ? 'block' : 'none' ?>">
    <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Từ khóa search SP</label>
    <input type="text" name="GiaTriGoiY" value="<?= e($giaTriGoiY) ?>"
           placeholder="VD: son, kem chong nang">
  </div>
</div>

<div class="grid grid-cols-2 gap-4">
  <div>
    <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Liên kết kèm theo</label>
    <input type="text" name="Link" value="<?= e($link) ?>" placeholder="VD: login.php hoặc products.php?cat=1">
  </div>
  <div>
    <label class="!text-sm !font-semibold !text-gray-700 !mb-1 block">Thứ tự ưu tiên</label>
    <input type="number" name="ThuTu" value="<?= (int)$thuTu ?>" placeholder="Số nhỏ = ưu tiên cao">
  </div>
</div>

<div class="grid grid-cols-2 gap-3">
  <label class="flex items-start gap-2 p-3 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100">
    <input type="checkbox" name="KichHoat" value="1" <?= $kichHoat ? 'checked' : '' ?>
           class="mt-0.5 w-4 h-4 accent-brand-500">
    <span class="text-sm">
      <strong class="block">Kích hoạt</strong>
      <span class="text-gray-500 text-xs">Bot sẽ phản hồi intent này</span>
    </span>
  </label>
  <label class="flex items-start gap-2 p-3 rounded-lg bg-amber-50 cursor-pointer hover:bg-amber-100">
    <input type="checkbox" name="LaQuickReply" value="1" <?= $laQuickReply ? 'checked' : '' ?>
           class="mt-0.5 w-4 h-4 accent-brand-500">
    <span class="text-sm">
      <strong class="block">Hiển thị Quick reply</strong>
      <span class="text-gray-500 text-xs">Show làm chip gợi ý khi mở chat</span>
    </span>
  </label>
</div>

<script>
(function() {
  const sel = document.currentScript.closest('form').querySelector('[data-intent-type]');
  const block = document.currentScript.closest('form').querySelector('[data-search-only]');
  if (sel && block) {
    sel.addEventListener('change', () => {
      block.style.display = sel.value === 'search' ? 'block' : 'none';
    });
  }
})();
</script>
