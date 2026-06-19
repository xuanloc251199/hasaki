-- =====================================================================
-- Migration v2: Vietnamese data + product detail enhancements
-- Run with: mysql -uroot --default-character-set=utf8mb4 < migrate_v2.sql
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

-- ---------------------------------------------------------------------
-- 1. Add product detail columns
-- ---------------------------------------------------------------------
ALTER TABLE SanPham
  ADD COLUMN ThuongHieu       VARCHAR(100) NULL AFTER TenSanPham,
  ADD COLUMN XuatXu           VARCHAR(100) NULL AFTER ThuongHieu,
  ADD COLUMN DungTich         VARCHAR(50)  NULL AFTER XuatXu,
  ADD COLUMN LoaiDa           VARCHAR(100) NULL AFTER DungTich,
  ADD COLUMN ThanhPhan        TEXT NULL,
  ADD COLUMN HuongDanSuDung   TEXT NULL,
  ADD COLUMN BaoQuan          TEXT NULL,
  ADD COLUMN HanSuDung        VARCHAR(100) NULL;

-- ---------------------------------------------------------------------
-- 2. Reviews table
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS DanhGia;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Update DanhMuc - Vietnamese with diacritics
-- ---------------------------------------------------------------------
UPDATE DanhMuc SET TenDanhMuc = 'Son môi',
       MoTa = 'Các loại son môi cao cấp - son lì, son tint, son dưỡng' WHERE MaDanhMuc = 1;
UPDATE DanhMuc SET TenDanhMuc = 'Dưỡng da',
       MoTa = 'Sữa dưỡng, kem dưỡng, serum cấp ẩm cho mọi loại da' WHERE MaDanhMuc = 2;
UPDATE DanhMuc SET TenDanhMuc = 'Trang điểm',
       MoTa = 'Phấn nền, phấn phủ, phấn má hồng và phụ kiện trang điểm' WHERE MaDanhMuc = 3;
UPDATE DanhMuc SET TenDanhMuc = 'Làm sạch da',
       MoTa = 'Sữa rửa mặt, tẩy trang, toner cân bằng' WHERE MaDanhMuc = 4;
UPDATE DanhMuc SET TenDanhMuc = 'Chăm sóc cơ thể',
       MoTa = 'Sữa tắm, lotion dưỡng thể, kem dưỡng tay' WHERE MaDanhMuc = 5;
UPDATE DanhMuc SET TenDanhMuc = 'Nước hoa',
       MoTa = 'Nước hoa nữ và nam từ các thương hiệu uy tín thế giới' WHERE MaDanhMuc = 6;

-- ---------------------------------------------------------------------
-- 4. Update LoaiTaiKhoan
-- ---------------------------------------------------------------------
UPDATE LoaiTaiKhoan SET TenLoaiTaiKhoan = 'Quản trị viên',
       MoTa = 'Quản trị toàn bộ hệ thống' WHERE MaLoaiTaiKhoan = 1;
UPDATE LoaiTaiKhoan SET TenLoaiTaiKhoan = 'Nhân viên',
       MoTa = 'Nhân viên cửa hàng - quản lý sản phẩm và đơn hàng' WHERE MaLoaiTaiKhoan = 2;
UPDATE LoaiTaiKhoan SET TenLoaiTaiKhoan = 'Khách hàng',
       MoTa = 'Khách hàng mua sắm trên website' WHERE MaLoaiTaiKhoan = 3;

-- ---------------------------------------------------------------------
-- 5. Update NhanVien
-- ---------------------------------------------------------------------
UPDATE NhanVien SET
  TenNV    = 'Lê Thị Kim Phụng',
  DiaChi   = 'Số 12 đường Lê Duẩn, TP. Buôn Ma Thuột, Đắk Lắk',
  Email    = 'phung@hasaki.vn',
  GioiTinh = 'Nữ'
WHERE MaNV = 1;

UPDATE NhanVien SET
  TenNV    = 'Nguyễn Văn An',
  DiaChi   = '45 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh',
  Email    = 'vana@hasaki.vn',
  GioiTinh = 'Nam'
WHERE MaNV = 2;

INSERT INTO NhanVien (TenNV, DiaChi, SDT, Email, CMND, GioiTinh, MaTaiKhoan) VALUES
('Trần Mỹ Linh',   '88 Trần Hưng Đạo, Quận 5, TP. HCM', '0903456789', 'linhtm@hasaki.vn', '079123456001', 'Nữ', NULL),
('Phạm Quốc Bảo',  '12 Lý Thường Kiệt, Đà Nẵng',         '0904567890', 'baopq@hasaki.vn', '048123456002', 'Nam', NULL),
('Hoàng Thuý Hằng','35 Hai Bà Trưng, Hà Nội',             '0905678901', 'hangth@hasaki.vn','001123456003', 'Nữ', NULL);

-- ---------------------------------------------------------------------
-- 6. Update KhachHang
-- ---------------------------------------------------------------------
UPDATE KhachHang SET
  TenKhachHang = 'Trần Thị Bích Ngọc',
  DiaChi       = '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
  Email        = 'tranngoc@gmail.com',
  GioiTinh     = 'Nữ'
WHERE MaKhachHang = 1;

INSERT INTO KhachHang (TenKhachHang, DiaChi, SDT, Email, GioiTinh) VALUES
('Nguyễn Thị Hồng Ngân', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', '0987111222', 'ngannth@gmail.com', 'Nữ'),
('Phạm Mai Anh',         '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM',        '0987222333', 'maianh@gmail.com', 'Nữ'),
('Lê Hồng Vy',           '45 Cách Mạng Tháng 8, Q.3, TP.HCM',          '0987333444', 'vylh@gmail.com',   'Nữ'),
('Đỗ Quỳnh Anh',         '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM',   '0987444555', 'quynhanh@gmail.com','Nữ'),
('Vũ Thanh Tâm',         '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM',       '0987555666', 'tamvt@gmail.com',  'Nam'),
('Bùi Khánh Linh',       '67 Lê Văn Sỹ, Q.3, TP.HCM',                  '0987666777', 'linhbk@gmail.com', 'Nữ');

-- ---------------------------------------------------------------------
-- 7. Update SanPham (Vietnamese names + full details)
-- ---------------------------------------------------------------------

-- 1. Son lì Ruby Kiss
UPDATE SanPham SET
  TenSanPham = 'Son lì Ruby Kiss Long-Wear Lipstick',
  ThuongHieu = 'Ruby Kiss',
  XuatXu = 'Hàn Quốc',
  DungTich = '3.5g',
  LoaiDa = 'Mọi loại môi',
  MauSac = 'Đỏ ruby',
  KichCo = '3.5g',
  MoTa = 'Son lì Ruby Kiss với màu đỏ ruby quyến rũ, lên màu chuẩn ngay từ lần đầu tiên. Công thức lì mịn lâu trôi đến 8 giờ, không khô môi nhờ chiết xuất bơ hạt mỡ và vitamin E. Phù hợp cho buổi tiệc, dạo phố và những dịp đặc biệt.',
  ThanhPhan = 'Hydrogenated Polyisobutene, Polyethylene, Bis-Diglyceryl Polyacyladipate-2, Octyldodecanol, Vitamin E (Tocopheryl Acetate), Shea Butter (Butyrospermum Parkii), Mica, Iron Oxides (CI 77491, CI 77492, CI 77499), Red 7 (CI 15850).',
  HuongDanSuDung = '1. Tẩy tế bào chết môi nhẹ nhàng trước khi sử dụng\n2. Thoa son trực tiếp từ giữa môi ra hai bên\n3. Tô đậm thêm 1 lớp ở giữa để tạo hiệu ứng ombre\n4. Dùng giấy thấm dầu để định hình son lì lâu trôi hơn',
  BaoQuan = 'Bảo quản nơi khô ráo, thoáng mát. Tránh ánh nắng trực tiếp. Đậy kín nắp sau khi sử dụng.',
  HanSuDung = '24 tháng kể từ ngày sản xuất',
  GiaBan = 259000, Sale = '20'
WHERE MaSanPham = 1;

-- 2. Son dưỡng Moist Touch
UPDATE SanPham SET
  TenSanPham = 'Son dưỡng môi Moist Touch Hydra',
  ThuongHieu = 'Moist Touch',
  XuatXu = 'Nhật Bản',
  DungTich = '4g',
  LoaiDa = 'Môi khô, môi nứt nẻ',
  MauSac = 'Hồng nude',
  KichCo = '4g',
  MoTa = 'Son dưỡng môi Moist Touch giúp dưỡng ẩm sâu, làm mềm và phục hồi đôi môi nứt nẻ. Hương cherry tự nhiên dễ chịu. Có thể dùng làm lớp lót trước khi đánh son lì để bảo vệ môi.',
  ThanhPhan = 'Hyaluronic Acid, Vitamin E, Squalane, Shea Butter, Cocoa Butter, Beeswax, Cherry Extract, Glycerin.',
  HuongDanSuDung = '• Dùng buổi sáng và tối, hoặc bất cứ khi nào môi khô\n• Có thể dùng làm lớp lót trước khi đánh son màu\n• Thoa lớp dày trước khi đi ngủ để dưỡng môi qua đêm',
  BaoQuan = 'Tránh nơi nhiệt độ cao trên 30°C. Đậy kín sau khi dùng.',
  HanSuDung = '36 tháng kể từ ngày sản xuất',
  GiaBan = 199000, Sale = NULL
WHERE MaSanPham = 2;

-- 3. Son kem Velvet Matte
UPDATE SanPham SET
  TenSanPham = 'Son kem lì mịn nhung Velvet Matte',
  ThuongHieu = 'Velvet',
  XuatXu = 'Hàn Quốc',
  DungTich = '6ml',
  LoaiDa = 'Mọi loại môi',
  MauSac = 'Cam đất',
  KichCo = '6ml',
  MoTa = 'Son kem lì mịn nhung Velvet Matte có chất kem mỏng nhẹ, lên màu mịn như nhung. Bám lâu suốt 10 giờ mà không gây khô môi. Đa dạng tone màu hot trend từ neutral đến đậm cá tính.',
  ThanhPhan = 'Isododecane, Trimethylsiloxysilicate, Silica, Dimethicone, Hyaluronic Acid, Vitamin E, Color CI Pigments.',
  HuongDanSuDung = '1. Lắc đều trước khi dùng\n2. Đầu cọ nhỏ giúp tô viền môi sắc nét\n3. Tán đều ra giữa môi\n4. Đợi 30 giây để son khô và lì hoàn toàn',
  BaoQuan = 'Đậy kín nắp ngay sau khi dùng để tránh khô son. Bảo quản nơi thoáng mát.',
  HanSuDung = '24 tháng',
  GiaBan = 289000, Sale = '15'
WHERE MaSanPham = 3;

-- 4. Phấn phủ Oil Control
UPDATE SanPham SET
  TenSanPham = 'Phấn phủ kiềm dầu Oil Control Powder',
  ThuongHieu = 'MAKE UP FOR EVER',
  XuatXu = 'Pháp',
  DungTich = '12g',
  LoaiDa = 'Da dầu, da hỗn hợp thiên dầu',
  MauSac = 'Tự nhiên',
  KichCo = '12g',
  MoTa = 'Phấn phủ Oil Control Powder kiềm dầu vượt trội, giữ lớp nền bền đẹp suốt 12 giờ. Hạt phấn siêu mịn, khô thoáng nhưng không gây cakey. Lý tưởng cho da dầu và khí hậu nóng ẩm Việt Nam.',
  ThanhPhan = 'Mica, Talc, Silica, Boron Nitride, Magnesium Stearate, Zinc Oxide, Iron Oxides.',
  HuongDanSuDung = '1. Sau khi đánh kem nền, dùng cọ tán phấn đều khắp mặt\n2. Tập trung vào vùng chữ T (trán, mũi, cằm)\n3. Có thể dặm thêm trong ngày để kiềm dầu',
  BaoQuan = 'Đậy kín nắp. Tránh ẩm và ánh nắng trực tiếp.',
  HanSuDung = '36 tháng',
  GiaBan = 299000, Sale = NULL
WHERE MaSanPham = 4;

-- 5. Phấn phủ khoáng Natural Veil
UPDATE SanPham SET
  TenSanPham = 'Phấn phủ khoáng Natural Veil',
  ThuongHieu = 'Natural Veil',
  XuatXu = 'Mỹ',
  DungTich = '10g',
  LoaiDa = 'Da nhạy cảm, da mụn',
  MauSac = 'Nude tự nhiên',
  KichCo = '10g',
  MoTa = 'Phấn phủ khoáng Natural Veil chứa khoáng chất tinh khiết, an toàn cho da nhạy cảm. Không gây bít tắc lỗ chân lông. Cho lớp nền tự nhiên, mỏng nhẹ như không trang điểm.',
  ThanhPhan = 'Mica, Titanium Dioxide, Iron Oxides, Bismuth Oxychloride, Zinc Oxide, Boron Nitride.',
  HuongDanSuDung = '1. Mở nắp, lắc nhẹ để bột phấn rơi xuống lưới\n2. Dùng cọ kabuki chấm phấn\n3. Phủi nhẹ trên mu bàn tay rồi tán đều khắp mặt theo chuyển động tròn',
  BaoQuan = 'Đóng kín nắp sau khi dùng. Tránh để nơi ẩm ướt.',
  HanSuDung = '36 tháng',
  GiaBan = 349000, Sale = '10'
WHERE MaSanPham = 5;

-- 6. Sữa dưỡng Bioderma Hydrabio
UPDATE SanPham SET
  TenSanPham = 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum',
  ThuongHieu = 'Bioderma',
  XuatXu = 'Pháp',
  DungTich = '40ml',
  LoaiDa = 'Da khô, da thiếu ẩm',
  MauSac = NULL,
  KichCo = '40ml',
  MoTa = 'Sữa dưỡng Bioderma Hydrabio chứa Aquagenium giúp khôi phục cơ chế cấp ẩm tự nhiên của da. Cấp ẩm tức thì lên đến 24 giờ, da căng mịn rạng rỡ. Phù hợp với mọi loại da, kể cả da nhạy cảm.',
  ThanhPhan = 'Aqua, Glycerin, Hyaluronic Acid, Niacinamide, Aquagenium Patent, Apple Seed Extract, Vitamin E, Tocopheryl Acetate.',
  HuongDanSuDung = '1. Dùng sau bước toner, trước kem dưỡng\n2. Lấy 2-3 giọt thoa đều lên mặt và cổ\n3. Vỗ nhẹ để tinh chất thẩm thấu\n4. Dùng sáng và tối hàng ngày',
  BaoQuan = 'Bảo quản dưới 25°C. Tránh ánh nắng trực tiếp.',
  HanSuDung = '12 tháng sau khi mở nắp',
  GiaBan = 459000, Sale = NULL
WHERE MaSanPham = 6;

-- 7. Kem chống nắng Anessa
UPDATE SanPham SET
  TenSanPham = 'Kem chống nắng Anessa Perfect UV Sunscreen',
  ThuongHieu = 'Anessa (Shiseido)',
  XuatXu = 'Nhật Bản',
  DungTich = '60ml',
  LoaiDa = 'Mọi loại da, kể cả da dầu',
  MauSac = NULL,
  KichCo = '60ml',
  MoTa = 'Kem chống nắng Anessa SPF50+ PA++++ chống nắng tối ưu, công nghệ Aqua Booster gặp nước & mồ hôi càng tăng hiệu quả. Không trôi khi đi bơi. Bảng thành phần dưỡng da với 50% công thức skincare.',
  ThanhPhan = 'Octinoxate, Octocrylene, Zinc Oxide, Titanium Dioxide, Hyaluronic Acid, Collagen, Royal Jelly Extract, Glycerin.',
  HuongDanSuDung = '1. Lắc đều trước khi dùng\n2. Bước cuối cùng trong chu trình skincare buổi sáng\n3. Thoa lượng đủ lớn (2 đốt ngón tay) lên mặt\n4. Bôi lại sau mỗi 2-3 giờ nếu hoạt động ngoài trời',
  BaoQuan = 'Bảo quản nơi khô ráo, thoáng mát.',
  HanSuDung = '12 tháng sau khi mở nắp',
  GiaBan = 569000, Sale = '5'
WHERE MaSanPham = 7;

-- 8. Kem dưỡng La Roche-Posay
UPDATE SanPham SET
  TenSanPham = 'Kem dưỡng La Roche-Posay Toleriane',
  ThuongHieu = 'La Roche-Posay',
  XuatXu = 'Pháp',
  DungTich = '50ml',
  LoaiDa = 'Da nhạy cảm, da kích ứng',
  MauSac = NULL,
  KichCo = '50ml',
  MoTa = 'Kem dưỡng La Roche-Posay Toleriane chuyên biệt cho da nhạy cảm. Không hương liệu, không paraben. Làm dịu, giảm kích ứng, phục hồi hàng rào bảo vệ da. Được kiểm nghiệm da liễu.',
  ThanhPhan = 'Aqua, Glycerin, Squalane, Shea Butter, Niacinamide, Thermal Spring Water, Tocopherol, Allantoin.',
  HuongDanSuDung = '1. Sau bước serum, dùng đầu ngón tay lấy 1 lượng vừa đủ\n2. Thoa đều lên mặt và cổ\n3. Massage nhẹ nhàng theo chuyển động tròn\n4. Sử dụng sáng và tối',
  BaoQuan = 'Đóng kín sau khi dùng. Tránh ánh nắng trực tiếp.',
  HanSuDung = '12 tháng sau khi mở',
  GiaBan = 489000, Sale = NULL
WHERE MaSanPham = 8;

-- 9. Sữa rửa mặt Cetaphil
UPDATE SanPham SET
  TenSanPham = 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser',
  ThuongHieu = 'Cetaphil',
  XuatXu = 'Mỹ',
  DungTich = '250ml',
  LoaiDa = 'Mọi loại da, đặc biệt da nhạy cảm',
  MauSac = NULL,
  KichCo = '250ml',
  MoTa = 'Sữa rửa mặt Cetaphil dịu nhẹ, không tạo bọt, không xà phòng. Làm sạch hiệu quả mà không làm khô da. Khuyên dùng cho mọi loại da, kể cả da em bé. Được khuyên dùng bởi các bác sĩ da liễu.',
  ThanhPhan = 'Aqua, Cetyl Alcohol, Propylene Glycol, Sodium Lauryl Sulfate, Stearyl Alcohol, Methylparaben, Propylparaben, Butylparaben.',
  HuongDanSuDung = '1. Làm ướt mặt với nước ấm\n2. Lấy 1 lượng vừa đủ vào tay\n3. Massage nhẹ nhàng khắp mặt và cổ\n4. Rửa sạch với nước hoặc lau bằng khăn ẩm',
  BaoQuan = 'Bảo quản ở nhiệt độ phòng. Đậy kín sau khi dùng.',
  HanSuDung = '36 tháng',
  GiaBan = 219000, Sale = NULL
WHERE MaSanPham = 9;

-- 10. Tẩy trang Bioderma
UPDATE SanPham SET
  TenSanPham = 'Nước tẩy trang Bioderma Sensibio H2O',
  ThuongHieu = 'Bioderma',
  XuatXu = 'Pháp',
  DungTich = '500ml',
  LoaiDa = 'Da nhạy cảm, mọi loại da',
  MauSac = NULL,
  KichCo = '500ml',
  MoTa = 'Nước tẩy trang Bioderma Sensibio H2O - sản phẩm bán chạy nhất thế giới. Làm sạch makeup, bụi bẩn nhẹ nhàng mà không cần rửa lại với nước. Phù hợp cho da nhạy cảm, mắt đeo kính áp tròng.',
  ThanhPhan = 'Aqua, PEG-6 Caprylic/Capric Glycerides, Cucumber (Cucumis Sativus) Fruit Extract, Mannitol, Xylitol, Rhamnose, Fructooligosaccharides.',
  HuongDanSuDung = '1. Thấm 1 lượng dung dịch ra bông tẩy trang\n2. Lau nhẹ nhàng khắp mặt, mắt và môi\n3. Không cần rửa lại với nước\n4. Tiếp tục các bước skincare',
  BaoQuan = 'Bảo quản ở nhiệt độ phòng. Tránh ánh nắng.',
  HanSuDung = '12 tháng sau khi mở nắp',
  GiaBan = 369000, Sale = '10'
WHERE MaSanPham = 10;

-- 11. Sữa tắm Dove
UPDATE SanPham SET
  TenSanPham = 'Sữa tắm Dove Body Wash dưỡng ẩm',
  ThuongHieu = 'Dove',
  XuatXu = 'Mỹ',
  DungTich = '500ml',
  LoaiDa = 'Mọi loại da',
  MauSac = NULL,
  KichCo = '500ml',
  MoTa = 'Sữa tắm Dove dưỡng ẩm với 1/4 kem dưỡng, làm sạch nhẹ nhàng và giữ độ ẩm tự nhiên cho da. Hương thơm dịu nhẹ, lưu hương lâu trên cơ thể.',
  ThanhPhan = 'Aqua, Sodium Lauroyl Isethionate, Sodium Lauryl Sulfate, Glycerin, Cocamidopropyl Betaine, Stearic Acid, Lauric Acid, Sodium Isethionate, Parfum.',
  HuongDanSuDung = '1. Làm ướt cơ thể\n2. Cho 1 lượng vừa đủ ra bông tắm hoặc tay\n3. Tạo bọt và massage khắp cơ thể\n4. Rửa sạch với nước',
  BaoQuan = 'Đóng kín nắp. Bảo quản nơi khô ráo.',
  HanSuDung = '36 tháng',
  GiaBan = 159000, Sale = NULL
WHERE MaSanPham = 11;

-- 12. Lotion Vaseline
UPDATE SanPham SET
  TenSanPham = 'Lotion dưỡng thể Vaseline Intensive Care',
  ThuongHieu = 'Vaseline',
  XuatXu = 'Mỹ',
  DungTich = '400ml',
  LoaiDa = 'Mọi loại da, da khô',
  MauSac = NULL,
  KichCo = '400ml',
  MoTa = 'Lotion dưỡng thể Vaseline với công nghệ khoá ẩm, dưỡng ẩm sâu suốt 24 giờ. Không nhờn rít, thẩm thấu nhanh. Được khuyên dùng bởi 9/10 bác sĩ da liễu.',
  ThanhPhan = 'Aqua, Petrolatum, Glycerin, Stearic Acid, Cetyl Alcohol, Glyceryl Stearate, Dimethicone, Tocopheryl Acetate.',
  HuongDanSuDung = '1. Dùng sau khi tắm, da còn hơi ẩm\n2. Lấy 1 lượng vừa đủ ra tay\n3. Thoa đều khắp cơ thể\n4. Massage nhẹ đến khi thẩm thấu hoàn toàn',
  BaoQuan = 'Bảo quản nơi mát mẻ.',
  HanSuDung = '36 tháng',
  GiaBan = 129000, Sale = NULL
WHERE MaSanPham = 12;

-- 13. Nước hoa Chanel
UPDATE SanPham SET
  TenSanPham = 'Nước hoa Chanel Coco Mademoiselle EDP',
  ThuongHieu = 'Chanel',
  XuatXu = 'Pháp',
  DungTich = '50ml',
  LoaiDa = NULL,
  MauSac = NULL,
  KichCo = '50ml',
  MoTa = 'Nước hoa nữ Chanel Coco Mademoiselle - một trong những nước hoa kinh điển của Chanel. Hương cam tươi mát mở đầu, tim hương hoa nhài và hồng, hậu hương xạ hương ấm áp. Quyến rũ và sang trọng.',
  ThanhPhan = 'Alcohol, Aqua, Parfum, Citrus Extracts, Jasmine, Rose, Patchouli, White Musk, Vanilla.',
  HuongDanSuDung = '• Xịt vào điểm mạch như cổ tay, sau tai, gáy\n• Không chà xát các điểm xịt nhau\n• Lưu hương lên đến 12 giờ\n• Phù hợp cho mọi dịp - làm việc, dạo phố, dạ tiệc',
  BaoQuan = 'Tránh ánh nắng trực tiếp và nhiệt độ cao.',
  HanSuDung = '36 tháng từ ngày sản xuất',
  GiaBan = 2890000, Sale = NULL
WHERE MaSanPham = 13;

-- 14. Nước hoa Dior
UPDATE SanPham SET
  TenSanPham = 'Nước hoa Dior Sauvage EDT',
  ThuongHieu = 'Dior',
  XuatXu = 'Pháp',
  DungTich = '60ml',
  LoaiDa = NULL,
  MauSac = NULL,
  KichCo = '60ml',
  MoTa = 'Nước hoa nam Dior Sauvage - hương thơm mạnh mẽ và quyến rũ. Mở đầu hương cam Bergamot Calabria sảng khoái, tim hương hoa oải hương Lavender, hậu hương Ambroxan ấm áp gợi cảm.',
  ThanhPhan = 'Alcohol, Aqua, Parfum, Bergamot, Pepper, Lavender, Pink Pepper, Vetiver, Ambroxan.',
  HuongDanSuDung = '• Xịt vào điểm mạch và da khô\n• Lý tưởng cho buổi tối và dịp đặc biệt\n• Lưu hương 8-10 giờ',
  BaoQuan = 'Bảo quản nơi khô mát, tránh ánh nắng.',
  HanSuDung = '36 tháng',
  GiaBan = 2590000, Sale = '5'
WHERE MaSanPham = 14;

-- 15. Phấn má Bobbi Brown
UPDATE SanPham SET
  TenSanPham = 'Phấn má hồng Bobbi Brown Pot Rouge',
  ThuongHieu = 'Bobbi Brown',
  XuatXu = 'Mỹ',
  DungTich = '3.5g',
  LoaiDa = 'Mọi loại da',
  MauSac = 'Hồng đào',
  KichCo = '3.5g',
  MoTa = 'Phấn má hồng Bobbi Brown Pot Rouge tạo hiệu ứng má hồng tự nhiên, rạng rỡ. Texture kem mịn dễ tán, có thể dùng cả trên môi để tạo look đồng bộ. Lên màu chuẩn, lâu trôi.',
  ThanhPhan = 'Mica, Talc, Octyldodecyl Stearoyl Stearate, Pentaerythrityl Tetraisostearate, Iron Oxides, Carmine.',
  HuongDanSuDung = '1. Chấm 1 chút lên mu bàn tay\n2. Dùng đầu ngón tay hoặc cọ tán đều\n3. Cười nhẹ để xác định gò má, chấm và tán đều\n4. Dùng làm son lì để tạo look thống nhất',
  BaoQuan = 'Đóng kín nắp sau khi dùng.',
  HanSuDung = '24 tháng',
  GiaBan = 429000, Sale = NULL
WHERE MaSanPham = 15;

-- 16. Son tint Romand
UPDATE SanPham SET
  TenSanPham = 'Son tint bóng nước Romand Juicy Lasting Tint',
  ThuongHieu = 'Romand',
  XuatXu = 'Hàn Quốc',
  DungTich = '5.5g',
  LoaiDa = 'Mọi loại môi',
  MauSac = 'Đỏ cam',
  KichCo = '5.5g',
  MoTa = 'Son tint Romand Juicy với độ bóng căng mọng tự nhiên, lên màu chuẩn ngay lần đầu. Cảm giác mỏng nhẹ như không son, cấp ẩm sâu nhờ chiết xuất nho và quả mọng. Bám lâu cả khi ăn uống.',
  ThanhPhan = 'Aqua, Castor Oil, Diisostearyl Malate, Mica, Polybutene, Grape Seed Oil, Vitamin E, Berry Extracts.',
  HuongDanSuDung = '1. Dùng đầu cọ tô viền môi sắc nét\n2. Tô đầy phần giữa\n3. Có thể tô nhiều lớp để tăng độ đậm\n4. Hiệu ứng bóng tự nhiên, không cần thêm gloss',
  BaoQuan = 'Đậy kín nắp. Tránh nơi nóng ẩm.',
  HanSuDung = '24 tháng',
  GiaBan = 189000, Sale = '20'
WHERE MaSanPham = 16;

-- ---------------------------------------------------------------------
-- 8. Update KhoHang to match new product names
-- ---------------------------------------------------------------------
UPDATE KhoHang kh
JOIN SanPham sp ON kh.MaSanPham = sp.MaSanPham
SET kh.TenSanPham = sp.TenSanPham,
    kh.LoaiSanPham = 'Hàng chính hãng';

-- ---------------------------------------------------------------------
-- 9. Sample reviews (3-5 per product)
-- ---------------------------------------------------------------------
INSERT INTO DanhGia (MaSanPham, MaKhachHang, TenNguoiDanhGia, SoSao, TieuDe, NoiDung, HuuIch, DaXacThucMua) VALUES
-- Product 1: Son lì Ruby Kiss
(1, 1, 'Trần Thị Bích Ngọc', 5, 'Màu son đẹp, lên chuẩn',
 'Mình rất ưng son này. Màu đỏ ruby quyến rũ, lên màu chuẩn ngay lần đầu. Lì 8 tiếng đúng như quảng cáo, không bị khô môi. Sẽ mua lại!', 24, 1),
(1, 2, 'Nguyễn Thị Hồng Ngân', 4, 'Đẹp nhưng hơi đậm',
 'Son đẹp, chất lượng OK. Tuy nhiên màu đỏ này hơi đậm với mình, có thể tô mỏng lại. Bao bì sang trọng.', 12, 1),
(1, 3, 'Phạm Mai Anh', 5, 'Yêu son này quá!',
 'Đây là cây son thứ 3 mình mua của Ruby Kiss. Lì cực, ăn uống không trôi nhiều. Giá cũng hợp lý.', 18, 1),
(1, NULL, 'Khách vô danh', 5, 'Đáng tiền',
 'Son đẹp, lên màu chuẩn. Giao hàng nhanh, đóng gói cẩn thận. Recommend!', 5, 0),

-- Product 2: Son dưỡng Moist Touch
(2, 1, 'Trần Thị Bích Ngọc', 5, 'Cứu tinh cho môi khô',
 'Mùa đông môi mình khô nẻ rất khó chịu. Dùng Moist Touch 1 tuần là môi mềm hẳn, hết bong tróc. Hương cherry rất dễ chịu!', 32, 1),
(2, 4, 'Lê Hồng Vy', 4, 'Dưỡng tốt nhưng hết nhanh',
 'Son dưỡng cấp ẩm tốt, môi mềm sau 2-3 ngày dùng. Chỉ tiếc là hộp 4g hết khá nhanh nếu dùng nhiều lần trong ngày.', 8, 1),
(2, 5, 'Đỗ Quỳnh Anh', 5, 'Dùng làm lớp lót son cũng tốt',
 'Mình dùng son dưỡng này trước khi đánh son lì, môi không bị khô và son lên màu đẹp hơn. Highly recommend!', 15, 1),

-- Product 3: Son kem Velvet Matte
(3, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Mịn như nhung thật sự',
 'Tên đúng nghĩa! Son lên môi mịn mượt, không bị bệt. Cam đất rất hợp da vàng Việt Nam. Lì cả ngày!', 27, 1),
(3, 6, 'Vũ Thanh Tâm', 4, 'Mua tặng vợ, vợ thích lắm',
 'Mua tặng vợ dịp sinh nhật. Vợ rất thích chất son và màu. Sẽ mua thêm tone khác.', 9, 1),
(3, 3, 'Phạm Mai Anh', 5, 'Lì lâu, không khô môi',
 'Bất ngờ là son lì mà không bị khô. Bám tới 8-10 tiếng mới phai nhẹ. Đáng mua!', 21, 1),

-- Product 4: Phấn phủ Oil Control
(4, 4, 'Lê Hồng Vy', 5, 'Da dầu mê tơi',
 'Da mình dầu khủng khiếp, dùng Oil Control thì lớp nền bền cả ngày. Phấn mịn không cakey. Sản phẩm chân ái cho da dầu!', 41, 1),
(4, 7, 'Bùi Khánh Linh', 4, 'Tốt nhưng hơi đắt',
 'Phấn kiềm dầu tốt, lớp nền bền. Tuy nhiên giá hơi cao so với dung tích 12g.', 6, 1),

-- Product 6: Bioderma Hydrabio
(6, 1, 'Trần Thị Bích Ngọc', 5, 'Cấp ẩm cực tốt',
 'Da mình khô và thiếu ẩm. Dùng Hydrabio 2 tuần thấy da mịn và căng hơn. Texture nhẹ, thấm nhanh, không nhờn rít.', 38, 1),
(6, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Yêu Bioderma',
 'Cả nhà mình dùng Bioderma. Hydrabio này cấp ẩm 24 giờ thật, mùa hè da không khô nữa.', 22, 1),
(6, 5, 'Đỗ Quỳnh Anh', 4, 'Tốt cho da nhạy cảm',
 'Da mình nhạy cảm hay kích ứng. Hydrabio không gây mẩn đỏ, da dễ chịu hơn. Recommend cho ai có da nhạy cảm.', 14, 1),

-- Product 7: Anessa
(7, 4, 'Lê Hồng Vy', 5, 'Kem chống nắng quốc dân',
 'Anessa là chân ái! Kiềm dầu tốt, không trắng bệch, không trôi khi đi bơi. Mùa hè không thể thiếu.', 56, 1),
(7, 3, 'Phạm Mai Anh', 5, 'Đáng đầu tư',
 'Hơi đắt nhưng xứng đáng. Bảo vệ da tuyệt vời, mua 1 chai dùng được lâu nếu chỉ thoa mặt.', 18, 1),
(7, 6, 'Vũ Thanh Tâm', 4, 'Mua dùm vợ, vợ khen',
 'Vợ dùng đi biển khen không bị cháy nắng. Sẽ mua lại.', 7, 1),

-- Product 9: Cetaphil
(9, 1, 'Trần Thị Bích Ngọc', 5, 'Sữa rửa mặt nhẹ nhàng',
 'Da mình mỏng và nhạy cảm, đã thử nhiều SRM mà toàn bị khô căng. Cetaphil thì dịu nhẹ, không xà phòng, không khô. Yêu!', 29, 1),
(9, 2, 'Nguyễn Thị Hồng Ngân', 4, 'OK cho da thường',
 'Sạch da nhưng không tạo bọt nên cảm giác chưa làm sạch sâu. Phù hợp da khô/nhạy cảm hơn da dầu.', 11, 1),

-- Product 10: Bioderma Sensibio
(10, 4, 'Lê Hồng Vy', 5, 'Best of best',
 'Đã dùng 5 năm rồi, không thay đổi. Tẩy trang sạch makeup đậm mà không cần rửa lại nước. Da không bị căng.', 67, 1),
(10, 7, 'Bùi Khánh Linh', 5, 'Đáng đồng tiền',
 'Chai 500ml dùng được rất lâu. Tẩy trang nhẹ nhàng, không cay mắt khi tẩy mascara.', 24, 1),

-- Product 13: Chanel Coco
(13, 5, 'Đỗ Quỳnh Anh', 5, 'Sang trọng và quyến rũ',
 'Mùi nước hoa nữ tính, sang trọng. Lưu hương rất lâu, cả ngày vẫn còn thoang thoảng. Worth it!', 19, 1),
(13, 1, 'Trần Thị Bích Ngọc', 5, 'Quà sinh nhật yêu',
 'Chồng tặng dịp sinh nhật. Mùi vô cùng hợp với mình. Cảm ơn Hasaki giao nhanh, đóng gói đẹp.', 11, 1),

-- Product 16: Romand
(16, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Bóng nước đẹp xuất sắc',
 'Romand không bao giờ làm thất vọng. Tint bóng môi, cấp ẩm mà bám lâu. Màu cam đỏ rất trendy.', 34, 1),
(16, 7, 'Bùi Khánh Linh', 5, 'Mê hoặc',
 'Đã mua 4 màu rồi. Lần này thử đỏ cam, hợp luôn. Sẽ mua tiếp các màu khác.', 23, 1),
(16, 3, 'Phạm Mai Anh', 4, 'Đẹp nhưng cần dặm lại',
 'Son đẹp, nhưng vì là tint bóng nên ăn uống có hơi phai. Cần dặm lại sau bữa ăn.', 7, 1);
