<?php
require_once __DIR__ . '/BaseDAL.php';

/**
 * Thong bao admin (bang ThongBao).
 * Hien tai phuc vu canh bao ton kho (het_hang / sap_het) nhung thiet ke
 * dung chung de mo rong cho cac loai thong bao khac.
 */
class NotificationDAL extends BaseDAL {

    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ThongBao (Loai, MaThamChieu, TieuDe, NoiDung, MucDo, Link)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['Loai']        ?? 'ton_kho',
            $data['MaThamChieu'] ?? null,
            $data['TieuDe'],
            $data['NoiDung'],
            $data['MucDo']       ?? 'info',
            $data['Link']        ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getRecent(int $limit = 10): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM ThongBao ORDER BY DaDoc ASC, NgayTao DESC LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM ThongBao ORDER BY NgayTao DESC");
        return $stmt->fetchAll();
    }

    public function countUnread(): int {
        $stmt = $this->db->query("SELECT COUNT(*) FROM ThongBao WHERE DaDoc = 0");
        return (int)$stmt->fetchColumn();
    }

    /**
     * Da co 1 thong bao CHUA DOC cung loai cho doi tuong nay chua?
     * Dung de tranh tao trung lap moi lan ban hang.
     */
    public function existsUnread(string $loai, int $maThamChieu): bool {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM ThongBao
             WHERE Loai = ? AND MaThamChieu = ? AND DaDoc = 0
             LIMIT 1"
        );
        $stmt->execute([$loai, $maThamChieu]);
        return (bool)$stmt->fetchColumn();
    }

    public function markRead(int $id): bool {
        $stmt = $this->db->prepare("UPDATE ThongBao SET DaDoc = 1 WHERE MaThongBao = ?");
        return $stmt->execute([$id]);
    }

    public function markAllRead(): bool {
        return $this->db->query("UPDATE ThongBao SET DaDoc = 1 WHERE DaDoc = 0") !== false;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM ThongBao WHERE MaThongBao = ?");
        return $stmt->execute([$id]);
    }

    public function deleteRead(): bool {
        return $this->db->query("DELETE FROM ThongBao WHERE DaDoc = 1") !== false;
    }
}
