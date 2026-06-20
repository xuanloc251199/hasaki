-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th6 19, 2026 lúc 02:32 PM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hasaki_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhinh`
--

CREATE TABLE `cauhinh` (
  `Khoa` varchar(100) NOT NULL,
  `GiaTri` text,
  `Loai` varchar(20) DEFAULT 'text',
  `Nhom` varchar(50) DEFAULT NULL,
  `TenHienThi` varchar(255) DEFAULT NULL,
  `GhiChu` varchar(500) DEFAULT NULL,
  `ThuTu` int DEFAULT '0',
  `CapNhatLuc` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhinh`
--

INSERT INTO `cauhinh` (`Khoa`, `GiaTri`, `Loai`, `Nhom`, `TenHienThi`, `GhiChu`, `ThuTu`, `CapNhatLuc`) VALUES
('contact_address', '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh', 'text', 'contact', 'Địa chỉ', 'Địa chỉ trụ sở chính', 1, '2026-05-10 14:55:07'),
('contact_email', 'support@hasaki.vn', 'email', 'contact', 'Email', 'Email liên hệ chính', 3, '2026-05-10 14:55:07'),
('contact_hours', '8:00 - 22:00 hàng ngày', 'text', 'contact', 'Giờ làm việc', 'Giờ mở cửa hiển thị ở footer', 4, '2026-05-10 14:55:07'),
('contact_map', '', 'textarea', 'contact', 'Google Maps embed', 'Mã iframe Google Maps (tuỳ chọn)', 5, '2026-05-10 14:55:07'),
('contact_phone', '1900 1234', 'tel', 'contact', 'Hotline', 'Số điện thoại tư vấn / hỗ trợ', 2, '2026-05-10 14:55:07'),
('footer_about', 'Hệ thống mỹ phẩm chính hãng - chăm sóc sắc đẹp toàn diện cho phái nữ Việt Nam. Trải nghiệm mua sắm đẳng cấp với hơn 1.000+ sản phẩm chất lượng.', 'textarea', 'footer', 'Giới thiệu ở footer', 'Đoạn mô tả ngắn ở khối brand trong footer', 1, '2026-05-10 14:55:07'),
('footer_copyright', '© {YEAR} HASAKI. Designed by Lê Thị Kim Phụng - D22CTC1.', 'text', 'footer', 'Dòng copyright', 'Có thể dùng {YEAR} để in năm tự động', 2, '2026-05-10 14:55:07'),
('hero_badge', 'Bộ sưu tập mới · Mùa hè 2026', 'text', 'hero', 'Badge phía trên hero', 'Nội dung badge nhỏ phía trên tiêu đề hero', 1, '2026-05-10 14:55:07'),
('hero_cta_primary', 'Mua ngay', 'text', 'hero', 'CTA chính', 'Nút chính', 4, '2026-05-10 14:55:07'),
('hero_cta_secondary', 'Câu chuyện thương hiệu', 'text', 'hero', 'CTA phụ', 'Nút phụ', 5, '2026-05-10 14:55:07'),
('hero_stat1_label', 'Khách hàng hài lòng', 'text', 'hero', 'Stat 1 - Nhãn', '', 11, '2026-05-10 14:55:07'),
('hero_stat1_number', '10K+', 'text', 'hero', 'Stat 1 - Số', '', 10, '2026-05-10 14:55:07'),
('hero_stat2_label', 'Sản phẩm chính hãng', 'text', 'hero', 'Stat 2 - Nhãn', '', 13, '2026-05-10 14:55:07'),
('hero_stat2_number', '1K+', 'text', 'hero', 'Stat 2 - Số', '', 12, '2026-05-10 14:55:07'),
('hero_stat3_label', 'Thương hiệu uy tín', 'text', 'hero', 'Stat 3 - Nhãn', '', 15, '2026-05-10 14:55:07'),
('hero_stat3_number', '50+', 'text', 'hero', 'Stat 3 - Số', '', 14, '2026-05-10 14:55:07'),
('hero_subtitle', 'Khám phá hơn <strong>1.000+</strong> sản phẩm mỹ phẩm chính hãng từ những thương hiệu hàng đầu thế giới. Giao hàng nhanh, giá tốt, đổi trả 7 ngày.', 'textarea', 'hero', 'Phụ đề', 'Mô tả ngắn dưới tiêu đề hero', 3, '2026-05-10 14:55:07'),
('hero_title', 'Tỏa sáng <em>vẻ đẹp</em> tự nhiên của bạn', 'textarea', 'hero', 'Tiêu đề hero', 'Tiêu đề lớn ở trang chủ. Dùng <em>...</em> để gradient', 2, '2026-05-10 14:55:07'),
('seo_keywords', 'mỹ phẩm, son môi, dưỡng da, kem chống nắng, hasaki', 'text', 'seo', 'Meta keywords', 'Ngăn cách bằng dấu phẩy', 2, '2026-05-10 14:55:07'),
('seo_og_image', '', 'image', 'seo', 'OG Image', 'Ảnh hiển thị khi share trên Facebook (1200x630px)', 3, '2026-05-10 14:55:07'),
('seo_title_suffix', 'HASAKI', 'text', 'seo', 'Suffix tiêu đề', 'Sẽ thêm vào sau tiêu đề các trang. VD: \"Sản phẩm - HASAKI\"', 1, '2026-05-10 14:55:07'),
('site_description', 'Hệ thống mỹ phẩm chính hãng - chăm sóc sắc đẹp toàn diện cho phái nữ Việt Nam.', 'textarea', 'general', 'Mô tả ngắn', 'Mô tả site dùng ở footer & SEO meta description', 3, '2026-05-10 14:55:07'),
('site_favicon', '', 'image', 'general', 'Favicon', 'Icon hiển thị trên tab trình duyệt (khuyên dùng 32x32 PNG)', 5, '2026-05-10 14:55:07'),
('site_logo', '', 'image', 'general', 'Logo', 'Upload logo (để trống sẽ dùng logo chữ mặc định)', 4, '2026-05-10 14:55:07'),
('site_name', 'HASAKI', 'text', 'general', 'Tên website', 'Tên thương hiệu hiển thị trên header & footer', 1, '2026-05-10 14:55:07'),
('site_tagline', 'Beauty & Wellness', 'text', 'general', 'Slogan', 'Khẩu hiệu hiển thị dưới logo', 2, '2026-05-10 14:55:07'),
('social_facebook', 'https://facebook.com/hasaki.vn', 'url', 'social', 'Facebook', 'URL trang Facebook', 1, '2026-05-10 14:55:07'),
('social_instagram', 'https://instagram.com/hasaki', 'url', 'social', 'Instagram', 'URL Instagram', 2, '2026-05-10 14:55:07'),
('social_tiktok', 'https://tiktok.com/@hasaki', 'url', 'social', 'TikTok', 'URL TikTok', 3, '2026-05-10 14:55:07'),
('social_youtube', '', 'url', 'social', 'YouTube', 'URL kênh YouTube', 4, '2026-05-10 14:55:07'),
('social_zalo', '', 'url', 'social', 'Zalo', 'Link Zalo OA', 5, '2026-05-10 14:55:07'),
('top_banner_active', '1', 'boolean', 'banner', 'Hiện banner trên', 'Bật/tắt thanh banner trên cùng', 1, '2026-05-10 14:55:07'),
('top_banner_cta', 'Mua ngay', 'text', 'banner', 'Nhãn nút CTA', 'Văn bản link CTA cuối banner', 4, '2026-05-10 14:55:07'),
('top_banner_link', 'products.php', 'url', 'banner', 'Link \"Mua ngay\"', 'Đường dẫn khi click mua ngay', 3, '2026-05-10 14:55:07'),
('top_banner_text', '🎁 Ưu đãi giảm <strong>20%</strong> khi đặt mỹ phẩm từ 200k', 'textarea', 'banner', 'Nội dung banner', 'Cho phép HTML cơ bản: <strong>, <em>', 2, '2026-05-10 14:55:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbotconfig`
--

CREATE TABLE `chatbotconfig` (
  `ConfigKey` varchar(50) NOT NULL,
  `ConfigValue` text,
  `GhiChu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbotconfig`
--

INSERT INTO `chatbotconfig` (`ConfigKey`, `ConfigValue`, `GhiChu`) VALUES
('bot_name', 'Hasaki Bot', 'Tên hiển thị của chatbot'),
('enable_fallback_search', '1', 'Cho phép fallback search products khi không match intent (1/0)'),
('fallback', 'Mình chưa hiểu rõ ý bạn 😅\nBạn có thể thử hỏi: tư vấn da khô/da dầu, tìm son môi, kem chống nắng, sản phẩm sale, cách đặt hàng, chính sách đổi trả...\n\nHoặc gọi hotline **1900 1234** để được tư vấn trực tiếp nhé 💕', 'Câu trả lời khi không tìm thấy intent phù hợp'),
('greeting', 'Xin chào! Mình là **Hasaki Bot** 💖\nMình có thể giúp bạn:\n• Tư vấn sản phẩm theo loại da\n• Tìm sản phẩm sale / mới / hot\n• Hướng dẫn đặt hàng / đổi trả', 'Lời chào khi mở chatbot'),
('max_products', '4', 'Số sản phẩm tối đa hiển thị trong 1 reply');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbotintent`
--

CREATE TABLE `chatbotintent` (
  `MaIntent` int NOT NULL,
  `TenIntent` varchar(100) NOT NULL,
  `TuKhoa` text NOT NULL,
  `CauTraLoi` text NOT NULL,
  `LoaiGoiY` varchar(20) DEFAULT 'none',
  `GiaTriGoiY` varchar(100) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `ThuTu` int DEFAULT '0',
  `KichHoat` tinyint DEFAULT '1',
  `LaQuickReply` tinyint DEFAULT '0',
  `NgayCapNhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbotintent`
--

INSERT INTO `chatbotintent` (`MaIntent`, `TenIntent`, `TuKhoa`, `CauTraLoi`, `LoaiGoiY`, `GiaTriGoiY`, `Link`, `ThuTu`, `KichHoat`, `LaQuickReply`, `NgayCapNhat`) VALUES
(1, 'Chào hỏi', 'xin chao|chao|hello|hi|hey', 'Chào bạn! Mình là **Hasaki Bot** - trợ lý mua sắm của bạn 💖\nMình có thể tư vấn sản phẩm, hướng dẫn đặt hàng và các chính sách. Bạn cần hỗ trợ gì ạ?', 'none', NULL, NULL, 1, 1, 0, '2026-05-10 13:09:26'),
(2, 'Cảm ơn', 'cam on|thanks|thank you|thx', 'Hasaki rất vui được hỗ trợ bạn! Chúc bạn mua sắm vui vẻ 🌸', 'none', NULL, NULL, 2, 1, 0, '2026-05-10 13:09:26'),
(3, 'Tạm biệt', 'tam biet|bye|goodbye|hen gap lai', 'Tạm biệt bạn! Hẹn gặp lại bạn lần sau 👋', 'none', NULL, NULL, 3, 1, 0, '2026-05-10 13:09:26'),
(4, 'Hướng dẫn đặt hàng', 'dat hang|cach mua|mua hang|mua nhu the nao|order|cach dat hang', 'Để đặt hàng tại Hasaki, bạn làm theo 3 bước:\n1️⃣ Chọn sản phẩm và nhấn **Thêm vào giỏ hàng**\n2️⃣ Vào **Giỏ hàng** → kiểm tra số lượng → nhấn **Tiến hành thanh toán**\n3️⃣ Điền địa chỉ và chọn hình thức thanh toán (COD hoặc ATM/VNPay)\n\nSau khi đặt hàng, bạn sẽ nhận được mã đơn hàng và đơn hàng sẽ được giao trong 1-3 ngày!', 'none', NULL, NULL, 10, 1, 1, '2026-05-10 13:09:26'),
(5, 'Giao hàng', 'giao hang|ship|van chuyen|phi giao', '🚚 **Chính sách giao hàng:**\n• Nội thành TP.HCM: 30 phút - 2 giờ\n• Tỉnh khác: 1-3 ngày\n• **Miễn phí giao hàng** cho đơn từ 200.000đ\n• Áp dụng cho tất cả sản phẩm có sẵn trong kho', 'none', NULL, NULL, 11, 1, 0, '2026-05-10 13:09:26'),
(6, 'Đổi trả', 'doi tra|tra hang|hoan tien|return|chinh sach doi tra', '🔄 **Chính sách đổi trả:**\n• Đổi trả miễn phí trong **7 ngày**\n• Sản phẩm phải còn nguyên seal, chưa qua sử dụng\n• Hỗ trợ đổi trả tận nhà, không cần ra cửa hàng\n• Hoàn 100% nếu sản phẩm lỗi từ nhà sản xuất', 'none', NULL, NULL, 12, 1, 1, '2026-05-10 13:09:26'),
(7, 'Thanh toán', 'thanh toan|tra tien|payment|cod|vnpay|visa', '💳 **Hasaki hỗ trợ các hình thức thanh toán:**\n• Thanh toán khi nhận hàng (COD)\n• Thẻ ATM nội địa / Internet Banking\n• Thẻ tín dụng Visa/Mastercard/JCB\n• Ví VNPay-QR\n• Chuyển khoản ngân hàng', 'none', NULL, NULL, 13, 1, 0, '2026-05-10 13:09:26'),
(8, 'Đăng ký', 'dang ky|tao tai khoan|register', 'Bạn có thể đăng ký tài khoản miễn phí tại đây để nhận ưu đãi 💕', 'none', NULL, 'register.php', 20, 1, 0, '2026-05-10 13:09:26'),
(9, 'Đăng nhập', 'dang nhap|login|sign in', 'Bạn có thể đăng nhập tại đây 👇', 'none', NULL, 'login.php', 21, 1, 0, '2026-05-10 13:09:26'),
(10, 'Tư vấn da khô', 'da kho', 'Đối với **da khô** ☀️, Hasaki gợi ý:\n• Dùng sữa rửa mặt dịu nhẹ, không tạo bọt\n• Kem dưỡng giàu ceramide & hyaluronic acid\n• Tránh sản phẩm có cồn\n\nMột số sản phẩm phù hợp:', 'search', 'duong', NULL, 30, 1, 1, '2026-05-10 13:09:26'),
(11, 'Tư vấn da dầu', 'da dau|da nhon', 'Đối với **da dầu** 💧, Hasaki khuyên dùng:\n• Sữa rửa mặt có salicylic acid (BHA)\n• Toner cân bằng pH\n• Kem chống nắng dạng gel/lotion thoáng\n\nGợi ý sản phẩm:', 'search', 'lam sach', NULL, 31, 1, 1, '2026-05-10 13:09:26'),
(12, 'Tư vấn da nhạy cảm', 'da nhay cam|kich ung', 'Da nhạy cảm cần ưu tiên các sản phẩm dịu nhẹ, không hương liệu, không cồn. Bạn có thể thử **La Roche-Posay** hoặc **Bioderma** - các thương hiệu chuyên cho da nhạy cảm 🌸', 'search', 'bioderma', NULL, 32, 1, 0, '2026-05-10 13:09:26'),
(13, 'Trị mụn', 'mun|tri mun', 'Để **trị mụn** hiệu quả, mình khuyên:\n• Rửa mặt 2 lần/ngày, tránh sờ tay lên mặt\n• Dùng BHA/AHA hoặc niacinamide\n• Hạn chế đường, sữa và đồ cay nóng\n• Uống đủ 2L nước/ngày\n\nNếu mụn nặng, bạn nên đi khám da liễu nha 💖', 'none', NULL, NULL, 33, 1, 0, '2026-05-10 13:09:26'),
(14, 'Son môi', 'son moi|son li|son tint|son', 'Đây là một số mẫu son hot tại Hasaki 💋', 'search', 'son', NULL, 40, 1, 1, '2026-05-10 13:09:26'),
(15, 'Kem chống nắng', 'kem chong nang|sunscreen|spf|chong nang', '☀️ Kem chống nắng là sản phẩm không thể thiếu mỗi ngày. Đây là gợi ý:', 'search', 'chong nang', NULL, 41, 1, 0, '2026-05-10 13:09:26'),
(16, 'Nước hoa', 'nuoc hoa|perfume', '🌹 Nước hoa cao cấp tại Hasaki:', 'search', 'nuoc hoa', NULL, 42, 1, 0, '2026-05-10 13:09:26'),
(17, 'Phấn phủ', 'phan phu|phan trang diem|lop nen', 'Một số sản phẩm phấn phủ / trang điểm hot:', 'search', 'phan', NULL, 43, 1, 0, '2026-05-10 13:09:26'),
(18, 'Sale - khuyến mãi', 'sale|giam gia|khuyen mai|uu dai|hot deal|san pham dang sale', '🔥 Sản phẩm đang **giảm giá** tại Hasaki:', 'featured', NULL, NULL, 50, 1, 1, '2026-05-10 13:09:26'),
(19, 'Bán chạy', 'ban chay|hot|noi bat|pho bien|duoc yeu thich', '⭐ Top sản phẩm bán chạy tại Hasaki:', 'featured', NULL, NULL, 51, 1, 0, '2026-05-10 13:09:26'),
(20, 'Mới ra mắt', 'moi nhat|moi ra mat|new arrival', '✨ Sản phẩm mới ra mắt:', 'newest', NULL, NULL, 52, 1, 0, '2026-05-10 13:09:26'),
(21, 'Liên hệ', 'lien he|hotline|so dien thoai|sdt|contact|tu van', '📞 **Liên hệ Hasaki:**\n• Hotline: 1900 1234 (8:00 - 22:00)\n• Email: support@hasaki.vn\n• Địa chỉ: 123 Lê Lợi, Q.1, TP.HCM\n• Fanpage Facebook: facebook.com/hasaki.vn', 'none', NULL, NULL, 60, 1, 0, '2026-05-10 13:09:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiethoadonban`
--

CREATE TABLE `chitiethoadonban` (
  `MaChiTietHoaDon` int NOT NULL,
  `MaHoaDon` int NOT NULL,
  `MaSanPham` int NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `SoLuong` int NOT NULL,
  `GiaBan` decimal(12,2) NOT NULL,
  `KichCo` varchar(100) DEFAULT NULL,
  `MauSac` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitiethoadonban`
--

INSERT INTO `chitiethoadonban` (`MaChiTietHoaDon`, `MaHoaDon`, `MaSanPham`, `TenSanPham`, `SoLuong`, `GiaBan`, `KichCo`, `MauSac`) VALUES
(1, 1, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(2, 1, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 3, 459000.00, '40ml', NULL),
(3, 1, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(4, 2, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(5, 2, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(6, 2, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 2, 429000.00, '3.5g', 'Hồng đào'),
(7, 2, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(8, 3, 8, 'Kem dưỡng La Roche-Posay Toleriane', 2, 489000.00, '50ml', NULL),
(9, 3, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(10, 3, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(11, 4, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(12, 4, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 2, 429000.00, '3.5g', 'Hồng đào'),
(13, 4, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 2, 429000.00, '3.5g', 'Hồng đào'),
(14, 4, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(15, 5, 3, 'Son kem lì mịn nhung Velvet Matte', 2, 289000.00, '6ml', 'Cam đất'),
(16, 5, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(17, 5, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(18, 5, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 3, 569000.00, '60ml', NULL),
(19, 6, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(20, 7, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 1, 299000.00, '12g', 'Tự nhiên'),
(21, 8, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(22, 8, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(23, 9, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 1, 299000.00, '12g', 'Tự nhiên'),
(24, 9, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 2, 2890000.00, '50ml', NULL),
(25, 9, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(26, 10, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(27, 10, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(28, 11, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 1, 159000.00, '500ml', NULL),
(29, 11, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(30, 11, 3, 'Son kem lì mịn nhung Velvet Matte', 3, 289000.00, '6ml', 'Cam đất'),
(31, 12, 8, 'Kem dưỡng La Roche-Posay Toleriane', 3, 489000.00, '50ml', NULL),
(32, 12, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(33, 12, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 3, 459000.00, '40ml', NULL),
(34, 12, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(35, 13, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(36, 14, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 3, 459000.00, '40ml', NULL),
(37, 14, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 2, 369000.00, '500ml', NULL),
(38, 14, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 2, 459000.00, '40ml', NULL),
(39, 15, 5, 'Phấn phủ khoáng Natural Veil', 2, 349000.00, '10g', 'Nude tự nhiên'),
(40, 15, 2, 'Son dưỡng môi Moist Touch Hydra', 1, 199000.00, '4g', 'Hồng nude'),
(41, 15, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 1, 219000.00, '250ml', NULL),
(42, 16, 5, 'Phấn phủ khoáng Natural Veil', 3, 349000.00, '10g', 'Nude tự nhiên'),
(43, 16, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(44, 16, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(45, 16, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 1, 459000.00, '40ml', NULL),
(46, 17, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(47, 17, 8, 'Kem dưỡng La Roche-Posay Toleriane', 3, 489000.00, '50ml', NULL),
(48, 17, 2, 'Son dưỡng môi Moist Touch Hydra', 3, 199000.00, '4g', 'Hồng nude'),
(49, 17, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(50, 18, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(51, 18, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(52, 18, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(53, 18, 3, 'Son kem lì mịn nhung Velvet Matte', 2, 289000.00, '6ml', 'Cam đất'),
(54, 19, 8, 'Kem dưỡng La Roche-Posay Toleriane', 2, 489000.00, '50ml', NULL),
(55, 19, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 1, 459000.00, '40ml', NULL),
(56, 20, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 3, 369000.00, '500ml', NULL),
(57, 20, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 2, 189000.00, '5.5g', 'Đỏ cam'),
(58, 20, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(59, 20, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(60, 21, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 2, 429000.00, '3.5g', 'Hồng đào'),
(61, 21, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(62, 22, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(63, 22, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(64, 22, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 3, 459000.00, '40ml', NULL),
(65, 23, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(66, 23, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(67, 23, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(68, 23, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(69, 24, 3, 'Son kem lì mịn nhung Velvet Matte', 1, 289000.00, '6ml', 'Cam đất'),
(70, 24, 8, 'Kem dưỡng La Roche-Posay Toleriane', 2, 489000.00, '50ml', NULL),
(71, 25, 14, 'Nước hoa Dior Sauvage EDT', 2, 2590000.00, '60ml', NULL),
(72, 26, 8, 'Kem dưỡng La Roche-Posay Toleriane', 3, 489000.00, '50ml', NULL),
(73, 26, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 1, 259000.00, '3.5g', 'Đỏ ruby'),
(74, 26, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(75, 27, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 3, 369000.00, '500ml', NULL),
(76, 27, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(77, 27, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(78, 27, 3, 'Son kem lì mịn nhung Velvet Matte', 2, 289000.00, '6ml', 'Cam đất'),
(79, 28, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(80, 29, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(81, 30, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 2, 129000.00, '400ml', NULL),
(82, 30, 3, 'Son kem lì mịn nhung Velvet Matte', 3, 289000.00, '6ml', 'Cam đất'),
(83, 30, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 3, 569000.00, '60ml', NULL),
(84, 31, 2, 'Son dưỡng môi Moist Touch Hydra', 3, 199000.00, '4g', 'Hồng nude'),
(85, 31, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(86, 32, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(87, 32, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(88, 32, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(89, 33, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(90, 33, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(91, 34, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(92, 35, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(93, 35, 5, 'Phấn phủ khoáng Natural Veil', 2, 349000.00, '10g', 'Nude tự nhiên'),
(94, 35, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(95, 36, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(96, 36, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 2, 459000.00, '40ml', NULL),
(97, 37, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 2, 369000.00, '500ml', NULL),
(98, 37, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 2, 129000.00, '400ml', NULL),
(99, 37, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 1, 219000.00, '250ml', NULL),
(100, 37, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(101, 38, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(102, 39, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(103, 40, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 1, 569000.00, '60ml', NULL),
(104, 40, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(105, 40, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(106, 40, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 1, 159000.00, '500ml', NULL),
(107, 41, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 2, 189000.00, '5.5g', 'Đỏ cam'),
(108, 42, 8, 'Kem dưỡng La Roche-Posay Toleriane', 1, 489000.00, '50ml', NULL),
(109, 42, 2, 'Son dưỡng môi Moist Touch Hydra', 2, 199000.00, '4g', 'Hồng nude'),
(110, 42, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(111, 42, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(112, 43, 3, 'Son kem lì mịn nhung Velvet Matte', 2, 289000.00, '6ml', 'Cam đất'),
(113, 43, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(114, 44, 2, 'Son dưỡng môi Moist Touch Hydra', 3, 199000.00, '4g', 'Hồng nude'),
(115, 45, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 1, 159000.00, '500ml', NULL),
(116, 45, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 3, 569000.00, '60ml', NULL),
(117, 45, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(118, 45, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(119, 46, 8, 'Kem dưỡng La Roche-Posay Toleriane', 1, 489000.00, '50ml', NULL),
(120, 47, 5, 'Phấn phủ khoáng Natural Veil', 1, 349000.00, '10g', 'Nude tự nhiên'),
(121, 47, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 1, 299000.00, '12g', 'Tự nhiên'),
(122, 47, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(123, 48, 3, 'Son kem lì mịn nhung Velvet Matte', 1, 289000.00, '6ml', 'Cam đất'),
(124, 49, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 3, 299000.00, '12g', 'Tự nhiên'),
(125, 50, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(126, 51, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 1, 459000.00, '40ml', NULL),
(127, 52, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 1, 159000.00, '500ml', NULL),
(128, 52, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(129, 52, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 2, 2890000.00, '50ml', NULL),
(130, 52, 2, 'Son dưỡng môi Moist Touch Hydra', 1, 199000.00, '4g', 'Hồng nude'),
(131, 53, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(132, 54, 5, 'Phấn phủ khoáng Natural Veil', 3, 349000.00, '10g', 'Nude tự nhiên'),
(133, 54, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(134, 55, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(135, 56, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 2, 129000.00, '400ml', NULL),
(136, 56, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(137, 56, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 2, 129000.00, '400ml', NULL),
(138, 57, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(139, 57, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(140, 57, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 2, 189000.00, '5.5g', 'Đỏ cam'),
(141, 57, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(142, 58, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(143, 58, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(144, 59, 2, 'Son dưỡng môi Moist Touch Hydra', 2, 199000.00, '4g', 'Hồng nude'),
(145, 59, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 1, 299000.00, '12g', 'Tự nhiên'),
(146, 59, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 2, 219000.00, '250ml', NULL),
(147, 59, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(148, 60, 2, 'Son dưỡng môi Moist Touch Hydra', 1, 199000.00, '4g', 'Hồng nude'),
(149, 60, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(150, 60, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(151, 61, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(152, 61, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 3, 459000.00, '40ml', NULL),
(153, 61, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 1, 369000.00, '500ml', NULL),
(154, 62, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 2, 429000.00, '3.5g', 'Hồng đào'),
(155, 62, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(156, 63, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(157, 63, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(158, 64, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(159, 64, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(160, 64, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(161, 65, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(162, 65, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(163, 65, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(164, 65, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(165, 66, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(166, 66, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(167, 66, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(168, 67, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 2, 459000.00, '40ml', NULL),
(169, 67, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(170, 67, 5, 'Phấn phủ khoáng Natural Veil', 1, 349000.00, '10g', 'Nude tự nhiên'),
(171, 67, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(172, 68, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(173, 68, 5, 'Phấn phủ khoáng Natural Veil', 2, 349000.00, '10g', 'Nude tự nhiên'),
(174, 68, 2, 'Son dưỡng môi Moist Touch Hydra', 3, 199000.00, '4g', 'Hồng nude'),
(175, 68, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 3, 189000.00, '5.5g', 'Đỏ cam'),
(176, 69, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(177, 70, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(178, 70, 14, 'Nước hoa Dior Sauvage EDT', 2, 2590000.00, '60ml', NULL),
(179, 71, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 1, 219000.00, '250ml', NULL),
(180, 71, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(181, 72, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 3, 369000.00, '500ml', NULL),
(182, 72, 5, 'Phấn phủ khoáng Natural Veil', 3, 349000.00, '10g', 'Nude tự nhiên'),
(183, 72, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 1, 569000.00, '60ml', NULL),
(184, 72, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 2, 459000.00, '40ml', NULL),
(185, 73, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(186, 73, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 3, 159000.00, '500ml', NULL),
(187, 73, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(188, 73, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 1, 2890000.00, '50ml', NULL),
(189, 74, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(190, 75, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 3, 299000.00, '12g', 'Tự nhiên'),
(191, 75, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(192, 76, 3, 'Son kem lì mịn nhung Velvet Matte', 1, 289000.00, '6ml', 'Cam đất'),
(193, 76, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 2, 2890000.00, '50ml', NULL),
(194, 77, 14, 'Nước hoa Dior Sauvage EDT', 2, 2590000.00, '60ml', NULL),
(195, 78, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 1, 569000.00, '60ml', NULL),
(196, 78, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 1, 569000.00, '60ml', NULL),
(197, 78, 8, 'Kem dưỡng La Roche-Posay Toleriane', 2, 489000.00, '50ml', NULL),
(198, 78, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(199, 79, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(200, 80, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 2, 369000.00, '500ml', NULL),
(201, 80, 14, 'Nước hoa Dior Sauvage EDT', 1, 2590000.00, '60ml', NULL),
(202, 80, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 2, 259000.00, '3.5g', 'Đỏ ruby'),
(203, 80, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(204, 81, 3, 'Son kem lì mịn nhung Velvet Matte', 1, 289000.00, '6ml', 'Cam đất'),
(205, 82, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 3, 299000.00, '12g', 'Tự nhiên'),
(206, 82, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 2, 159000.00, '500ml', NULL),
(207, 82, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 3, 219000.00, '250ml', NULL),
(208, 82, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 2, 189000.00, '5.5g', 'Đỏ cam'),
(209, 83, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 3, 2890000.00, '50ml', NULL),
(210, 83, 8, 'Kem dưỡng La Roche-Posay Toleriane', 3, 489000.00, '50ml', NULL),
(211, 84, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 2, 2890000.00, '50ml', NULL),
(212, 84, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 3, 429000.00, '3.5g', 'Hồng đào'),
(213, 85, 2, 'Son dưỡng môi Moist Touch Hydra', 3, 199000.00, '4g', 'Hồng nude'),
(214, 86, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 2, 2890000.00, '50ml', NULL),
(215, 86, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(216, 87, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 3, 129000.00, '400ml', NULL),
(217, 87, 5, 'Phấn phủ khoáng Natural Veil', 1, 349000.00, '10g', 'Nude tự nhiên'),
(218, 87, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(219, 87, 5, 'Phấn phủ khoáng Natural Veil', 3, 349000.00, '10g', 'Nude tự nhiên'),
(220, 88, 5, 'Phấn phủ khoáng Natural Veil', 2, 349000.00, '10g', 'Nude tự nhiên'),
(221, 88, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 2, 299000.00, '12g', 'Tự nhiên'),
(222, 88, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 1, 569000.00, '60ml', NULL),
(223, 89, 2, 'Son dưỡng môi Moist Touch Hydra', 1, 199000.00, '4g', 'Hồng nude'),
(224, 89, 14, 'Nước hoa Dior Sauvage EDT', 3, 2590000.00, '60ml', NULL),
(225, 89, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 2, 189000.00, '5.5g', 'Đỏ cam'),
(226, 89, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 1, 459000.00, '40ml', NULL),
(227, 90, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(228, 91, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, '5.5g', 'Đỏ cam'),
(229, 91, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 1, 429000.00, '3.5g', 'Hồng đào'),
(230, 94, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 1, 129000.00, '400ml', NULL),
(231, 95, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 1, 189000.00, NULL, 'Đỏ cam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitiethoadonnhap`
--

CREATE TABLE `chitiethoadonnhap` (
  `MaChiTietHoaDon` int NOT NULL,
  `MaHoaDon` int NOT NULL,
  `MaSanPham` int NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `MauSac` varchar(100) DEFAULT NULL,
  `KichCo` varchar(100) DEFAULT NULL,
  `SoLuong` int NOT NULL,
  `GiaNhap` decimal(12,2) NOT NULL,
  `AnhSanPham` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `MaDanhGia` int NOT NULL,
  `MaSanPham` int NOT NULL,
  `MaKhachHang` int DEFAULT NULL,
  `TenNguoiDanhGia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SoSao` tinyint NOT NULL,
  `TieuDe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `NoiDung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `HuuIch` int DEFAULT '0',
  `DaXacThucMua` tinyint DEFAULT '0',
  `NgayDanhGia` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`MaDanhGia`, `MaSanPham`, `MaKhachHang`, `TenNguoiDanhGia`, `SoSao`, `TieuDe`, `NoiDung`, `HuuIch`, `DaXacThucMua`, `NgayDanhGia`) VALUES
(1, 1, 1, 'Trần Thị Bích Ngọc', 5, 'Màu son đẹp, lên chuẩn', 'Mình rất ưng son này. Màu đỏ ruby quyến rũ, lên màu chuẩn ngay lần đầu. Lì 8 tiếng đúng như quảng cáo, không bị khô môi. Sẽ mua lại!', 24, 1, '2026-05-10 14:37:09'),
(2, 1, 2, 'Nguyễn Thị Hồng Ngân', 4, 'Đẹp nhưng hơi đậm', 'Son đẹp, chất lượng OK. Tuy nhiên màu đỏ này hơi đậm với mình, có thể tô mỏng lại. Bao bì sang trọng.', 12, 1, '2026-05-10 14:37:09'),
(3, 1, 3, 'Phạm Mai Anh', 5, 'Yêu son này quá!', 'Đây là cây son thứ 3 mình mua của Ruby Kiss. Lì cực, ăn uống không trôi nhiều. Giá cũng hợp lý.', 18, 1, '2026-05-10 14:37:09'),
(4, 1, NULL, 'Khách vô danh', 5, 'Đáng tiền', 'Son đẹp, lên màu chuẩn. Giao hàng nhanh, đóng gói cẩn thận. Recommend!', 5, 0, '2026-05-10 14:37:09'),
(5, 2, 1, 'Trần Thị Bích Ngọc', 5, 'Cứu tinh cho môi khô', 'Mùa đông môi mình khô nẻ rất khó chịu. Dùng Moist Touch 1 tuần là môi mềm hẳn, hết bong tróc. Hương cherry rất dễ chịu!', 32, 1, '2026-05-10 14:37:09'),
(6, 2, 4, 'Lê Hồng Vy', 4, 'Dưỡng tốt nhưng hết nhanh', 'Son dưỡng cấp ẩm tốt, môi mềm sau 2-3 ngày dùng. Chỉ tiếc là hộp 4g hết khá nhanh nếu dùng nhiều lần trong ngày.', 8, 1, '2026-05-10 14:37:09'),
(7, 2, 5, 'Đỗ Quỳnh Anh', 5, 'Dùng làm lớp lót son cũng tốt', 'Mình dùng son dưỡng này trước khi đánh son lì, môi không bị khô và son lên màu đẹp hơn. Highly recommend!', 15, 1, '2026-05-10 14:37:09'),
(8, 3, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Mịn như nhung thật sự', 'Tên đúng nghĩa! Son lên môi mịn mượt, không bị bệt. Cam đất rất hợp da vàng Việt Nam. Lì cả ngày!', 27, 1, '2026-05-10 14:37:09'),
(9, 3, 6, 'Vũ Thanh Tâm', 4, 'Mua tặng vợ, vợ thích lắm', 'Mua tặng vợ dịp sinh nhật. Vợ rất thích chất son và màu. Sẽ mua thêm tone khác.', 9, 1, '2026-05-10 14:37:09'),
(10, 3, 3, 'Phạm Mai Anh', 5, 'Lì lâu, không khô môi', 'Bất ngờ là son lì mà không bị khô. Bám tới 8-10 tiếng mới phai nhẹ. Đáng mua!', 21, 1, '2026-05-10 14:37:09'),
(11, 4, 4, 'Lê Hồng Vy', 5, 'Da dầu mê tơi', 'Da mình dầu khủng khiếp, dùng Oil Control thì lớp nền bền cả ngày. Phấn mịn không cakey. Sản phẩm chân ái cho da dầu!', 41, 1, '2026-05-10 14:37:09'),
(12, 4, 7, 'Bùi Khánh Linh', 4, 'Tốt nhưng hơi đắt', 'Phấn kiềm dầu tốt, lớp nền bền. Tuy nhiên giá hơi cao so với dung tích 12g.', 6, 1, '2026-05-10 14:37:09'),
(13, 6, 1, 'Trần Thị Bích Ngọc', 5, 'Cấp ẩm cực tốt', 'Da mình khô và thiếu ẩm. Dùng Hydrabio 2 tuần thấy da mịn và căng hơn. Texture nhẹ, thấm nhanh, không nhờn rít.', 38, 1, '2026-05-10 14:37:09'),
(14, 6, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Yêu Bioderma', 'Cả nhà mình dùng Bioderma. Hydrabio này cấp ẩm 24 giờ thật, mùa hè da không khô nữa.', 22, 1, '2026-05-10 14:37:09'),
(15, 6, 5, 'Đỗ Quỳnh Anh', 4, 'Tốt cho da nhạy cảm', 'Da mình nhạy cảm hay kích ứng. Hydrabio không gây mẩn đỏ, da dễ chịu hơn. Recommend cho ai có da nhạy cảm.', 14, 1, '2026-05-10 14:37:09'),
(16, 7, 4, 'Lê Hồng Vy', 5, 'Kem chống nắng quốc dân', 'Anessa là chân ái! Kiềm dầu tốt, không trắng bệch, không trôi khi đi bơi. Mùa hè không thể thiếu.', 56, 1, '2026-05-10 14:37:09'),
(17, 7, 3, 'Phạm Mai Anh', 5, 'Đáng đầu tư', 'Hơi đắt nhưng xứng đáng. Bảo vệ da tuyệt vời, mua 1 chai dùng được lâu nếu chỉ thoa mặt.', 18, 1, '2026-05-10 14:37:09'),
(18, 7, 6, 'Vũ Thanh Tâm', 4, 'Mua dùm vợ, vợ khen', 'Vợ dùng đi biển khen không bị cháy nắng. Sẽ mua lại.', 7, 1, '2026-05-10 14:37:09'),
(19, 9, 1, 'Trần Thị Bích Ngọc', 5, 'Sữa rửa mặt nhẹ nhàng', 'Da mình mỏng và nhạy cảm, đã thử nhiều SRM mà toàn bị khô căng. Cetaphil thì dịu nhẹ, không xà phòng, không khô. Yêu!', 29, 1, '2026-05-10 14:37:09'),
(20, 9, 2, 'Nguyễn Thị Hồng Ngân', 4, 'OK cho da thường', 'Sạch da nhưng không tạo bọt nên cảm giác chưa làm sạch sâu. Phù hợp da khô/nhạy cảm hơn da dầu.', 11, 1, '2026-05-10 14:37:09'),
(21, 10, 4, 'Lê Hồng Vy', 5, 'Best of best', 'Đã dùng 5 năm rồi, không thay đổi. Tẩy trang sạch makeup đậm mà không cần rửa lại nước. Da không bị căng.', 67, 1, '2026-05-10 14:37:09'),
(22, 10, 7, 'Bùi Khánh Linh', 5, 'Đáng đồng tiền', 'Chai 500ml dùng được rất lâu. Tẩy trang nhẹ nhàng, không cay mắt khi tẩy mascara.', 24, 1, '2026-05-10 14:37:09'),
(23, 13, 5, 'Đỗ Quỳnh Anh', 5, 'Sang trọng và quyến rũ', 'Mùi nước hoa nữ tính, sang trọng. Lưu hương rất lâu, cả ngày vẫn còn thoang thoảng. Worth it!', 19, 1, '2026-05-10 14:37:09'),
(24, 13, 1, 'Trần Thị Bích Ngọc', 5, 'Quà sinh nhật yêu', 'Chồng tặng dịp sinh nhật. Mùi vô cùng hợp với mình. Cảm ơn Hasaki giao nhanh, đóng gói đẹp.', 11, 1, '2026-05-10 14:37:09'),
(25, 16, 2, 'Nguyễn Thị Hồng Ngân', 5, 'Bóng nước đẹp xuất sắc', 'Romand không bao giờ làm thất vọng. Tint bóng môi, cấp ẩm mà bám lâu. Màu cam đỏ rất trendy.', 34, 1, '2026-05-10 14:37:09'),
(26, 16, 7, 'Bùi Khánh Linh', 5, 'Mê hoặc', 'Đã mua 4 màu rồi. Lần này thử đỏ cam, hợp luôn. Sẽ mua tiếp các màu khác.', 23, 1, '2026-05-10 14:37:09'),
(27, 16, 3, 'Phạm Mai Anh', 4, 'Đẹp nhưng cần dặm lại', 'Son đẹp, nhưng vì là tint bóng nên ăn uống có hơi phai. Cần dặm lại sau bữa ăn.', 7, 1, '2026-05-10 14:37:09'),
(28, 16, 1, 'Trần Thị Bích Ngọc', 5, 'Đẹp', 'Sản phẩm ok', 0, 0, '2026-05-10 22:06:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` int NOT NULL,
  `TenDanhMuc` varchar(100) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL,
  `AnhDanhMuc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`MaDanhMuc`, `TenDanhMuc`, `MoTa`, `AnhDanhMuc`) VALUES
(1, 'Son môi', 'Các loại son môi cao cấp - son lì, son tint, son dưỡng', 'images/categories/son-moi.jpg'),
(2, 'Dưỡng da', 'Sữa dưỡng, kem dưỡng, serum cấp ẩm cho mọi loại da', 'images/categories/duong-da.jpg'),
(3, 'Trang điểm', 'Phấn nền, phấn phủ, phấn má hồng và phụ kiện trang điểm', 'images/categories/trang-diem.jpg'),
(4, 'Làm sạch da', 'Sữa rửa mặt, tẩy trang, toner cân bằng', 'images/categories/lam-sach-da.jpg'),
(5, 'Chăm sóc cơ thể', 'Sữa tắm, lotion dưỡng thể, kem dưỡng tay', 'images/categories/cham-soc-co-the.jpg'),
(6, 'Nước hoa', 'Nước hoa nữ và nam từ các thương hiệu uy tín thế giới', 'images/categories/nuoc-hoa.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadonban`
--

CREATE TABLE `hoadonban` (
  `MaHoaDon` int NOT NULL,
  `NgayBan` datetime DEFAULT CURRENT_TIMESTAMP,
  `ThanhTien` decimal(12,2) NOT NULL,
  `MaKhachHang` int NOT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChiGH` varchar(255) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `HinhThucTT` varchar(50) DEFAULT 'COD',
  `TrangThai` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoadonban`
--

INSERT INTO `hoadonban` (`MaHoaDon`, `NgayBan`, `ThanhTien`, `MaKhachHang`, `SDT`, `Email`, `DiaChiGH`, `GhiChu`, `HinhThucTT`, `TrangThai`) VALUES
(1, '2026-04-19 09:50:35', 2104000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(2, '2026-03-14 06:10:45', 2283000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'COD', 1),
(3, '2026-05-07 15:08:40', 2742000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'COD', 2),
(4, '2026-05-07 14:28:46', 7196000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(5, '2026-04-02 02:59:37', 5832000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(6, '2026-04-18 08:24:02', 1287000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'VNPAY', 0),
(7, '2026-02-14 17:26:42', 299000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(8, '2026-05-09 12:05:57', 995000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'BANK', 1),
(9, '2026-03-01 01:22:30', 6508000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'MOMO', 2),
(10, '2026-02-21 13:33:52', 5180000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'VNPAY', 1),
(11, '2026-05-05 10:15:57', 1464000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 2),
(12, '2026-04-05 15:45:26', 3829000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'VNPAY', 2),
(13, '2026-05-10 16:23:21', 387000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 1),
(14, '2026-04-09 01:54:07', 3033000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 2),
(15, '2026-04-28 15:24:46', 1116000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 0),
(16, '2026-04-22 05:31:38', 10743000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'VNPAY', 2),
(17, '2026-03-19 06:56:35', 10432000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'VNPAY', 3),
(18, '2026-04-01 00:48:14', 9764000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'BANK', 1),
(19, '2026-04-28 02:45:14', 1437000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(20, '2026-05-04 21:20:55', 2151000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 2),
(21, '2026-05-04 16:47:00', 2145000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 2),
(22, '2026-05-08 20:18:44', 2333000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'VNPAY', 2),
(23, '2026-04-05 06:36:23', 10062000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(24, '2026-03-13 05:52:00', 1267000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'MOMO', 2),
(25, '2026-04-22 15:46:42', 5180000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'VNPAY', 1),
(26, '2026-05-10 00:02:54', 2164000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'COD', 2),
(27, '2026-03-27 19:09:17', 3101000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 0),
(28, '2026-04-29 09:16:15', 429000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 2),
(29, '2026-02-16 01:09:10', 129000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 2),
(30, '2026-05-04 22:28:52', 2832000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'VNPAY', 1),
(31, '2026-04-12 06:53:43', 1254000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'COD', 2),
(32, '2026-03-18 23:23:32', 3795000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 2),
(33, '2026-05-09 07:54:55', 10660000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'BANK', 1),
(34, '2026-05-02 21:47:39', 477000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 2),
(35, '2026-04-24 11:15:48', 9797000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'VNPAY', 3),
(36, '2026-03-28 04:52:25', 1107000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 3),
(37, '2026-05-10 17:03:37', 1404000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(38, '2026-04-15 23:01:10', 129000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 0),
(39, '2026-04-21 10:05:46', 318000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'BANK', 2),
(40, '2026-05-02 22:12:51', 1893000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(41, '2026-04-26 13:56:59', 378000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 2),
(42, '2026-03-23 19:02:03', 1643000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(43, '2026-05-05 01:31:34', 1007000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(44, '2026-05-07 01:03:44', 597000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(45, '2026-05-04 02:26:02', 13426000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'BANK', 2),
(46, '2026-05-11 06:32:53', 489000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'MOMO', 2),
(47, '2026-04-12 06:18:35', 777000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'VNPAY', 2),
(48, '2026-05-08 07:31:39', 289000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 0),
(49, '2026-05-03 04:09:32', 897000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 0),
(50, '2026-04-11 19:42:15', 518000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 2),
(51, '2026-03-03 18:19:43', 459000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'COD', 1),
(52, '2026-04-11 15:53:49', 6615000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'VNPAY', 2),
(53, '2026-02-15 10:01:51', 429000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 1),
(54, '2026-04-17 02:01:13', 1614000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'VNPAY', 2),
(55, '2026-04-17 22:51:56', 657000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 1),
(56, '2026-04-08 13:11:46', 1083000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'VNPAY', 3),
(57, '2026-04-13 08:06:25', 2532000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'BANK', 0),
(58, '2026-04-13 21:27:47', 16440000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 2),
(59, '2026-02-24 11:17:06', 1653000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'COD', 2),
(60, '2026-05-10 16:46:55', 1063000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'VNPAY', 2),
(61, '2026-04-22 23:44:26', 2223000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'BANK', 2),
(62, '2026-04-24 13:07:56', 1456000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 2),
(63, '2026-04-13 17:07:05', 1674000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 1),
(64, '2026-04-28 08:28:18', 1184000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'VNPAY', 2),
(65, '2026-04-19 05:45:36', 15840000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(66, '2026-04-17 18:06:12', 1173000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'MOMO', 0),
(67, '2026-02-15 09:41:13', 3841000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'BANK', 0),
(68, '2026-03-06 00:38:47', 10532000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'COD', 2),
(69, '2026-05-11 06:34:04', 318000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 2),
(70, '2026-03-19 12:53:40', 5309000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'VNPAY', 2),
(71, '2026-04-14 00:07:18', 537000.00, 8, '0987666777', 'linhbk@gmail.com', '67 Lê Văn Sỹ, Q.3, TP.HCM', NULL, 'COD', 2),
(72, '2026-05-06 08:51:20', 3641000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'COD', 0),
(73, '2026-03-25 16:43:13', 3874000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 2),
(74, '2026-02-25 13:02:18', 318000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'MOMO', 0),
(75, '2026-03-27 18:16:15', 1495000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'BANK', 2),
(76, '2026-04-22 01:36:56', 6069000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 1),
(77, '2026-03-29 18:06:51', 5180000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'MOMO', 2),
(78, '2026-03-10 22:39:04', 2773000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 1),
(79, '2026-03-31 10:29:53', 657000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', NULL, 'COD', 1),
(80, '2026-05-02 16:16:14', 3975000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(81, '2026-05-09 10:21:17', 289000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'VNPAY', 2),
(82, '2026-05-10 21:05:52', 2250000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'COD', 1),
(83, '2026-02-13 17:43:47', 10137000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'COD', 2),
(84, '2026-02-27 18:02:10', 7067000.00, 6, '0987444555', 'quynhanh@gmail.com', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', NULL, 'VNPAY', 0),
(85, '2026-04-20 03:45:25', 597000.00, 5, '0987333444', 'vylh@gmail.com', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', NULL, 'BANK', 2),
(86, '2026-04-15 13:40:54', 5909000.00, 4, '0987222333', 'maianh@gmail.com', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', NULL, 'COD', 1),
(87, '2026-02-12 00:29:38', 9553000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'VNPAY', 1),
(88, '2026-04-07 15:08:47', 1865000.00, 3, '0987111222', 'ngannth@gmail.com', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', NULL, 'COD', 2),
(89, '2026-04-30 02:06:09', 8806000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', NULL, 'COD', 2),
(90, '2026-04-12 10:15:01', 129000.00, 7, '0987555666', 'tamvt@gmail.com', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', NULL, 'VNPAY', 2),
(91, '2026-05-10 19:09:49', 618000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', '', 'COD', 0),
(94, '2026-05-10 22:05:48', 129000.00, 1, '0903333333', 'tranngoc@gmail.com', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', '', 'COD', 0),
(95, '2026-05-13 16:19:46', 189000.00, 2, '0987654321', 'kimphung@gmail.com', 'Phú Yên', '', 'COD', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoadonnhap`
--

CREATE TABLE `hoadonnhap` (
  `MaHoaDon` int NOT NULL,
  `NgayNhap` datetime DEFAULT CURRENT_TIMESTAMP,
  `ThanhTien` decimal(12,2) DEFAULT NULL,
  `TenNCC` varchar(100) NOT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `MaNV` int DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChiLayHang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` int NOT NULL,
  `TenKhachHang` varchar(100) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `MaTaiKhoan` int DEFAULT NULL,
  `NgayDangKy` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `TenKhachHang`, `DiaChi`, `SDT`, `Email`, `GioiTinh`, `MaTaiKhoan`, `NgayDangKy`) VALUES
(1, 'Trần Thị Bích Ngọc', '123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', '0903333333', 'tranngoc@gmail.com', 'Nữ', 3, '2026-05-10 11:40:23'),
(2, 'Lê Thị Kim Phụng', 'Phú Yên', '0987654321', 'kimphung@gmail.com', 'Nu', 4, '2026-05-05 15:36:53'),
(3, 'Nguyễn Thị Hồng Ngân', '12 Đinh Tiên Hoàng, Q. Bình Thạnh, TP.HCM', '0987111222', 'ngannth@gmail.com', 'Nữ', NULL, '2026-02-02 15:36:53'),
(4, 'Phạm Mai Anh', '78 Phan Đăng Lưu, Phú Nhuận, TP.HCM', '0987222333', 'maianh@gmail.com', 'Nữ', NULL, '2026-01-17 15:36:53'),
(5, 'Lê Hồng Vy', '45 Cách Mạng Tháng 8, Q.3, TP.HCM', '0987333444', 'vylh@gmail.com', 'Nữ', NULL, '2026-02-08 15:36:53'),
(6, 'Đỗ Quỳnh Anh', '23 Nguyễn Văn Trỗi, Q. Phú Nhuận, TP.HCM', '0987444555', 'quynhanh@gmail.com', 'Nữ', NULL, '2026-01-10 15:36:53'),
(7, 'Vũ Thanh Tâm', '99 Hoàng Văn Thụ, Q.Tân Bình, TP.HCM', '0987555666', 'tamvt@gmail.com', 'Nam', NULL, '2025-12-14 15:36:53'),
(8, 'Bùi Khánh Linh', '67 Lê Văn Sỹ, Q.3, TP.HCM', '0987666777', 'linhbk@gmail.com', 'Nữ', NULL, '2026-04-27 15:36:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khohang`
--

CREATE TABLE `khohang` (
  `MaKho` int NOT NULL,
  `MaSanPham` int NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `SoLuongCon` int NOT NULL DEFAULT '0',
  `NgayNhap` date DEFAULT NULL,
  `LoaiSanPham` varchar(100) DEFAULT NULL,
  `AnhSanPham` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khohang`
--

INSERT INTO `khohang` (`MaKho`, `MaSanPham`, `TenSanPham`, `SoLuongCon`, `NgayNhap`, `LoaiSanPham`, `AnhSanPham`) VALUES
(1, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 100, '2026-05-10', 'Hàng chính hãng', NULL),
(2, 2, 'Son dưỡng môi Moist Touch Hydra', 81, '2026-06-17', 'Hàng chính hãng', NULL),
(3, 3, 'Son kem lì mịn nhung Velvet Matte', 120, '2026-05-10', 'Hàng chính hãng', NULL),
(4, 4, 'Phấn phủ kiềm dầu Oil Control Powder', 60, '2026-05-10', 'Hàng chính hãng', NULL),
(5, 5, 'Phấn phủ khoáng Natural Veil', 50, '2026-05-10', 'Hàng chính hãng', NULL),
(6, 6, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 40, '2026-05-10', 'Hàng chính hãng', NULL),
(7, 7, 'Kem chống nắng Anessa Perfect UV Sunscreen', 70, '2026-05-10', 'Hàng chính hãng', NULL),
(8, 8, 'Kem dưỡng La Roche-Posay Toleriane', 35, '2026-05-10', 'Hàng chính hãng', NULL),
(9, 9, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 90, '2026-05-10', 'Hàng chính hãng', NULL),
(10, 10, 'Nước tẩy trang Bioderma Sensibio H2O', 75, '2026-05-10', 'Hàng chính hãng', NULL),
(11, 11, 'Sữa tắm Dove Body Wash dưỡng ẩm', 110, '2026-05-10', 'Hàng chính hãng', NULL),
(12, 12, 'Lotion dưỡng thể Vaseline Intensive Care', 95, '2026-05-10', 'Hàng chính hãng', NULL),
(13, 13, 'Nước hoa Chanel Coco Mademoiselle EDP', 20, '2026-05-10', 'Hàng chính hãng', NULL),
(14, 14, 'Nước hoa Dior Sauvage EDT', 25, '2026-05-10', 'Hàng chính hãng', NULL),
(15, 15, 'Phấn má hồng Bobbi Brown Pot Rouge', 55, '2026-05-10', 'Hàng chính hãng', NULL),
(16, 16, 'Son tint bóng nước Romand Juicy Lasting Tint', 130, '2026-05-10', 'Hàng chính hãng', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaitaikhoan`
--

CREATE TABLE `loaitaikhoan` (
  `MaLoaiTaiKhoan` int NOT NULL,
  `TenLoaiTaiKhoan` varchar(50) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaitaikhoan`
--

INSERT INTO `loaitaikhoan` (`MaLoaiTaiKhoan`, `TenLoaiTaiKhoan`, `MoTa`) VALUES
(1, 'Quản trị viên', 'Quản trị toàn bộ hệ thống'),
(2, 'Nhân viên', 'Nhân viên cửa hàng - quản lý sản phẩm và đơn hàng'),
(3, 'Khách hàng', 'Khách hàng mua sắm trên website');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menuitem`
--

CREATE TABLE `menuitem` (
  `MaMenu` int NOT NULL,
  `ViTri` varchar(30) NOT NULL,
  `TieuDe` varchar(100) NOT NULL,
  `DuongDan` varchar(255) NOT NULL,
  `Icon` varchar(50) DEFAULT NULL,
  `ThuTu` int DEFAULT '0',
  `KichHoat` tinyint DEFAULT '1',
  `MoCuaSoMoi` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menuitem`
--

INSERT INTO `menuitem` (`MaMenu`, `ViTri`, `TieuDe`, `DuongDan`, `Icon`, `ThuTu`, `KichHoat`, `MoCuaSoMoi`) VALUES
(1, 'header', 'Trang chủ', 'index.php', NULL, 1, 1, 0),
(2, 'header', 'Sản phẩm', 'products.php', NULL, 2, 1, 0),
(3, 'header', 'Giới thiệu', 'about.php', NULL, 3, 1, 0),
(4, 'header', 'Khuyến mãi', 'products.php', 'fa-fire', 4, 1, 0),
(5, 'footer_support', 'Hướng dẫn mua hàng', '#', NULL, 1, 1, 0),
(6, 'footer_support', 'Phương thức thanh toán', '#', NULL, 2, 1, 0),
(7, 'footer_support', 'Chính sách đổi trả', '#', NULL, 3, 1, 0),
(8, 'footer_support', 'Câu hỏi thường gặp', '#', NULL, 4, 1, 0),
(9, 'footer_support', 'Chính sách bảo mật', '#', NULL, 5, 1, 0),
(10, 'footer_account', 'Đăng nhập', 'login.php', NULL, 1, 1, 0),
(11, 'footer_account', 'Đăng ký', 'register.php', NULL, 2, 1, 0),
(12, 'footer_account', 'Tài khoản của tôi', 'account.php', NULL, 3, 1, 0),
(13, 'footer_account', 'Giỏ hàng', 'cart.php', NULL, 4, 1, 0),
(14, 'footer_account', 'Lịch sử đơn hàng', 'account.php#orders', NULL, 5, 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNV` int NOT NULL,
  `TenNV` varchar(100) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SDT` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `CMND` varchar(20) NOT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `AnhNhanVien` varchar(255) DEFAULT NULL,
  `MaTaiKhoan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `TenNV`, `DiaChi`, `SDT`, `Email`, `CMND`, `GioiTinh`, `AnhNhanVien`, `MaTaiKhoan`) VALUES
(1, 'Lê Thị Kim Phụng', 'Số 12 đường Lê Duẩn, TP. Buôn Ma Thuột, Đắk Lắk', '0901111111', 'phung@hasaki.vn', '241234567890', 'Nữ', 'images/employees/nv-1.jpg', 1),
(2, 'Nguyễn Văn An', '45 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh', '0902222222', 'vana@hasaki.vn', '241234567891', 'Nam', 'images/employees/nv-2.jpg', 2),
(3, 'Trần Mỹ Linh', '88 Trần Hưng Đạo, Quận 5, TP. HCM', '0903456789', 'linhtm@hasaki.vn', '079123456001', 'Nữ', 'images/employees/nv-3.jpg', NULL),
(4, 'Phạm Quốc Bảo', '12 Lý Thường Kiệt, Đà Nẵng', '0904567890', 'baopq@hasaki.vn', '048123456002', 'Nam', 'images/employees/nv-4.jpg', NULL),
(5, 'Hoàng Thuý Hằng', '35 Hai Bà Trưng, Hà Nội', '0905678901', 'hangth@hasaki.vn', '001123456003', 'Nữ', 'images/employees/nv-5.jpg', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int NOT NULL,
  `MaDanhMuc` int NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `ThuongHieu` varchar(100) DEFAULT NULL,
  `XuatXu` varchar(100) DEFAULT NULL,
  `DungTich` varchar(50) DEFAULT NULL,
  `LoaiDa` varchar(100) DEFAULT NULL,
  `SoLuong` int DEFAULT '0',
  `GiaBan` decimal(12,2) NOT NULL,
  `MauSac` varchar(100) DEFAULT NULL,
  `KichCo` varchar(100) DEFAULT NULL,
  `MoTa` text,
  `HinhAnh` varchar(255) NOT NULL,
  `AnhHover1` varchar(255) DEFAULT NULL,
  `AnhHover2` varchar(255) DEFAULT NULL,
  `Sale` varchar(20) DEFAULT NULL,
  `ThongBao` varchar(255) DEFAULT NULL,
  `NgayTao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ThanhPhan` text,
  `HuongDanSuDung` text,
  `BaoQuan` text,
  `HanSuDung` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `MaDanhMuc`, `TenSanPham`, `ThuongHieu`, `XuatXu`, `DungTich`, `LoaiDa`, `SoLuong`, `GiaBan`, `MauSac`, `KichCo`, `MoTa`, `HinhAnh`, `AnhHover1`, `AnhHover2`, `Sale`, `ThongBao`, `NgayTao`, `ThanhPhan`, `HuongDanSuDung`, `BaoQuan`, `HanSuDung`) VALUES
(1, 1, 'Son lì Ruby Kiss Long-Wear Lipstick', 'Ruby Kiss', 'Hàn Quốc', '3.5g', 'Mọi loại môi', 100, 259000.00, 'Đỏ ruby', '3.5g', 'Son lì Ruby Kiss với màu đỏ ruby quyến rũ, lên màu chuẩn ngay từ lần đầu tiên. Công thức lì mịn lâu trôi đến 8 giờ, không khô môi nhờ chiết xuất bơ hạt mỡ và vitamin E. Phù hợp cho buổi tiệc, dạo phố và những dịp đặc biệt.', 'images/products/son-li-ruby-kiss.jpg', 'images/products/son-li-ruby-kiss-hover1.jpg', 'images/products/son-li-ruby-kiss-hover2.jpg', '20', '🔥 Bán chạy nhất tuần', '2026-05-10 11:40:23', 'Hydrogenated Polyisobutene, Polyethylene, Bis-Diglyceryl Polyacyladipate-2, Octyldodecanol, Vitamin E (Tocopheryl Acetate), Shea Butter (Butyrospermum Parkii), Mica, Iron Oxides (CI 77491, CI 77492, CI 77499), Red 7 (CI 15850).', '1. Tẩy tế bào chết môi nhẹ nhàng trước khi sử dụng\n2. Thoa son trực tiếp từ giữa môi ra hai bên\n3. Tô đậm thêm 1 lớp ở giữa để tạo hiệu ứng ombre\n4. Dùng giấy thấm dầu để định hình son lì lâu trôi hơn', 'Bảo quản nơi khô ráo, thoáng mát. Tránh ánh nắng trực tiếp. Đậy kín nắp sau khi sử dụng.', '24 tháng kể từ ngày sản xuất'),
(2, 1, 'Son dưỡng môi Moist Touch Hydra', 'Moist Touch', 'Nhật Bản', '4g', 'Môi khô, môi nứt nẻ', 80, 199000.00, 'Hồng nude', '4g', 'Son dưỡng môi Moist Touch giúp dưỡng ẩm sâu, làm mềm và phục hồi đôi môi nứt nẻ. Hương cherry tự nhiên dễ chịu. Có thể dùng làm lớp lót trước khi đánh son lì để bảo vệ môi.', 'images/products/son-duong-moist-touch.jpg', 'images/products/son-duong-moist-touch-hover1.jpg', NULL, NULL, NULL, '2026-05-10 11:40:23', 'Hyaluronic Acid, Vitamin E, Squalane, Shea Butter, Cocoa Butter, Beeswax, Cherry Extract, Glycerin.', '• Dùng buổi sáng và tối, hoặc bất cứ khi nào môi khô\n• Có thể dùng làm lớp lót trước khi đánh son màu\n• Thoa lớp dày trước khi đi ngủ để dưỡng môi qua đêm', 'Tránh nơi nhiệt độ cao trên 30°C. Đậy kín sau khi dùng.', '36 tháng kể từ ngày sản xuất'),
(3, 1, 'Son kem lì mịn nhung Velvet Matte', 'Velvet', 'Hàn Quốc', '6ml', 'Mọi loại môi', 120, 289000.00, 'Cam đất', '6ml', 'Son kem lì mịn nhung Velvet Matte có chất kem mỏng nhẹ, lên màu mịn như nhung. Bám lâu suốt 10 giờ mà không gây khô môi. Đa dạng tone màu hot trend từ neutral đến đậm cá tính.', 'images/products/son-kem-velvet.jpg', 'images/products/son-kem-velvet-hover1.jpg', NULL, '15', NULL, '2026-05-10 11:40:23', 'Isododecane, Trimethylsiloxysilicate, Silica, Dimethicone, Hyaluronic Acid, Vitamin E, Color CI Pigments.', '1. Lắc đều trước khi dùng\n2. Đầu cọ nhỏ giúp tô viền môi sắc nét\n3. Tán đều ra giữa môi\n4. Đợi 30 giây để son khô và lì hoàn toàn', 'Đậy kín nắp ngay sau khi dùng để tránh khô son. Bảo quản nơi thoáng mát.', '24 tháng'),
(4, 3, 'Phấn phủ kiềm dầu Oil Control Powder', 'MAKE UP FOR EVER', 'Pháp', '12g', 'Da dầu, da hỗn hợp thiên dầu', 60, 299000.00, 'Tự nhiên', '12g', 'Phấn phủ Oil Control Powder kiềm dầu vượt trội, giữ lớp nền bền đẹp suốt 12 giờ. Hạt phấn siêu mịn, khô thoáng nhưng không gây cakey. Lý tưởng cho da dầu và khí hậu nóng ẩm Việt Nam.', 'images/products/phan-phu-oil-control.jpg', 'images/products/phan-phu-oil-control-hover1.jpg', NULL, NULL, NULL, '2026-05-10 11:40:23', 'Mica, Talc, Silica, Boron Nitride, Magnesium Stearate, Zinc Oxide, Iron Oxides.', '1. Sau khi đánh kem nền, dùng cọ tán phấn đều khắp mặt\n2. Tập trung vào vùng chữ T (trán, mũi, cằm)\n3. Có thể dặm thêm trong ngày để kiềm dầu', 'Đậy kín nắp. Tránh ẩm và ánh nắng trực tiếp.', '36 tháng'),
(5, 3, 'Phấn phủ khoáng Natural Veil', 'Natural Veil', 'Mỹ', '10g', 'Da nhạy cảm, da mụn', 50, 349000.00, 'Nude tự nhiên', '10g', 'Phấn phủ khoáng Natural Veil chứa khoáng chất tinh khiết, an toàn cho da nhạy cảm. Không gây bít tắc lỗ chân lông. Cho lớp nền tự nhiên, mỏng nhẹ như không trang điểm.', 'images/products/phan-phu-natural-veil.jpg', NULL, NULL, '10', '✨ Sản phẩm mới ra mắt', '2026-05-10 11:40:23', 'Mica, Titanium Dioxide, Iron Oxides, Bismuth Oxychloride, Zinc Oxide, Boron Nitride.', '1. Mở nắp, lắc nhẹ để bột phấn rơi xuống lưới\n2. Dùng cọ kabuki chấm phấn\n3. Phủi nhẹ trên mu bàn tay rồi tán đều khắp mặt theo chuyển động tròn', 'Đóng kín nắp sau khi dùng. Tránh để nơi ẩm ướt.', '36 tháng'),
(6, 2, 'Sữa dưỡng cấp ẩm Bioderma Hydrabio Sérum', 'Bioderma', 'Pháp', '40ml', 'Da khô, da thiếu ẩm', 40, 459000.00, NULL, '40ml', 'Sữa dưỡng Bioderma Hydrabio chứa Aquagenium giúp khôi phục cơ chế cấp ẩm tự nhiên của da. Cấp ẩm tức thì lên đến 24 giờ, da căng mịn rạng rỡ. Phù hợp với mọi loại da, kể cả da nhạy cảm.', 'images/products/sua-duong-bioderma.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Aqua, Glycerin, Hyaluronic Acid, Niacinamide, Aquagenium Patent, Apple Seed Extract, Vitamin E, Tocopheryl Acetate.', '1. Dùng sau bước toner, trước kem dưỡng\n2. Lấy 2-3 giọt thoa đều lên mặt và cổ\n3. Vỗ nhẹ để tinh chất thẩm thấu\n4. Dùng sáng và tối hàng ngày', 'Bảo quản dưới 25°C. Tránh ánh nắng trực tiếp.', '12 tháng sau khi mở nắp'),
(7, 2, 'Kem chống nắng Anessa Perfect UV Sunscreen', 'Anessa (Shiseido)', 'Nhật Bản', '60ml', 'Mọi loại da, kể cả da dầu', 70, 569000.00, NULL, '60ml', 'Kem chống nắng Anessa SPF50+ PA++++ chống nắng tối ưu, công nghệ Aqua Booster gặp nước & mồ hôi càng tăng hiệu quả. Không trôi khi đi bơi. Bảng thành phần dưỡng da với 50% công thức skincare.', 'images/products/kem-chong-nang-anessa.jpg', 'images/products/kem-chong-nang-anessa-hover1.jpg', 'images/products/kem-chong-nang-anessa-hover2.jpg', '5', '🔥 Bán chạy nhất tuần', '2026-05-10 11:40:23', 'Octinoxate, Octocrylene, Zinc Oxide, Titanium Dioxide, Hyaluronic Acid, Collagen, Royal Jelly Extract, Glycerin.', '1. Lắc đều trước khi dùng\n2. Bước cuối cùng trong chu trình skincare buổi sáng\n3. Thoa lượng đủ lớn (2 đốt ngón tay) lên mặt\n4. Bôi lại sau mỗi 2-3 giờ nếu hoạt động ngoài trời', 'Bảo quản nơi khô ráo, thoáng mát.', '12 tháng sau khi mở nắp'),
(8, 2, 'Kem dưỡng La Roche-Posay Toleriane', 'La Roche-Posay', 'Pháp', '50ml', 'Da nhạy cảm, da kích ứng', 35, 489000.00, NULL, '50ml', 'Kem dưỡng La Roche-Posay Toleriane chuyên biệt cho da nhạy cảm. Không hương liệu, không paraben. Làm dịu, giảm kích ứng, phục hồi hàng rào bảo vệ da. Được kiểm nghiệm da liễu.', 'images/products/kem-duong-laroche.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Aqua, Glycerin, Squalane, Shea Butter, Niacinamide, Thermal Spring Water, Tocopherol, Allantoin.', '1. Sau bước serum, dùng đầu ngón tay lấy 1 lượng vừa đủ\n2. Thoa đều lên mặt và cổ\n3. Massage nhẹ nhàng theo chuyển động tròn\n4. Sử dụng sáng và tối', 'Đóng kín sau khi dùng. Tránh ánh nắng trực tiếp.', '12 tháng sau khi mở'),
(9, 4, 'Sữa rửa mặt Cetaphil Gentle Skin Cleanser', 'Cetaphil', 'Mỹ', '250ml', 'Mọi loại da, đặc biệt da nhạy cảm', 90, 219000.00, NULL, '250ml', 'Sữa rửa mặt Cetaphil dịu nhẹ, không tạo bọt, không xà phòng. Làm sạch hiệu quả mà không làm khô da. Khuyên dùng cho mọi loại da, kể cả da em bé. Được khuyên dùng bởi các bác sĩ da liễu.', 'images/products/sua-rua-mat-cetaphil.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Aqua, Cetyl Alcohol, Propylene Glycol, Sodium Lauryl Sulfate, Stearyl Alcohol, Methylparaben, Propylparaben, Butylparaben.', '1. Làm ướt mặt với nước ấm\n2. Lấy 1 lượng vừa đủ vào tay\n3. Massage nhẹ nhàng khắp mặt và cổ\n4. Rửa sạch với nước hoặc lau bằng khăn ẩm', 'Bảo quản ở nhiệt độ phòng. Đậy kín sau khi dùng.', '36 tháng'),
(10, 4, 'Nước tẩy trang Bioderma Sensibio H2O', 'Bioderma', 'Pháp', '500ml', 'Da nhạy cảm, mọi loại da', 75, 369000.00, NULL, '500ml', 'Nước tẩy trang Bioderma Sensibio H2O - sản phẩm bán chạy nhất thế giới. Làm sạch makeup, bụi bẩn nhẹ nhàng mà không cần rửa lại với nước. Phù hợp cho da nhạy cảm, mắt đeo kính áp tròng.', 'images/products/tay-trang-bioderma.jpg', NULL, NULL, '10', NULL, '2026-05-10 11:40:23', 'Aqua, PEG-6 Caprylic/Capric Glycerides, Cucumber (Cucumis Sativus) Fruit Extract, Mannitol, Xylitol, Rhamnose, Fructooligosaccharides.', '1. Thấm 1 lượng dung dịch ra bông tẩy trang\n2. Lau nhẹ nhàng khắp mặt, mắt và môi\n3. Không cần rửa lại với nước\n4. Tiếp tục các bước skincare', 'Bảo quản ở nhiệt độ phòng. Tránh ánh nắng.', '12 tháng sau khi mở nắp'),
(11, 5, 'Sữa tắm Dove Body Wash dưỡng ẩm', 'Dove', 'Mỹ', '500ml', 'Mọi loại da', 110, 159000.00, NULL, '500ml', 'Sữa tắm Dove dưỡng ẩm với 1/4 kem dưỡng, làm sạch nhẹ nhàng và giữ độ ẩm tự nhiên cho da. Hương thơm dịu nhẹ, lưu hương lâu trên cơ thể.', 'images/products/sua-tam-dove.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Aqua, Sodium Lauroyl Isethionate, Sodium Lauryl Sulfate, Glycerin, Cocamidopropyl Betaine, Stearic Acid, Lauric Acid, Sodium Isethionate, Parfum.', '1. Làm ướt cơ thể\n2. Cho 1 lượng vừa đủ ra bông tắm hoặc tay\n3. Tạo bọt và massage khắp cơ thể\n4. Rửa sạch với nước', 'Đóng kín nắp. Bảo quản nơi khô ráo.', '36 tháng'),
(12, 5, 'Lotion dưỡng thể Vaseline Intensive Care', 'Vaseline', 'Mỹ', '400ml', 'Mọi loại da, da khô', 94, 129000.00, NULL, '400ml', 'Lotion dưỡng thể Vaseline với công nghệ khoá ẩm, dưỡng ẩm sâu suốt 24 giờ. Không nhờn rít, thẩm thấu nhanh. Được khuyên dùng bởi 9/10 bác sĩ da liễu.', 'images/products/lotion-vaseline.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Aqua, Petrolatum, Glycerin, Stearic Acid, Cetyl Alcohol, Glyceryl Stearate, Dimethicone, Tocopheryl Acetate.', '1. Dùng sau khi tắm, da còn hơi ẩm\n2. Lấy 1 lượng vừa đủ ra tay\n3. Thoa đều khắp cơ thể\n4. Massage nhẹ đến khi thẩm thấu hoàn toàn', 'Bảo quản nơi mát mẻ.', '36 tháng'),
(13, 6, 'Nước hoa Chanel Coco Mademoiselle EDP', 'Chanel', 'Pháp', '50ml', NULL, 20, 2890000.00, NULL, '50ml', 'Nước hoa nữ Chanel Coco Mademoiselle - một trong những nước hoa kinh điển của Chanel. Hương cam tươi mát mở đầu, tim hương hoa nhài và hồng, hậu hương xạ hương ấm áp. Quyến rũ và sang trọng.', 'images/products/nuoc-hoa-chanel.jpg', 'images/products/nuoc-hoa-chanel-hover1.jpg', NULL, NULL, '👑 Cao cấp', '2026-05-10 11:40:23', 'Alcohol, Aqua, Parfum, Citrus Extracts, Jasmine, Rose, Patchouli, White Musk, Vanilla.', '• Xịt vào điểm mạch như cổ tay, sau tai, gáy\n• Không chà xát các điểm xịt nhau\n• Lưu hương lên đến 12 giờ\n• Phù hợp cho mọi dịp - làm việc, dạo phố, dạ tiệc', 'Tránh ánh nắng trực tiếp và nhiệt độ cao.', '36 tháng từ ngày sản xuất'),
(14, 6, 'Nước hoa Dior Sauvage EDT', 'Dior', 'Pháp', '60ml', NULL, 25, 2590000.00, NULL, '60ml', 'Nước hoa nam Dior Sauvage - hương thơm mạnh mẽ và quyến rũ. Mở đầu hương cam Bergamot Calabria sảng khoái, tim hương hoa oải hương Lavender, hậu hương Ambroxan ấm áp gợi cảm.', 'images/products/nuoc-hoa-dior.jpg', NULL, NULL, '5', '👑 Cao cấp', '2026-05-10 11:40:23', 'Alcohol, Aqua, Parfum, Bergamot, Pepper, Lavender, Pink Pepper, Vetiver, Ambroxan.', '• Xịt vào điểm mạch và da khô\n• Lý tưởng cho buổi tối và dịp đặc biệt\n• Lưu hương 8-10 giờ', 'Bảo quản nơi khô mát, tránh ánh nắng.', '36 tháng'),
(15, 3, 'Phấn má hồng Bobbi Brown Pot Rouge', 'Bobbi Brown', 'Mỹ', '3.5g', 'Mọi loại da', 54, 429000.00, 'Hồng đào', '3.5g', 'Phấn má hồng Bobbi Brown Pot Rouge tạo hiệu ứng má hồng tự nhiên, rạng rỡ. Texture kem mịn dễ tán, có thể dùng cả trên môi để tạo look đồng bộ. Lên màu chuẩn, lâu trôi.', 'images/products/phan-ma-bobbi-brown.jpg', NULL, NULL, NULL, NULL, '2026-05-10 11:40:23', 'Mica, Talc, Octyldodecyl Stearoyl Stearate, Pentaerythrityl Tetraisostearate, Iron Oxides, Carmine.', '1. Chấm 1 chút lên mu bàn tay\n2. Dùng đầu ngón tay hoặc cọ tán đều\n3. Cười nhẹ để xác định gò má, chấm và tán đều\n4. Dùng làm son lì để tạo look thống nhất', 'Đóng kín nắp sau khi dùng.', '24 tháng'),
(16, 1, 'Son tint bóng nước Romand Juicy Lasting Tint', 'Romand', 'Hàn Quốc', '5.5g', 'Mọi loại môi', 128, 189000.00, 'Đỏ cam', NULL, 'Son tint Romand Juicy với độ bóng căng mọng tự nhiên, lên màu chuẩn ngay lần đầu. Cảm giác mỏng nhẹ như không son, cấp ẩm sâu nhờ chiết xuất nho và quả mọng. Bám lâu cả khi ăn uống.', 'images/products/son-tint-romand.jpg', NULL, NULL, '20', NULL, '2026-05-10 11:40:23', 'Aqua, Castor Oil, Diisostearyl Malate, Mica, Polybutene, Grape Seed Oil, Vitamin E, Berry Extracts.', '1. Dùng đầu cọ tô viền môi sắc nét\r\n2. Tô đầy phần giữa\r\n3. Có thể tô nhiều lớp để tăng độ đậm\r\n4. Hiệu ứng bóng tự nhiên, không cần thêm gloss', 'Đậy kín nắp. Tránh nơi nóng ẩm.', '24 tháng'),
(18, 1, 'Son Kem Lì Merzy Bite The Beat Mellow Tint', 'Merzy', NULL, NULL, NULL, 20, 350000.00, NULL, NULL, NULL, 'images/uploads/img_6a009f61203695.46060237.jpg', NULL, NULL, NULL, NULL, '2026-05-10 22:08:17', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTaiKhoan` int NOT NULL,
  `TenTaiKhoan` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `LoaiTaiKhoan` int NOT NULL,
  `TrangThai` tinyint NOT NULL DEFAULT '1',
  `NgayTao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTaiKhoan`, `TenTaiKhoan`, `MatKhau`, `LoaiTaiKhoan`, `TrangThai`, `NgayTao`) VALUES
(1, 'admin', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 1, 1, '2026-05-10 11:40:23'),
(2, 'nhanvien1', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 2, 1, '2026-05-10 11:40:23'),
(3, 'khach01', '$2y$10$fjuNAfkaMosBgEUtyb1Scezsz7wRxmaIx/hcCNDaGED2Yf7HlQ2TS', 3, 1, '2026-05-10 11:40:23'),
(4, 'kimphung', '$2y$10$SaVqaoRctNzGxdguA3/zdOs9PO37qMr4t8N9J1oZZ/KCx1UchQXQ2', 3, 1, '2026-05-10 11:48:04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhinh`
--
ALTER TABLE `cauhinh`
  ADD PRIMARY KEY (`Khoa`);

--
-- Chỉ mục cho bảng `chatbotconfig`
--
ALTER TABLE `chatbotconfig`
  ADD PRIMARY KEY (`ConfigKey`);

--
-- Chỉ mục cho bảng `chatbotintent`
--
ALTER TABLE `chatbotintent`
  ADD PRIMARY KEY (`MaIntent`);

--
-- Chỉ mục cho bảng `chitiethoadonban`
--
ALTER TABLE `chitiethoadonban`
  ADD PRIMARY KEY (`MaChiTietHoaDon`),
  ADD KEY `MaHoaDon` (`MaHoaDon`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `chitiethoadonnhap`
--
ALTER TABLE `chitiethoadonnhap`
  ADD PRIMARY KEY (`MaChiTietHoaDon`),
  ADD KEY `MaHoaDon` (`MaHoaDon`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`MaDanhGia`),
  ADD KEY `MaSanPham` (`MaSanPham`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `hoadonban`
--
ALTER TABLE `hoadonban`
  ADD PRIMARY KEY (`MaHoaDon`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Chỉ mục cho bảng `hoadonnhap`
--
ALTER TABLE `hoadonnhap`
  ADD PRIMARY KEY (`MaHoaDon`),
  ADD KEY `MaNV` (`MaNV`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `khohang`
--
ALTER TABLE `khohang`
  ADD PRIMARY KEY (`MaKho`),
  ADD KEY `MaSanPham` (`MaSanPham`);

--
-- Chỉ mục cho bảng `loaitaikhoan`
--
ALTER TABLE `loaitaikhoan`
  ADD PRIMARY KEY (`MaLoaiTaiKhoan`);

--
-- Chỉ mục cho bảng `menuitem`
--
ALTER TABLE `menuitem`
  ADD PRIMARY KEY (`MaMenu`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNV`),
  ADD KEY `MaTaiKhoan` (`MaTaiKhoan`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`),
  ADD KEY `MaDanhMuc` (`MaDanhMuc`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTaiKhoan`),
  ADD UNIQUE KEY `TenTaiKhoan` (`TenTaiKhoan`),
  ADD KEY `LoaiTaiKhoan` (`LoaiTaiKhoan`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chatbotintent`
--
ALTER TABLE `chatbotintent`
  MODIFY `MaIntent` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `chitiethoadonban`
--
ALTER TABLE `chitiethoadonban`
  MODIFY `MaChiTietHoaDon` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT cho bảng `chitiethoadonnhap`
--
ALTER TABLE `chitiethoadonnhap`
  MODIFY `MaChiTietHoaDon` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `MaDanhGia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDanhMuc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `hoadonban`
--
ALTER TABLE `hoadonban`
  MODIFY `MaHoaDon` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT cho bảng `hoadonnhap`
--
ALTER TABLE `hoadonnhap`
  MODIFY `MaHoaDon` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKhachHang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `khohang`
--
ALTER TABLE `khohang`
  MODIFY `MaKho` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `loaitaikhoan`
--
ALTER TABLE `loaitaikhoan`
  MODIFY `MaLoaiTaiKhoan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `menuitem`
--
ALTER TABLE `menuitem`
  MODIFY `MaMenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNV` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `MaTaiKhoan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `chitiethoadonban`
--
ALTER TABLE `chitiethoadonban`
  ADD CONSTRAINT `chitiethoadonban_ibfk_1` FOREIGN KEY (`MaHoaDon`) REFERENCES `hoadonban` (`MaHoaDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitiethoadonban_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`);

--
-- Ràng buộc cho bảng `chitiethoadonnhap`
--
ALTER TABLE `chitiethoadonnhap`
  ADD CONSTRAINT `chitiethoadonnhap_ibfk_1` FOREIGN KEY (`MaHoaDon`) REFERENCES `hoadonnhap` (`MaHoaDon`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitiethoadonnhap_ibfk_2` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`);

--
-- Ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgia_ibfk_2` FOREIGN KEY (`MaKhachHang`) REFERENCES `khachhang` (`MaKhachHang`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `hoadonban`
--
ALTER TABLE `hoadonban`
  ADD CONSTRAINT `hoadonban_ibfk_1` FOREIGN KEY (`MaKhachHang`) REFERENCES `khachhang` (`MaKhachHang`);

--
-- Ràng buộc cho bảng `hoadonnhap`
--
ALTER TABLE `hoadonnhap`
  ADD CONSTRAINT `hoadonnhap_ibfk_1` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Ràng buộc cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `khohang`
--
ALTER TABLE `khohang`
  ADD CONSTRAINT `khohang_ibfk_1` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`MaTaiKhoan`) REFERENCES `taikhoan` (`MaTaiKhoan`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`);

--
-- Ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `taikhoan_ibfk_1` FOREIGN KEY (`LoaiTaiKhoan`) REFERENCES `loaitaikhoan` (`MaLoaiTaiKhoan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
