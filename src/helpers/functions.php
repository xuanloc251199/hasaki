<?php

/**
 * Common helper functions
 */

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_currency($amount): string
{
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}

function format_date($date): string
{
    if (!$date) return '';
    return date('d/m/Y', strtotime($date));
}

function format_datetime($date): string
{
    if (!$date) return '';
    return date('d/m/Y H:i', strtotime($date));
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function is_admin(): bool
{
    return isset($_SESSION['role']) && (int)$_SESSION['role'] === 1;
}

function is_employee(): bool
{
    return isset($_SESSION['role']) && in_array((int)$_SESSION['role'], [1, 2], true);
}

function require_login(string $redirect = '/login.php'): void
{
    if (!is_logged_in()) {
        $_SESSION['flash_error'] = 'Vui lòng đăng nhập để tiếp tục';
        redirect(url($redirect));
    }
}

function require_admin(): void
{
    if (!is_employee()) {
        $_SESSION['flash_error'] = 'Bạn không có quyền truy cập';
        redirect(url('/login.php'));
    }
}

function flash_error(): ?string
{
    if (isset($_SESSION['flash_error'])) {
        $msg = $_SESSION['flash_error'];
        unset($_SESSION['flash_error']);
        return $msg;
    }
    return null;
}

function flash_success(): ?string
{
    if (isset($_SESSION['flash_success'])) {
        $msg = $_SESSION['flash_success'];
        unset($_SESSION['flash_success']);
        return $msg;
    }
    return null;
}

function set_flash_success(string $msg): void
{
    $_SESSION['flash_success'] = $msg;
}

function set_flash_error(string $msg): void
{
    $_SESSION['flash_error'] = $msg;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool
{
    return $token && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Get a site setting value (loads all settings once per request).
 * Usage: setting('site_name', 'HASAKI')
 */
function setting(string $key, ?string $default = null): ?string
{
    static $bll = null;
    if ($bll === null) {
        require_once __DIR__ . '/../Business/SettingBLL.php';
        $bll = new SettingBLL();
    }
    return $bll->get($key, $default);
}

/**
 * Get menu items for a position.
 * Usage: foreach (menu('header') as $item) { ... }
 */
function menu(string $position): array
{
    static $bll = null;
    if ($bll === null) {
        require_once __DIR__ . '/../Business/MenuBLL.php';
        $bll = new MenuBLL();
    }
    return $bll->getByPosition($position);
}

/**
 * Bank-transfer QR payment is available only when enabled in admin settings
 * AND a QR image has been uploaded (there is no real gateway — the customer
 * scans the image and transfers manually).
 */
function bank_transfer_enabled(): bool
{
    return setting('bank_transfer_enable', '1') === '1'
        && trim((string)setting('bank_qr_image', '')) !== '';
}

/**
 * Human-readable label for a payment method code stored in HinhThucTT.
 */
function payment_method_label(?string $code): string
{
    return match ($code) {
        'COD'   => 'Thanh toán khi nhận hàng (COD)',
        'VNPAY' => 'Thẻ ATM / Internet Banking / VNPay',
        'MOMO'  => 'Ví điện tử MoMo',
        'BANK'  => 'Chuyển khoản ngân hàng (quét mã QR)',
        default => (string)$code,
    };
}

function cart_count(): int
{
    if (empty($_SESSION['cart'])) return 0;
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)$item['SoLuong'];
    }
    return $count;
}

function cart_total(): float
{
    if (empty($_SESSION['cart'])) return 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += (float)$item['GiaBan'] * (int)$item['SoLuong'];
    }
    return $total;
}

/**
 * Read the current page number from the query string (1-based, never below 1).
 */
function current_page(string $param = 'page'): int
{
    return max(1, (int)($_GET[$param] ?? 1));
}

/**
 * Slice an array of rows for the current page.
 * Returns: items (the slice), page (clamped), per_page, total, total_pages, offset.
 */
function paginate(array $items, int $page, int $perPage = 10): array
{
    $perPage    = max(1, $perPage);
    $total      = count($items);
    $totalPages = max(1, (int)ceil($total / $perPage));
    $page       = max(1, min($page, $totalPages));
    $offset     = ($page - 1) * $perPage;
    return [
        'items'       => array_slice($items, $offset, $perPage),
        'page'        => $page,
        'per_page'    => $perPage,
        'total'       => $total,
        'total_pages' => $totalPages,
        'offset'      => $offset,
    ];
}

/**
 * Render a pagination bar (inline-styled so it works on both the storefront and
 * the admin without depending on the Tailwind/SCSS build).
 * $params = extra query params to keep on each link (e.g. ['q' => $keyword]).
 */
function render_pagination(int $page, int $totalPages, array $params = [], string $accent = '#DD2D4A'): string
{
    if ($totalPages <= 1) return '';

    $link = function (int $p) use ($params): string {
        return '?' . http_build_query(array_merge($params, ['page' => $p]));
    };

    // Windowed page list: always show first & last, plus current ±2, with ellipses.
    $window = 2;
    $pages  = [];
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i === 1 || $i === $totalPages || ($i >= $page - $window && $i <= $page + $window)) {
            $pages[] = $i;
        } elseif (end($pages) !== '…') {
            $pages[] = '…';
        }
    }

    $base     = 'display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 12px;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;border:1px solid #e5e7eb;background:#fff;color:#374151';
    $active   = 'background:' . $accent . ';border-color:' . $accent . ';color:#fff;box-shadow:0 2px 8px ' . $accent . '55';
    $disabled = 'opacity:.45;pointer-events:none';
    $gap      = 'display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;color:#9ca3af;font-weight:700';

    ob_start();
?>
    <nav style="display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap;margin-top:24px" aria-label="Phân trang">
        <a href="<?= e($link(max(1, $page - 1))) ?>" style="<?= $base ?>;<?= $page <= 1 ? $disabled : '' ?>" aria-label="Trang trước">
            <i class="fa-solid fa-chevron-left" style="font-size:12px"></i>
        </a>
        <?php foreach ($pages as $p): ?>
            <?php if ($p === '…'): ?>
                <span style="<?= $gap ?>">…</span>
            <?php elseif ($p === $page): ?>
                <span style="<?= $base ?>;<?= $active ?>" aria-current="page"><?= $p ?></span>
            <?php else: ?>
                <a href="<?= e($link($p)) ?>" style="<?= $base ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
        <a href="<?= e($link(min($totalPages, $page + 1))) ?>" style="<?= $base ?>;<?= $page >= $totalPages ? $disabled : '' ?>" aria-label="Trang sau">
            <i class="fa-solid fa-chevron-right" style="font-size:12px"></i>
        </a>
    </nav>
<?php
    return ob_get_clean();
}
