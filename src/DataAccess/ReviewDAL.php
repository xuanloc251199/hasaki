<?php
require_once __DIR__ . '/BaseDAL.php';

class ReviewDAL extends BaseDAL {

    public function getByProduct(int $productId, int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM DanhGia WHERE MaSanPham = ? ORDER BY NgayDanhGia DESC";
        if ($limit > 0) $sql .= " LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function countByProduct(int $productId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM DanhGia WHERE MaSanPham = ?");
        $stmt->execute([$productId]);
        return (int)$stmt->fetchColumn();
    }

    /** Returns associative array of star -> count */
    public function distributionByProduct(int $productId): array {
        $stmt = $this->db->prepare(
            "SELECT SoSao, COUNT(*) AS so_luong
             FROM DanhGia WHERE MaSanPham = ?
             GROUP BY SoSao"
        );
        $stmt->execute([$productId]);
        $out = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($stmt->fetchAll() as $row) {
            $out[(int)$row['SoSao']] = (int)$row['so_luong'];
        }
        return $out;
    }

    public function averageByProduct(int $productId): float {
        $stmt = $this->db->prepare("SELECT AVG(SoSao) FROM DanhGia WHERE MaSanPham = ?");
        $stmt->execute([$productId]);
        $avg = $stmt->fetchColumn();
        return $avg === null || $avg === false ? 0.0 : round((float)$avg, 1);
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO DanhGia
             (MaSanPham, MaKhachHang, TenNguoiDanhGia, SoSao, TieuDe, NoiDung, DaXacThucMua)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['MaSanPham'],
            $data['MaKhachHang'] ?? null,
            $data['TenNguoiDanhGia'],
            $data['SoSao'],
            $data['TieuDe'] ?? null,
            $data['NoiDung'],
            $data['DaXacThucMua'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function incrementHelpful(int $id): bool {
        $stmt = $this->db->prepare("UPDATE DanhGia SET HuuIch = HuuIch + 1 WHERE MaDanhGia = ?");
        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM DanhGia WHERE MaDanhGia = ?");
        return $stmt->execute([$id]);
    }
}
