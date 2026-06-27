<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/SettingBLL.php';

require_admin();

$page_title = 'Cài đặt website';
$bll = new SettingBLL();

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payload = [];

  // Process file uploads (images)
  foreach ($_FILES as $field => $info) {
    if (str_starts_with($field, 'file_')) {
      $key = substr($field, 5); // strip "file_" prefix
      $existing = trim($_POST[$key] ?? '');
      $upload = handle_image_upload($field, $existing ?: null);
      if (isset($upload['error'])) {
        set_flash_error('Lỗi tải ảnh ' . $key . ': ' . $upload['error']);
        redirect(url('admin/settings.php'));
      }
      $payload[$key] = $upload['path'];
    }
  }

  // Process all other fields
  foreach ($_POST as $key => $value) {
    if ($key === 'group_active') continue;
    if (str_ends_with($key, '_existing')) continue;
    if (isset($payload[$key])) continue; // already handled by upload above
    $payload[$key] = is_array($value) ? json_encode($value) : trim((string)$value);
  }

  // Boolean checkboxes: any boolean key that wasn't in POST means unchecked → 0
  $groups = $bll->getAllGrouped();
  foreach ($groups as $rows) {
    foreach ($rows as $r) {
      if ($r['Loai'] === 'boolean' && !isset($_POST[$r['Khoa']])) {
        $payload[$r['Khoa']] = '0';
      }
    }
  }

  $bll->setMany($payload);
  set_flash_success('Đã lưu cài đặt');
  redirect(url('admin/settings.php?group=' . ($_POST['group_active'] ?? 'general')));
}

$groups = $bll->getAllGrouped();
$activeGroup = $_GET['group'] ?? 'general';

$groupMeta = [
  'general' => ['Thông tin chung', 'fa-circle-info',  'Tên, logo, favicon, mô tả ngắn'],
  'contact' => ['Liên hệ',          'fa-phone',        'Địa chỉ, hotline, email, giờ làm việc'],
  'social'  => ['Mạng xã hội',      'fa-share-nodes',  'Facebook, Instagram, TikTok, YouTube...'],
  'banner'  => ['Banner trên cùng', 'fa-bullhorn',     'Thanh thông báo / khuyến mãi đầu trang'],
  'hero'    => ['Hero trang chủ',   'fa-image',        'Tiêu đề, phụ đề, CTA, số liệu thống kê'],
  'footer'  => ['Footer',           'fa-shoe-prints',  'Giới thiệu, copyright'],
  'seo'     => ['SEO',              'fa-magnifying-glass', 'Meta tags, OG image'],
  'kho'     => ['Kho hàng',         'fa-warehouse',    'Ngưỡng cảnh báo tồn kho, email nhận cảnh báo'],
];

require __DIR__ . '/includes/layout-top.php';
?>

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-bold text-gray-900 m-0">Cài đặt website</h1>
    <p class="text-sm text-gray-500 mt-1">Tùy chỉnh logo, thông tin liên hệ, mạng xã hội, banner và các nội dung tĩnh</p>
  </div>
  <a href="<?= url('admin/menus.php') ?>" class="btn-outline btn"><i class="fa-solid fa-bars-staggered"></i> Quản lý menu</a>
</div>

<div class="grid lg:grid-cols-[260px_1fr] gap-5">

  <!-- Group sidebar -->
  <aside class="bg-white rounded-2xl shadow-card border border-gray-100 p-3 self-start">
    <nav class="flex flex-col gap-0.5">
      <?php foreach ($groupMeta as $key => [$label, $icon, $desc]):
        $count = count($groups[$key] ?? []);
        if ($count === 0) continue;
        $active = $activeGroup === $key;
      ?>
        <a href="?group=<?= $key ?>"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm <?= $active ? 'bg-brand-50 text-brand-600' : 'text-gray-700 hover:bg-gray-50' ?>">
          <span class="w-8 h-8 rounded-lg flex items-center justify-center <?= $active ? 'bg-white text-brand-500' : 'bg-gray-100 text-gray-500' ?>">
            <i class="fa-solid <?= $icon ?>"></i>
          </span>
          <span class="flex-1 min-w-0">
            <span class="block font-semibold"><?= e($label) ?></span>
            <span class="block text-[11px] text-gray-400 truncate"><?= e($desc) ?></span>
          </span>
          <span class="text-xs text-gray-400"><?= $count ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
  </aside>

  <!-- Active group form -->
  <div class="bg-white rounded-2xl shadow-card border border-gray-100">
    <?php $rows = $groups[$activeGroup] ?? []; ?>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold m-0 flex items-center gap-2">
          <i class="fa-solid <?= $groupMeta[$activeGroup][1] ?? 'fa-cog' ?> text-brand-500"></i>
          <?= e($groupMeta[$activeGroup][0] ?? ucfirst($activeGroup)) ?>
        </h2>
        <p class="text-sm text-gray-500 mt-1"><?= e($groupMeta[$activeGroup][2] ?? '') ?></p>
      </div>
    </div>

    <form method="post" enctype="multipart/form-data" class="p-6 space-y-5">
      <input type="hidden" name="group_active" value="<?= e($activeGroup) ?>">

      <?php foreach ($rows as $row):
        $type  = $row['Loai'] ?? 'text';
        $key   = $row['Khoa'];
        $label = $row['TenHienThi'] ?: $key;
        $val   = $row['GiaTri'] ?? '';
        $hint  = $row['GhiChu'] ?? '';
      ?>
        <div class="form-row">
          <label class="!text-sm !font-semibold !text-gray-800 !mb-1.5 block"><?= e($label) ?></label>

          <?php if ($type === 'image'): ?>
            <input type="hidden" name="<?= e($key) ?>" value="<?= e($val) ?>">
            <input type="hidden" name="<?= e($key) ?>_existing" value="<?= e($val) ?>">
            <div class="image-uploader">
              <div class="preview" data-preview style="width:120px;height:120px">
                <?php if ($val): ?>
                  <img src="<?= asset(e($val)) ?>" onerror="this.style.display='none';this.parentNode.innerHTML='<i class=&quot;fa-regular fa-image&quot;></i>'">
                <?php else: ?>
                  <i class="fa-regular fa-image"></i>
                <?php endif; ?>
              </div>
              <div class="uploader-fields">
                <input type="file" name="file_<?= e($key) ?>" accept="image/*" data-image-input>
                <?php if ($val): ?>
                  <small class="muted block mt-1.5">Hiện tại: <code><?= e($val) ?></code> · để trống nếu giữ nguyên</small>
                <?php endif; ?>
                <?php if ($hint): ?><small class="muted block mt-1"><?= e($hint) ?></small><?php endif; ?>
              </div>
            </div>

          <?php elseif ($type === 'textarea'): ?>
            <textarea name="<?= e($key) ?>" rows="<?= str_contains($val, "\n") || mb_strlen($val) > 100 ? 4 : 2 ?>"><?= e($val) ?></textarea>
            <?php if ($hint): ?><small class="muted block mt-1"><?= e($hint) ?></small><?php endif; ?>

          <?php elseif ($type === 'boolean'): ?>
            <label class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 cursor-pointer">
              <input type="checkbox" name="<?= e($key) ?>" value="1"
                     <?= $val === '1' ? 'checked' : '' ?>
                     class="w-5 h-5 accent-brand-500">
              <span class="text-sm">
                <strong class="block text-gray-900">Bật chức năng</strong>
                <?php if ($hint): ?><span class="text-gray-500 text-xs"><?= e($hint) ?></span><?php endif; ?>
              </span>
            </label>

          <?php elseif (in_array($type, ['url', 'email', 'tel'])): ?>
            <input type="<?= $type ?>" name="<?= e($key) ?>" value="<?= e($val) ?>"
                   placeholder="<?= $type === 'url' ? 'https://...' : ($type === 'email' ? 'email@example.com' : '0123456789') ?>">
            <?php if ($hint): ?><small class="muted block mt-1"><?= e($hint) ?></small><?php endif; ?>

          <?php elseif ($type === 'color'): ?>
            <div class="flex items-center gap-3">
              <input type="color" name="<?= e($key) ?>" value="<?= e($val ?: '#DD2D4A') ?>"
                     class="!w-16 !h-10 !p-1 !cursor-pointer">
              <code class="text-xs text-gray-500"><?= e($val) ?></code>
            </div>

          <?php else: ?>
            <input type="text" name="<?= e($key) ?>" value="<?= e($val) ?>">
            <?php if ($hint): ?><small class="muted block mt-1"><?= e($hint) ?></small><?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <div class="flex items-center justify-end gap-2 pt-5 border-t border-gray-100">
        <a href="<?= url('admin/settings.php?group=' . $activeGroup) ?>" class="btn-outline btn">Huỷ thay đổi</a>
        <button class="btn"><i class="fa-solid fa-floppy-disk"></i> Lưu cài đặt</button>
      </div>
    </form>
  </div>
</div>

<style>
.muted.block { display: block; }
.form-row { padding-bottom: 4px; }
</style>

<script>
// Image preview when uploading
document.querySelectorAll('[data-image-input]').forEach(input => {
  input.addEventListener('change', e => {
    const file = e.target.files[0];
    const preview = input.closest('.image-uploader').querySelector('[data-preview]');
    if (file) {
      const reader = new FileReader();
      reader.onload = ev => { preview.innerHTML = '<img src="' + ev.target.result + '">'; };
      reader.readAsDataURL(file);
    }
  });
});
</script>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
