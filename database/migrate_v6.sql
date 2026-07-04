-- =====================================================================
-- Migration v6: Thanh toan chuyen khoan ngan hang bang ma QR
-- ---------------------------------------------------------------------
-- Them nhom cai dat "thanhtoan" vao bang CauHinh de admin tu quan ly
-- anh QR + thong tin ngan hang. KHONG tich hop cong thanh toan that:
-- chi hien anh QR (admin upload) de khach quet & chuyen khoan thu cong,
-- khach phai tich xac nhan da chuyen khoan thi don moi duoc tao.
--
-- Chay: mysql -uroot --default-character-set=utf8mb4 < database/migrate_v6.sql
-- =====================================================================
USE hasaki_db;

INSERT INTO CauHinh (Khoa, GiaTri, Loai, Nhom, TenHienThi, GhiChu, ThuTu) VALUES
('bank_transfer_enable', '1',  'boolean', 'thanhtoan', 'Bật thanh toán QR',
    'Cho phép khách chọn hình thức chuyển khoản ngân hàng bằng mã QR khi thanh toán', 1),
('bank_qr_image',        '',   'image',   'thanhtoan', 'Ảnh mã QR',
    'Ảnh QR chuyển khoản (chụp từ app ngân hàng / VietQR). Khách quét ảnh này để chuyển tiền', 2),
('bank_name',            'Vietcombank', 'text', 'thanhtoan', 'Tên ngân hàng',
    'Ngân hàng & chi nhánh hiển thị cho khách, VD: Vietcombank - CN Phú Yên', 3),
('bank_account_no',      '', 'text', 'thanhtoan', 'Số tài khoản',
    'Số tài khoản nhận tiền', 4),
('bank_account_name',    '', 'text', 'thanhtoan', 'Chủ tài khoản',
    'Tên chủ tài khoản (viết in hoa không dấu như trên thẻ)', 5),
('bank_transfer_note',   'HASAKI DH', 'text', 'thanhtoan', 'Tiền tố nội dung CK',
    'Nội dung chuyển khoản = tiền tố này + mã đơn hàng, VD: "HASAKI DH123"', 6)
ON DUPLICATE KEY UPDATE
    Loai = VALUES(Loai), Nhom = VALUES(Nhom),
    TenHienThi = VALUES(TenHienThi), GhiChu = VALUES(GhiChu), ThuTu = VALUES(ThuTu);

SELECT Khoa, Loai, Nhom, TenHienThi FROM CauHinh WHERE Nhom = 'thanhtoan' ORDER BY ThuTu;
