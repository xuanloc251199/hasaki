<?php
require_once __DIR__ . '/../DataAccess/CategoryDAL.php';

class CategoryBLL {
    private CategoryDAL $dal;

    public function __construct() {
        $this->dal = new CategoryDAL();
    }

    public function getAll(): array { return $this->dal->getAll(); }
    public function getById(int $id): ?array { return $this->dal->getById($id); }
    public function search(string $kw): array { return $this->dal->search($kw); }

    public function create(array $data): array {
        if (empty(trim($data['TenDanhMuc'] ?? ''))) return ['error' => 'Tên danh mục không được trống'];
        $id = $this->dal->insert($data);
        return ['success' => true, 'id' => $id];
    }

    public function update(int $id, array $data): array {
        if (empty(trim($data['TenDanhMuc'] ?? ''))) return ['error' => 'Tên danh mục không được trống'];
        return $this->dal->update($id, $data) ? ['success' => true] : ['error' => 'Cập nhật thất bại'];
    }

    public function delete(int $id): bool { return $this->dal->delete($id); }
}
