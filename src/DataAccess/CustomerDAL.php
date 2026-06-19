<?php
require_once __DIR__ . '/BaseDAL.php';

class CustomerDAL extends BaseDAL {

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM KhachHang ORDER BY MaKhachHang DESC");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM KhachHang WHERE MaKhachHang = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByAccount(int $accountId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM KhachHang WHERE MaTaiKhoan = ?");
        $stmt->execute([$accountId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function search(string $keyword): array {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT * FROM KhachHang
             WHERE TenKhachHang LIKE ? OR Email LIKE ? OR SDT LIKE ?
             ORDER BY MaKhachHang DESC"
        );
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO KhachHang (TenKhachHang, DiaChi, SDT, Email, GioiTinh, MaTaiKhoan)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['TenKhachHang'],
            $data['DiaChi'] ?? null,
            $data['SDT'] ?? null,
            $data['Email'] ?? null,
            $data['GioiTinh'] ?? null,
            $data['MaTaiKhoan'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE KhachHang SET TenKhachHang=?, DiaChi=?, SDT=?, Email=?, GioiTinh=? WHERE MaKhachHang=?"
        );
        return $stmt->execute([
            $data['TenKhachHang'],
            $data['DiaChi'] ?? null,
            $data['SDT'] ?? null,
            $data['Email'] ?? null,
            $data['GioiTinh'] ?? null,
            $id,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM KhachHang WHERE MaKhachHang = ?");
        return $stmt->execute([$id]);
    }

    /** New customers per month - last $months months. */
    public function growthByMonth(int $months = 12): array {
        $stmt = $this->db->prepare(
            "SELECT DATE_FORMAT(NgayDangKy, '%Y-%m') AS thang, COUNT(*) AS so_kh
             FROM KhachHang
             WHERE NgayDangKy >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY thang ORDER BY thang ASC"
        );
        $stmt->bindValue(1, $months, PDO::PARAM_INT);
        $stmt->execute();
        $byMonth = [];
        foreach ($stmt->fetchAll() as $r) $byMonth[$r['thang']] = (int)$r['so_kh'];

        $out = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-$i months"));
            $out[] = ['thang' => $m, 'so_kh' => $byMonth[$m] ?? 0];
        }
        return $out;
    }
}
