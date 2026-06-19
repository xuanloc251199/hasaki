<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Business/InvoiceBLL.php';
require_once __DIR__ . '/../../src/Business/CustomerBLL.php';
require_once __DIR__ . '/../../src/Business/ProductBLL.php';

require_admin();

$page_title = 'Thống kê & Báo cáo';
$invoiceBLL  = new InvoiceBLL();
$customerBLL = new CustomerBLL();
$productBLL  = new ProductBLL();

// KPIs
$totalRevenue = $invoiceBLL->totalRevenue();
$totalOrders  = $invoiceBLL->countAll();
$completed    = $invoiceBLL->countByStatus(2);
$pending      = $invoiceBLL->countByStatus(0);
$avgOrder     = $invoiceBLL->averageOrderValue();

// Time series
$days = (int)($_GET['days'] ?? 30);
if (!in_array($days, [7, 14, 30, 60, 90], true)) $days = 30;
$revenueTrend  = $invoiceBLL->revenueTrend($days);
$revenueMonths = $invoiceBLL->revenueByMonth(12);

// Distributions
$statusDist  = $invoiceBLL->statusDistribution();
$paymentDist = $invoiceBLL->paymentDistribution();
$categoryRev = $invoiceBLL->revenueByCategory();
$topProducts = $invoiceBLL->topProducts(10);
$customerGrowth = $customerBLL->growthByMonth(12);
$ordersByHour   = $invoiceBLL->ordersByHour();

// Status labels
$statusLabels = [0 => 'Chờ xác nhận', 1 => 'Đang giao', 2 => 'Hoàn thành', 3 => 'Đã hủy'];
$statusColors = [0 => '#f59e0b', 1 => '#3b82f6', 2 => '#10b981', 3 => '#ef4444'];

require __DIR__ . '/includes/layout-top.php';
?>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
  <div>
    <h1 class="text-2xl font-bold text-gray-900 m-0">Thống kê & Báo cáo</h1>
    <p class="text-sm text-gray-500 mt-1">Phân tích doanh thu, đơn hàng và khách hàng</p>
  </div>
  <div class="flex items-center gap-2 bg-white rounded-full p-1 border border-gray-200 shadow-sm">
    <span class="text-xs text-gray-500 px-3">Khoảng:</span>
    <?php foreach ([7 => '7N', 14 => '14N', 30 => '30N', 60 => '60N', 90 => '90N'] as $d => $label): ?>
      <a href="?days=<?= $d ?>"
         class="px-3 py-1.5 text-xs font-semibold rounded-full transition-colors <?= $days === $d ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
        <?= $label ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- KPIs -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="bg-gradient-to-br from-brand-500 to-brand-400 text-white rounded-xl p-5 shadow-soft relative overflow-hidden">
    <div class="absolute -right-3 -top-3 text-6xl text-white/20"><i class="fa-solid fa-sack-dollar"></i></div>
    <div class="relative">
      <div class="text-xs uppercase tracking-wider opacity-90">Doanh thu</div>
      <div class="text-2xl font-extrabold mt-1.5"><?= format_currency($totalRevenue) ?></div>
      <div class="text-[11px] opacity-80 mt-1">Đã hoàn thành</div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Tổng đơn</div>
        <div class="text-2xl font-extrabold mt-1.5 text-gray-900"><?= $totalOrders ?></div>
        <div class="text-[11px] text-emerald-600 mt-1"><i class="fa-solid fa-check"></i> <?= $completed ?> hoàn tất</div>
      </div>
      <div class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-receipt"></i>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Giá trị TB/đơn</div>
        <div class="text-2xl font-extrabold mt-1.5 text-gray-900"><?= format_currency($avgOrder) ?></div>
        <div class="text-[11px] text-blue-600 mt-1"><i class="fa-solid fa-chart-line"></i> AOV</div>
      </div>
      <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-coins"></i>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-xl p-5 shadow-card border border-gray-100">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-xs uppercase tracking-wider text-gray-500">Chờ xử lý</div>
        <div class="text-2xl font-extrabold mt-1.5 text-gray-900"><?= $pending ?></div>
        <div class="text-[11px] text-amber-600 mt-1"><i class="fa-solid fa-clock"></i> Đang chờ</div>
      </div>
      <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
        <i class="fa-solid fa-hourglass-half"></i>
      </div>
    </div>
  </div>
</div>

<!-- Revenue trend (full-width) -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 p-5 mb-5">
  <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <h2 class="text-base font-bold m-0 flex items-center gap-2">
      <i class="fa-solid fa-chart-line text-brand-500"></i> Xu hướng doanh thu (<?= $days ?> ngày qua)
    </h2>
    <div class="flex gap-3 text-xs">
      <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span> Doanh thu</div>
      <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-mint-500"></span> Số đơn</div>
    </div>
  </div>
  <div style="position:relative; height: 320px"><canvas id="chartRevenue"></canvas></div>
</div>

<!-- 2-col charts -->
<div class="grid lg:grid-cols-3 gap-5 mb-5">
  <!-- Status donut -->
  <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
    <h2 class="text-base font-bold m-0 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-chart-pie text-brand-500"></i> Trạng thái đơn
    </h2>
    <div style="position:relative; height: 220px"><canvas id="chartStatus"></canvas></div>
    <div class="mt-3 space-y-1.5 text-xs">
      <?php foreach ($statusDist as $s):
        $key = (int)$s['TrangThai'];
        $pct = $totalOrders ? round(($s['so_don'] / $totalOrders) * 100, 1) : 0;
      ?>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full" style="background:<?= $statusColors[$key] ?? '#999' ?>"></span>
            <span class="text-gray-700"><?= e($statusLabels[$key] ?? '?') ?></span>
          </div>
          <div class="text-gray-500"><?= $s['so_don'] ?> đơn · <?= $pct ?>%</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Payment methods -->
  <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
    <h2 class="text-base font-bold m-0 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-credit-card text-brand-500"></i> Hình thức thanh toán
    </h2>
    <div style="position:relative; height: 220px"><canvas id="chartPayment"></canvas></div>
    <div class="mt-3 space-y-1.5 text-xs">
      <?php
      $paymentColors = ['COD' => '#ec4899', 'VNPAY' => '#3b82f6', 'MOMO' => '#a855f7', 'BANK' => '#14b8a6'];
      $totalCompleted = array_sum(array_column($paymentDist, 'so_don'));
      foreach ($paymentDist as $p):
        $pct = $totalCompleted ? round(($p['so_don'] / $totalCompleted) * 100, 1) : 0;
      ?>
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full" style="background:<?= $paymentColors[$p['HinhThucTT']] ?? '#999' ?>"></span>
            <span class="text-gray-700 font-semibold"><?= e($p['HinhThucTT']) ?></span>
          </div>
          <div class="text-gray-500"><?= $p['so_don'] ?> đơn · <?= $pct ?>%</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Category revenue -->
  <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
    <h2 class="text-base font-bold m-0 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-tags text-brand-500"></i> Doanh thu theo danh mục
    </h2>
    <div style="position:relative; height: 220px"><canvas id="chartCategory"></canvas></div>
    <div class="mt-3 space-y-1.5 text-xs">
      <?php
      $catTotal = array_sum(array_column($categoryRev, 'doanh_thu'));
      foreach ($categoryRev as $i => $c):
        $pct = $catTotal ? round(($c['doanh_thu'] / $catTotal) * 100, 1) : 0;
      ?>
        <div class="flex items-center justify-between">
          <span class="text-gray-700 truncate"><?= e($c['TenDanhMuc']) ?></span>
          <span class="text-gray-500 shrink-0 ml-2"><?= $pct ?>%</span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Top products -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 p-5 mb-5">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-base font-bold m-0 flex items-center gap-2">
      <i class="fa-solid fa-trophy text-brand-500"></i> Top 10 sản phẩm bán chạy
    </h2>
    <span class="text-xs text-gray-500">Theo số lượng bán</span>
  </div>
  <div class="grid lg:grid-cols-[1fr_1fr] gap-6">
    <div style="position:relative; height: 360px"><canvas id="chartTopProducts"></canvas></div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
          <tr>
            <th class="px-3 py-2 text-left">#</th>
            <th class="px-3 py-2 text-left">Sản phẩm</th>
            <th class="px-3 py-2 text-right">SL</th>
            <th class="px-3 py-2 text-right">DT</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($topProducts as $i => $p): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-3 py-2">
                <span class="inline-flex w-6 h-6 rounded-full text-[10px] font-bold items-center justify-center
                  <?= $i === 0 ? 'bg-amber-100 text-amber-700' : ($i <= 2 ? 'bg-gray-100 text-gray-600' : 'bg-gray-50 text-gray-500') ?>">
                  <?= $i + 1 ?>
                </span>
              </td>
              <td class="px-3 py-2">
                <div class="font-semibold text-gray-800 line-clamp-1"><?= e($p['TenSanPham']) ?></div>
                <div class="text-[11px] text-gray-400"><?= e($p['ThuongHieu'] ?? '—') ?> · <?= e($p['TenDanhMuc']) ?></div>
              </td>
              <td class="px-3 py-2 text-right font-bold text-gray-700"><?= (int)$p['so_luong_ban'] ?></td>
              <td class="px-3 py-2 text-right font-bold text-brand-500 text-xs"><?= format_currency($p['doanh_thu']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($topProducts)): ?>
            <tr><td colspan="4" class="px-3 py-6 text-center text-gray-400">Chưa có dữ liệu</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Monthly revenue + Customer growth -->
<div class="grid lg:grid-cols-2 gap-5 mb-5">
  <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
    <h2 class="text-base font-bold m-0 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-chart-column text-brand-500"></i> Doanh thu theo tháng (12 tháng)
    </h2>
    <div style="position:relative; height: 280px"><canvas id="chartMonthly"></canvas></div>
  </div>

  <div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
    <h2 class="text-base font-bold m-0 mb-3 flex items-center gap-2">
      <i class="fa-solid fa-user-plus text-brand-500"></i> Tăng trưởng khách hàng
    </h2>
    <div style="position:relative; height: 280px"><canvas id="chartCustomers"></canvas></div>
  </div>
</div>

<!-- Hourly heatmap -->
<div class="bg-white rounded-xl shadow-card border border-gray-100 p-5">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-base font-bold m-0 flex items-center gap-2">
      <i class="fa-solid fa-clock text-brand-500"></i> Phân bố đơn theo giờ trong ngày
    </h2>
    <span class="text-xs text-gray-500">30 ngày qua</span>
  </div>
  <div style="position:relative; height: 220px"><canvas id="chartHourly"></canvas></div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  // Chart.js global defaults
  Chart.defaults.font.family  = "'Plus Jakarta Sans', system-ui, sans-serif";
  Chart.defaults.font.size    = 11;
  Chart.defaults.color        = '#6b7280';
  Chart.defaults.plugins.legend.display = false;

  const formatVND = v => new Intl.NumberFormat('vi-VN').format(v) + 'đ';

  // ===== Revenue trend (line) =====
  new Chart(document.getElementById('chartRevenue'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_map(fn($r) => date('d/m', strtotime($r['ngay'])), $revenueTrend), JSON_UNESCAPED_UNICODE) ?>,
      datasets: [
        {
          label: 'Doanh thu',
          data: <?= json_encode(array_column($revenueTrend, 'doanh_thu')) ?>,
          borderColor: '#DD2D4A',
          backgroundColor: ctx => {
            const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 320);
            g.addColorStop(0, 'rgba(221,45,74,0.25)');
            g.addColorStop(1, 'rgba(221,45,74,0)');
            return g;
          },
          fill: true, tension: 0.35, borderWidth: 2.5,
          pointRadius: 0, pointHoverRadius: 6,
          pointBackgroundColor: '#DD2D4A',
          yAxisID: 'y'
        },
        {
          label: 'Số đơn',
          data: <?= json_encode(array_column($revenueTrend, 'so_don')) ?>,
          borderColor: '#4FAEC2',
          backgroundColor: 'rgba(79,174,194,0.1)',
          tension: 0.35, borderWidth: 2,
          pointRadius: 0, pointHoverRadius: 5,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        tooltip: {
          backgroundColor: '#fff', titleColor: '#111', bodyColor: '#374151',
          borderColor: '#e5e7eb', borderWidth: 1, padding: 12, cornerRadius: 8,
          callbacks: {
            label: ctx => ctx.dataset.label === 'Doanh thu'
              ? 'Doanh thu: ' + formatVND(ctx.raw)
              : ctx.dataset.label + ': ' + ctx.raw + ' đơn'
          }
        }
      },
      scales: {
        x: { grid: { display: false } },
        y:  { position: 'left',  ticks: { callback: v => v >= 1e6 ? (v/1e6).toFixed(1) + 'tr' : (v/1e3) + 'k' }, grid: { color: '#f3f4f6' } },
        y1: { position: 'right', grid: { display: false }, ticks: { precision: 0 } }
      }
    }
  });

  // ===== Status (doughnut) =====
  new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_map(fn($s) => $statusLabels[(int)$s['TrangThai']] ?? '?', $statusDist), JSON_UNESCAPED_UNICODE) ?>,
      datasets: [{
        data: <?= json_encode(array_column($statusDist, 'so_don')) ?>,
        backgroundColor: <?= json_encode(array_map(fn($s) => $statusColors[(int)$s['TrangThai']] ?? '#999', $statusDist)) ?>,
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '70%',
      plugins: {
        tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' đơn' } }
      }
    }
  });

  // ===== Payment (doughnut) =====
  new Chart(document.getElementById('chartPayment'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_column($paymentDist, 'HinhThucTT')) ?>,
      datasets: [{
        data: <?= json_encode(array_column($paymentDist, 'so_don')) ?>,
        backgroundColor: <?= json_encode(array_map(fn($p) => $paymentColors[$p['HinhThucTT']] ?? '#999', $paymentDist)) ?>,
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '70%',
      plugins: { tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + ' đơn' } } }
    }
  });

  // ===== Category revenue (pie) =====
  const categoryColors = ['#DD2D4A', '#F26A8D', '#F49CBB', '#4FAEC2', '#A8DEE8', '#7DC9D8', '#f59e0b', '#a855f7'];
  new Chart(document.getElementById('chartCategory'), {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_column($categoryRev, 'TenDanhMuc'), JSON_UNESCAPED_UNICODE) ?>,
      datasets: [{
        data: <?= json_encode(array_column($categoryRev, 'doanh_thu')) ?>,
        backgroundColor: categoryColors.slice(0, <?= count($categoryRev) ?>),
        borderWidth: 2, borderColor: '#fff',
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { tooltip: { callbacks: { label: ctx => ctx.label + ': ' + formatVND(ctx.raw) } } }
    }
  });

  // ===== Top products (horizontal bar) =====
  new Chart(document.getElementById('chartTopProducts'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_map(fn($p) => mb_strlen($p['TenSanPham']) > 32 ? mb_substr($p['TenSanPham'], 0, 30) . '…' : $p['TenSanPham'], $topProducts), JSON_UNESCAPED_UNICODE) ?>,
      datasets: [{
        label: 'Số lượng bán',
        data: <?= json_encode(array_column($topProducts, 'so_luong_ban')) ?>,
        backgroundColor: ctx => {
          const g = ctx.chart.ctx.createLinearGradient(0, 0, ctx.chart.width, 0);
          g.addColorStop(0, '#DD2D4A');
          g.addColorStop(1, '#F26A8D');
          return g;
        },
        borderRadius: 6, borderSkipped: false,
      }]
    },
    options: {
      indexAxis: 'y', responsive: true, maintainAspectRatio: false,
      plugins: { tooltip: { callbacks: { label: ctx => 'Đã bán ' + ctx.raw + ' sản phẩm' } } },
      scales: {
        x: { grid: { color: '#f3f4f6' }, ticks: { precision: 0 } },
        y: { grid: { display: false } }
      }
    }
  });

  // ===== Monthly revenue (bar) =====
  new Chart(document.getElementById('chartMonthly'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_map(fn($r) => 'T' . (int)substr($r['thang'], 5, 2), $revenueMonths)) ?>,
      datasets: [{
        label: 'Doanh thu',
        data: <?= json_encode(array_column($revenueMonths, 'doanh_thu')) ?>,
        backgroundColor: ctx => {
          const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
          g.addColorStop(0, '#DD2D4A');
          g.addColorStop(1, '#F49CBB');
          return g;
        },
        borderRadius: 8, borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { tooltip: { callbacks: { label: ctx => formatVND(ctx.raw) } } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#f3f4f6' }, ticks: { callback: v => v >= 1e6 ? (v/1e6).toFixed(0) + 'tr' : (v/1e3) + 'k' } }
      }
    }
  });

  // ===== Customer growth (line) =====
  new Chart(document.getElementById('chartCustomers'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_map(fn($r) => 'T' . (int)substr($r['thang'], 5, 2), $customerGrowth)) ?>,
      datasets: [{
        label: 'Khách hàng mới',
        data: <?= json_encode(array_column($customerGrowth, 'so_kh')) ?>,
        borderColor: '#4FAEC2',
        backgroundColor: ctx => {
          const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
          g.addColorStop(0, 'rgba(79,174,194,0.3)');
          g.addColorStop(1, 'rgba(79,174,194,0)');
          return g;
        },
        fill: true, tension: 0.4, borderWidth: 2.5,
        pointBackgroundColor: '#4FAEC2', pointRadius: 4, pointHoverRadius: 6,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { tooltip: { callbacks: { label: ctx => ctx.raw + ' khách hàng' } } },
      scales: {
        x: { grid: { display: false } },
        y: { grid: { color: '#f3f4f6' }, beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });

  // ===== Hourly distribution (bar) =====
  new Chart(document.getElementById('chartHourly'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_map(fn($h) => str_pad($h['gio'], 2, '0', STR_PAD_LEFT) . 'h', $ordersByHour)) ?>,
      datasets: [{
        data: <?= json_encode(array_column($ordersByHour, 'so_don')) ?>,
        backgroundColor: '#F26A8D',
        borderRadius: 4, borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { tooltip: { callbacks: { label: ctx => ctx.raw + ' đơn' } } },
      scales: {
        x: { grid: { display: false }, ticks: { autoSkip: false, maxRotation: 0 } },
        y: { grid: { color: '#f3f4f6' }, ticks: { precision: 0 } }
      }
    }
  });
</script>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
