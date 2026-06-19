<?php
require_once __DIR__ . '/ProductBLL.php';
require_once __DIR__ . '/CategoryBLL.php';
require_once __DIR__ . '/../DataAccess/ChatbotDAL.php';

/**
 * AI Chatbot - rule-based, intent-driven.
 * Intents are loaded from the ChatbotIntent table (admin-editable).
 * Config (greeting, fallback message, etc.) loaded from ChatbotConfig.
 */
class ChatbotBLL {
    private ProductBLL $productBLL;
    private CategoryBLL $categoryBLL;
    private ChatbotDAL $dal;

    public function __construct() {
        $this->productBLL  = new ProductBLL();
        $this->categoryBLL = new CategoryBLL();
        $this->dal         = new ChatbotDAL();
    }

    public function reply(string $message): array {
        $msg = $this->normalize($message);

        if ($msg === '') {
            return ['reply' => 'Bạn cần Hasaki giúp gì ạ? 😊'];
        }

        $intents = $this->dal->getActiveIntents();
        $matched = $this->matchIntent($msg, $intents);

        if ($matched) {
            return $this->buildReply($matched);
        }

        // Fallback: search products if enabled
        if ((int)$this->dal->getConfig('enable_fallback_search', '1') === 1) {
            $words = explode(' ', $msg);
            $relevant = array_filter($words, fn($w) => mb_strlen($w) >= 3);
            if (!empty($relevant)) {
                $items = $this->productBLL->search(implode(' ', $relevant));
                if (!empty($items)) {
                    $max = (int)$this->dal->getConfig('max_products', '4');
                    return [
                        'reply'    => 'Mình tìm thấy ' . count($items) . ' sản phẩm liên quan:',
                        'products' => array_slice($items, 0, $max),
                    ];
                }
            }
        }

        return [
            'reply' => $this->dal->getConfig('fallback', 'Mình chưa hiểu rõ ý bạn 😅'),
        ];
    }

    public function greeting(): array {
        return [
            'reply' => $this->dal->getConfig('greeting', 'Xin chào! Hasaki có thể giúp gì cho bạn?'),
            'quick' => $this->dal->getQuickReplies(),
            'name'  => $this->dal->getConfig('bot_name', 'Hasaki Bot'),
        ];
    }

    private function matchIntent(string $msg, array $intents): ?array {
        foreach ($intents as $intent) {
            $keywords = array_map('trim', explode('|', $intent['TuKhoa']));
            foreach ($keywords as $kw) {
                $kw = $this->normalize($kw);
                if ($kw !== '' && str_contains($msg, $kw)) {
                    return $intent;
                }
            }
        }
        return null;
    }

    private function buildReply(array $intent): array {
        $out = ['reply' => $intent['CauTraLoi']];
        $max = (int)$this->dal->getConfig('max_products', '4');

        switch ($intent['LoaiGoiY']) {
            case 'search':
                $kw = $intent['GiaTriGoiY'] ?? '';
                if ($kw !== '') {
                    $items = $this->productBLL->search($kw);
                    if (!empty($items)) $out['products'] = array_slice($items, 0, $max);
                }
                break;
            case 'featured':
                $out['products'] = $this->productBLL->getFeatured($max);
                break;
            case 'newest':
                $out['products'] = $this->productBLL->getNewest($max);
                break;
        }

        if (!empty($intent['Link'])) {
            $out['link'] = $intent['Link'];
        }
        return $out;
    }

    /** Lower-case + strip Vietnamese diacritics for fuzzy matching. */
    private function normalize(string $s): string {
        $s = mb_strtolower(trim($s), 'UTF-8');
        $map = [
            'à','á','ả','ã','ạ','ă','ằ','ắ','ẳ','ẵ','ặ','â','ầ','ấ','ẩ','ẫ','ậ',
            'è','é','ẻ','ẽ','ẹ','ê','ề','ế','ể','ễ','ệ',
            'ì','í','ỉ','ĩ','ị',
            'ò','ó','ỏ','õ','ọ','ô','ồ','ố','ổ','ỗ','ộ','ơ','ờ','ớ','ở','ỡ','ợ',
            'ù','ú','ủ','ũ','ụ','ư','ừ','ứ','ử','ữ','ự',
            'ỳ','ý','ỷ','ỹ','ỵ',
            'đ',
        ];
        $rep = [
            'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
        ];
        return str_replace($map, $rep, $s);
    }

    // ===== Admin helpers (CRUD passthrough) =====

    public function adminListIntents(string $keyword = ''): array {
        return $keyword !== '' ? $this->dal->searchIntents($keyword) : $this->dal->getAllIntents();
    }

    public function adminGetIntent(int $id): ?array { return $this->dal->getIntent($id); }

    public function adminCreateIntent(array $data): array {
        $err = $this->validateIntent($data);
        if ($err) return ['error' => $err];
        return ['success' => true, 'id' => $this->dal->insertIntent($data)];
    }

    public function adminUpdateIntent(int $id, array $data): array {
        $err = $this->validateIntent($data);
        if ($err) return ['error' => $err];
        return $this->dal->updateIntent($id, $data) ? ['success' => true] : ['error' => 'Cập nhật thất bại'];
    }

    public function adminDeleteIntent(int $id): bool { return $this->dal->deleteIntent($id); }
    public function adminToggleIntent(int $id): bool { return $this->dal->toggleIntent($id); }

    public function adminGetConfig(): array { return $this->dal->getAllConfig(); }
    public function adminSetConfig(string $key, string $value): bool {
        return $this->dal->setConfig($key, $value);
    }

    private function validateIntent(array $data): ?string {
        if (empty(trim($data['TenIntent'] ?? ''))) return 'Tên intent không được trống';
        if (empty(trim($data['TuKhoa'] ?? '')))    return 'Từ khóa không được trống';
        if (empty(trim($data['CauTraLoi'] ?? ''))) return 'Câu trả lời không được trống';
        $allowed = ['none', 'search', 'featured', 'newest'];
        if (!in_array($data['LoaiGoiY'] ?? 'none', $allowed, true)) {
            return 'Loại gợi ý không hợp lệ';
        }
        return null;
    }
}
