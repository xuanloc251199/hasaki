<?php
require_once __DIR__ . '/BaseDAL.php';

class ChatbotDAL extends BaseDAL {

    // ========== Intent ==========

    public function getActiveIntents(): array {
        $stmt = $this->db->query(
            "SELECT * FROM ChatbotIntent WHERE KichHoat = 1 ORDER BY ThuTu ASC, MaIntent ASC"
        );
        return $stmt->fetchAll();
    }

    public function getAllIntents(): array {
        $stmt = $this->db->query(
            "SELECT * FROM ChatbotIntent ORDER BY ThuTu ASC, MaIntent ASC"
        );
        return $stmt->fetchAll();
    }

    public function getQuickReplies(int $limit = 6): array {
        $stmt = $this->db->prepare(
            "SELECT TenIntent FROM ChatbotIntent
             WHERE KichHoat = 1 AND LaQuickReply = 1
             ORDER BY ThuTu ASC LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => $r['TenIntent'], $stmt->fetchAll());
    }

    public function getIntent(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM ChatbotIntent WHERE MaIntent = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function searchIntents(string $kw): array {
        $like = '%' . $kw . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM ChatbotIntent
             WHERE TenIntent LIKE ? OR TuKhoa LIKE ?
             ORDER BY ThuTu ASC"
        );
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function insertIntent(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO ChatbotIntent
             (TenIntent, TuKhoa, CauTraLoi, LoaiGoiY, GiaTriGoiY, Link, ThuTu, KichHoat, LaQuickReply)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['TenIntent'],
            $data['TuKhoa'],
            $data['CauTraLoi'],
            $data['LoaiGoiY'] ?? 'none',
            $data['GiaTriGoiY'] ?? null,
            $data['Link'] ?? null,
            $data['ThuTu'] ?? 0,
            $data['KichHoat'] ?? 1,
            $data['LaQuickReply'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateIntent(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE ChatbotIntent SET TenIntent=?, TuKhoa=?, CauTraLoi=?, LoaiGoiY=?, GiaTriGoiY=?, Link=?, ThuTu=?, KichHoat=?, LaQuickReply=? WHERE MaIntent=?"
        );
        return $stmt->execute([
            $data['TenIntent'],
            $data['TuKhoa'],
            $data['CauTraLoi'],
            $data['LoaiGoiY'] ?? 'none',
            $data['GiaTriGoiY'] ?? null,
            $data['Link'] ?? null,
            $data['ThuTu'] ?? 0,
            $data['KichHoat'] ?? 1,
            $data['LaQuickReply'] ?? 0,
            $id,
        ]);
    }

    public function deleteIntent(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM ChatbotIntent WHERE MaIntent = ?");
        return $stmt->execute([$id]);
    }

    public function toggleIntent(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE ChatbotIntent SET KichHoat = 1 - KichHoat WHERE MaIntent = ?"
        );
        return $stmt->execute([$id]);
    }

    // ========== Config ==========

    public function getAllConfig(): array {
        $stmt = $this->db->query("SELECT * FROM ChatbotConfig");
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[$row['ConfigKey']] = $row;
        }
        return $out;
    }

    public function getConfig(string $key, ?string $default = null): ?string {
        $stmt = $this->db->prepare("SELECT ConfigValue FROM ChatbotConfig WHERE ConfigKey = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public function setConfig(string $key, string $value): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO ChatbotConfig (ConfigKey, ConfigValue) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE ConfigValue = VALUES(ConfigValue)"
        );
        return $stmt->execute([$key, $value]);
    }
}
