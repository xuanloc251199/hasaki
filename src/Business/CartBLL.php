<?php
require_once __DIR__ . '/../DataAccess/ProductDAL.php';

/**
 * Shopping cart - session-based
 * cart structure: $_SESSION['cart'][MaSanPham] = [...]
 */
class CartBLL {
    private ProductDAL $productDAL;

    public function __construct() {
        $this->productDAL = new ProductDAL();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add(int $productId, int $quantity = 1, ?string $kichCo = null, ?string $mauSac = null): array {
        $product = $this->productDAL->getById($productId);
        if (!$product) return ['error' => 'Sản phẩm không tồn tại'];
        if ($quantity <= 0) $quantity = 1;

        // Chan mua khi het hang.
        $stock = (int)($product['SoLuong'] ?? 0);
        if ($stock <= 0) {
            return ['error' => 'Sản phẩm "' . $product['TenSanPham'] . '" đã hết hàng'];
        }

        $key = $productId;
        $current = isset($_SESSION['cart'][$key]) ? (int)$_SESSION['cart'][$key]['SoLuong'] : 0;
        $desired = $current + $quantity;

        // Khong cho vuot qua so luong ton kho.
        if ($desired > $stock) {
            if ($current >= $stock) {
                return ['error' => 'Chỉ còn ' . $stock . ' sản phẩm trong kho, bạn đã thêm tối đa'];
            }
            $desired = $stock;
        }

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['SoLuong'] = $desired;
        } else {
            $_SESSION['cart'][$key] = [
                'MaSanPham'  => (int)$product['MaSanPham'],
                'TenSanPham' => $product['TenSanPham'],
                'GiaBan'     => (float)$product['GiaBan'],
                'HinhAnh'    => $product['HinhAnh'],
                'KichCo'     => $kichCo ?? $product['KichCo'],
                'MauSac'     => $mauSac ?? $product['MauSac'],
                'SoLuong'    => $desired,
            ];
        }
        return ['success' => true, 'count' => $this->count()];
    }

    public function update(int $productId, int $quantity): array {
        $key = $productId;
        if (!isset($_SESSION['cart'][$key])) return ['error' => 'Sản phẩm không có trong giỏ'];
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['SoLuong'] = $quantity;
        }
        return ['success' => true];
    }

    public function remove(int $productId): void {
        unset($_SESSION['cart'][$productId]);
    }

    public function clear(): void {
        $_SESSION['cart'] = [];
    }

    public function getItems(): array {
        return $_SESSION['cart'] ?? [];
    }

    public function count(): int {
        $count = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $count += (int)$item['SoLuong'];
        }
        return $count;
    }

    public function total(): float {
        $sum = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $sum += (float)$item['GiaBan'] * (int)$item['SoLuong'];
        }
        return $sum;
    }
}
