<?php
require_once __DIR__ . '/BaseDAL.php';

class WarehouseDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT kh.*, sp.HinhAnh, dm.TenDanhMuc
             FROM KhoHang kh
             LEFT JOIN SanPham sp ON kh.MaSanPham = sp.MaSanPham
             LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             ORDER BY kh.MaKho DESC"
        );
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT kh.*, sp.HinhAnh
             FROM KhoHang kh
             LEFT JOIN SanPham sp ON kh.MaSanPham = sp.MaSanPham
             WHERE kh.TenSanPham LIKE ? OR kh.LoaiSanPham LIKE ?
             ORDER BY kh.MaKho DESC"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM KhoHang WHERE MaKho = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE KhoHang SET TenSanPham=?, SoLuongCon=?, NgayNhap=?, LoaiSanPham=? WHERE MaKho=?"
        );
        return $stmt->execute([
            $data['TenSanPham'],
            $data['SoLuongCon'],
            $data['NgayNhap'] ?? date('Y-m-d'),
            $data['LoaiSanPham'] ?? null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM KhoHang WHERE MaKho = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Cộng dồn tồn kho cho 1 sản phẩm khi nhập hàng.
     * Nếu sản phẩm đã có dòng trong KhoHang thì cộng SoLuongCon và cập nhật NgayNhap,
     * ngược lại tạo dòng mới.
     */
    public function addStock(int $maSanPham, string $tenSanPham, int $qty, string $ngayNhap, ?string $loai = null, ?string $anh = null): bool {
        $stmt = $this->db->prepare("SELECT MaKho FROM KhoHang WHERE MaSanPham = ? LIMIT 1");
        $stmt->execute([$maSanPham]);
        $maKho = $stmt->fetchColumn();

        if ($maKho) {
            $upd = $this->db->prepare(
                "UPDATE KhoHang SET SoLuongCon = SoLuongCon + ?, NgayNhap = ? WHERE MaKho = ?"
            );
            return $upd->execute([$qty, $ngayNhap, (int)$maKho]);
        }

        $ins = $this->db->prepare(
            "INSERT INTO KhoHang (MaSanPham, TenSanPham, SoLuongCon, NgayNhap, LoaiSanPham, AnhSanPham)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $ins->execute([$maSanPham, $tenSanPham, $qty, $ngayNhap, $loai, $anh]);
    }

    /** Trừ tồn kho cho 1 sản phẩm (dùng khi sửa/hoàn phiếu nhập), không cho âm. */
    public function reduceStock(int $maSanPham, int $qty): bool {
        $stmt = $this->db->prepare(
            "UPDATE KhoHang SET SoLuongCon = GREATEST(SoLuongCon - ?, 0) WHERE MaSanPham = ?"
        );
        return $stmt->execute([$qty, $maSanPham]);
    }
}
