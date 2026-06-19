<?php
require_once __DIR__ . '/BaseDAL.php';

class EmployeeDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM NhanVien ORDER BY MaNV DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM NhanVien WHERE MaNV = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByAccount(int $accountId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM NhanVien WHERE MaTaiKhoan = ? LIMIT 1");
        $stmt->execute([$accountId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function search(string $keyword): array {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM NhanVien WHERE TenNV LIKE ? OR Email LIKE ? OR SDT LIKE ? ORDER BY MaNV DESC"
        );
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO NhanVien (TenNV, DiaChi, SDT, Email, CMND, GioiTinh, AnhNhanVien, MaTaiKhoan)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['TenNV'],
            $data['DiaChi'] ?? null,
            $data['SDT'] ?? null,
            $data['Email'] ?? null,
            $data['CMND'],
            $data['GioiTinh'] ?? null,
            $data['AnhNhanVien'] ?? null,
            $data['MaTaiKhoan'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE NhanVien SET TenNV=?, DiaChi=?, SDT=?, Email=?, CMND=?, GioiTinh=?, AnhNhanVien=?
             WHERE MaNV=?"
        );
        return $stmt->execute([
            $data['TenNV'],
            $data['DiaChi'] ?? null,
            $data['SDT'] ?? null,
            $data['Email'] ?? null,
            $data['CMND'],
            $data['GioiTinh'] ?? null,
            $data['AnhNhanVien'] ?? null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM NhanVien WHERE MaNV = ?");
        return $stmt->execute([$id]);
    }
}
