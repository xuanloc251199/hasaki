<?php
require_once __DIR__ . '/BaseDAL.php';

class ImportInvoiceDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT hdn.*, nv.TenNV
             FROM HoaDonNhap hdn
             LEFT JOIN NhanVien nv ON hdn.MaNV = nv.MaNV
             ORDER BY hdn.MaHoaDon DESC"
        );
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT hdn.*, nv.TenNV
             FROM HoaDonNhap hdn
             LEFT JOIN NhanVien nv ON hdn.MaNV = nv.MaNV
             WHERE hdn.MaHoaDon = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getDetails(int $invoiceId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ChiTietHoaDonNhap WHERE MaHoaDon = ?"
        );
        $stmt->execute([$invoiceId]);
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT hdn.*, nv.TenNV
             FROM HoaDonNhap hdn
             LEFT JOIN NhanVien nv ON hdn.MaNV = nv.MaNV
             WHERE hdn.TenNCC LIKE ? OR hdn.SDT LIKE ?
             ORDER BY hdn.MaHoaDon DESC"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function create(array $invoice, array $items): int {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                "INSERT INTO HoaDonNhap (ThanhTien, TenNCC, SDT, MaNV, Email, DiaChiLayHang)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $invoice['ThanhTien'] ?? 0,
                $invoice['TenNCC'],
                $invoice['SDT'] ?? null,
                $invoice['MaNV'] ?? null,
                $invoice['Email'] ?? null,
                $invoice['DiaChiLayHang'] ?? null,
            ]);
            $invoiceId = (int)$this->db->lastInsertId();

            $itemStmt = $this->db->prepare(
                "INSERT INTO ChiTietHoaDonNhap (MaHoaDon, MaSanPham, TenSanPham, MauSac, KichCo, SoLuong, GiaNhap, AnhSanPham)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            foreach ($items as $item) {
                $itemStmt->execute([
                    $invoiceId,
                    $item['MaSanPham'],
                    $item['TenSanPham'],
                    $item['MauSac'] ?? null,
                    $item['KichCo'] ?? null,
                    $item['SoLuong'],
                    $item['GiaNhap'],
                    $item['AnhSanPham'] ?? null,
                ]);
            }

            $this->db->commit();
            return $invoiceId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $invoice, array $items): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                "UPDATE HoaDonNhap
                 SET ThanhTien = ?, TenNCC = ?, SDT = ?, MaNV = ?, Email = ?, DiaChiLayHang = ?
                 WHERE MaHoaDon = ?"
            );
            $stmt->execute([
                $invoice['ThanhTien'] ?? 0,
                $invoice['TenNCC'],
                $invoice['SDT'] ?? null,
                $invoice['MaNV'] ?? null,
                $invoice['Email'] ?? null,
                $invoice['DiaChiLayHang'] ?? null,
                $id,
            ]);

            $this->db->prepare("DELETE FROM ChiTietHoaDonNhap WHERE MaHoaDon = ?")->execute([$id]);

            $itemStmt = $this->db->prepare(
                "INSERT INTO ChiTietHoaDonNhap (MaHoaDon, MaSanPham, TenSanPham, MauSac, KichCo, SoLuong, GiaNhap, AnhSanPham)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            foreach ($items as $item) {
                $itemStmt->execute([
                    $id,
                    $item['MaSanPham'],
                    $item['TenSanPham'],
                    $item['MauSac'] ?? null,
                    $item['KichCo'] ?? null,
                    $item['SoLuong'],
                    $item['GiaNhap'],
                    $item['AnhSanPham'] ?? null,
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM HoaDonNhap WHERE MaHoaDon = ?");
        return $stmt->execute([$id]);
    }
}
