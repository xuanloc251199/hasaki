<?php
require_once __DIR__ . '/../DataAccess/NotificationDAL.php';
require_once __DIR__ . '/../helpers/mailer.php';

/**
 * Nghiep vu thong bao admin + canh bao ton kho.
 *
 * Diem mau chot: checkStockLevel() duoc goi sau khi tru kho luc thanh toan
 * (InvoiceBLL::checkout). Khi ton kho cham nguong se tao thong bao noi bo
 * (chuong admin) va gui email canh bao (neu bat).
 */
class NotificationBLL {
    private NotificationDAL $dal;

    public function __construct() {
        $this->dal = new NotificationDAL();
    }

    /** Nguong canh bao "sap het hang" (mac dinh 10). */
    public function lowStockThreshold(): int {
        $v = (int)setting('low_stock_threshold', '10');
        return $v >= 0 ? $v : 10;
    }

    /**
     * Kiem tra muc ton kho cua 1 san pham va tao canh bao neu can.
     * Tra ve loai canh bao da tao ('het_hang' | 'sap_het') hoac null neu khong.
     */
    public function checkStockLevel(int $productId, string $tenSanPham, int $soLuong): ?string {
        $threshold = $this->lowStockThreshold();

        if ($soLuong <= 0) {
            $loai  = 'het_hang';
            $mucDo = 'danger';
            $tieuDe = 'Sản phẩm đã hết hàng';
            $noiDung = sprintf('Sản phẩm "%s" (#%d) đã HẾT HÀNG. Vui lòng nhập thêm hàng.', $tenSanPham, $productId);
        } elseif ($soLuong <= $threshold) {
            $loai  = 'sap_het';
            $mucDo = 'warning';
            $tieuDe = 'Sản phẩm sắp hết hàng';
            $noiDung = sprintf('Sản phẩm "%s" (#%d) sắp hết hàng - chỉ còn %d sản phẩm (ngưỡng %d).',
                               $tenSanPham, $productId, $soLuong, $threshold);
        } else {
            return null; // ton kho con du, khong canh bao
        }

        // Tranh tao trung lap: chi tao khi chua co thong bao chua doc cung loai.
        if ($this->dal->existsUnread($loai, $productId)) {
            return null;
        }

        $this->dal->create([
            'Loai'        => $loai,
            'MaThamChieu' => $productId,
            'TieuDe'      => $tieuDe,
            'NoiDung'     => $noiDung,
            'MucDo'       => $mucDo,
            'Link'        => 'admin/warehouse.php?q=' . rawurlencode($tenSanPham),
        ]);

        // Gui email canh bao (best-effort, khong lam hong checkout).
        $this->sendStockEmail($loai, $tenSanPham, $productId, $soLuong, $threshold);

        return $loai;
    }

    private function sendStockEmail(string $loai, string $tenSanPham, int $productId, int $soLuong, int $threshold): void {
        $isOut   = $loai === 'het_hang';
        $subject = ($isOut ? '[HẾT HÀNG] ' : '[SẮP HẾT] ') . $tenSanPham;

        $badgeColor = $isOut ? '#ef4444' : '#f59e0b';
        $statusText = $isOut
            ? 'Sản phẩm đã <strong>HẾT HÀNG</strong> (tồn = 0).'
            : 'Sản phẩm <strong>sắp hết hàng</strong>, chỉ còn <strong>' . $soLuong . '</strong> sản phẩm (ngưỡng cảnh báo: ' . $threshold . ').';

        $body = '<div style="font-family:Arial,Helvetica,sans-serif;max-width:560px;margin:0 auto;border:1px solid #eee;border-radius:10px;overflow:hidden">'
            . '<div style="background:' . $badgeColor . ';color:#fff;padding:16px 20px;font-size:16px;font-weight:bold">'
            . ($isOut ? '⛔ Cảnh báo HẾT HÀNG' : '⚠️ Cảnh báo sắp hết hàng')
            . '</div>'
            . '<div style="padding:20px;color:#333;font-size:14px;line-height:1.6">'
            . '<p style="margin:0 0 10px">Xin chào Quản trị viên,</p>'
            . '<p style="margin:0 0 14px">' . $statusText . '</p>'
            . '<table style="width:100%;border-collapse:collapse;font-size:14px">'
            . '<tr><td style="padding:6px 0;color:#888;width:140px">Sản phẩm</td><td style="padding:6px 0;font-weight:bold">' . e($tenSanPham) . '</td></tr>'
            . '<tr><td style="padding:6px 0;color:#888">Mã sản phẩm</td><td style="padding:6px 0">#' . $productId . '</td></tr>'
            . '<tr><td style="padding:6px 0;color:#888">Số lượng còn</td><td style="padding:6px 0;font-weight:bold;color:' . $badgeColor . '">' . $soLuong . '</td></tr>'
            . '<tr><td style="padding:6px 0;color:#888">Thời gian</td><td style="padding:6px 0">' . date('d/m/Y H:i') . '</td></tr>'
            . '</table>'
            . '<p style="margin:16px 0 0;color:#666">Vui lòng đăng nhập trang quản trị để nhập thêm hàng cho sản phẩm này.</p>'
            . '</div>'
            . '<div style="background:#fafafa;padding:12px 20px;color:#999;font-size:12px">Email tự động từ hệ thống ' . e((string)setting('site_name', SITE_NAME)) . '</div>'
            . '</div>';

        send_admin_alert($subject, $body);
    }

    // -------- Pass-through cho UI admin --------

    public function recent(int $limit = 10): array { return $this->dal->getRecent($limit); }
    public function all(): array                    { return $this->dal->getAll(); }
    public function countUnread(): int              { return $this->dal->countUnread(); }
    public function markRead(int $id): bool         { return $this->dal->markRead($id); }
    public function markAllRead(): bool             { return $this->dal->markAllRead(); }
    public function delete(int $id): bool           { return $this->dal->delete($id); }
    public function deleteRead(): bool              { return $this->dal->deleteRead(); }

    /** Nhan mau hien thi cho loai thong bao. */
    public function typeLabel(string $loai): string {
        return match ($loai) {
            'het_hang' => 'Hết hàng',
            'sap_het'  => 'Sắp hết',
            default    => 'Thông báo',
        };
    }
}
