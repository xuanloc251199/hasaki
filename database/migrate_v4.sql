-- =====================================================================
-- Migration v4: Wire all images + flesh out content
-- Run AFTER `php database/download_images.php` finishes successfully.
-- mysql -uroot --default-character-set=utf8mb4 < migrate_v4.sql
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

-- ---------------------------------------------------------------------
-- Product images (main + 2 hover slots)
-- ---------------------------------------------------------------------
UPDATE SanPham SET HinhAnh = 'images/products/son-li-ruby-kiss.jpg',
       AnhHover1 = 'images/products/son-li-ruby-kiss-hover1.jpg',
       AnhHover2 = 'images/products/son-li-ruby-kiss-hover2.jpg' WHERE MaSanPham = 1;

UPDATE SanPham SET HinhAnh = 'images/products/son-duong-moist-touch.jpg',
       AnhHover1 = 'images/products/son-duong-moist-touch-hover1.jpg' WHERE MaSanPham = 2;

UPDATE SanPham SET HinhAnh = 'images/products/son-kem-velvet.jpg',
       AnhHover1 = 'images/products/son-kem-velvet-hover1.jpg' WHERE MaSanPham = 3;

UPDATE SanPham SET HinhAnh = 'images/products/phan-phu-oil-control.jpg',
       AnhHover1 = 'images/products/phan-phu-oil-control-hover1.jpg' WHERE MaSanPham = 4;

UPDATE SanPham SET HinhAnh = 'images/products/phan-phu-natural-veil.jpg' WHERE MaSanPham = 5;

UPDATE SanPham SET HinhAnh = 'images/products/sua-duong-bioderma.jpg' WHERE MaSanPham = 6;

UPDATE SanPham SET HinhAnh = 'images/products/kem-chong-nang-anessa.jpg',
       AnhHover1 = 'images/products/kem-chong-nang-anessa-hover1.jpg',
       AnhHover2 = 'images/products/kem-chong-nang-anessa-hover2.jpg' WHERE MaSanPham = 7;

UPDATE SanPham SET HinhAnh = 'images/products/kem-duong-laroche.jpg' WHERE MaSanPham = 8;

UPDATE SanPham SET HinhAnh = 'images/products/sua-rua-mat-cetaphil.jpg' WHERE MaSanPham = 9;

UPDATE SanPham SET HinhAnh = 'images/products/tay-trang-bioderma.jpg' WHERE MaSanPham = 10;

UPDATE SanPham SET HinhAnh = 'images/products/sua-tam-dove.jpg' WHERE MaSanPham = 11;

UPDATE SanPham SET HinhAnh = 'images/products/lotion-vaseline.jpg' WHERE MaSanPham = 12;

UPDATE SanPham SET HinhAnh = 'images/products/nuoc-hoa-chanel.jpg',
       AnhHover1 = 'images/products/nuoc-hoa-chanel-hover1.jpg' WHERE MaSanPham = 13;

UPDATE SanPham SET HinhAnh = 'images/products/nuoc-hoa-dior.jpg' WHERE MaSanPham = 14;

UPDATE SanPham SET HinhAnh = 'images/products/phan-ma-bobbi-brown.jpg' WHERE MaSanPham = 15;

UPDATE SanPham SET HinhAnh = 'images/products/son-tint-romand.jpg' WHERE MaSanPham = 16;

-- ---------------------------------------------------------------------
-- Category images
-- ---------------------------------------------------------------------
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/son-moi.jpg'         WHERE MaDanhMuc = 1;
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/duong-da.jpg'        WHERE MaDanhMuc = 2;
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/trang-diem.jpg'      WHERE MaDanhMuc = 3;
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/lam-sach-da.jpg'     WHERE MaDanhMuc = 4;
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/cham-soc-co-the.jpg' WHERE MaDanhMuc = 5;
UPDATE DanhMuc SET AnhDanhMuc = 'images/categories/nuoc-hoa.jpg'        WHERE MaDanhMuc = 6;

-- ---------------------------------------------------------------------
-- Employee avatars
-- ---------------------------------------------------------------------
UPDATE NhanVien SET AnhNhanVien = 'images/employees/nv-1.jpg' WHERE MaNV = 1;
UPDATE NhanVien SET AnhNhanVien = 'images/employees/nv-2.jpg' WHERE MaNV = 2;
UPDATE NhanVien SET AnhNhanVien = 'images/employees/nv-3.jpg' WHERE MaNV = 3;
UPDATE NhanVien SET AnhNhanVien = 'images/employees/nv-4.jpg' WHERE MaNV = 4;
UPDATE NhanVien SET AnhNhanVien = 'images/employees/nv-5.jpg' WHERE MaNV = 5;

-- ---------------------------------------------------------------------
-- Add useful product fields that may have been missed (ThongBao for hot products)
-- ---------------------------------------------------------------------
UPDATE SanPham SET ThongBao = '🔥 Bán chạy nhất tuần'  WHERE MaSanPham IN (1, 7);
UPDATE SanPham SET ThongBao = '✨ Sản phẩm mới ra mắt'  WHERE MaSanPham IN (5, 16);
UPDATE SanPham SET ThongBao = '👑 Cao cấp'              WHERE MaSanPham IN (13, 14);

-- ---------------------------------------------------------------------
-- Update site_logo + site_favicon if available (use brand-themed image)
-- ---------------------------------------------------------------------
UPDATE CauHinh SET GiaTri = '' WHERE Khoa = 'site_logo';
UPDATE CauHinh SET GiaTri = '' WHERE Khoa = 'site_favicon';
-- (Keep empty so the letter-logo fallback shows; admin can upload custom logo later)

-- ---------------------------------------------------------------------
-- Verify
-- ---------------------------------------------------------------------
SELECT MaSanPham, TenSanPham, HinhAnh FROM SanPham ORDER BY MaSanPham LIMIT 20;
SELECT MaDanhMuc, TenDanhMuc, AnhDanhMuc FROM DanhMuc;
SELECT MaNV, TenNV, AnhNhanVien FROM NhanVien;
