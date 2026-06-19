<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/EmployeeBLL.php';

require_admin();

$page_title = 'Quản lý nhân viên';
$bll = new EmployeeBLL();
$action = $_REQUEST['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $existingImage = trim($_POST['AnhNhanVien_existing'] ?? '');
  $upload = handle_image_upload('AnhNhanVienFile', $existingImage ?: null);
  if (isset($upload['error'])) {
    set_flash_error($upload['error']);
    redirect(url('admin/employees.php'));
  }
  $imagePath = $upload['path'] ?? null;

  $data = [
    'TenNV'       => trim($_POST['TenNV'] ?? ''),
    'DiaChi'      => trim($_POST['DiaChi'] ?? ''),
    'SDT'         => trim($_POST['SDT'] ?? ''),
    'Email'       => trim($_POST['Email'] ?? ''),
    'CMND'        => trim($_POST['CMND'] ?? ''),
    'GioiTinh'    => $_POST['GioiTinh'] ?? 'Nu',
    'AnhNhanVien' => $imagePath,
  ];
  if ($action === 'create') {
    $res = $bll->create($data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã thêm nhân viên');
  } elseif ($action === 'update') {
    $res = $bll->update((int)$_POST['id'], $data);
    isset($res['error']) ? set_flash_error($res['error']) : set_flash_success('Đã cập nhật');
  }
  redirect(url('admin/employees.php'));
}
if ($action === 'delete') {
  $bll->delete((int)$_GET['id']);
  set_flash_success('Đã xóa');
  redirect(url('admin/employees.php'));
}

$keyword = trim($_GET['q'] ?? '');
$rows = $keyword !== '' ? $bll->search($keyword) : $bll->getAll();
$edit = $action === 'edit' ? $bll->getById((int)$_GET['id']) : null;

require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
  <form class="search" method="get">
    <input type="text" name="q" placeholder="Tìm nhân viên..." value="<?= e($keyword) ?>">
    <button><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
  <button class="btn" data-modal-open="modal-create"><i class="fa-solid fa-plus"></i> Thêm nhân viên</button>
</div>

<table class="table">
  <thead><tr><th>#</th><th>Ảnh</th><th>Họ tên</th><th>SĐT</th><th>Email</th><th>CMND</th><th>Địa chỉ</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="8" class="text-center muted">Không có dữ liệu</td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td>#<?= (int)$r['MaNV'] ?></td>
        <td>
          <?php if (!empty($r['AnhNhanVien'])): ?>
            <img class="thumb" src="<?= asset(e($r['AnhNhanVien'])) ?>" style="border-radius:50%" onerror="this.style.display='none'">
          <?php else: ?>
            <div class="thumb" style="border-radius:50%;background:#fff0f3;color:#ff5e8e;display:flex;align-items:center;justify-content:center;font-weight:700"><?= e(mb_strtoupper(mb_substr($r['TenNV'], 0, 1))) ?></div>
          <?php endif; ?>
        </td>
        <td><strong><?= e($r['TenNV']) ?></strong></td>
        <td><?= e($r['SDT']) ?></td>
        <td><?= e($r['Email']) ?></td>
        <td><?= e($r['CMND']) ?></td>
        <td class="muted"><?= e($r['DiaChi']) ?></td>
        <td class="actions">
          <a class="btn-outline btn btn-sm" href="?action=edit&id=<?= (int)$r['MaNV'] ?>"><i class="fa-solid fa-pen"></i></a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaNV'] ?>" data-confirm="Xóa nhân viên này?"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<div class="modal-bg" id="modal-create" style="display:none">
  <div class="modal">
    <div class="modal-head"><h3>Thêm nhân viên</h3><button class="close" data-modal-close="modal-create">&times;</button></div>
    <form method="post" action="?action=create" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="form-group"><label>Họ tên *</label><input type="text" name="TenNV" required></div>
        <div class="row">
          <div class="col form-group"><label>SĐT</label><input type="text" name="SDT"></div>
          <div class="col form-group"><label>Email</label><input type="email" name="Email"></div>
        </div>
        <div class="row">
          <div class="col form-group"><label>CMND/CCCD *</label><input type="text" name="CMND" required></div>
          <div class="col form-group">
            <label>Giới tính</label>
            <select name="GioiTinh"><option value="Nu">Nữ</option><option value="Nam">Nam</option></select>
          </div>
        </div>
        <div class="form-group"><label>Địa chỉ</label><input type="text" name="DiaChi"></div>
        <div class="form-group">
          <label>Ảnh nhân viên</label>
          <div class="image-uploader">
            <div class="preview" data-preview><i class="fa-regular fa-user"></i></div>
            <div class="uploader-fields">
              <input type="file" name="AnhNhanVienFile" accept="image/*" data-image-input>
              <small class="muted" style="display:block;margin-top:4px">JPG / PNG / WEBP - tối đa 5MB</small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-outline btn" data-modal-close="modal-create">Hủy</button>
        <button class="btn">Thêm</button>
      </div>
    </form>
  </div>
</div>

<?php if ($edit): ?>
<div class="modal-bg" style="display:flex">
  <div class="modal">
    <div class="modal-head"><h3>Sửa nhân viên</h3><a class="close" href="<?= url('admin/employees.php') ?>">&times;</a></div>
    <form method="post" action="?action=update" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= (int)$edit['MaNV'] ?>">
      <input type="hidden" name="AnhNhanVien_existing" value="<?= e($edit['AnhNhanVien']) ?>">
      <div class="modal-body">
        <div class="form-group"><label>Họ tên *</label><input type="text" name="TenNV" required value="<?= e($edit['TenNV']) ?>"></div>
        <div class="row">
          <div class="col form-group"><label>SĐT</label><input type="text" name="SDT" value="<?= e($edit['SDT']) ?>"></div>
          <div class="col form-group"><label>Email</label><input type="email" name="Email" value="<?= e($edit['Email']) ?>"></div>
        </div>
        <div class="row">
          <div class="col form-group"><label>CMND/CCCD *</label><input type="text" name="CMND" required value="<?= e($edit['CMND']) ?>"></div>
          <div class="col form-group">
            <label>Giới tính</label>
            <select name="GioiTinh">
              <option value="Nu" <?= $edit['GioiTinh'] === 'Nu' ? 'selected' : '' ?>>Nữ</option>
              <option value="Nam" <?= $edit['GioiTinh'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
            </select>
          </div>
        </div>
        <div class="form-group"><label>Địa chỉ</label><input type="text" name="DiaChi" value="<?= e($edit['DiaChi']) ?>"></div>
        <div class="form-group">
          <label>Ảnh nhân viên</label>
          <div class="image-uploader">
            <div class="preview" data-preview>
              <?php if (!empty($edit['AnhNhanVien'])): ?>
                <img src="<?= asset(e($edit['AnhNhanVien'])) ?>" onerror="this.style.display='none';this.parentNode.innerHTML='<i class=&quot;fa-regular fa-user&quot;></i>'">
              <?php else: ?>
                <i class="fa-regular fa-user"></i>
              <?php endif; ?>
            </div>
            <div class="uploader-fields">
              <input type="file" name="AnhNhanVienFile" accept="image/*" data-image-input>
              <small class="muted" style="display:block;margin-top:6px">Để trống nếu giữ nguyên ảnh hiện tại</small>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-foot">
        <a class="btn-outline btn" href="<?= url('admin/employees.php') ?>">Hủy</a>
        <button class="btn">Lưu</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
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
