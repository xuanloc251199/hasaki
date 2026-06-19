<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/ReviewBLL.php';
require_once __DIR__ . '/../src/Business/CustomerBLL.php';

require_login();

$bll = new ReviewBLL();
$action = $_REQUEST['action'] ?? 'create';

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)($_POST['MaSanPham'] ?? 0);
    $customerBLL = new CustomerBLL();
    $customer = $customerBLL->getByAccount((int)$_SESSION['user_id']);
    $name = $customer['TenKhachHang'] ?? ($_SESSION['display_name'] ?? 'Khách hàng');

    $res = $bll->create([
        'MaSanPham'       => $productId,
        'MaKhachHang'     => $customer ? (int)$customer['MaKhachHang'] : null,
        'TenNguoiDanhGia' => $name,
        'SoSao'           => (int)($_POST['SoSao'] ?? 0),
        'TieuDe'          => trim($_POST['TieuDe'] ?? ''),
        'NoiDung'         => trim($_POST['NoiDung'] ?? ''),
        'DaXacThucMua'    => 0,
    ]);

    isset($res['error'])
        ? set_flash_error($res['error'])
        : set_flash_success('Cảm ơn bạn đã đánh giá sản phẩm!');
    redirect(url('product-detail.php?id=' . $productId . '#reviews'));
}

if ($action === 'helpful') {
    $id = (int)($_GET['id'] ?? 0);
    $pid = (int)($_GET['pid'] ?? 0);
    $bll->markHelpful($id);
    redirect(url('product-detail.php?id=' . $pid . '#reviews'));
}

redirect(url('index.php'));
