<?php
require_once __DIR__ . '/../DataAccess/MenuDAL.php';

class MenuBLL {
    private MenuDAL $dal;
    private static array $cache = [];

    public const POSITIONS = [
        'header'         => 'Menu chính (Header)',
        'footer_support' => 'Footer - Hỗ trợ',
        'footer_account' => 'Footer - Tài khoản',
    ];

    public function __construct() { $this->dal = new MenuDAL(); }

    public function getByPosition(string $position): array {
        if (!isset(self::$cache[$position])) {
            self::$cache[$position] = $this->dal->getByPosition($position, true);
        }
        return self::$cache[$position];
    }

    public function getAll(): array       { return $this->dal->getAll(); }
    public function getById(int $id): ?array { return $this->dal->getById($id); }

    public function create(array $data): array {
        $err = $this->validate($data);
        if ($err) return ['error' => $err];
        self::$cache = [];
        return ['success' => true, 'id' => $this->dal->insert($data)];
    }

    public function update(int $id, array $data): array {
        $err = $this->validate($data);
        if ($err) return ['error' => $err];
        self::$cache = [];
        return $this->dal->update($id, $data) ? ['success' => true] : ['error' => 'Cập nhật thất bại'];
    }

    public function delete(int $id): bool { self::$cache = []; return $this->dal->delete($id); }
    public function toggle(int $id): bool { self::$cache = []; return $this->dal->toggle($id); }

    private function validate(array $d): ?string {
        if (empty(trim($d['TieuDe'] ?? '')))    return 'Tiêu đề không được trống';
        if (empty(trim($d['DuongDan'] ?? '')))  return 'Đường dẫn không được trống';
        if (!isset(self::POSITIONS[$d['ViTri'] ?? ''])) return 'Vị trí không hợp lệ';
        return null;
    }
}
