<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/NotificationBLL.php';

require_admin();

$page_title = 'Thông báo';
$bll = new NotificationBLL();
$action = $_GET['action'] ?? 'list';

/** Chi cho phep dieu huong noi bo (chong open-redirect). */
function safe_internal_url(?string $u): string {
    $u = (string)$u;
    if ($u !== '' && (str_starts_with($u, BASE_URL . '/') || str_starts_with($u, '/'))) {
        return $u;
    }
    return url('admin/notifications.php');
}

if ($action === 'read') {
    $bll->markRead((int)($_GET['id'] ?? 0));
    redirect(safe_internal_url($_GET['goto'] ?? null));
}
if ($action === 'read_all') {
    $bll->markAllRead();
    set_flash_success('Đã đánh dấu tất cả là đã đọc');
    redirect(url('admin/notifications.php'));
}
if ($action === 'delete') {
    $bll->delete((int)($_GET['id'] ?? 0));
    set_flash_success('Đã xóa thông báo');
    redirect(url('admin/notifications.php'));
}
if ($action === 'clear_read') {
    $bll->deleteRead();
    set_flash_success('Đã xóa các thông báo đã đọc');
    redirect(url('admin/notifications.php'));
}

$all = $bll->all();
$pg  = paginate($all, current_page(), 15);
$rows = $pg['items'];
$unread = $bll->countUnread();

require __DIR__ . '/includes/layout-top.php';
?>
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
  <div>
    <h1 class="text-2xl font-bold text-gray-900 m-0">Thông báo</h1>
    <p class="text-sm text-gray-500 mt-1">Cảnh báo tồn kho và các thông báo hệ thống · <strong><?= (int)$unread ?></strong> chưa đọc</p>
  </div>
  <div class="flex items-center gap-2">
    <?php if ($unread > 0): ?>
      <a href="?action=read_all" class="btn-outline btn btn-sm"><i class="fa-solid fa-check-double"></i> Đánh dấu đã đọc</a>
    <?php endif; ?>
    <a href="?action=clear_read" class="btn-danger btn btn-sm" data-confirm="Xóa tất cả thông báo đã đọc?"><i class="fa-solid fa-broom"></i> Xóa đã đọc</a>
  </div>
</div>

<table class="table">
  <thead><tr><th>Loại</th><th>Nội dung</th><th>Thời gian</th><th></th></tr></thead>
  <tbody>
    <?php if (empty($rows)): ?>
      <tr><td colspan="4" class="text-center muted" style="padding:32px">Chưa có thông báo nào</td></tr>
    <?php else: foreach ($rows as $r):
      $unreadRow = (int)$r['DaDoc'] === 0;
      [$badgeBg, $badgeText] = match ($r['MucDo']) {
        'danger'  => ['#fee2e2', '#b91c1c'],
        'warning' => ['#fef3c7', '#b45309'],
        default   => ['#dbeafe', '#1d4ed8'],
      };
      $goto = !empty($r['Link']) ? url($r['Link']) : url('admin/notifications.php');
    ?>
      <tr style="<?= $unreadRow ? 'background:#fff7f8' : '' ?>">
        <td>
          <span class="badge" style="background:<?= $badgeBg ?>;color:<?= $badgeText ?>">
            <?= e($bll->typeLabel($r['Loai'])) ?>
          </span>
        </td>
        <td>
          <div style="font-weight:<?= $unreadRow ? '700' : '500' ?>;color:#111">
            <?php if ($unreadRow): ?><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#DD2D4A;margin-right:6px"></span><?php endif; ?>
            <?= e($r['TieuDe']) ?>
          </div>
          <div class="muted" style="font-size:13px;margin-top:2px"><?= e($r['NoiDung']) ?></div>
        </td>
        <td class="muted" style="white-space:nowrap"><?= format_datetime($r['NgayTao']) ?></td>
        <td class="actions" style="white-space:nowrap">
          <a class="btn-outline btn btn-sm" href="?action=read&id=<?= (int)$r['MaThongBao'] ?>&goto=<?= urlencode($goto) ?>" title="Xem / đánh dấu đã đọc">
            <i class="fa-solid fa-up-right-from-square"></i>
          </a>
          <a class="btn-danger btn btn-sm" href="?action=delete&id=<?= (int)$r['MaThongBao'] ?>" data-confirm="Xóa thông báo này?">
            <i class="fa-solid fa-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?= render_pagination($pg['page'], $pg['total_pages']) ?>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
