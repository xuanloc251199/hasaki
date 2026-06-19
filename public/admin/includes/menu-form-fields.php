<?php
$item = $item ?? [];
$mVal = fn(string $key, $default = '') => $item[$key] ?? $default;
?>
<div class="form-group">
  <label>Vị trí *</label>
  <select name="ViTri" required>
    <?php foreach (MenuBLL::POSITIONS as $key => $label): ?>
      <option value="<?= $key ?>" <?= $mVal('ViTri') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label>Tiêu đề *</label>
  <input type="text" name="TieuDe" required value="<?= e($mVal('TieuDe')) ?>" placeholder="VD: Trang chủ">
</div>

<div class="form-group">
  <label>Đường dẫn *</label>
  <input type="text" name="DuongDan" required value="<?= e($mVal('DuongDan')) ?>" placeholder="VD: products.php hoặc https://...">
  <small class="muted block mt-1">Có thể là đường dẫn nội bộ (products.php) hoặc URL ngoài (https://...)</small>
</div>

<div class="row">
  <div class="col form-group">
    <label>Icon (FontAwesome)</label>
    <input type="text" name="Icon" value="<?= e($mVal('Icon')) ?>" placeholder="VD: fa-fire">
    <small class="muted block mt-1">Tên class FontAwesome, không cần "fa-solid"</small>
  </div>
  <div class="col form-group">
    <label>Thứ tự</label>
    <input type="number" name="ThuTu" value="<?= (int)$mVal('ThuTu', '0') ?>">
  </div>
</div>

<div class="grid grid-cols-2 gap-3">
  <label class="flex items-start gap-2 p-3 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100">
    <input type="checkbox" name="KichHoat" value="1" <?= ($mVal('KichHoat', '1') == 1) ? 'checked' : '' ?> class="mt-0.5 w-4 h-4 accent-brand-500">
    <span class="text-sm"><strong class="block">Kích hoạt</strong>
      <span class="text-gray-500 text-xs">Hiển thị trên website</span>
    </span>
  </label>
  <label class="flex items-start gap-2 p-3 rounded-lg bg-gray-50 cursor-pointer hover:bg-gray-100">
    <input type="checkbox" name="MoCuaSoMoi" value="1" <?= $mVal('MoCuaSoMoi') == 1 ? 'checked' : '' ?> class="mt-0.5 w-4 h-4 accent-brand-500">
    <span class="text-sm"><strong class="block">Mở tab mới</strong>
      <span class="text-gray-500 text-xs">target="_blank"</span>
    </span>
  </label>
</div>
