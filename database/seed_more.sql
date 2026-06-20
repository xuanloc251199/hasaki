-- =====================================================================
-- HASAKI - Seeder BỔ SUNG: đa dạng sản phẩm & đơn hàng
-- ---------------------------------------------------------------------
-- Mục đích : Thêm nhiều sản phẩm (nhiều thương hiệu / mức giá / loại da)
--            và sinh thêm đơn hàng đa dạng để dashboard / thống kê /
--            danh sách sản phẩm trông phong phú hơn.
--
-- Đặc điểm : ADDITIVE (chỉ THÊM, KHÔNG xoá dữ liệu cũ). Dùng
--            AUTO_INCREMENT nên an toàn với mọi MaSanPham/MaHoaDon
--            hiện có — không cần biết ID lớn nhất.
--
-- Lưu ý    : Chạy SAU khi đã import hasaki_db.sql (hoặc schema.sql).
--            Nếu chạy file này NHIỀU LẦN, dữ liệu sẽ bị nhân đôi —
--            chỉ chạy lại khi muốn thêm tiếp.
--
-- Cách chạy (Windows / Laragon):
--   mysql -uroot --default-character-set=utf8mb4 hasaki_db < database/seed_more.sql
--   (hoặc import qua phpMyAdmin / HeidiSQL)
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

-- =====================================================================
-- 1. SẢN PHẨM MỚI (đa dạng thương hiệu, giá, loại da, danh mục)
--    Ảnh tái sử dụng ảnh có sẵn theo từng danh mục để hiển thị đẹp;
--    nếu thiếu ảnh, product-card có sẵn nền gradient dự phòng.
-- =====================================================================
INSERT INTO sanpham
  (MaDanhMuc, TenSanPham, ThuongHieu, XuatXu, DungTich, LoaiDa, SoLuong, GiaBan, MauSac, KichCo, MoTa, HinhAnh, Sale, ThongBao, NgayTao)
VALUES
-- ----- Danh mục 1: Son môi -----
(1, 'Son tint 3CE Velvet Lip Tint', '3CE', 'Hàn Quốc', '4g', 'Mọi loại môi', 90, 230000, 'Đỏ hồng', '4g',
 'Son tint lì 3CE chất lì mịn như nhung, lên màu chuẩn trend, bám màu lâu suốt cả ngày mà không gây khô môi.',
 'images/products/son-tint-romand.jpg', '15', '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 40 DAY)),
(1, 'Son kem MAC Powder Kiss Liquid', 'MAC', 'Mỹ', '5ml', 'Mọi loại môi', 60, 650000, 'Đỏ gạch', '5ml',
 'Son kem MAC Powder Kiss chất son mờ lì mịn nhẹ tênh, màu sang chảnh, che phủ tốt và lưu màu bền bỉ.',
 'images/products/son-kem-velvet.jpg', NULL, '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 22 DAY)),
(1, 'Son dưỡng có màu Dior Addict Lip Glow', 'Dior', 'Pháp', '3.2g', 'Môi khô', 45, 890000, 'Hồng cánh sen', '3.2g',
 'Son dưỡng Dior Lip Glow phản ứng với độ ẩm môi cho màu hồng tự nhiên riêng từng người, dưỡng ẩm và làm hồng môi.',
 'images/products/son-duong-moist-touch.jpg', '5', '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(1, 'Son lì Black Rouge Air Fit Velvet', 'Black Rouge', 'Hàn Quốc', '4.5g', 'Mọi loại môi', 140, 165000, 'Đỏ đất', '4.5g',
 'Son lì Black Rouge tone đất hot trend, chất son mỏng nhẹ, lì lâu trôi và giá cực kỳ phải chăng.',
 'images/products/son-li-ruby-kiss.jpg', '25', '💝 Ưu đãi đặc biệt', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 'Son kem Bbia Last Velvet Lip Tint', 'Bbia', 'Hàn Quốc', '5g', 'Mọi loại môi', 150, 175000, 'Hồng nâu', '5g',
 'Son kem Bbia Last Velvet bảng màu rộng, chất lì mịn, lên màu chuẩn và bám màu lâu, được giới trẻ yêu thích.',
 'images/products/son-tint-romand.jpg', '10', NULL, DATE_SUB(NOW(), INTERVAL 28 DAY)),
(1, 'Son thỏi YSL Rouge Pur Couture', 'YSL', 'Pháp', '3.8g', 'Mọi loại môi', 30, 1150000, 'Đỏ thuần', '3.8g',
 'Son thỏi YSL biểu tượng sang trọng, chất son mịn mượt, lên màu đậm đầy đặn và dưỡng ẩm cho môi.',
 'images/products/son-kem-velvet.jpg', NULL, '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 18 DAY)),

-- ----- Danh mục 2: Dưỡng da -----
(2, 'Serum Vitamin C Klairs Freshly Juiced', 'Klairs', 'Hàn Quốc', '35ml', 'Da nhạy cảm', 80, 320000, NULL, '35ml',
 'Serum Vitamin C Klairs nồng độ dịu nhẹ giúp làm sáng da, mờ thâm và đều màu da, phù hợp cả da nhạy cảm.',
 'images/products/sua-duong-bioderma.jpg', '10', '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 35 DAY)),
(2, 'Serum The Ordinary Niacinamide 10% + Zinc 1%', 'The Ordinary', 'Canada', '30ml', 'Da dầu, da mụn', 120, 280000, NULL, '30ml',
 'Serum The Ordinary Niacinamide kiểm soát dầu, se khít lỗ chân lông và giảm mụn hiệu quả với giá hợp lý.',
 'images/products/sua-duong-bioderma.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 31 DAY)),
(2, 'Kem chống nắng La Roche-Posay Anthelios UVMune 400', 'La Roche-Posay', 'Pháp', '50ml', 'Da nhạy cảm', 65, 495000, NULL, '50ml',
 'Kem chống nắng La Roche-Posay SPF50+ bảo vệ phổ rộng, kết cấu mỏng nhẹ, không bết dính, phù hợp da nhạy cảm.',
 'images/products/kem-chong-nang-anessa.jpg', '8', NULL, DATE_SUB(NOW(), INTERVAL 9 DAY)),
(2, 'Kem dưỡng ẩm CeraVe Moisturizing Cream', 'CeraVe', 'Mỹ', '340g', 'Da khô', 100, 385000, NULL, '340g',
 'Kem dưỡng CeraVe chứa 3 loại Ceramide và Hyaluronic Acid, cấp ẩm sâu và phục hồi hàng rào bảo vệ da khô.',
 'images/products/kem-duong-laroche.jpg', NULL, '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 26 DAY)),
(2, 'Mặt nạ ngủ Laneige Water Sleeping Mask', 'Laneige', 'Hàn Quốc', '70ml', 'Mọi loại da', 70, 450000, NULL, '70ml',
 'Mặt nạ ngủ Laneige cấp ẩm qua đêm, sáng dậy da căng mọng mịn màng, hương thơm thư giãn dễ chịu.',
 'images/products/sua-duong-bioderma.jpg', '12', NULL, DATE_SUB(NOW(), INTERVAL 14 DAY)),
(2, 'Serum The Ordinary Hyaluronic Acid 2% + B5', 'The Ordinary', 'Canada', '30ml', 'Da khô, thiếu ẩm', 110, 250000, NULL, '30ml',
 'Serum cấp ẩm The Ordinary với Hyaluronic Acid đa phân tử, giúp da căng mọng và giữ nước suốt ngày dài.',
 'images/products/sua-duong-bioderma.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 20 DAY)),
(2, 'Kem dưỡng mắt The Face Shop Rice Water Bright', 'The Face Shop', 'Hàn Quốc', '25ml', 'Mọi loại da', 75, 290000, NULL, '25ml',
 'Kem dưỡng mắt chiết xuất nước gạo giúp giảm quầng thâm, bọng mắt và làm sáng vùng da quanh mắt.',
 'images/products/kem-duong-laroche.jpg', '15', NULL, DATE_SUB(NOW(), INTERVAL 16 DAY)),
(2, 'Kem chống nắng Skin Aqua Tone Up UV Essence', 'Skin Aqua', 'Nhật Bản', '80g', 'Da thường, da dầu', 130, 195000, 'Tím lavender', '80g',
 'Kem chống nắng nâng tông Skin Aqua SPF50+ vừa chống nắng vừa hiệu chỉnh sắc da, cho làn da sáng hồng tự nhiên.',
 'images/products/kem-chong-nang-anessa.jpg', '10', '💝 Ưu đãi đặc biệt', DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- ----- Danh mục 3: Trang điểm -----
(3, 'Kem nền Maybelline Fit Me Matte + Poreless', 'Maybelline', 'Mỹ', '30ml', 'Da dầu', 95, 199000, 'Tông 220', '30ml',
 'Kem nền Maybelline Fit Me kiềm dầu, che phủ tự nhiên và thu nhỏ lỗ chân lông, lý tưởng cho da dầu.',
 'images/products/phan-phu-oil-control.jpg', '20', '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 33 DAY)),
(3, 'Cushion Black Rouge Real Cover Lasting', 'Black Rouge', 'Hàn Quốc', '15g', 'Da thường, da khô', 70, 295000, 'Tông sáng', '15g',
 'Cushion Black Rouge che phủ tốt, lớp nền căng mịn lâu trôi, kèm SPF50+ bảo vệ da khi trang điểm cả ngày.',
 'images/products/phan-phu-natural-veil.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 24 DAY)),
(3, 'Phấn má hồng 3CE Mood Recipe Face Blush', '3CE', 'Hàn Quốc', '5.5g', 'Mọi loại da', 65, 320000, 'Hồng san hô', '5.5g',
 'Phấn má 3CE lên màu mịn, tệp da tự nhiên, tạo khối má hồng hào hứng rạng rỡ cho gương mặt.',
 'images/products/phan-ma-bobbi-brown.jpg', '10', NULL, DATE_SUB(NOW(), INTERVAL 21 DAY)),
(3, 'Bảng phấn mắt Etude House Play Color Eyes', 'Etude House', 'Hàn Quốc', '10g', 'Mọi loại da', 55, 350000, 'Nâu cam', '10g',
 'Bảng phấn mắt Etude House 10 ô màu phối hài hòa, độ bám tốt, dễ tán, tạo nhiều layout makeup khác nhau.',
 'images/products/phan-ma-bobbi-brown.jpg', NULL, '✨ Mới ra mắt', DATE_SUB(NOW(), INTERVAL 6 DAY)),
(3, 'Mascara Maybelline Hyper Curl Volum Express', 'Maybelline', 'Mỹ', '9.2ml', 'Mọi loại da', 120, 159000, 'Đen', '9.2ml',
 'Mascara Maybelline làm cong và dày mi tức thì, không lem, giữ nếp cong suốt ngày dài.',
 'images/products/phan-phu-oil-control.jpg', '15', NULL, DATE_SUB(NOW(), INTERVAL 19 DAY)),
(3, 'Chì kẻ mày Etude House Drawing Eye Brow', 'Etude House', 'Hàn Quốc', '0.25g', 'Mọi loại da', 160, 65000, 'Nâu xám', '0.25g',
 'Chì kẻ mày Etude House đầu chì mảnh dễ tạo dáng, kèm chuốt mày, lên màu tự nhiên, giá học sinh sinh viên.',
 'images/products/phan-ma-bobbi-brown.jpg', NULL, '💝 Ưu đãi đặc biệt', DATE_SUB(NOW(), INTERVAL 27 DAY)),
(3, 'Kem nền L''Oréal Infallible 24H Fresh Wear', 'L''Oréal', 'Pháp', '30ml', 'Mọi loại da', 80, 345000, 'Tông 130', '30ml',
 'Kem nền L''Oréal Infallible lâu trôi đến 24 giờ, lớp nền nhẹ thoáng, che phủ cao và bền màu cả ngày.',
 'images/products/phan-phu-natural-veil.jpg', '18', NULL, DATE_SUB(NOW(), INTERVAL 11 DAY)),

-- ----- Danh mục 4: Làm sạch da -----
(4, 'Sữa rửa mặt CeraVe Foaming Cleanser', 'CeraVe', 'Mỹ', '236ml', 'Da dầu, hỗn hợp', 110, 295000, NULL, '236ml',
 'Sữa rửa mặt tạo bọt CeraVe làm sạch sâu, kiểm soát dầu mà vẫn giữ ẩm nhờ Ceramide và Niacinamide.',
 'images/products/sua-rua-mat-cetaphil.jpg', '10', '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 30 DAY)),
(4, 'Nước tẩy trang L''Oréal Micellar Water 3-in-1', 'L''Oréal', 'Pháp', '400ml', 'Mọi loại da', 100, 215000, NULL, '400ml',
 'Nước tẩy trang L''Oréal làm sạch lớp trang điểm và bụi bẩn nhẹ nhàng, không cần rửa lại, không gây khô da.',
 'images/products/tay-trang-bioderma.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 23 DAY)),
(4, 'Sữa rửa mặt Simple Kind to Skin', 'Simple', 'Anh', '150ml', 'Da nhạy cảm', 90, 135000, NULL, '150ml',
 'Sữa rửa mặt Simple công thức dịu nhẹ, không hương liệu, không cồn, phù hợp làn da nhạy cảm và dễ kích ứng.',
 'images/products/sua-rua-mat-cetaphil.jpg', '12', NULL, DATE_SUB(NOW(), INTERVAL 17 DAY)),
(4, 'Toner Some By Mi AHA BHA PHA 30 Days Miracle', 'Some By Mi', 'Hàn Quốc', '150ml', 'Da mụn', 85, 320000, NULL, '150ml',
 'Toner Some By Mi với AHA-BHA-PHA giúp tẩy tế bào chết hóa học, làm sạch lỗ chân lông và cải thiện da mụn.',
 'images/products/tay-trang-bioderma.jpg', NULL, '✨ Mới ra mắt', DATE_SUB(NOW(), INTERVAL 8 DAY)),
(4, 'Dầu tẩy trang Senka Perfect Oil', 'Senka', 'Nhật Bản', '230ml', 'Mọi loại da', 95, 185000, NULL, '230ml',
 'Dầu tẩy trang Senka làm sạch lớp trang điểm lì và kem chống nắng, nhũ hóa nhanh, rửa sạch không nhờn rít.',
 'images/products/tay-trang-bioderma.jpg', '15', NULL, DATE_SUB(NOW(), INTERVAL 25 DAY)),
(4, 'Tẩy tế bào chết Cosrx AHA 7 Whitehead Power', 'Cosrx', 'Hàn Quốc', '100ml', 'Da xỉn màu', 75, 290000, NULL, '100ml',
 'Sản phẩm Cosrx AHA 7 giúp loại bỏ tế bào chết, làm sáng và mịn da, hỗ trợ giảm mụn đầu trắng hiệu quả.',
 'images/products/sua-rua-mat-cetaphil.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 13 DAY)),

-- ----- Danh mục 5: Chăm sóc cơ thể -----
(5, 'Sữa tắm Hada Labo Perfect Body Wash', 'Hada Labo', 'Nhật Bản', '500ml', 'Mọi loại da', 120, 175000, NULL, '500ml',
 'Sữa tắm Hada Labo chứa Hyaluronic Acid dưỡng ẩm, làm sạch dịu nhẹ và lưu hương thanh khiết trên da.',
 'images/products/sua-tam-dove.jpg', '10', NULL, DATE_SUB(NOW(), INTERVAL 29 DAY)),
(5, 'Lotion dưỡng thể Nivea Body Serum Extra White', 'Nivea', 'Đức', '380ml', 'Da khô', 130, 145000, NULL, '380ml',
 'Lotion dưỡng thể Nivea dưỡng trắng và cấp ẩm, thẩm thấu nhanh, cho làn da mềm mịn sáng khỏe.',
 'images/products/lotion-vaseline.jpg', NULL, '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 22 DAY)),
(5, 'Kem dưỡng tay L''Occitane Shea Butter', 'L''Occitane', 'Pháp', '75ml', 'Da khô', 60, 320000, NULL, '75ml',
 'Kem dưỡng tay L''Occitane chứa 20% bơ hạt mỡ, dưỡng ẩm chuyên sâu cho đôi tay khô ráp mềm mại tức thì.',
 'images/products/lotion-vaseline.jpg', '8', '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(5, 'Sữa tắm The Body Shop Tea Tree', 'The Body Shop', 'Anh', '250ml', 'Da mụn lưng', 70, 290000, NULL, '250ml',
 'Sữa tắm The Body Shop chiết xuất tràm trà giúp làm sạch, kiểm soát nhờn và hỗ trợ giảm mụn vùng lưng, ngực.',
 'images/products/sua-tam-dove.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 18 DAY)),
(5, 'Tẩy tế bào chết Cocoon Cà Phê Đắk Lắk', 'Cocoon', 'Việt Nam', '200ml', 'Mọi loại da', 140, 165000, NULL, '200ml',
 'Tẩy tế bào chết body Cocoon từ cà phê Đắk Lắk, lấy đi tế bào chết, làm sáng và mềm mịn da, thuần chay.',
 'images/products/sua-tam-dove.jpg', '15', '✨ Mới ra mắt', DATE_SUB(NOW(), INTERVAL 4 DAY)),
(5, 'Xịt khoáng Avène Thermal Spring Water', 'Avène', 'Pháp', '300ml', 'Da nhạy cảm', 85, 350000, NULL, '300ml',
 'Xịt khoáng Avène làm dịu, cấp ẩm tức thì và giảm kích ứng, phù hợp da nhạy cảm và sau khi đi nắng.',
 'images/products/lotion-vaseline.jpg', NULL, NULL, DATE_SUB(NOW(), INTERVAL 10 DAY)),

-- ----- Danh mục 6: Nước hoa -----
(6, 'Nước hoa Versace Bright Crystal EDT', 'Versace', 'Ý', '90ml', NULL, 35, 1850000, NULL, '90ml',
 'Nước hoa nữ Versace Bright Crystal hương hoa trái cây tươi mát, ngọt ngào quyến rũ, phù hợp ban ngày.',
 'images/products/nuoc-hoa-chanel.jpg', '10', NULL, DATE_SUB(NOW(), INTERVAL 20 DAY)),
(6, 'Nước hoa Narciso Rodriguez For Her EDP', 'Narciso Rodriguez', 'Pháp', '90ml', NULL, 25, 2350000, NULL, '90ml',
 'Nước hoa nữ Narciso For Her hương xạ hương ấm áp gợi cảm, sang trọng và lưu hương cực kỳ bền bỉ.',
 'images/products/nuoc-hoa-chanel.jpg', NULL, '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 17 DAY)),
(6, 'Nước hoa Bleu de Chanel EDP', 'Chanel', 'Pháp', '100ml', NULL, 28, 3450000, NULL, '100ml',
 'Nước hoa nam Bleu de Chanel hương gỗ thơm nam tính, lịch lãm và đầy cuốn hút cho quý ông hiện đại.',
 'images/products/nuoc-hoa-dior.jpg', '5', '👑 Cao cấp', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(6, 'Nước hoa Calvin Klein CK One EDT', 'Calvin Klein', 'Mỹ', '100ml', NULL, 40, 1250000, NULL, '100ml',
 'Nước hoa unisex CK One hương cam chanh tươi mát, trẻ trung năng động, dùng được cho cả nam và nữ.',
 'images/products/nuoc-hoa-dior.jpg', NULL, '🔥 Bán chạy', DATE_SUB(NOW(), INTERVAL 26 DAY)),
(6, 'Nước hoa Lancôme La Vie Est Belle EDP', 'Lancôme', 'Pháp', '75ml', NULL, 22, 2750000, NULL, '75ml',
 'Nước hoa nữ Lancôme La Vie Est Belle hương ngọt ngào của hoa diên vĩ và vani, biểu tượng của hạnh phúc.',
 'images/products/nuoc-hoa-chanel.jpg', '8', NULL, DATE_SUB(NOW(), INTERVAL 14 DAY));

-- =====================================================================
-- 2. ĐỒNG BỘ KHO HÀNG cho các sản phẩm chưa có trong kho
-- =====================================================================
INSERT INTO khohang (MaSanPham, TenSanPham, SoLuongCon, NgayNhap, LoaiSanPham)
SELECT sp.MaSanPham, sp.TenSanPham, sp.SoLuong, CURDATE(), 'Hàng chính hãng'
FROM sanpham sp
WHERE sp.MaSanPham NOT IN (SELECT kh.MaSanPham FROM khohang kh);

-- =====================================================================
-- 3. KHÁCH HÀNG MỚI (đa dạng để đơn hàng có nhiều người mua)
--    MaTaiKhoan NULL = khách vãng lai (giống dữ liệu mẫu sẵn có).
-- =====================================================================
INSERT INTO khachhang (TenKhachHang, DiaChi, SDT, Email, GioiTinh, MaTaiKhoan, NgayDangKy) VALUES
('Nguyễn Hoàng Yến', '34 Nguyễn Trãi, Quận 5, TP.HCM', '0912000111', 'yennh@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 95 DAY)),
('Trần Quốc Bảo', '210 Cầu Giấy, Hà Nội', '0912000222', 'baotq@gmail.com', 'Nam', NULL, DATE_SUB(NOW(), INTERVAL 88 DAY)),
('Lê Phương Thảo', '56 Hùng Vương, TP. Đà Nẵng', '0912000333', 'thaolp@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 70 DAY)),
('Phạm Anh Tuấn', '12 Lê Duẩn, TP. Biên Hòa, Đồng Nai', '0912000444', 'tuanpa@gmail.com', 'Nam', NULL, DATE_SUB(NOW(), INTERVAL 64 DAY)),
('Võ Thị Mỹ Duyên', '89 Trần Hưng Đạo, TP. Cần Thơ', '0912000555', 'duyenvtm@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 52 DAY)),
('Đặng Minh Khôi', '7 Bà Triệu, TP. Huế', '0912000666', 'khoidm@gmail.com', 'Nam', NULL, DATE_SUB(NOW(), INTERVAL 45 DAY)),
('Hoàng Thị Lan Anh', '145 Nguyễn Văn Cừ, Long Biên, Hà Nội', '0912000777', 'lananhht@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 33 DAY)),
('Ngô Gia Hân', '23 Hai Bà Trưng, TP. Vũng Tàu', '0912000888', 'hanng@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 21 DAY)),
('Bùi Tấn Phát', '301 Lý Thường Kiệt, Quận 10, TP.HCM', '0912000999', 'phatbt@gmail.com', 'Nam', NULL, DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Dương Thảo Vy', '67 Phạm Ngũ Lão, Quận 1, TP.HCM', '0912001000', 'vydt@gmail.com', 'Nữ', NULL, DATE_SUB(NOW(), INTERVAL 6 DAY));

-- =====================================================================
-- 4. SINH ĐƠN HÀNG ĐA DẠNG (stored procedure)
--    - Trải đều ~150 ngày gần đây, dồn về thời gian gần.
--    - Trạng thái & hình thức thanh toán phân bố đa dạng.
--    - Mỗi đơn 1-5 sản phẩm (lấy ngẫu nhiên, gồm cả sản phẩm mới).
--    - ADDITIVE: KHÔNG xoá đơn cũ.
-- =====================================================================
DROP PROCEDURE IF EXISTS seed_more_orders;

DELIMITER //
CREATE PROCEDURE seed_more_orders(IN total_orders INT)
BEGIN
  DECLARE i INT DEFAULT 0;
  DECLARE order_id INT;
  DECLARE order_date DATETIME;
  DECLARE order_status INT;
  DECLARE payment_type VARCHAR(20);
  DECLARE customer_id INT;
  DECLARE order_total DECIMAL(12,2);
  DECLARE day_offset INT;
  DECLARE n_items INT;
  DECLARE j INT;
  DECLARE pid INT;
  DECLARE pqty INT;
  DECLARE pprice DECIMAL(12,2);
  DECLARE pname VARCHAR(255);
  DECLARE pcolor VARCHAR(100);
  DECLARE psize VARCHAR(100);
  DECLARE note VARCHAR(255);
  DECLARE cust_sdt VARCHAR(15);
  DECLARE cust_email VARCHAR(100);
  DECLARE cust_addr VARCHAR(255);

  WHILE i < total_orders DO
    -- Phân bố theo ~150 ngày gần đây, dồn về hiện tại
    SET day_offset = FLOOR(POW(RAND(), 2) * 150);
    SET order_date = DATE_SUB(NOW(), INTERVAL day_offset DAY) + INTERVAL FLOOR(RAND() * 86400) SECOND;

    -- Trạng thái: ~58% hoàn thành, ~16% đang giao, ~16% chờ xác nhận, ~10% đã huỷ
    SET order_status = CASE
      WHEN RAND() < 0.58 THEN 2
      WHEN RAND() < 0.40 THEN 1
      WHEN RAND() < 0.55 THEN 0
      ELSE 3
    END;

    -- Hình thức thanh toán: COD / VNPAY / MOMO / BANK
    SET payment_type = CASE
      WHEN RAND() < 0.50 THEN 'COD'
      WHEN RAND() < 0.50 THEN 'VNPAY'
      WHEN RAND() < 0.50 THEN 'MOMO'
      ELSE 'BANK'
    END;

    -- Ghi chú ngẫu nhiên cho một số đơn
    SET note = CASE FLOOR(RAND() * 6)
      WHEN 0 THEN 'Giao giờ hành chính'
      WHEN 1 THEN 'Gọi trước khi giao'
      WHEN 2 THEN 'Gói quà giúp shop nhé'
      ELSE NULL
    END;

    -- Khách ngẫu nhiên (lấy trực tiếp 1 MaKhachHang CÓ THẬT để tránh
    -- lỗi khi bảng khachhang có khoảng trống ID do đã xoá khách)
    SELECT MaKhachHang, IFNULL(SDT,''), IFNULL(Email,''), IFNULL(DiaChi,'')
      INTO customer_id, cust_sdt, cust_email, cust_addr
      FROM khachhang ORDER BY RAND() LIMIT 1;

    -- Tạo đơn rỗng (cập nhật tổng tiền sau khi thêm chi tiết)
    INSERT INTO hoadonban (NgayBan, ThanhTien, MaKhachHang, SDT, Email, DiaChiGH, GhiChu, HinhThucTT, TrangThai)
    VALUES (order_date, 0, customer_id, NULLIF(cust_sdt,''), NULLIF(cust_email,''), NULLIF(cust_addr,''), note, payment_type, order_status);
    SET order_id = LAST_INSERT_ID();

    -- 1-5 sản phẩm mỗi đơn
    SET n_items = 1 + FLOOR(RAND() * 5);
    SET order_total = 0;
    SET j = 0;
    WHILE j < n_items DO
      SELECT MaSanPham, TenSanPham, GiaBan, IFNULL(MauSac,''), IFNULL(KichCo,'')
        INTO pid, pname, pprice, pcolor, psize
        FROM sanpham ORDER BY RAND() LIMIT 1;
      SET pqty = 1 + FLOOR(RAND() * 3);

      INSERT INTO chitiethoadonban (MaHoaDon, MaSanPham, TenSanPham, SoLuong, GiaBan, KichCo, MauSac)
      VALUES (order_id, pid, pname, pqty, pprice, NULLIF(psize,''), NULLIF(pcolor,''));

      SET order_total = order_total + (pprice * pqty);
      SET j = j + 1;
    END WHILE;

    UPDATE hoadonban SET ThanhTien = order_total WHERE MaHoaDon = order_id;
    SET i = i + 1;
  END WHILE;
END//
DELIMITER ;

-- Sinh thêm 130 đơn hàng đa dạng
CALL seed_more_orders(130);
DROP PROCEDURE seed_more_orders;

-- =====================================================================
-- 5. KIỂM TRA NHANH KẾT QUẢ
-- =====================================================================
SELECT (SELECT COUNT(*) FROM sanpham)   AS tong_san_pham,
       (SELECT COUNT(*) FROM khachhang) AS tong_khach_hang,
       (SELECT COUNT(*) FROM hoadonban) AS tong_don_hang;
SELECT TrangThai, COUNT(*) AS so_don FROM hoadonban GROUP BY TrangThai ORDER BY TrangThai;
SELECT HinhThucTT, COUNT(*) AS so_don FROM hoadonban GROUP BY HinhThucTT ORDER BY so_don DESC;
SELECT SUM(ThanhTien) AS doanh_thu_hoan_thanh FROM hoadonban WHERE TrangThai = 2;
