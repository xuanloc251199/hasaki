<?php
/**
 * Common helper functions
 */

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_currency($amount): string {
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}

function format_date($date): string {
    if (!$date) return '';
    return date('d/m/Y', strtotime($date));
}

function format_datetime($date): string {
    if (!$date) return '';
    return date('d/m/Y H:i', strtotime($date));
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function is_admin(): bool {
    return isset($_SESSION['role']) && (int)$_SESSION['role'] === 1;
}

function is_employee(): bool {
    return isset($_SESSION['role']) && in_array((int)$_SESSION['role'], [1, 2], true);
}

function require_login(string $redirect = '/login.php'): void {
    if (!is_logged_in()) {
        $_SESSION['flash_error'] = 'Vui lòng đăng nhập để tiếp tục';
        redirect(url($redirect));
    }
}

function require_admin(): void {
    if (!is_employee()) {
        $_SESSION['flash_error'] = 'Bạn không có quyền truy cập';
        redirect(url('/login.php'));
    }
}

function flash_error(): ?string {
    if (isset($_SESSION['flash_error'])) {
        $msg = $_SESSION['flash_error'];
        unset($_SESSION['flash_error']);
        return $msg;
    }
    return null;
}

function flash_success(): ?string {
    if (isset($_SESSION['flash_success'])) {
        $msg = $_SESSION['flash_success'];
        unset($_SESSION['flash_success']);
        return $msg;
    }
    return null;
}

function set_flash_success(string $msg): void {
    $_SESSION['flash_success'] = $msg;
}

function set_flash_error(string $msg): void {
    $_SESSION['flash_error'] = $msg;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool {
    return $token && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Get a site setting value (loads all settings once per request).
 * Usage: setting('site_name', 'HASAKI')
 */
function setting(string $key, ?string $default = null): ?string {
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
function menu(string $position): array {
    static $bll = null;
    if ($bll === null) {
        require_once __DIR__ . '/../Business/MenuBLL.php';
        $bll = new MenuBLL();
    }
    return $bll->getByPosition($position);
}

function cart_count(): int {
    if (empty($_SESSION['cart'])) return 0;
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)$item['SoLuong'];
    }
    return $count;
}

function cart_total(): float {
    if (empty($_SESSION['cart'])) return 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += (float)$item['GiaBan'] * (int)$item['SoLuong'];
    }
    return $total;
}
