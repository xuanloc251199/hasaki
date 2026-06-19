<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Business/AuthBLL.php';
(new AuthBLL())->logout();
session_start();
set_flash_success('Đã đăng xuất');
redirect(url('login.php'));
