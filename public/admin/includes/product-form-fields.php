<?php
// Shared product form fields. Expects optional $product (existing row) and $categories in scope.
$product = $product ?? [];
$pid     = $product['MaSanPham']     ?? null;
$pVal = fn(string $key, $default = '') => $product[$key] ?? $default;
?>
<div class="form-group">
  <label>Tên sản phẩm *</label>
  <input type="text" name="TenSanPham" required value="<?= e($pVal('TenSanPham')) ?>">
</div>

<!-- Brand info row -->
<div class="row">
  <div class="col form-group">
    <label>Thương hiệu</label>
    <input type="text" name="ThuongHieu" value="<?= e($pVal('ThuongHieu')) ?>" placeholder="VD: Bioderma">
  </div>
  <div class="col form-group">
    <label>Xuất xứ</label>
    <input type="text" name="XuatXu" value="<?= e($pVal('XuatXu')) ?>" placeholder="VD: Pháp">
  </div>
</div>

<div class="row">
  <div class="col form-group">
    <label>Danh mục *</label>
    <select name="MaDanhMuc" required>
      <option value="">-- Chọn --</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= (int)$c['MaDanhMuc'] ?>" <?= (int)$c['MaDanhMuc'] === (int)$pVal('MaDanhMuc', 0) ? 'selected' : '' ?>>
          <?= e($c['TenDanhMuc']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col form-group">
    <label>Giá bán *</label>
    <input type="number" name="GiaBan" step="1000" required value="<?= e($pVal('GiaBan')) ?>">
  </div>
</div>

<div class="row">
  <div class="col form-group">
    <label>Số lượng tồn</label>
    <input type="number" name="SoLuong" value="<?= e($pVal('SoLuong', '0')) ?>">
  </div>
  <div class="col form-group">
    <label>Sale (%)</label>
    <input type="number" name="Sale" value="<?= e($pVal('Sale')) ?>" placeholder="VD: 20">
  </div>
  <div class="col form-group">
    <label>Hạn sử dụng</label>
    <input type="text" name="HanSuDung" value="<?= e($pVal('HanSuDung')) ?>" placeholder="VD: 24 tháng">
  </div>
</div>

<div class="row">
  <div class="col form-group">
    <label>Màu sắc</label>
    <input type="text" name="MauSac" value="<?= e($pVal('MauSac')) ?>" placeholder="VD: Đỏ ruby">
  </div>
  <div class="col form-group">
    <label>Dung tích</label>
    <input type="text" name="DungTich" value="<?= e($pVal('DungTich')) ?>" placeholder="VD: 50ml">
  </div>
  <div class="col form-group">
    <label>Loại da phù hợp</label>
    <input type="text" name="LoaiDa" value="<?= e($pVal('LoaiDa')) ?>" placeholder="VD: Da khô, da nhạy cảm">
  </div>
</div>

<div class="form-group">
  <label>Hình ảnh sản phẩm <?= $pid ? '' : '*' ?></label>
  <input type="hidden" name="HinhAnh_existing" value="<?= e($pVal('HinhAnh')) ?>">
  <div class="image-uploader">
    <div class="preview" data-preview>
      <?php if ($pVal('HinhAnh')): ?>
        <img src="<?= asset(e($pVal('HinhAnh'))) ?>" onerror="this.style.display='none';this.parentNode.innerHTML='<i class=&quot;fa-regular fa-image&quot;></i>'">
      <?php else: ?>
        <i class="fa-regular fa-image"></i>
      <?php endif; ?>
    </div>
    <div class="uploader-fields">
      <input type="file" name="HinhAnhFile" accept="image/*" data-image-input>
      <small class="muted" style="display:block;margin-top:6px">JPG / PNG / WEBP - tối đa 5MB. <?= $pid ? 'Để trống nếu giữ nguyên ảnh hiện tại.' : '' ?></small>
      <small class="muted" style="display:block;margin-top:4px">Hoặc nhập URL/đường dẫn:</small>
      <input type="text" name="HinhAnh" value="" placeholder="<?= $pid ? e($pVal('HinhAnh')) : 'images/products/abc.jpg' ?>" style="margin-top:4px">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Mô tả ngắn</label>
  <textarea name="MoTa" rows="3" placeholder="Mô tả ngắn về sản phẩm, công dụng chính..."><?= e($pVal('MoTa')) ?></textarea>
</div>

<div class="form-group">
  <label>Thành phần (Ingredients)</label>
  <textarea name="ThanhPhan" rows="3" placeholder="Liệt kê thành phần INCI, ngăn cách bằng dấu phẩy..."><?= e($pVal('ThanhPhan')) ?></textarea>
</div>

<div class="form-group">
  <label>Hướng dẫn sử dụng</label>
  <textarea name="HuongDanSuDung" rows="3" placeholder="Mỗi bước 1 dòng. VD: 1. Lắc đều..."><?= e($pVal('HuongDanSuDung')) ?></textarea>
</div>

<div class="form-group">
  <label>Hướng dẫn bảo quản</label>
  <textarea name="BaoQuan" rows="2" placeholder="VD: Bảo quản nơi khô ráo, tránh ánh nắng..."><?= e($pVal('BaoQuan')) ?></textarea>
</div>
