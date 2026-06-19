<?php
require_once __DIR__ . '/BaseDAL.php';

class AccountDAL extends BaseDAL {

    public function findByUsername(string $username): ?array {
        $stmt = $this->db->prepare(
            "SELECT tk.*, lt.TenLoaiTaiKhoan
             FROM TaiKhoan tk
             JOIN LoaiTaiKhoan lt ON tk.LoaiTaiKhoan = lt.MaLoaiTaiKhoan
             WHERE tk.TenTaiKhoan = ?"
        );
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT tk.*, lt.TenLoaiTaiKhoan
             FROM TaiKhoan tk
             JOIN LoaiTaiKhoan lt ON tk.LoaiTaiKhoan = lt.MaLoaiTaiKhoan
             WHERE tk.MaTaiKhoan = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT tk.*, lt.TenLoaiTaiKhoan
             FROM TaiKhoan tk
             JOIN LoaiTaiKhoan lt ON tk.LoaiTaiKhoan = lt.MaLoaiTaiKhoan
             ORDER BY tk.MaTaiKhoan DESC"
        );
        return $stmt->fetchAll();
    }

    public function insert(string $username, string $passwordHash, int $loaiTK): int {
        $stmt = $this->db->prepare(
            "INSERT INTO TaiKhoan (TenTaiKhoan, MatKhau, LoaiTaiKhoan) VALUES (?, ?, ?)"
        );
        $stmt->execute([$username, $passwordHash, $loaiTK]);
        return (int)$this->db->lastInsertId();
    }

    public function updatePassword(int $id, string $passwordHash): bool {
        $stmt = $this->db->prepare("UPDATE TaiKhoan SET MatKhau = ? WHERE MaTaiKhoan = ?");
        return $stmt->execute([$passwordHash, $id]);
    }

    public function setStatus(int $id, int $status): bool {
        $stmt = $this->db->prepare("UPDATE TaiKhoan SET TrangThai = ? WHERE MaTaiKhoan = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM TaiKhoan WHERE MaTaiKhoan = ?");
        return $stmt->execute([$id]);
    }

    public function search(string $keyword): array {
        $stmt = $this->db->prepare(
            "SELECT tk.*, lt.TenLoaiTaiKhoan
             FROM TaiKhoan tk
             JOIN LoaiTaiKhoan lt ON tk.LoaiTaiKhoan = lt.MaLoaiTaiKhoan
             WHERE tk.TenTaiKhoan LIKE ?
             ORDER BY tk.MaTaiKhoan DESC"
        );
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }
}
