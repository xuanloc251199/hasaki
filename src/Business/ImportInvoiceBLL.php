<?php
require_once __DIR__ . '/../DataAccess/ImportInvoiceDAL.php';
require_once __DIR__ . '/../DataAccess/ProductDAL.php';
require_once __DIR__ . '/../DataAccess/WarehouseDAL.php';

/**
 * Nghiệp vụ nhập kho (hóa đơn nhập hàng từ nhà cung cấp).
 * Theo đúng pattern của InvoiceBLL::checkout: tạo hóa đơn rồi cập nhật tồn kho.
 */
class ImportInvoiceBLL {
    private ImportInvoiceDAL $importDAL;
    private ProductDAL $productDAL;
    private WarehouseDAL $warehouseDAL;

    public function __construct() {
        $this->importDAL    = new ImportInvoiceDAL();
        $this->productDAL   = new ProductDAL();
        $this->warehouseDAL = new WarehouseDAL();
    }

    /**
     * Tạo phiếu nhập kho.
     * @param array $info  Thông tin NCC: TenNCC, SDT, Email, DiaChiLayHang, MaNV
     * @param array $items Danh sách dòng nhập: MaSanPham, SoLuong, GiaNhap (TenSanPham/MauSac/KichCo/AnhSanPham tự bù từ SanPham)
     */
    public function createImport(array $info, array $items): array {
        if (empty(trim($info['TenNCC'] ?? ''))) {
            return ['error' => 'Vui lòng nhập tên nhà cung cấp'];
        }

        [$clean, $total] = $this->buildItems($items);
        if (empty($clean)) {
            return ['error' => 'Vui lòng thêm ít nhất 1 sản phẩm hợp lệ (số lượng > 0)'];
        }

        $invoiceId = $this->importDAL->create($this->buildHeader($info, $total), $clean);

        // Cộng tồn kho cho từng sản phẩm
        $this->applyStock($clean, +1);

        return ['success' => true, 'invoice_id' => $invoiceId];
    }

    /**
     * Sửa phiếu nhập: hoàn tồn kho của các dòng cũ rồi áp tồn kho theo các dòng mới.
     */
    public function updateImport(int $id, array $info, array $items): array {
        $existing = $this->importDAL->getById($id);
        if (!$existing) return ['error' => 'Không tìm thấy phiếu nhập'];
        if (empty(trim($info['TenNCC'] ?? ''))) {
            return ['error' => 'Vui lòng nhập tên nhà cung cấp'];
        }

        [$clean, $total] = $this->buildItems($items);
        if (empty($clean)) {
            return ['error' => 'Vui lòng thêm ít nhất 1 sản phẩm hợp lệ (số lượng > 0)'];
        }

        // Hoàn lại tồn kho của các dòng cũ
        $oldItems = $this->importDAL->getDetails($id);
        $this->applyStock($oldItems, -1);

        $this->importDAL->update($id, $this->buildHeader($info, $total), $clean);

        // Áp tồn kho theo các dòng mới
        $this->applyStock($clean, +1);

        return ['success' => true, 'invoice_id' => $id];
    }

    /** Chuẩn hóa & validate dòng nhập; trả [danh sách dòng sạch, tổng tiền]. */
    private function buildItems(array $items): array {
        $clean = [];
        $total = 0;
        foreach ($items as $it) {
            $maSP = (int)($it['MaSanPham'] ?? 0);
            $qty  = (int)($it['SoLuong'] ?? 0);
            $gia  = (float)($it['GiaNhap'] ?? 0);
            if ($maSP <= 0 || $qty <= 0) continue;

            $product = $this->productDAL->getById($maSP);
            if (!$product) continue;

            $clean[] = [
                'MaSanPham'   => $maSP,
                'TenSanPham'  => $product['TenSanPham'],
                'MauSac'      => $product['MauSac'] ?? null,
                'KichCo'      => $product['KichCo'] ?? null,
                'SoLuong'     => $qty,
                'GiaNhap'     => $gia,
                'AnhSanPham'  => $product['HinhAnh'] ?? null,
                'LoaiSanPham' => $product['TenDanhMuc'] ?? null,
            ];
            $total += $gia * $qty;
        }
        return [$clean, $total];
    }

    private function buildHeader(array $info, float $total): array {
        return [
            'ThanhTien'     => $total,
            'TenNCC'        => trim($info['TenNCC']),
            'SDT'           => $info['SDT'] ?? null,
            'MaNV'          => !empty($info['MaNV']) ? (int)$info['MaNV'] : null,
            'Email'         => $info['Email'] ?? null,
            'DiaChiLayHang' => $info['DiaChiLayHang'] ?? null,
        ];
    }

    /** Cập nhật tồn kho: $sign = +1 cộng vào, -1 trừ ra. */
    private function applyStock(array $items, int $sign): void {
        $today = date('Y-m-d');
        foreach ($items as $it) {
            $maSP = (int)$it['MaSanPham'];
            $qty  = (int)$it['SoLuong'];
            if ($sign > 0) {
                $this->productDAL->increaseStock($maSP, $qty);
                $this->warehouseDAL->addStock(
                    $maSP,
                    $it['TenSanPham'],
                    $qty,
                    $today,
                    $it['LoaiSanPham'] ?? null,
                    $it['AnhSanPham'] ?? null
                );
            } else {
                $this->productDAL->decreaseStock($maSP, $qty);
                $this->warehouseDAL->reduceStock($maSP, $qty);
            }
        }
    }

    public function getAll(): array              { return $this->importDAL->getAll(); }
    public function getById(int $id): ?array     { return $this->importDAL->getById($id); }
    public function getDetails(int $id): array   { return $this->importDAL->getDetails($id); }
    public function search(string $kw): array    { return $this->importDAL->search($kw); }
    public function delete(int $id): bool        { return $this->importDAL->delete($id); }
}
