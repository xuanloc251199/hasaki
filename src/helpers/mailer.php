<?php
/**
 * Mailer gui email qua SMTP (Gmail / Google Workspace).
 *
 * Truoc day dung ham mail() cua PHP - ham nay KHONG chay duoc tren localhost
 * (Laragon/XAMPP) neu chua cau hinh sendmail/SMTP relay. Nay chuyen sang ket
 * noi truc tiep toi SMTP cua Gmail bang thong tin trong config/mail.php.
 *
 * Tat ca cac ham deu NUOT loi (tra ve false), KHONG BAO GIO nem exception de
 * tranh lam hong luong checkout khi gui mail that bai.
 */

/**
 * Dia chi email nhan canh bao quan tri.
 * Uu tien "admin_alert_email" (Cai dat) -> "contact_email" -> ADMIN_ALERT_EMAIL.
 */
function admin_alert_recipient(): ?string {
    $to = trim((string)setting('admin_alert_email', ''));
    if ($to === '') {
        $to = trim((string)setting('contact_email', ''));
    }
    if ($to === '' && defined('ADMIN_ALERT_EMAIL')) {
        $to = trim((string)ADMIN_ALERT_EMAIL);
    }
    return $to !== '' ? $to : null;
}

/**
 * Gui 1 email HTML (UTF-8). Tra ve true neu gui thanh cong, false neu khong.
 * Khong nem loi trong moi truong hop.
 */
function send_html_mail(string $to, string $subject, string $htmlBody): bool {
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Neu da cau hinh SMTP thi uu tien gui qua SMTP (chay duoc tren localhost).
    if (defined('MAIL_ENABLE') && MAIL_ENABLE && defined('SMTP_HOST') && SMTP_HOST !== '') {
        return smtp_send_mail($to, $subject, $htmlBody);
    }

    // Du phong: dung mail() cua PHP (can MTA da cau hinh).
    return php_mail_fallback($to, $subject, $htmlBody);
}

/**
 * Gui canh bao quan tri toi dia chi cau hinh san. Tra ve false neu chua bat
 * email, chua co nguoi nhan, hoac gui that bai.
 */
function send_admin_alert(string $subject, string $htmlBody): bool {
    if ((string)setting('low_stock_email_enable', '1') !== '1') {
        return false;
    }
    $to = admin_alert_recipient();
    if ($to === null) {
        return false;
    }
    return send_html_mail($to, $subject, $htmlBody);
}

// =====================================================================
// SMTP client toi gian (ho tro SSL cong 465 va STARTTLS cong 587)
// =====================================================================

/**
 * Gui email HTML qua SMTP. Tra ve true/false, ghi loi ra error_log de debug.
 */
function smtp_send_mail(string $to, string $subject, string $htmlBody): bool {
    $host   = SMTP_HOST;
    $port   = defined('SMTP_PORT') ? (int)SMTP_PORT : 465;
    $secure = defined('SMTP_SECURE') ? strtolower((string)SMTP_SECURE) : 'ssl';
    $user   = defined('SMTP_USER') ? (string)SMTP_USER : '';
    // App password Gmail thuong dan kem khoang trang -> loai bo.
    $pass   = defined('SMTP_PASS') ? str_replace(' ', '', (string)SMTP_PASS) : '';

    $fromEmail = $user !== '' ? $user : (trim((string)setting('contact_email', '')) ?: 'no-reply@hasaki.local');
    $fromName  = defined('SMTP_FROM_NAME') ? (string)SMTP_FROM_NAME : (string)setting('site_name', SITE_NAME);

    try {
        $remote = ($secure === 'ssl' ? 'ssl://' : '') . $host . ':' . $port;
        $ctx = stream_context_create([
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true],
        ]);
        $fp = @stream_socket_client($remote, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
        if (!$fp) {
            error_log("SMTP connect failed: $errstr ($errno)");
            return false;
        }
        stream_set_timeout($fp, 15);

        if (!smtp_expect($fp, '220')) { fclose($fp); return false; }

        $ehloHost = 'localhost';
        smtp_cmd($fp, "EHLO $ehloHost");
        if (!smtp_expect($fp, '250')) { fclose($fp); return false; }

        // STARTTLS cho cong 587.
        if ($secure === 'tls') {
            smtp_cmd($fp, 'STARTTLS');
            if (!smtp_expect($fp, '220')) { fclose($fp); return false; }
            if (!@stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                error_log('SMTP STARTTLS handshake failed');
                fclose($fp);
                return false;
            }
            smtp_cmd($fp, "EHLO $ehloHost");
            if (!smtp_expect($fp, '250')) { fclose($fp); return false; }
        }

        // Xac thuc AUTH LOGIN.
        if ($user !== '') {
            smtp_cmd($fp, 'AUTH LOGIN');
            if (!smtp_expect($fp, '334')) { fclose($fp); return false; }
            smtp_cmd($fp, base64_encode($user));
            if (!smtp_expect($fp, '334')) { fclose($fp); return false; }
            smtp_cmd($fp, base64_encode($pass));
            if (!smtp_expect($fp, '235')) { error_log('SMTP auth failed'); fclose($fp); return false; }
        }

        smtp_cmd($fp, 'MAIL FROM:<' . $fromEmail . '>');
        if (!smtp_expect($fp, '250')) { fclose($fp); return false; }

        smtp_cmd($fp, 'RCPT TO:<' . $to . '>');
        if (!smtp_expect($fp, '250')) { fclose($fp); return false; }

        smtp_cmd($fp, 'DATA');
        if (!smtp_expect($fp, '354')) { fclose($fp); return false; }

        $headers  = 'From: =?UTF-8?B?' . base64_encode($fromName) . "?= <$fromEmail>\r\n";
        $headers .= "To: <$to>\r\n";
        $headers .= 'Subject: =?UTF-8?B?' . base64_encode($subject) . "?=\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= 'Date: ' . date('r') . "\r\n";

        // Body base64 de tieng Viet khong bi loi font; chunk_split tu them CRLF.
        $body = chunk_split(base64_encode($htmlBody));

        // Ket thuc DATA bang dong chi chua dau cham.
        smtp_cmd($fp, $headers . "\r\n" . $body . "\r\n.");
        if (!smtp_expect($fp, '250')) { fclose($fp); return false; }

        smtp_cmd($fp, 'QUIT');
        fclose($fp);
        return true;
    } catch (\Throwable $e) {
        error_log('SMTP exception: ' . $e->getMessage());
        if (isset($fp) && is_resource($fp)) { @fclose($fp); }
        return false;
    }
}

/** Gui mot lenh SMTP (tu them CRLF). */
function smtp_cmd($fp, string $cmd): void {
    fwrite($fp, $cmd . "\r\n");
}

/**
 * Doc phan hoi nhieu dong cua SMTP va kiem tra ma tra ve mong doi.
 * Dinh dang phan hoi: "250-..." (con tiep) / "250 ..." (dong cuoi).
 */
function smtp_expect($fp, string $expectedCode): bool {
    $line = '';
    do {
        $line = fgets($fp, 515);
        if ($line === false) {
            return false;
        }
        // Dong cuoi co ky tu thu 4 la khoang trang (vd "250 OK").
    } while (isset($line[3]) && $line[3] === '-');

    return strncmp($line, $expectedCode, strlen($expectedCode)) === 0;
}

// =====================================================================
// Du phong: mail() cua PHP (chi dung khi khong cau hinh SMTP)
// =====================================================================

function php_mail_fallback(string $to, string $subject, string $htmlBody): bool {
    $siteName  = trim((string)setting('site_name', SITE_NAME));
    $fromEmail = trim((string)setting('contact_email', '')) ?: 'no-reply@hasaki.local';

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $encodedName    = '=?UTF-8?B?' . base64_encode($siteName) . '?=';

    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $encodedName . ' <' . $fromEmail . '>',
        'X-Mailer: Hasaki-Admin',
    ];

    try {
        return @mail($to, $encodedSubject, $htmlBody, implode("\r\n", $headers));
    } catch (\Throwable $e) {
        return false;
    }
}
