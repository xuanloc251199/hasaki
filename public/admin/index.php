<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/InvoiceBLL.php';
require_once __DIR__ . '/../../src/Business/ProductBLL.php';
require_once __DIR__ . '/../../src/Business/CustomerBLL.php';
require_once __DIR__ . '/../../src/Business/EmployeeBLL.php';

require_admin();

$page_title = 'Tổng quan';
$invoiceBLL  = new InvoiceBLL();
$productBLL  = new ProductBLL();
$customerBLL = new CustomerBLL();
$employeeBLL = new EmployeeBLL();

$kpi_revenue   = $invoiceBLL->totalRevenue();
$kpi_orders    = $invoiceBLL->countAll();
$kpi_pending   = $invoiceBLL->countByStatus(0);
$kpi_completed = $invoiceBLL->countByStatus(2);
$kpi_products  = $productBLL->countAll();
$kpi_customers = count($customerBLL->getAll());
$kpi_employees = count($employeeBLL->getAll());

$recent_orders   = array_slice($invoiceBLL->getAll(), 0, 8);
$top_products    = $invoiceBLL->topProducts(5);
$revenue_trend14 = $invoiceBLL->revenueTrend(14);
$status_dist     = $invoiceBLL->statusDistribution();
$status_labels   = [0 => 'Chờ xác nhận', 1 => 'Đang giao', 2 => 'Hoàn thành', 3 => 'Đã hủy'];
$status_colors   = [0 => '#f59e0b', 1 => '#3b82f6', 2 => '#10b981', 3 => '#ef4444'];

// Canh bao ton kho: san pham het / sap het hang (theo nguong cau hinh)
$low_stock_threshold = (int)setting('low_stock_threshold', '10');
$low_stock_products  = $productBLL->getLowStock($low_stock_threshold);
$out_of_stock_count  = 0;
foreach ($low_stock_products as $__lp) { if ((int)$__lp['SoLuong'] <= 0) $out_of_stock_count++; }

require __DIR__ . '/includes/layout-top.php';
?>

<!-- Welcome -->
<div class="mb-6">
  <div class="flex items-center justify-between flex-wrap gap-3">
    <div>
      <h1 class="text-2xl font-bold m-0 text-gray-900">Xin chào, <?= e($_SESSION['display_name'] ?? 'Admin') ?> 👋</h1>
      <p class="text-sm text-gray-500 mt-1">Đây là bảng điều khiển tổng quan của Hasaki - <?= date('d/m/Y') ?></p>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= url('admin/products.php') ?>" class="btn-outline btn"><i class="fa-solid fa-box"></i> Sản phẩm</a>
      <a href="<?= url('admin/invoices.php') ?>" class="btn"><i class="fa-solid fa-receipt"></i> Đơn hàng</a>
    </div>
  </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
  <div class="relative bg-gradient-to-br from-brand-500 to-brand-400 text-white rounded-xl p-5 shadow-soft overflow-hidden">
    <div class="absolute -right-4 -top-4 text-6xl text-white/20"><i class="fa-solid fa-sack-dollar"></i></div>
    <div class="text-xs uppercase tracking-wider opacity-90">Doanh thu</div>
    <div class="text-3xl font-extrabold mt-2"><?= format_currency($kpi_revenue) ?></div>
    <div class="text-xs opacity-80 mt-1"><?= $kpi_completed ?> đơn hoàn thành</div>
  </div>

  <div class="relative bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Tổng đơn hàng</div>
        <div class="text-3xl font-extrabold mt-2 text-gray-900"><?= $kpi_orders ?></div>
        <div class="text-xs text-amber-600 mt-1"><i class="fa-solid fa-clock"></i> <?= $kpi_pending ?> chờ xử lý</div>
      </div>
      <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
        <i class="fa-solid fa-receipt"></i>
      </div>
    </div>
  </div>

  <div class="relative bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Sản phẩm</div>
        <div class="text-3xl font-extrabold mt-2 text-gray-900"><?= $kpi_products ?></div>
        <div class="text-xs text-blue-600 mt-1"><i class="fa-solid fa-tags"></i> Đang bán</div>
      </div>
      <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
        <i class="fa-solid fa-box"></i>
      </div>
    </div>
  </div>

  <div class="relative bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Khách hàng</div>
        <div class="text-3xl font-extrabold mt-2 text-gray-900"><?= $kpi_customers ?></div>
        <div class="text-xs text-violet-600 mt-1"><i class="fa-solid fa-user-tie"></i> <?= $kpi_employees ?> nhân viên</div>
      </div>
      <div class="w-12 h-12 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center text-xl">
        <i class="fa-solid fa-users"></i>
      </div>
    </div>
  </div>
</div>

<!-- Inventory alerts -->
<?php if (!empty($low_stock_products)): ?>
<div class="bg-white rounded-xl shadow-card border border-amber-200 mb-6 overflow-hidden">
  <div class="px-5 py-4 border-b border-amber-100 flex items-center justify-between bg-amber-50 flex-wrap gap-2">
    <h2 class="text-base font-bold m-0 flex items-center gap-2 text-amber-800">
      <i class="fa-solid fa-triangle-exclamation"></i> Cảnh báo tồn kho
      <?php if ($out_of_stock_count > 0): ?>
        <span class="text-xs font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full"><?= $out_of_stock_count ?> hết hàng</span>
      <?php endif; ?>
      <span class="text-xs font-semibold bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full"><?= count($low_stock_products) ?> cần chú ý</span>
    </h2>
    <a href="<?= url('admin/warehouse.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">Quản lý kho →</a>
  </div>
  <div class="divide-y divide-gray-100">
    <?php foreach (array_slice($low_stock_products, 0, 6) as $lp):
      $isOut = (int)$lp['SoLuong'] <= 0; ?>
      <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors">
        <img src="<?= asset(e($lp['HinhAnh'])) ?>" onerror="this.src='https://placehold.co/48'"
             class="w-10 h-10 rounded-lg object-cover border border-gray-100 shrink-0">
        <div class="flex-1 min-w-0">
          <div class="text-sm font-semibold text-gray-800 line-clamp-1"><?= e($lp['TenSanPham']) ?></div>
          <div class="text-xs text-gray-500"><?= e($lp['TenDanhMuc'] ?? '') ?></div>
        </div>
        <?php if ($isOut): ?>
          <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700 shrink-0">Hết hàng</span>
        <?php else: ?>
          <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 shrink-0">Còn <?= (int)$lp['SoLuong'] ?></span>
        <?php endif; ?>
        <a href="<?= url('admin/products.php?action=edit&id=' . (int)$lp['MaSanPham']) ?>"
           class="text-xs text-brand-500 font-semibold hover:underline whitespace-nowrap shrink-0">Nhập thêm</a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (count($low_stock_products) > 6): ?>
    <div class="px-5 py-3 text-center bg-gray-50">
      <a href="<?= url('admin/warehouse.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">
        Xem tất cả <?= count($low_stock_products) ?> sản phẩm tồn thấp →
      </a>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- 2-column layout -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-6">

  <!-- Recent orders -->
  <div class="xl:col-span-2 bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
      <h2 class="text-base font-bold m-0">Đơn hàng gần đây</h2>
      <a href="<?= url('admin/invoices.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">
        Xem tất cả <i class="fa-solid fa-arrow-right text-[10px]"></i>
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
          <tr>
            <th class="px-5 py-3 text-left">Mã</th>
            <th class="px-5 py-3 text-left">Khách hàng</th>
            <th class="px-5 py-3 text-left">Ngày</th>
            <th class="px-5 py-3 text-right">Tiền</th>
            <th class="px-5 py-3 text-left">Trạng thái</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php if (empty($recent_orders)): ?>
            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Chưa có đơn hàng nào</td></tr>
          <?php else: foreach ($recent_orders as $o):
            $statusClass = match ((int)$o['TrangThai']) {
              0 => 'bg-amber-100 text-amber-700',
              1 => 'bg-blue-100 text-blue-700',
              2 => 'bg-emerald-100 text-emerald-700',
              3 => 'bg-red-100 text-red-700',
              default => 'bg-gray-100 text-gray-700',
            };
          ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-3 font-semibold text-gray-700">#<?= (int)$o['MaHoaDon'] ?></td>
              <td class="px-5 py-3"><?= e($o['TenKhachHang'] ?? '—') ?></td>
              <td class="px-5 py-3 text-gray-500 text-xs"><?= format_datetime($o['NgayBan']) ?></td>
              <td class="px-5 py-3 text-right font-bold text-brand-500"><?= format_currency($o['ThanhTien']) ?></td>
              <td class="px-5 py-3">
                <span class="inline-block px-2 py-1 rounded-md text-xs font-semibold <?= $statusClass ?>">
                  <?= e($invoiceBLL->statusLabel((int)$o['TrangThai'])) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Right column -->
  <div class="space-y-4">
    <!-- Status donut -->
    <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold m-0">📦 Trạng thái đơn</h2>
        <a href="<?= url('admin/stats.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">Chi tiết →</a>
      </div>
      <div style="position:relative; height: 180px"><canvas id="dashStatusChart"></canvas></div>
      <div class="mt-3 space-y-1 text-xs">
        <?php foreach ($status_dist as $s):
          $key = (int)$s['TrangThai'];
        ?>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full" style="background:<?= $status_colors[$key] ?? '#999' ?>"></span>
              <span class="text-gray-700"><?= e($status_labels[$key] ?? '?') ?></span>
            </div>
            <span class="text-gray-500 font-semibold"><?= $s['so_don'] ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Top products (sold) -->
    <div class="bg-white rounded-xl shadow-card border border-gray-100 overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-bold m-0">🏆 Bán chạy</h2>
        <a href="<?= url('admin/stats.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">Xem tất cả</a>
      </div>
      <div class="divide-y divide-gray-100">
        <?php if (empty($top_products)): ?>
          <div class="p-5 text-center text-gray-400 text-sm">Chưa có dữ liệu bán hàng</div>
        <?php else: foreach ($top_products as $i => $p): ?>
          <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors">
            <span class="w-7 h-7 rounded-full bg-brand-50 text-brand-600 text-xs font-bold flex items-center justify-center shrink-0">
              <?= $i + 1 ?>
            </span>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-semibold text-gray-800 line-clamp-1"><?= e($p['TenSanPham']) ?></div>
              <div class="text-xs text-gray-500">Đã bán <?= (int)$p['so_luong_ban'] ?> · <?= format_currency($p['doanh_thu']) ?></div>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Revenue chart (full width below KPIs+orders) -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 p-5 mb-5">
  <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <h2 class="text-base font-bold m-0 flex items-center gap-2">
      <i class="fa-solid fa-chart-line text-brand-500"></i> Doanh thu 14 ngày qua
    </h2>
    <a href="<?= url('admin/stats.php') ?>" class="text-xs text-brand-500 font-semibold hover:underline">
      Xem toàn bộ thống kê →
    </a>
  </div>
  <div style="position:relative; height: 240px"><canvas id="dashRevenueChart"></canvas></div>
</div>

<!-- Quick action grid -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
  <h2 class="text-base font-bold m-0 mb-4">Truy cập nhanh</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    <?php
    $quick = [
      ['Sản phẩm',  'products.php',        'fa-box',           'bg-blue-50 text-blue-600'],
      ['Danh mục',  'categories.php',      'fa-list',          'bg-violet-50 text-violet-600'],
      ['Đơn hàng',  'invoices.php',        'fa-receipt',       'bg-emerald-50 text-emerald-600'],
      ['Khách hàng','customers.php',       'fa-users',         'bg-brand-50 text-brand-600'],
      ['Nhân viên', 'employees.php',       'fa-user-tie',      'bg-amber-50 text-amber-600'],
      ['Chatbot',   'chatbot.php',         'fa-robot',         'bg-indigo-50 text-indigo-600'],
    ];
    foreach ($quick as [$label, $href, $icon, $bg]): ?>
      <a href="<?= url('admin/' . $href) ?>"
         class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-100 hover:border-brand-300 hover:shadow-card transition-all">
        <div class="w-12 h-12 rounded-lg <?= $bg ?> flex items-center justify-center text-xl">
          <i class="fa-solid <?= $icon ?>"></i>
        </div>
        <div class="text-xs font-semibold text-gray-700 text-center"><?= $label ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
  Chart.defaults.font.size = 11;
  Chart.defaults.color = '#6b7280';
  Chart.defaults.plugins.legend.display = false;
  const fmtVND = v => new Intl.NumberFormat('vi-VN').format(v) + 'đ';

  // Revenue trend
  new Chart(document.getElementById('dashRevenueChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_map(fn($r) => date('d/m', strtotime($r['ngay'])), $revenue_trend14)) ?>,
      datasets: [{
        label: 'Doanh thu',
        data: <?= json_encode(array_column($revenue_trend14, 'doanh_thu')) ?>,
        borderColor: '#DD2D4A',
        backgroundColor: ctx => {
          const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 240);
          g.addColorStop(0, 'rgba(221,45,74,0.25)');
          g.addColorStop(1, 'rgba(221,45,74,0)');
          return g;
        },
        fill: true, tension: 0.35, borderWidth: 2.5,
        pointRadius: 0, pointHoverRadius: 6, pointBackgroundColor: '#DD2D4A',
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: { tooltip: { callbacks: { label: ctx => fmtVND(ctx.raw) } } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#f3f4f6' }, ticks: { callback: v => v >= 1e6 ? (v/1e6).toFixed(1) + 'tr' : (v/1e3) + 'k' } }
      }
    }
  });

  // Status donut
  new Chart(document.getElementById('dashStatusChart'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_map(fn($s) => $status_labels[(int)$s['TrangThai']] ?? '?', $status_dist), JSON_UNESCAPED_UNICODE) ?>,
      datasets: [{
        data: <?= json_encode(array_column($status_dist, 'so_don')) ?>,
        backgroundColor: <?= json_encode(array_map(fn($s) => $status_colors[(int)$s['TrangThai']] ?? '#999', $status_dist)) ?>,
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '70%',
      plugins: { tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' đơn' } } }
    }
  });
</script>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
