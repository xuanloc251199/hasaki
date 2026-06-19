<?php
/**
 * Application config
 */
define('SITE_NAME', 'HASAKI');
define('SITE_TAGLINE', 'My pham chinh hang - Lam dep moi ngay');
define('BASE_URL', '/hasaki');
define('UPLOAD_DIR', __DIR__ . '/../public/assets/images/uploads/');
define('UPLOAD_URL', BASE_URL . '/assets/images/uploads/');

date_default_timezone_set('Asia/Ho_Chi_Minh');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/helpers/upload.php';
