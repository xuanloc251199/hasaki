<?php
/**
 * AI Chatbot endpoint - returns JSON
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/ChatbotBLL.php';

header('Content-Type: application/json; charset=utf-8');

$bot = new ChatbotBLL();

if (($_GET['action'] ?? '') === 'init') {
    echo json_encode($bot->greeting(), JSON_UNESCAPED_UNICODE);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$message = trim($input['message'] ?? $_POST['message'] ?? '');

if ($message === '') {
    echo json_encode(['error' => 'Empty message'], JSON_UNESCAPED_UNICODE);
    exit;
}

$result = $bot->reply($message);

// Format products for client
if (!empty($result['products'])) {
    $result['products'] = array_map(function ($p) {
        return [
            'id'    => (int)$p['MaSanPham'],
            'name'  => $p['TenSanPham'],
            'price' => format_currency($p['GiaBan']),
            'image' => asset($p['HinhAnh']),
            'url'   => url('product-detail.php?id=' . (int)$p['MaSanPham']),
        ];
    }, $result['products']);
}

if (!empty($result['link'])) {
    $result['link'] = url($result['link']);
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
