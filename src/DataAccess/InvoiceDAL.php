<?php
require_once __DIR__ . '/BaseDAL.php';

class InvoiceDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT hd.*, kh.TenKhachHang
             FROM HoaDonBan hd
             LEFT JOIN KhachHang kh ON hd.MaKhachHang = kh.MaKhachHang
             ORDER BY hd.MaHoaDon DESC"
        );
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT hd.*, kh.TenKhachHang
             FROM HoaDonBan hd
             LEFT JOIN KhachHang kh ON hd.MaKhachHang = kh.MaKhachHang
             WHERE hd.MaHoaDon = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByCustomer(int $customerId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM HoaDonBan WHERE MaKhachHang = ? ORDER BY MaHoaDon DESC"
        );
        $stmt->execute([$customerId]);
        return $stmt->fetchAll();
    }

    public function getDetails(int $invoiceId): array {
        $stmt = $this->db->prepare(
            "SELECT cthd.*, sp.HinhAnh
             FROM ChiTietHoaDonBan cthd
             LEFT JOIN SanPham sp ON cthd.MaSanPham = sp.MaSanPham
             WHERE cthd.MaHoaDon = ?"
        );
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT hd.*, kh.TenKhachHang
             FROM HoaDonBan hd
             LEFT JOIN KhachHang kh ON hd.MaKhachHang = kh.MaKhachHang
             WHERE kh.TenKhachHang LIKE ? OR hd.SDT LIKE ? OR hd.MaHoaDon = ?
             ORDER BY hd.MaHoaDon DESC"
        );
        $stmt->execute([$like, $like, (int)$keyword]);
        return $stmt->fetchAll();
    }

    public function create(array $invoice, array $items): int {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "INSERT INTO HoaDonBan (ThanhTien, MaKhachHang, SDT, Email, DiaChiGH, GhiChu, HinhThucTT, TrangThai)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $invoice['ThanhTien'],
                $invoice['MaKhachHang'],
                $invoice['SDT'] ?? null,
                $invoice['Email'] ?? null,
                $invoice['DiaChiGH'] ?? null,
                $invoice['GhiChu'] ?? null,
                $invoice['HinhThucTT'] ?? 'COD',
                $invoice['TrangThai'] ?? 0,
            ]);
            $invoiceId = (int)$this->db->lastInsertId();

            $itemStmt = $this->db->prepare(
                "INSERT INTO ChiTietHoaDonBan (MaHoaDon, MaSanPham, TenSanPham, SoLuong, GiaBan, KichCo, MauSac)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            foreach ($items as $item) {
                $itemStmt->execute([
                    $invoiceId,
                    $item['MaSanPham'],
                    $item['TenSanPham'],
                    $item['SoLuong'],
                    $item['GiaBan'],
                    $item['KichCo'] ?? null,
                    $item['MauSac'] ?? null,
                ]);
            }

            $this->db->commit();
            return $invoiceId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateStatus(int $id, int $status): bool {
        $stmt = $this->db->prepare("UPDATE HoaDonBan SET TrangThai = ? WHERE MaHoaDon = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM HoaDonBan WHERE MaHoaDon = ?");
        return $stmt->execute([$id]);
    }

    public function statsRevenue(): array {
        $stmt = $this->db->query(
            "SELECT DATE(NgayBan) AS ngay, SUM(ThanhTien) AS doanh_thu, COUNT(*) AS so_don
             FROM HoaDonBan
             WHERE TrangThai = 2
             GROUP BY DATE(NgayBan)
             ORDER BY ngay DESC
             LIMIT 30"
        );
        return $stmt->fetchAll();
    }

    /** Daily revenue trend for last $days, gap-filled (zero-fill missing dates). */
    public function revenueTrend(int $days = 30): array {
        $stmt = $this->db->prepare(
            "SELECT DATE(NgayBan) AS ngay, SUM(ThanhTien) AS doanh_thu, COUNT(*) AS so_don
             FROM HoaDonBan
             WHERE TrangThai = 2 AND NgayBan >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY DATE(NgayBan)
             ORDER BY ngay ASC"
        );
        $stmt->bindValue(1, $days, PDO::PARAM_INT);
        $stmt->execute();
        $byDay = [];
        foreach ($stmt->fetchAll() as $r) $byDay[$r['ngay']] = $r;

        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $out[] = [
                'ngay'      => $d,
                'doanh_thu' => (float)($byDay[$d]['doanh_thu'] ?? 0),
                'so_don'    => (int)($byDay[$d]['so_don'] ?? 0),
            ];
        }
        return $out;
    }

    /** Revenue by month - last $months months. */
    public function revenueByMonth(int $months = 12): array {
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(NgayBan, '%Y-%m') AS thang,
                    SUM(ThanhTien) AS doanh_thu,
                    COUNT(*) AS so_don
             FROM HoaDonBan
             WHERE TrangThai = 2 AND NgayBan >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY thang ORDER BY thang ASC"
        );
        $stmt->bindValue(1, $months, PDO::PARAM_INT);
        $stmt->execute();
        $byMonth = [];
        foreach ($stmt->fetchAll() as $r) $byMonth[$r['thang']] = $r;

        $out = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $out[] = [
                'thang'     => $m,
                'doanh_thu' => (float)($byMonth[$m]['doanh_thu'] ?? 0),
                'so_don'    => (int)($byMonth[$m]['so_don'] ?? 0),
            ];
        }
        return $out;
    }

    /** Top selling products (only counting completed orders). */
    public function topProducts(int $limit = 10): array {
        $stmt = $this->db->prepare(
            "SELECT sp.MaSanPham, sp.TenSanPham, sp.HinhAnh, sp.ThuongHieu, dm.TenDanhMuc,
                    SUM(ct.SoLuong) AS so_luong_ban,
                    SUM(ct.SoLuong * ct.GiaBan) AS doanh_thu
             FROM ChiTietHoaDonBan ct
             JOIN HoaDonBan hd ON ct.MaHoaDon = hd.MaHoaDon
             JOIN SanPham sp ON ct.MaSanPham = sp.MaSanPham
             LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             WHERE hd.TrangThai = 2
             GROUP BY sp.MaSanPham
             ORDER BY so_luong_ban DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Revenue distribution by category. */
    public function revenueByCategory(): array {
        $stmt = $this->db->query(
            "SELECT dm.MaDanhMuc, dm.TenDanhMuc,
                    SUM(ct.SoLuong * ct.GiaBan) AS doanh_thu,
                    SUM(ct.SoLuong) AS so_luong_ban,
                    COUNT(DISTINCT hd.MaHoaDon) AS so_don
             FROM ChiTietHoaDonBan ct
             JOIN HoaDonBan hd ON ct.MaHoaDon = hd.MaHoaDon
             JOIN SanPham sp ON ct.MaSanPham = sp.MaSanPham
             JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             WHERE hd.TrangThai = 2
             GROUP BY dm.MaDanhMuc
             ORDER BY doanh_thu DESC"
        );
        return $stmt->fetchAll();
    }

    /** Order count by status (all orders, not only completed). */
    public function statusDistribution(): array {
        $stmt = $this->db->query(
            "SELECT TrangThai, COUNT(*) AS so_don, COALESCE(SUM(ThanhTien),0) AS doanh_thu
             FROM HoaDonBan
             GROUP BY TrangThai"
        );
        return $stmt->fetchAll();
    }

    /** Payment method distribution. */
    public function paymentDistribution(): array {
        $stmt = $this->db->query(
            "SELECT HinhThucTT, COUNT(*) AS so_don, COALESCE(SUM(ThanhTien),0) AS doanh_thu
             FROM HoaDonBan
             WHERE TrangThai = 2
             GROUP BY HinhThucTT
             ORDER BY so_don DESC"
        );
        return $stmt->fetchAll();
    }

    /** Average order value. */
    public function averageOrderValue(): float {
        $stmt = $this->db->query("SELECT AVG(ThanhTien) FROM HoaDonBan WHERE TrangThai = 2");
        $val = $stmt->fetchColumn();
        return $val === null || $val === false ? 0.0 : (float)$val;
    }

    /** Hourly distribution (last 30 days, completed). */
    public function ordersByHour(): array {
        $stmt = $this->db->query(
            "SELECT HOUR(NgayBan) AS gio, COUNT(*) AS so_don
             FROM HoaDonBan
             WHERE TrangThai = 2 AND NgayBan >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY HOUR(NgayBan)
             ORDER BY gio"
        );
        $byHour = [];
        foreach ($stmt->fetchAll() as $r) $byHour[(int)$r['gio']] = (int)$r['so_don'];
        $out = [];
        for ($h = 0; $h < 24; $h++) {
            $out[] = ['gio' => $h, 'so_don' => $byHour[$h] ?? 0];
        }
        return $out;
    }

    public function totalRevenue(): float {
        $stmt = $this->db->query("SELECT COALESCE(SUM(ThanhTien),0) FROM HoaDonBan WHERE TrangThai = 2");
        return (float)$stmt->fetchColumn();
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM HoaDonBan")->fetchColumn();
    }

    public function countByStatus(int $status): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM HoaDonBan WHERE TrangThai = ?");
        $stmt->execute([$status]);
        return (int)$stmt->fetchColumn();
    }
}
