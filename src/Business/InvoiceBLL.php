<?php
require_once __DIR__ . '/../DataAccess/InvoiceDAL.php';
require_once __DIR__ . '/../DataAccess/ProductDAL.php';
require_once __DIR__ . '/CartBLL.php';
require_once __DIR__ . '/NotificationBLL.php';

class InvoiceBLL {
    private InvoiceDAL $invoiceDAL;
    private ProductDAL $productDAL;
    private NotificationBLL $notificationBLL;

    public function __construct() {
        $this->invoiceDAL = new InvoiceDAL();
        $this->productDAL = new ProductDAL();
        $this->notificationBLL = new NotificationBLL();
    }

    public function checkout(int $maKhachHang, array $info, array $cartItems): array {
        if (empty($cartItems)) return ['error' => 'Giỏ hàng trống'];
        if (empty($info['DiaChiGH'])) return ['error' => 'Vui lòng nhập địa chỉ giao hàng'];
        if (empty($info['SDT'])) return ['error' => 'Vui lòng nhập số điện thoại'];

        $total = 0;
        foreach ($cartItems as $it) {
            $total += (float)$it['GiaBan'] * (int)$it['SoLuong'];
        }

        $invoiceId = $this->invoiceDAL->create([
            'ThanhTien'   => $total,
            'MaKhachHang' => $maKhachHang,
            'SDT'         => $info['SDT'],
            'Email'       => $info['Email'] ?? null,
            'DiaChiGH'    => $info['DiaChiGH'],
            'GhiChu'      => $info['GhiChu'] ?? null,
            'HinhThucTT'  => $info['HinhThucTT'] ?? 'COD',
            'TrangThai'   => 0,
        ], $cartItems);

        // Khong tru kho luc dat hang. Ton kho chi bi tru khi don duoc xac nhan
        // "Hoan thanh" (xem updateStatus). Nho vay don bi huy se khong an mat kho.
        return ['success' => true, 'invoice_id' => $invoiceId];
    }

    public function getAll(): array {
        return $this->invoiceDAL->getAll();
    }

    public function getById(int $id): ?array {
        return $this->invoiceDAL->getById($id);
    }

    public function getDetails(int $id): array {
        return $this->invoiceDAL->getDetails($id);
    }

    public function getByCustomer(int $cid): array {
        return $this->invoiceDAL->getByCustomer($cid);
    }

    public function search(string $kw): array {
        return $this->invoiceDAL->search($kw);
    }

    /**
     * Cap nhat trang thai don hang, dong thoi quan ly ton kho:
     *  - Chuyen SANG "Hoan thanh" (2) lan dau  -> tru kho + canh bao ton thap.
     *  - ROI KHOI "Hoan thanh" (vd huy lai)     -> hoan tra kho da tru.
     * Chong tru/hoan trung bang cach so sanh trang thai cu va moi.
     */
    public function updateStatus(int $id, int $status): bool {
        $current = $this->invoiceDAL->getById($id);
        if (!$current) return false;
        $old = (int)$current['TrangThai'];

        if ($old === $status) return true; // khong doi gi

        if (!$this->invoiceDAL->updateStatus($id, $status)) return false;

        if ($status === 2 && $old !== 2) {
            $this->applyOrderStock($id, -1); // tru kho khi hoan thanh
        } elseif ($old === 2 && $status !== 2) {
            $this->applyOrderStock($id, +1); // hoan kho khi roi khoi hoan thanh
        }
        return true;
    }

    /** Tru (-1) hoac hoan (+1) kho theo cac dong cua 1 don hang. */
    private function applyOrderStock(int $invoiceId, int $sign): void {
        foreach ($this->invoiceDAL->getDetails($invoiceId) as $d) {
            $pid = (int)($d['MaSanPham'] ?? 0);
            $qty = (int)($d['SoLuong'] ?? 0);
            if ($pid <= 0 || $qty <= 0) continue;

            if ($sign < 0) {
                $this->productDAL->decreaseStock($pid, $qty);
                // Sau khi tru kho: canh bao admin neu san pham het / sap het hang.
                $remaining = $this->productDAL->getStock($pid);
                $this->notificationBLL->checkStockLevel($pid, (string)($d['TenSanPham'] ?? ('SP #' . $pid)), $remaining);
            } else {
                $this->productDAL->increaseStock($pid, $qty);
            }
        }
    }

    public function delete(int $id): bool {
        return $this->invoiceDAL->delete($id);
    }

    public function statusLabel(int $status): string {
        return match ($status) {
            0 => 'Chờ xác nhận',
            1 => 'Đang giao',
            2 => 'Hoàn thành',
            3 => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    public function totalRevenue(): float {
        return $this->invoiceDAL->totalRevenue();
    }

    public function countAll(): int {
        return $this->invoiceDAL->countAll();
    }

    public function countByStatus(int $status): int {
        return $this->invoiceDAL->countByStatus($status);
    }

    public function statsRevenue(): array {
        return $this->invoiceDAL->statsRevenue();
    }

    public function revenueTrend(int $days = 30): array         { return $this->invoiceDAL->revenueTrend($days); }
    public function revenueByMonth(int $months = 12): array     { return $this->invoiceDAL->revenueByMonth($months); }
    public function topProducts(int $limit = 10): array         { return $this->invoiceDAL->topProducts($limit); }
    public function revenueByCategory(): array                  { return $this->invoiceDAL->revenueByCategory(); }
    public function statusDistribution(): array                 { return $this->invoiceDAL->statusDistribution(); }
    public function paymentDistribution(): array                { return $this->invoiceDAL->paymentDistribution(); }
    public function averageOrderValue(): float                  { return $this->invoiceDAL->averageOrderValue(); }
    public function ordersByHour(): array                       { return $this->invoiceDAL->ordersByHour(); }
}
