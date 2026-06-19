<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/CartBLL.php';

$cart = new CartBLL();
$action = $_REQUEST['action'] ?? 'add';
$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

switch ($action) {
  case 'add':
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $res = $cart->add($id, $qty);
    if (isset($res['error'])) {
      set_flash_error($res['error']);
    } else {
      set_flash_success('Đã thêm sản phẩm vào giỏ hàng');
    }
    if (!empty($_POST['buy_now'])) {
      redirect(url('cart.php'));
    }
    redirect($_SERVER['HTTP_REFERER'] ?? url('cart.php'));
    break;

  case 'update':
    $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $cart->update($id, $qty);
    redirect(url('cart.php'));
    break;

  case 'remove':
    $cart->remove($id);
    set_flash_success('Đã xóa sản phẩm khỏi giỏ hàng');
    redirect(url('cart.php'));
    break;

  case 'clear':
    $cart->clear();
    redirect(url('cart.php'));
    break;
}

redirect(url('cart.php'));
