<?php
/**
 * Cau hinh gui email qua SMTP (Gmail / Google Workspace).
 *
 * Dung "App Password" (mat khau ung dung 16 ky tu) cua tai khoan Google,
 * KHONG dung mat khau dang nhap thuong. Tao tai:
 *   Google Account -> Security -> 2-Step Verification -> App passwords
 *
 * Vi mail() cua PHP khong chay duoc tren localhost (Laragon) neu chua co
 * MTA, he thong se gui truc tiep qua SMTP cua Gmail bang cac thong tin duoi.
 */

// Bat/tat toan bo viec gui email qua SMTP.
define('MAIL_ENABLE', true);

// May chu SMTP cua Gmail / Google Workspace.
define('SMTP_HOST', 'smtp.gmail.com');

// 465 = SSL (ket noi ma hoa ngay tu dau) | 587 = TLS (STARTTLS).
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl'); // 'ssl' cho cong 465, 'tls' cho cong 587

// Tai khoan gui (dong thoi la dia chi "From").
define('SMTP_USER', 'lethikimphung.d22ctc1@muce.edu.vn');

// App password 16 ky tu (co the dan ke ca khoang trang, he thong tu loai bo).
define('SMTP_PASS', 'udre phss stfu rfnw');

// Ten hien thi nguoi gui.
define('SMTP_FROM_NAME', SITE_NAME);

// Dia chi mac dinh nhan canh bao quan tri (khi chua cau hinh trong Cai dat).
// De trong '' neu muon bat buoc cau hinh qua trang Cai dat.
define('ADMIN_ALERT_EMAIL', 'lethikimphung.d22ctc1@muce.edu.vn');
