-- =================================================================
-- HASAKI - Cosmetics E-commerce Website
-- Database schema based on report by Le Thi Kim Phung (D22CTC1)
-- IMPORTANT: pipe with --default-character-set=utf8mb4 to avoid
-- mojibake on Windows (mysql.exe defaults to cp437/cp850 otherwise).
-- =================================================================
SET NAMES utf8mb4;

DROP DATABASE IF EXISTS hasaki_db;
CREATE DATABASE hasaki_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hasaki_db;

-- =================================================================
-- 1. LOAI TAI KHOAN (Account types)
-- =================================================================
CREATE TABLE LoaiTaiKhoan (
    MaLoaiTaiKhoan   INT AUTO_INCREMENT PRIMARY KEY,
    TenLoaiTaiKhoan  VARCHAR(50) NOT NULL,
    MoTa             VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 2. TAI KHOAN (Accounts)
-- =================================================================
CREATE TABLE TaiKhoan (
    MaTaiKhoan    INT AUTO_INCREMENT PRIMARY KEY,
    TenTaiKhoan   VARCHAR(100) NOT NULL UNIQUE,
    MatKhau       VARCHAR(255) NOT NULL,
    LoaiTaiKhoan  INT NOT NULL,
    TrangThai     TINYINT NOT NULL DEFAULT 1,
    NgayTao       DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (LoaiTaiKhoan) REFERENCES LoaiTaiKhoan(MaLoaiTaiKhoan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 3. NHAN VIEN (Employees)
-- =================================================================
CREATE TABLE NhanVien (
    MaNV         INT AUTO_INCREMENT PRIMARY KEY,
    TenNV        VARCHAR(100) NOT NULL,
    DiaChi       VARCHAR(255) NULL,
    SDT          VARCHAR(15) NULL,
    Email        VARCHAR(100) NULL,
    CMND         VARCHAR(20) NOT NULL,
    GioiTinh     VARCHAR(10) NULL,
    AnhNhanVien  VARCHAR(255) NULL,
    MaTaiKhoan   INT NULL,
    FOREIGN KEY (MaTaiKhoan) REFERENCES TaiKhoan(MaTaiKhoan) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 4. KHACH HANG (Customers)
-- =================================================================
CREATE TABLE KhachHang (
    MaKhachHang   INT AUTO_INCREMENT PRIMARY KEY,
    TenKhachHang  VARCHAR(100) NOT NULL,
    DiaChi        VARCHAR(255) NULL,
    SDT           VARCHAR(15) NULL,
    Email         VARCHAR(100) NULL,
    GioiTinh      VARCHAR(10) NULL,
    MaTaiKhoan    INT NULL,
    NgayDangKy    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaTaiKhoan) REFERENCES TaiKhoan(MaTaiKhoan) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 5. DANH MUC (Categories)
-- =================================================================
CREATE TABLE DanhMuc (
    MaDanhMuc   INT AUTO_INCREMENT PRIMARY KEY,
    TenDanhMuc  VARCHAR(100) NOT NULL,
    MoTa        VARCHAR(255) NULL,
    AnhDanhMuc  VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 6. SAN PHAM (Products)
-- =================================================================
CREATE TABLE SanPham (
    MaSanPham       INT AUTO_INCREMENT PRIMARY KEY,
    MaDanhMuc       INT NOT NULL,
    TenSanPham      VARCHAR(255) NOT NULL,
    ThuongHieu      VARCHAR(100) NULL,
    XuatXu          VARCHAR(100) NULL,
    DungTich        VARCHAR(50)  NULL,
    LoaiDa          VARCHAR(100) NULL,
    SoLuong         INT NULL DEFAULT 0,
    GiaBan          DECIMAL(12,2) NOT NULL,
    MauSac          VARCHAR(100) NULL,
    KichCo          VARCHAR(100) NULL,
    MoTa            TEXT NULL,
    ThanhPhan       TEXT NULL,
    HuongDanSuDung  TEXT NULL,
    BaoQuan         TEXT NULL,
    HanSuDung       VARCHAR(100) NULL,
    HinhAnh         VARCHAR(255) NOT NULL,
    AnhHover1       VARCHAR(255) NULL,
    AnhHover2       VARCHAR(255) NULL,
    Sale            VARCHAR(20) NULL,
    ThongBao        VARCHAR(255) NULL,
    NgayTao         DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaDanhMuc) REFERENCES DanhMuc(MaDanhMuc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- DANH GIA (Product reviews)
-- =================================================================
CREATE TABLE DanhGia (
    MaDanhGia        INT AUTO_INCREMENT PRIMARY KEY,
    MaSanPham        INT NOT NULL,
    MaKhachHang      INT NULL,
    TenNguoiDanhGia  VARCHAR(100) NOT NULL,
    SoSao            TINYINT NOT NULL,
    TieuDe           VARCHAR(255) NULL,
    NoiDung          TEXT NOT NULL,
    HuuIch           INT DEFAULT 0,
    DaXacThucMua     TINYINT DEFAULT 0,
    NgayDanhGia      DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_sosao CHECK (SoSao BETWEEN 1 AND 5),
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham) ON DELETE CASCADE,
    FOREIGN KEY (MaKhachHang) REFERENCES KhachHang(MaKhachHang) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 7. HOA DON BAN (Sales invoices)
-- =================================================================
CREATE TABLE HoaDonBan (
    MaHoaDon      INT AUTO_INCREMENT PRIMARY KEY,
    NgayBan       DATETIME DEFAULT CURRENT_TIMESTAMP,
    ThanhTien     DECIMAL(12,2) NOT NULL,
    MaKhachHang   INT NOT NULL,
    SDT           VARCHAR(15) NULL,
    Email         VARCHAR(100) NULL,
    DiaChiGH      VARCHAR(255) NULL,
    GhiChu        VARCHAR(255) NULL,
    HinhThucTT    VARCHAR(50) DEFAULT 'COD',
    TrangThai     INT NOT NULL DEFAULT 0,
    FOREIGN KEY (MaKhachHang) REFERENCES KhachHang(MaKhachHang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- TrangThai: 0=Cho xac nhan, 1=Dang giao, 2=Hoan thanh, 3=Da huy

-- =================================================================
-- 8. CHI TIET HOA DON BAN (Sales invoice details)
-- =================================================================
CREATE TABLE ChiTietHoaDonBan (
    MaChiTietHoaDon   INT AUTO_INCREMENT PRIMARY KEY,
    MaHoaDon          INT NOT NULL,
    MaSanPham         INT NOT NULL,
    TenSanPham        VARCHAR(255) NOT NULL,
    SoLuong           INT NOT NULL,
    GiaBan            DECIMAL(12,2) NOT NULL,
    KichCo            VARCHAR(100) NULL,
    MauSac            VARCHAR(100) NULL,
    FOREIGN KEY (MaHoaDon) REFERENCES HoaDonBan(MaHoaDon) ON DELETE CASCADE,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 9. HOA DON NHAP (Import invoices)
-- =================================================================
CREATE TABLE HoaDonNhap (
    MaHoaDon       INT AUTO_INCREMENT PRIMARY KEY,
    NgayNhap       DATETIME DEFAULT CURRENT_TIMESTAMP,
    ThanhTien      DECIMAL(12,2) NULL,
    TenNCC         VARCHAR(100) NOT NULL,
    SDT            VARCHAR(15) NULL,
    MaNV           INT NULL,
    Email          VARCHAR(100) NULL,
    DiaChiLayHang  VARCHAR(255) NULL,
    FOREIGN KEY (MaNV) REFERENCES NhanVien(MaNV)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 10. CHI TIET HOA DON NHAP (Import invoice details)
-- =================================================================
CREATE TABLE ChiTietHoaDonNhap (
    MaChiTietHoaDon  INT AUTO_INCREMENT PRIMARY KEY,
    MaHoaDon         INT NOT NULL,
    MaSanPham        INT NOT NULL,
    TenSanPham       VARCHAR(255) NOT NULL,
    MauSac           VARCHAR(100) NULL,
    KichCo           VARCHAR(100) NULL,
    SoLuong          INT NOT NULL,
    GiaNhap          DECIMAL(12,2) NOT NULL,
    AnhSanPham       VARCHAR(255) NULL,
    FOREIGN KEY (MaHoaDon) REFERENCES HoaDonNhap(MaHoaDon) ON DELETE CASCADE,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- 11. CHATBOT INTENT (AI chatbot training data - admin editable)
-- =================================================================
CREATE TABLE ChatbotIntent (
    MaIntent       INT AUTO_INCREMENT PRIMARY KEY,
    TenIntent      VARCHAR(100) NOT NULL,
    TuKhoa         TEXT NOT NULL,                 -- pipe-separated keywords (no diacritics)
    CauTraLoi      TEXT NOT NULL,
    LoaiGoiY       VARCHAR(20) DEFAULT 'none',    -- none / search / featured / newest
    GiaTriGoiY     VARCHAR(100) NULL,             -- search keyword if LoaiGoiY=search
    Link           VARCHAR(255) NULL,             -- e.g. "login.php"
    ThuTu          INT DEFAULT 0,
    KichHoat       TINYINT DEFAULT 1,
    LaQuickReply   TINYINT DEFAULT 0,             -- show in quick-reply chips
    NgayCapNhat    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ChatbotConfig (
    ConfigKey   VARCHAR(50) PRIMARY KEY,
    ConfigValue TEXT NULL,
    GhiChu      VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default config
INSERT INTO ChatbotConfig (ConfigKey, ConfigValue, GhiChu) VALUES
('greeting',         'Xin chào! Mình là **Hasaki Bot** 💖\nMình có thể giúp bạn:\n• Tư vấn sản phẩm theo loại da\n• Tìm sản phẩm sale / mới / hot\n• Hướng dẫn đặt hàng / đổi trả', 'Lời chào khi mở chatbot'),
('fallback',         'Mình chưa hiểu rõ ý bạn 😅\nBạn có thể thử hỏi: tư vấn da khô/da dầu, tìm son môi, kem chống nắng, sản phẩm sale, cách đặt hàng, chính sách đổi trả...\n\nHoặc gọi hotline **1900 1234** để được tư vấn trực tiếp nhé 💕', 'Câu trả lời khi không tìm thấy intent phù hợp'),
('bot_name',         'Hasaki Bot', 'Tên hiển thị của chatbot'),
('max_products',     '4', 'Số sản phẩm tối đa hiển thị trong 1 reply'),
('enable_fallback_search', '1', 'Cho phép fallback search products khi không match intent (1/0)');

-- Default intents
INSERT INTO ChatbotIntent (TenIntent, TuKhoa, CauTraLoi, LoaiGoiY, GiaTriGoiY, Link, ThuTu, LaQuickReply) VALUES
('Chào hỏi',           'xin chao|chao|hello|hi|hey',
 'Chào bạn! Mình là **Hasaki Bot** - trợ lý mua sắm của bạn 💖\nMình có thể tư vấn sản phẩm, hướng dẫn đặt hàng và các chính sách. Bạn cần hỗ trợ gì ạ?',
 'none', NULL, NULL, 1, 0),

('Cảm ơn',             'cam on|thanks|thank you|thx',
 'Hasaki rất vui được hỗ trợ bạn! Chúc bạn mua sắm vui vẻ 🌸',
 'none', NULL, NULL, 2, 0),

('Tạm biệt',           'tam biet|bye|goodbye|hen gap lai',
 'Tạm biệt bạn! Hẹn gặp lại bạn lần sau 👋',
 'none', NULL, NULL, 3, 0),

('Hướng dẫn đặt hàng', 'dat hang|cach mua|mua hang|mua nhu the nao|order|cach dat hang',
 'Để đặt hàng tại Hasaki, bạn làm theo 3 bước:\n1️⃣ Chọn sản phẩm và nhấn **Thêm vào giỏ hàng**\n2️⃣ Vào **Giỏ hàng** → kiểm tra số lượng → nhấn **Tiến hành thanh toán**\n3️⃣ Điền địa chỉ và chọn hình thức thanh toán (COD hoặc ATM/VNPay)\n\nSau khi đặt hàng, bạn sẽ nhận được mã đơn hàng và đơn hàng sẽ được giao trong 1-3 ngày!',
 'none', NULL, NULL, 10, 1),

('Giao hàng',          'giao hang|ship|van chuyen|phi giao',
 '🚚 **Chính sách giao hàng:**\n• Nội thành TP.HCM: 30 phút - 2 giờ\n• Tỉnh khác: 1-3 ngày\n• **Miễn phí giao hàng** cho đơn từ 200.000đ\n• Áp dụng cho tất cả sản phẩm có sẵn trong kho',
 'none', NULL, NULL, 11, 0),

('Đổi trả',            'doi tra|tra hang|hoan tien|return|chinh sach doi tra',
 '🔄 **Chính sách đổi trả:**\n• Đổi trả miễn phí trong **7 ngày**\n• Sản phẩm phải còn nguyên seal, chưa qua sử dụng\n• Hỗ trợ đổi trả tận nhà, không cần ra cửa hàng\n• Hoàn 100% nếu sản phẩm lỗi từ nhà sản xuất',
 'none', NULL, NULL, 12, 1),

('Thanh toán',         'thanh toan|tra tien|payment|cod|vnpay|visa',
 '💳 **Hasaki hỗ trợ các hình thức thanh toán:**\n• Thanh toán khi nhận hàng (COD)\n• Thẻ ATM nội địa / Internet Banking\n• Thẻ tín dụng Visa/Mastercard/JCB\n• Ví VNPay-QR\n• Chuyển khoản ngân hàng',
 'none', NULL, NULL, 13, 0),

('Đăng ký',            'dang ky|tao tai khoan|register',
 'Bạn có thể đăng ký tài khoản miễn phí tại đây để nhận ưu đãi 💕',
 'none', NULL, 'register.php', 20, 0),

('Đăng nhập',          'dang nhap|login|sign in',
 'Bạn có thể đăng nhập tại đây 👇',
 'none', NULL, 'login.php', 21, 0),

('Tư vấn da khô',      'da kho',
 'Đối với **da khô** ☀️, Hasaki gợi ý:\n• Dùng sữa rửa mặt dịu nhẹ, không tạo bọt\n• Kem dưỡng giàu ceramide & hyaluronic acid\n• Tránh sản phẩm có cồn\n\nMột số sản phẩm phù hợp:',
 'search', 'duong', NULL, 30, 1),

('Tư vấn da dầu',      'da dau|da nhon',
 'Đối với **da dầu** 💧, Hasaki khuyên dùng:\n• Sữa rửa mặt có salicylic acid (BHA)\n• Toner cân bằng pH\n• Kem chống nắng dạng gel/lotion thoáng\n\nGợi ý sản phẩm:',
 'search', 'lam sach', NULL, 31, 1),

('Tư vấn da nhạy cảm', 'da nhay cam|kich ung',
 'Da nhạy cảm cần ưu tiên các sản phẩm dịu nhẹ, không hương liệu, không cồn. Bạn có thể thử **La Roche-Posay** hoặc **Bioderma** - các thương hiệu chuyên cho da nhạy cảm 🌸',
 'search', 'bioderma', NULL, 32, 0),

('Trị mụn',            'mun|tri mun',
 'Để **trị mụn** hiệu quả, mình khuyên:\n• Rửa mặt 2 lần/ngày, tránh sờ tay lên mặt\n• Dùng BHA/AHA hoặc niacinamide\n• Hạn chế đường, sữa và đồ cay nóng\n• Uống đủ 2L nước/ngày\n\nNếu mụn nặng, bạn nên đi khám da liễu nha 💖',
 'none', NULL, NULL, 33, 0),

('Son môi',            'son moi|son li|son tint|son',
 'Đây là một số mẫu son hot tại Hasaki 💋',
 'search', 'son', NULL, 40, 1),

('Kem chống nắng',     'kem chong nang|sunscreen|spf|chong nang',
 '☀️ Kem chống nắng là sản phẩm không thể thiếu mỗi ngày. Đây là gợi ý:',
 'search', 'chong nang', NULL, 41, 0),

('Nước hoa',           'nuoc hoa|perfume',
 '🌹 Nước hoa cao cấp tại Hasaki:',
 'search', 'nuoc hoa', NULL, 42, 0),

('Phấn phủ',           'phan phu|phan trang diem|lop nen',
 'Một số sản phẩm phấn phủ / trang điểm hot:',
 'search', 'phan', NULL, 43, 0),

('Sale - khuyến mãi',  'sale|giam gia|khuyen mai|uu dai|hot deal|san pham dang sale',
 '🔥 Sản phẩm đang **giảm giá** tại Hasaki:',
 'featured', NULL, NULL, 50, 1),

('Bán chạy',           'ban chay|hot|noi bat|pho bien|duoc yeu thich',
 '⭐ Top sản phẩm bán chạy tại Hasaki:',
 'featured', NULL, NULL, 51, 0),

('Mới ra mắt',         'moi nhat|moi ra mat|new arrival',
 '✨ Sản phẩm mới ra mắt:',
 'newest', NULL, NULL, 52, 0),

('Liên hệ',            'lien he|hotline|so dien thoai|sdt|contact|tu van',
 '📞 **Liên hệ Hasaki:**\n• Hotline: 1900 1234 (8:00 - 22:00)\n• Email: support@hasaki.vn\n• Địa chỉ: 123 Lê Lợi, Q.1, TP.HCM\n• Fanpage Facebook: facebook.com/hasaki.vn',
 'none', NULL, NULL, 60, 0);

-- =================================================================
-- 12. KHO HANG (Warehouse)
-- =================================================================
CREATE TABLE KhoHang (
    MaKho        INT AUTO_INCREMENT PRIMARY KEY,
    MaSanPham    INT NOT NULL,
    TenSanPham   VARCHAR(255) NOT NULL,
    SoLuongCon   INT NOT NULL DEFAULT 0,
    NgayNhap     DATE NULL,
    LoaiSanPham  VARCHAR(100) NULL,
    AnhSanPham   VARCHAR(255) NULL,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =================================================================
-- SEED DATA
-- =================================================================

-- Loai tai khoan
INSERT INTO LoaiTaiKhoan (TenLoaiTaiKhoan, MoTa) VALUES
('Admin', 'Quan tri he thong'),
('NhanVien', 'Nhan vien cua hang'),
('KhachHang', 'Khach hang mua hang');

-- Tai khoan (default password is 123456 - hashed via password_hash with PASSWORD_BCRYPT)
-- '123456' bcrypt hash:
INSERT INTO TaiKhoan (TenTaiKhoan, MatKhau, LoaiTaiKhoan) VALUES
('admin', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 1),
('nhanvien1', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 2),
('khach01', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 3);

-- Nhan vien
INSERT INTO NhanVien (TenNV, DiaChi, SDT, Email, CMND, GioiTinh, MaTaiKhoan) VALUES
('Le Thi Kim Phung', 'Dak Lak', '0901111111', 'phung@hasaki.vn', '241234567890', 'Nu', 1),
('Nguyen Van A', 'TP HCM', '0902222222', 'vana@hasaki.vn', '241234567891', 'Nam', 2);

-- Khach hang
INSERT INTO KhachHang (TenKhachHang, DiaChi, SDT, Email, GioiTinh, MaTaiKhoan) VALUES
('Tran Thi B', '123 Nguyen Hue, Q1, TP HCM', '0903333333', 'tranb@gmail.com', 'Nu', 3);

-- Danh muc
INSERT INTO DanhMuc (TenDanhMuc, MoTa) VALUES
('Son moi', 'Cac loai son moi cao cap'),
('Duong da', 'San pham duong da'),
('Trang diem', 'My pham trang diem'),
('Lam sach da', 'Sua rua mat, tay trang'),
('Cham soc co the', 'Sua tam, lotion duong the'),
('Nuoc hoa', 'Nuoc hoa nu / nam');

-- San pham
INSERT INTO SanPham (MaDanhMuc, TenSanPham, SoLuong, GiaBan, MauSac, KichCo, MoTa, HinhAnh, Sale) VALUES
(1, 'Son li Ruby Kiss', 100, 259000, 'Do', '3.5g', 'Son li mau do quyen ru, lau troi 8 gio', 'images/products/son-ruby-kiss.jpg', '20'),
(1, 'Son duong Moist Touch', 80, 199000, 'Hong', '4g', 'Son duong moi mem mai, khong kho moi', 'images/products/son-moist-touch.jpg', NULL),
(1, 'Son kem Velvet Matte', 120, 289000, 'Cam dat', '6ml', 'Son kem li min mong nhu nhung', 'images/products/son-velvet-matte.jpg', '15'),
(3, 'Phan phu Oil Control Powder', 60, 299000, 'Tu nhien', '12g', 'Phan phu kiem dau, giu lop nen ben suot ngay', 'images/products/phan-phu-oil-control.jpg', NULL),
(3, 'Phan phu khoang Natural Veil', 50, 349000, 'Nude', '10g', 'Phan phu khoang lam diu da, khong gay bit tac', 'images/products/phan-phu-natural-veil.jpg', '10'),
(2, 'Sua duong Bioderma Hydrabio', 40, 459000, NULL, '40ml', 'Sua duong cap am cho da hon hop', 'images/products/bioderma-hydrabio.jpg', NULL),
(2, 'Kem chong nang Anessa', 70, 569000, NULL, '60ml', 'Kem chong nang Anessa SPF50+ PA++++', 'images/products/anessa-sunscreen.jpg', '5'),
(2, 'Kem duong La Roche-Posay', 35, 489000, NULL, '50ml', 'Kem duong da nhay cam La Roche-Posay', 'images/products/laroche-cream.jpg', NULL),
(4, 'Sua rua mat Cetaphil', 90, 219000, NULL, '250ml', 'Sua rua mat dieu hoa cho moi loai da', 'images/products/cetaphil-cleanser.jpg', NULL),
(4, 'Tay trang Bioderma Sensibio', 75, 369000, NULL, '500ml', 'Nuoc tay trang Bioderma Sensibio H2O', 'images/products/bioderma-h2o.jpg', '10'),
(5, 'Sua tam Dove Body Wash', 110, 159000, NULL, '500ml', 'Sua tam Dove duong am, huong nhe nhang', 'images/products/dove-bodywash.jpg', NULL),
(5, 'Lotion duong the Vaseline', 95, 129000, NULL, '400ml', 'Lotion duong the Vaseline cap am 24h', 'images/products/vaseline-lotion.jpg', NULL),
(6, 'Nuoc hoa Chanel Coco', 20, 2890000, NULL, '50ml', 'Nuoc hoa nu Chanel Coco Mademoiselle EDP', 'images/products/chanel-coco.jpg', NULL),
(6, 'Nuoc hoa Dior Sauvage', 25, 2590000, NULL, '60ml', 'Nuoc hoa nam Dior Sauvage EDT', 'images/products/dior-sauvage.jpg', '5'),
(3, 'Phan ma hong Bobbi Brown', 55, 429000, 'Hong dao', '3.5g', 'Phan ma hong tao khoi tu nhien', 'images/products/bobbi-blush.jpg', NULL),
(1, 'Son tint Romand Juicy', 130, 189000, 'Do cam', '5g', 'Son tint bong nuoc cap am moi', 'images/products/romand-tint.jpg', '20');

-- Kho hang
INSERT INTO KhoHang (MaSanPham, TenSanPham, SoLuongCon, NgayNhap, LoaiSanPham)
SELECT MaSanPham, TenSanPham, SoLuong, CURDATE(), 'Hang chinh hang' FROM SanPham;
