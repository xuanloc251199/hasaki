<?php
require_once __DIR__ . '/../DataAccess/ReviewDAL.php';

class ReviewBLL {
    private ReviewDAL $dal;

    public function __construct() { $this->dal = new ReviewDAL(); }

    public function getByProduct(int $productId, int $limit = 0, int $offset = 0): array {
        return $this->dal->getByProduct($productId, $limit, $offset);
    }

    public function summary(int $productId): array {
        return [
            'count'        => $this->dal->countByProduct($productId),
            'average'      => $this->dal->averageByProduct($productId),
            'distribution' => $this->dal->distributionByProduct($productId),
        ];
    }

    public function create(array $data): array {
        if (empty(trim($data['TenNguoiDanhGia'] ?? '')))    return ['error' => 'Vui lòng nhập tên'];
        if (empty(trim($data['NoiDung'] ?? '')))            return ['error' => 'Vui lòng nhập nội dung đánh giá'];
        if (mb_strlen(trim($data['NoiDung'])) < 10)         return ['error' => 'Nội dung quá ngắn (tối thiểu 10 ký tự)'];
        $sao = (int)($data['SoSao'] ?? 0);
        if ($sao < 1 || $sao > 5)                           return ['error' => 'Vui lòng chọn số sao (1-5)'];
        $id = $this->dal->insert($data);
        return ['success' => true, 'id' => $id];
    }

    public function markHelpful(int $id): bool { return $this->dal->incrementHelpful($id); }
    public function delete(int $id): bool { return $this->dal->delete($id); }
}
