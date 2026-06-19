<?php
require_once __DIR__ . '/BaseDAL.php';

class MenuDAL extends BaseDAL {

    public function getByPosition(string $position, bool $activeOnly = true): array {
        $sql = "SELECT * FROM MenuItem WHERE ViTri = ?";
        if ($activeOnly) $sql .= " AND KichHoat = 1";
        $sql .= " ORDER BY ThuTu ASC, MaMenu ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$position]);
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM MenuItem ORDER BY ViTri, ThuTu, MaMenu");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM MenuItem WHERE MaMenu = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO MenuItem (ViTri, TieuDe, DuongDan, Icon, ThuTu, KichHoat, MoCuaSoMoi)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['ViTri'],
            $data['TieuDe'],
            $data['DuongDan'],
            $data['Icon'] ?? null,
            $data['ThuTu'] ?? 0,
            $data['KichHoat'] ?? 1,
            $data['MoCuaSoMoi'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE MenuItem SET ViTri=?, TieuDe=?, DuongDan=?, Icon=?, ThuTu=?, KichHoat=?, MoCuaSoMoi=?
             WHERE MaMenu=?"
        );
        return $stmt->execute([
            $data['ViTri'],
            $data['TieuDe'],
            $data['DuongDan'],
            $data['Icon'] ?? null,
            $data['ThuTu'] ?? 0,
            $data['KichHoat'] ?? 1,
            $data['MoCuaSoMoi'] ?? 0,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM MenuItem WHERE MaMenu = ?");
        return $stmt->execute([$id]);
    }

    public function toggle(int $id): bool {
        $stmt = $this->db->prepare("UPDATE MenuItem SET KichHoat = 1 - KichHoat WHERE MaMenu = ?");
        return $stmt->execute([$id]);
    }
}
