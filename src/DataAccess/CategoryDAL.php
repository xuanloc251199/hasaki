<?php
require_once __DIR__ . '/BaseDAL.php';

class CategoryDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM DanhMuc ORDER BY MaDanhMuc ASC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM DanhMuc WHERE MaDanhMuc = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function search(string $keyword): array {
        $stmt = $this->db->prepare("SELECT * FROM DanhMuc WHERE TenDanhMuc LIKE ? ORDER BY MaDanhMuc ASC");
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO DanhMuc (TenDanhMuc, MoTa, AnhDanhMuc) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $data['TenDanhMuc'],
            $data['MoTa'] ?? null,
            $data['AnhDanhMuc'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE DanhMuc SET TenDanhMuc = ?, MoTa = ?, AnhDanhMuc = ? WHERE MaDanhMuc = ?"
        );
        return $stmt->execute([
            $data['TenDanhMuc'],
            $data['MoTa'] ?? null,
            $data['AnhDanhMuc'] ?? null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM DanhMuc WHERE MaDanhMuc = ?");
        return $stmt->execute([$id]);
    }
}
