<?php
require_once __DIR__ . '/BaseDAL.php';

class SettingDAL extends BaseDAL {

    /** Returns key=>value map of all settings (cached per request). */
    public function getAllAsMap(): array {
        $stmt = $this->db->query("SELECT Khoa, GiaTri FROM CauHinh");
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[$row['Khoa']] = $row['GiaTri'];
        }
        return $out;
    }

    /** Returns full rows grouped by Nhom for admin UI. */
    public function getAllGrouped(): array {
        $stmt = $this->db->query(
            "SELECT * FROM CauHinh ORDER BY Nhom, ThuTu, Khoa"
        );
        $groups = [];
        foreach ($stmt->fetchAll() as $row) {
            $groups[$row['Nhom'] ?? 'other'][] = $row;
        }
        return $groups;
    }

    public function get(string $key): ?string {
        $stmt = $this->db->prepare("SELECT GiaTri FROM CauHinh WHERE Khoa = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val === false ? null : $val;
    }

    public function set(string $key, ?string $value): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO CauHinh (Khoa, GiaTri) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE GiaTri = VALUES(GiaTri)"
        );
        return $stmt->execute([$key, $value]);
    }

    public function setMany(array $kvs): bool {
        $this->db->beginTransaction();
        try {
            foreach ($kvs as $k => $v) {
                $this->set($k, $v);
            }
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
