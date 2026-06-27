-- =====================================================================
-- Migration v5: Canh bao ton kho (Inventory alerts)
--   - Bang ThongBao: thong bao noi bo cho admin (chuong tren topbar)
--   - Them cau hinh nhom "kho": nguong canh bao, bat/tat email, email nhan
-- Chay:  mysql -uroot --default-character-set=utf8mb4 < database/migrate_v5.sql
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

-- ---------------------------------------------------------------------
-- Bang thong bao admin (dung chung, hien tai phuc vu canh bao ton kho)
--   Loai : 'het_hang' | 'sap_het' (co the mo rong sau)
--   MucDo: 'info' | 'warning' | 'danger'
--   MaThamChieu: ID doi tuong lien quan (vd MaSanPham) - khong FK de giu
--                lich su thong bao ke ca khi san pham bi xoa.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS ThongBao (
    MaThongBao   INT AUTO_INCREMENT PRIMARY KEY,
    Loai         VARCHAR(30)  NOT NULL DEFAULT 'ton_kho',
    MaThamChieu  INT          NULL,
    TieuDe       VARCHAR(255) NOT NULL,
    NoiDung      VARCHAR(500) NOT NULL,
    MucDo        VARCHAR(20)  NOT NULL DEFAULT 'info',
    Link         VARCHAR(255) NULL,
    DaDoc        TINYINT      NOT NULL DEFAULT 0,
    NgayTao      DATETIME     DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_thongbao_dadoc (DaDoc),
    INDEX idx_thongbao_loai_ref (Loai, MaThamChieu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Cau hinh nhom "kho" (hien trong trang Cai dat website)
-- ---------------------------------------------------------------------
INSERT INTO CauHinh (Khoa, GiaTri, Loai, Nhom, TenHienThi, GhiChu, ThuTu) VALUES
('low_stock_threshold', '10', 'number', 'kho', 'Ngưỡng cảnh báo sắp hết hàng',
 'Khi số lượng tồn của sản phẩm còn ≤ giá trị này (và > 0) sẽ tạo thông báo "sắp hết hàng" cho admin.', 1),
('low_stock_email_enable', '1', 'boolean', 'kho', 'Gửi email cảnh báo cho admin',
 'Bật để gửi email mỗi khi có sản phẩm sắp hết / hết hàng. Email gửi qua SMTP Gmail (cấu hình tài khoản gửi trong config/mail.php).', 2),
('admin_alert_email', '', 'email', 'kho', 'Email nhận cảnh báo',
 'Địa chỉ email nhận thông báo tồn kho. Để trống sẽ dùng Email liên hệ (mục Liên hệ), nếu vẫn trống dùng ADMIN_ALERT_EMAIL trong config/mail.php.', 3)
ON DUPLICATE KEY UPDATE TenHienThi = VALUES(TenHienThi), GhiChu = VALUES(GhiChu), Nhom = VALUES(Nhom);

-- ---------------------------------------------------------------------
-- Verify
-- ---------------------------------------------------------------------
SELECT 'ThongBao' AS Bang, COUNT(*) AS SoDong FROM ThongBao;
SELECT Khoa, GiaTri, Nhom FROM CauHinh WHERE Nhom = 'kho' ORDER BY ThuTu;
