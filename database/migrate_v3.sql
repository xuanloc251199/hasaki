-- =====================================================================
-- Migration v3: Site settings + menu management
-- Run with: mysql -uroot --default-character-set=utf8mb4 < migrate_v3.sql
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

DROP TABLE IF EXISTS CauHinh;
DROP TABLE IF EXISTS MenuItem;

-- ---------------------------------------------------------------------
-- CauHinh: site-wide settings (key-value)
-- Loai: text | textarea | image | url | email | tel | color | boolean
-- Nhom: general | contact | social | banner | hero | footer | seo
-- ---------------------------------------------------------------------
CREATE TABLE CauHinh (
  Khoa          VARCHAR(100) PRIMARY KEY,
  GiaTri        TEXT NULL,
  Loai          VARCHAR(20) DEFAULT 'text',
  Nhom          VARCHAR(50) NULL,
  TenHienThi    VARCHAR(255) NULL,
  GhiChu        VARCHAR(500) NULL,
  ThuTu         INT DEFAULT 0,
  CapNhatLuc    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- MenuItem: header & footer menus (manageable in admin)
-- ViTri: header | footer_support | footer_account
-- ---------------------------------------------------------------------
CREATE TABLE MenuItem (
  MaMenu        INT AUTO_INCREMENT PRIMARY KEY,
  ViTri         VARCHAR(30) NOT NULL,
  TieuDe        VARCHAR(100) NOT NULL,
  DuongDan      VARCHAR(255) NOT NULL,
  Icon          VARCHAR(50) NULL,
  ThuTu         INT DEFAULT 0,
  KichHoat      TINYINT DEFAULT 1,
  MoCuaSoMoi    TINYINT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Default settings
-- ---------------------------------------------------------------------
INSERT INTO CauHinh (Khoa, GiaTri, Loai, Nhom, TenHienThi, GhiChu, ThuTu) VALUES
-- General
('site_name',         'HASAKI',                                 'text',     'general', 'Tên website',         'Tên thương hiệu hiển thị trên header & footer',                  1),
('site_tagline',      'Beauty & Wellness',                      'text',     'general', 'Slogan',              'Khẩu hiệu hiển thị dưới logo',                                   2),
('site_description',  'Hệ thống mỹ phẩm chính hãng - chăm sóc sắc đẹp toàn diện cho phái nữ Việt Nam.', 'textarea', 'general', 'Mô tả ngắn', 'Mô tả site dùng ở footer & SEO meta description',         3),
('site_logo',         '',                                       'image',    'general', 'Logo',                'Upload logo (để trống sẽ dùng logo chữ mặc định)',               4),
('site_favicon',      '',                                       'image',    'general', 'Favicon',             'Icon hiển thị trên tab trình duyệt (khuyên dùng 32x32 PNG)',     5),

-- Contact
('contact_address',   '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh',    'text',     'contact', 'Địa chỉ',             'Địa chỉ trụ sở chính',                                            1),
('contact_phone',     '1900 1234',                              'tel',      'contact', 'Hotline',             'Số điện thoại tư vấn / hỗ trợ',                                  2),
('contact_email',     'support@hasaki.vn',                      'email',    'contact', 'Email',               'Email liên hệ chính',                                            3),
('contact_hours',     '8:00 - 22:00 hàng ngày',                 'text',     'contact', 'Giờ làm việc',        'Giờ mở cửa hiển thị ở footer',                                   4),
('contact_map',       '',                                       'textarea', 'contact', 'Google Maps embed',   'Mã iframe Google Maps (tuỳ chọn)',                                5),

-- Social
('social_facebook',   'https://facebook.com/hasaki.vn',         'url',      'social',  'Facebook',            'URL trang Facebook',                                              1),
('social_instagram',  'https://instagram.com/hasaki',           'url',      'social',  'Instagram',           'URL Instagram',                                                   2),
('social_tiktok',     'https://tiktok.com/@hasaki',             'url',      'social',  'TikTok',              'URL TikTok',                                                      3),
('social_youtube',    '',                                       'url',      'social',  'YouTube',             'URL kênh YouTube',                                                4),
('social_zalo',       '',                                       'url',      'social',  'Zalo',                'Link Zalo OA',                                                    5),

-- Banner
('top_banner_active', '1',                                      'boolean',  'banner',  'Hiện banner trên',    'Bật/tắt thanh banner trên cùng',                                 1),
('top_banner_text',   '🎁 Ưu đãi giảm <strong>20%</strong> khi đặt mỹ phẩm từ 200k', 'textarea', 'banner', 'Nội dung banner', 'Cho phép HTML cơ bản: <strong>, <em>',     2),
('top_banner_link',   'products.php',                           'url',      'banner',  'Link "Mua ngay"',     'Đường dẫn khi click mua ngay',                                   3),
('top_banner_cta',    'Mua ngay',                               'text',     'banner',  'Nhãn nút CTA',        'Văn bản link CTA cuối banner',                                   4),

-- Hero (homepage)
('hero_badge',        'Bộ sưu tập mới · Mùa hè 2026',           'text',     'hero',    'Badge phía trên hero','Nội dung badge nhỏ phía trên tiêu đề hero',                       1),
('hero_title',        'Tỏa sáng <em>vẻ đẹp</em> tự nhiên của bạn', 'textarea', 'hero', 'Tiêu đề hero',        'Tiêu đề lớn ở trang chủ. Dùng <em>...</em> để gradient',          2),
('hero_subtitle',     'Khám phá hơn <strong>1.000+</strong> sản phẩm mỹ phẩm chính hãng từ những thương hiệu hàng đầu thế giới. Giao hàng nhanh, giá tốt, đổi trả 7 ngày.', 'textarea', 'hero', 'Phụ đề', 'Mô tả ngắn dưới tiêu đề hero', 3),
('hero_cta_primary',  'Mua ngay',                               'text',     'hero',    'CTA chính',           'Nút chính',                                                       4),
('hero_cta_secondary','Câu chuyện thương hiệu',                 'text',     'hero',    'CTA phụ',             'Nút phụ',                                                         5),
('hero_stat1_number', '10K+',                                   'text',     'hero',    'Stat 1 - Số',         '',                                                                10),
('hero_stat1_label',  'Khách hàng hài lòng',                    'text',     'hero',    'Stat 1 - Nhãn',       '',                                                                11),
('hero_stat2_number', '1K+',                                    'text',     'hero',    'Stat 2 - Số',         '',                                                                12),
('hero_stat2_label',  'Sản phẩm chính hãng',                    'text',     'hero',    'Stat 2 - Nhãn',       '',                                                                13),
('hero_stat3_number', '50+',                                    'text',     'hero',    'Stat 3 - Số',         '',                                                                14),
('hero_stat3_label',  'Thương hiệu uy tín',                     'text',     'hero',    'Stat 3 - Nhãn',       '',                                                                15),

-- Footer
('footer_about',      'Hệ thống mỹ phẩm chính hãng - chăm sóc sắc đẹp toàn diện cho phái nữ Việt Nam. Trải nghiệm mua sắm đẳng cấp với hơn 1.000+ sản phẩm chất lượng.', 'textarea', 'footer', 'Giới thiệu ở footer', 'Đoạn mô tả ngắn ở khối brand trong footer', 1),
('footer_copyright',  '© {YEAR} HASAKI. Designed by Lê Thị Kim Phụng - D22CTC1.', 'text', 'footer', 'Dòng copyright', 'Có thể dùng {YEAR} để in năm tự động', 2),

-- SEO
('seo_title_suffix',  'HASAKI',                                 'text',     'seo',     'Suffix tiêu đề',      'Sẽ thêm vào sau tiêu đề các trang. VD: "Sản phẩm - HASAKI"',     1),
('seo_keywords',      'mỹ phẩm, son môi, dưỡng da, kem chống nắng, hasaki', 'text', 'seo', 'Meta keywords', 'Ngăn cách bằng dấu phẩy',                                       2),
('seo_og_image',      '',                                       'image',    'seo',     'OG Image',            'Ảnh hiển thị khi share trên Facebook (1200x630px)',              3);

-- ---------------------------------------------------------------------
-- Default menu items
-- ---------------------------------------------------------------------

-- Header menu
INSERT INTO MenuItem (ViTri, TieuDe, DuongDan, Icon, ThuTu) VALUES
('header', 'Trang chủ',  'index.php',           NULL,         1),
('header', 'Sản phẩm',   'products.php',        NULL,         2),
('header', 'Giới thiệu', 'about.php',           NULL,         3),
('header', 'Khuyến mãi', 'products.php',        'fa-fire',    4);

-- Footer "Hỗ trợ"
INSERT INTO MenuItem (ViTri, TieuDe, DuongDan, ThuTu) VALUES
('footer_support', 'Hướng dẫn mua hàng',     '#', 1),
('footer_support', 'Phương thức thanh toán', '#', 2),
('footer_support', 'Chính sách đổi trả',     '#', 3),
('footer_support', 'Câu hỏi thường gặp',     '#', 4),
('footer_support', 'Chính sách bảo mật',     '#', 5);

-- Footer "Tài khoản"
INSERT INTO MenuItem (ViTri, TieuDe, DuongDan, ThuTu) VALUES
('footer_account', 'Đăng nhập',          'login.php',           1),
('footer_account', 'Đăng ký',            'register.php',        2),
('footer_account', 'Tài khoản của tôi',  'account.php',         3),
('footer_account', 'Giỏ hàng',           'cart.php',            4),
('footer_account', 'Lịch sử đơn hàng',   'account.php#orders',  5);
