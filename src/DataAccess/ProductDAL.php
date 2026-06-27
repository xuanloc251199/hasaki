<?php
require_once __DIR__ . '/BaseDAL.php';

class ProductDAL extends BaseDAL {

    public function getAll(int $limit = 0, int $offset = 0): array {
        $sql = "SELECT sp.*, dm.TenDanhMuc
                FROM SanPham sp
                LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
                ORDER BY sp.MaSanPham DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit OFFSET $offset";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM SanPham");
        return (int)$stmt->fetchColumn();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT sp.*, dm.TenDanhMuc
             FROM SanPham sp
             LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             WHERE sp.MaSanPham = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByCategory(int $maDanhMuc): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM SanPham WHERE MaDanhMuc = ? ORDER BY MaSanPham DESC"
        );
        $stmt->execute([$maDanhMuc]);
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array {
        $stmt = $this->db->prepare(
            "SELECT sp.*, dm.TenDanhMuc
             FROM SanPham sp
             LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             WHERE sp.TenSanPham LIKE ? OR dm.TenDanhMuc LIKE ?
             ORDER BY sp.MaSanPham DESC"
        );
        $like = '%' . $keyword . '%';
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function getFeatured(int $limit = 8): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM SanPham WHERE Sale IS NOT NULL ORDER BY MaSanPham DESC LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getNewest(int $limit = 8): array {
        $stmt = $this->db->prepare("SELECT * FROM SanPham ORDER BY NgayTao DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO SanPham
             (MaDanhMuc, TenSanPham, ThuongHieu, XuatXu, DungTich, LoaiDa, SoLuong, GiaBan, MauSac, KichCo,
              MoTa, ThanhPhan, HuongDanSuDung, BaoQuan, HanSuDung,
              HinhAnh, AnhHover1, AnhHover2, Sale, ThongBao)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['MaDanhMuc'],
            $data['TenSanPham'],
            $data['ThuongHieu']     ?? null,
            $data['XuatXu']         ?? null,
            $data['DungTich']       ?? null,
            $data['LoaiDa']         ?? null,
            $data['SoLuong']        ?? 0,
            $data['GiaBan'],
            $data['MauSac']         ?? null,
            $data['KichCo']         ?? null,
            $data['MoTa']           ?? null,
            $data['ThanhPhan']      ?? null,
            $data['HuongDanSuDung'] ?? null,
            $data['BaoQuan']        ?? null,
            $data['HanSuDung']      ?? null,
            $data['HinhAnh'],
            $data['AnhHover1']      ?? null,
            $data['AnhHover2']      ?? null,
            $data['Sale']           ?? null,
            $data['ThongBao']       ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE SanPham SET
              MaDanhMuc=?, TenSanPham=?, ThuongHieu=?, XuatXu=?, DungTich=?, LoaiDa=?,
              SoLuong=?, GiaBan=?, MauSac=?, KichCo=?,
              MoTa=?, ThanhPhan=?, HuongDanSuDung=?, BaoQuan=?, HanSuDung=?,
              HinhAnh=?, Sale=?, ThongBao=?
             WHERE MaSanPham=?"
        );
        return $stmt->execute([
            $data['MaDanhMuc'],
            $data['TenSanPham'],
            $data['ThuongHieu']     ?? null,
            $data['XuatXu']         ?? null,
            $data['DungTich']       ?? null,
            $data['LoaiDa']         ?? null,
            $data['SoLuong']        ?? 0,
            $data['GiaBan'],
            $data['MauSac']         ?? null,
            $data['KichCo']         ?? null,
            $data['MoTa']           ?? null,
            $data['ThanhPhan']      ?? null,
            $data['HuongDanSuDung'] ?? null,
            $data['BaoQuan']        ?? null,
            $data['HanSuDung']      ?? null,
            $data['HinhAnh'],
            $data['Sale']           ?? null,
            $data['ThongBao']       ?? null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM SanPham WHERE MaSanPham = ?");
        return $stmt->execute([$id]);
    }

    public function decreaseStock(int $id, int $qty): bool {
        $stmt = $this->db->prepare("UPDATE SanPham SET SoLuong = GREATEST(SoLuong - ?, 0) WHERE MaSanPham = ?");
        return $stmt->execute([$qty, $id]);
    }

    public function increaseStock(int $id, int $qty): bool {
        $stmt = $this->db->prepare("UPDATE SanPham SET SoLuong = COALESCE(SoLuong, 0) + ? WHERE MaSanPham = ?");
        return $stmt->execute([$qty, $id]);
    }

    /** So luong ton hien tai cua 1 san pham (0 neu khong ton tai). */
    public function getStock(int $id): int {
        $stmt = $this->db->prepare("SELECT COALESCE(SoLuong, 0) FROM SanPham WHERE MaSanPham = ?");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Cac san pham co ton kho <= nguong (gom ca het hang), sap xep tang dan
     * theo so luong de het/sap-het hien len truoc. Dung cho widget canh bao.
     */
    public function getLowStock(int $threshold): array {
        $stmt = $this->db->prepare(
            "SELECT sp.*, dm.TenDanhMuc
             FROM SanPham sp
             LEFT JOIN DanhMuc dm ON sp.MaDanhMuc = dm.MaDanhMuc
             WHERE COALESCE(sp.SoLuong, 0) <= ?
             ORDER BY COALESCE(sp.SoLuong, 0) ASC, sp.TenSanPham ASC"
        );
        $stmt->bindValue(1, $threshold, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
