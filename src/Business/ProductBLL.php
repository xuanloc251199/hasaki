<?php
require_once __DIR__ . '/../DataAccess/ProductDAL.php';
require_once __DIR__ . '/../DataAccess/CategoryDAL.php';

class ProductBLL {
    private ProductDAL $productDAL;
    private CategoryDAL $categoryDAL;

    public function __construct() {
        $this->productDAL  = new ProductDAL();
        $this->categoryDAL = new CategoryDAL();
    }

    public function getAll(int $limit = 0, int $offset = 0): array {
        return $this->productDAL->getAll($limit, $offset);
    }

    public function countAll(): int {
        return $this->productDAL->countAll();
    }

    public function getById(int $id): ?array {
        return $this->productDAL->getById($id);
    }

    public function getByCategory(int $maDM): array {
        return $this->productDAL->getByCategory($maDM);
    }

    public function search(string $kw): array {
        return $this->productDAL->search(trim($kw));
    }

    public function getFeatured(int $limit = 8): array {
        return $this->productDAL->getFeatured($limit);
    }

    public function getNewest(int $limit = 8): array {
        return $this->productDAL->getNewest($limit);
    }

    /** San pham co ton kho <= nguong (gom ca het hang). Dung cho canh bao. */
    public function getLowStock(int $threshold): array {
        return $this->productDAL->getLowStock($threshold);
    }

    public function getRelated(int $id, int $maDM, int $limit = 4): array {
        $list = $this->productDAL->getByCategory($maDM);
        $out  = [];
        foreach ($list as $p) {
            if ((int)$p['MaSanPham'] === $id) continue;
            $out[] = $p;
            if (count($out) >= $limit) break;
        }
        return $out;
    }

    public function create(array $data): array {
        if (empty($data['TenSanPham'])) return ['error' => 'Tên sản phẩm không được để trống'];
        if (empty($data['MaDanhMuc'])) return ['error' => 'Vui lòng chọn danh mục'];
        if (!isset($data['GiaBan']) || (float)$data['GiaBan'] <= 0) return ['error' => 'Giá bán không hợp lệ'];
        if (empty($data['HinhAnh'])) return ['error' => 'Vui lòng nhập đường dẫn hình ảnh'];
        $id = $this->productDAL->insert($data);
        return ['success' => true, 'id' => $id];
    }

    public function update(int $id, array $data): array {
        if (empty($data['TenSanPham'])) return ['error' => 'Tên sản phẩm không được để trống'];
        $ok = $this->productDAL->update($id, $data);
        return $ok ? ['success' => true] : ['error' => 'Cập nhật thất bại'];
    }

    public function delete(int $id): bool {
        return $this->productDAL->delete($id);
    }

    public function categories(): array {
        return $this->categoryDAL->getAll();
    }
}
